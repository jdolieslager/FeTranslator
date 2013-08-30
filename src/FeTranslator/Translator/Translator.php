<?php
namespace FeTranslator\Translator;

use FeTranslator\Exception;
use FeTranslator\EventManager\EventManager;
use FeTranslator\Translator\LoaderPluginManager;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Uri\Uri;
use Zend\Mvc\ModuleRouteListener;

/**
 * @category    FeTranslator
 * @package     Translator
 *
 * @method string translate($message, $namespace, $locale)
 */
class Translator extends \Zend\I18n\Translator\Translator
{
    const NAMESPACE_ROUTE_MATCH  = 'routeMatch';

    /**
     * Maps parameters for URL generation to the correct key
     *
     * @var array
     */
    protected $urlMapping = array(
        'controller' => '__CONTROLLER__',
    );

    /**
     * @var MvcEvent
     */
    protected $mvcEvent;

    /**
     * @var EventManager
     */
    protected $eventManager;
    
    /**
    * Constructor
    */
    public function __construct()
    {
        // Override the phparray service with our own service
        $this->getPluginManager()->setAllowOverride(true);
        $this->getPluginManager()->setInvokableClass(
            'phparray', 
            'FeTranslator\Translator\Loader\PhpArray',
            true
        );
        $this->getPluginManager()->setAllowOverride(false);
    }


    /**
     * @param EventManager $eventManager
     * @return Translator
     */
    public function setMyEventManager(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;

        return $this;
    }

    /**
     * Get the event Manager
     *
     * @return EventManager
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }
    
    /**
     * Get a translated message.
     *
     * @param  string      $message     Dottec separated for nested php arrays
     * @param  string      $locale
     * @param  string      $namespace
     * @return string|null
     */
    protected function getTranslatedMessage(
        $message,
        $locale    = null,
        $namespace = 'default'
    ) {
        if (empty($message)) {
            return '';
        }

        if (!isset($this->messages[$namespace][$locale])) {
            $this->loadMessages($namespace, $locale);
        }

        if (array_key_exists($namespace, $this->messages) === false) {
            return null;
        }

        if (array_key_exists($locale, $this->messages[$namespace]) === false)  {
            return null;
        }

        $parts  = explode('.', $message);
        $lookup = $this->messages[$namespace][$locale];

        if (is_array($lookup) === false && !($lookup instanceOf \ArrayAccess)) {
            return $lookup;
        }

        foreach ($parts as $part) {
            if (array_key_exists($part, $lookup) === false) {
                return null;
            }

            $lookup = $lookup[$part];
        }

        return $lookup;
    }

    /**
     * Translate a routematch
     * Will be used by the Module bootstrap
     *
     * @param RouteMatch $routeMatch
     * @return void
     */
    public function translateRouteMatch(RouteMatch $routeMatch)
    {
        $params    = $routeMatch->getParams();
        $routeName = $routeMatch->getMatchedRouteName();

        $result = $this->translate($routeName, self::NAMESPACE_ROUTE_MATCH);
        if ($result === $routeName) {
            return;
        }

        foreach ($params as $key => $value) {
            $translateKey = "{$routeName}.{$key}.{$value}";
            $result       = $this->translate($translateKey, self::NAMESPACE_ROUTE_MATCH);

            if ($result !== $translateKey) {
                $routeMatch->setParam($key, $result);
            }
        }
    }

    /**
     * Translate an url
     *
     * @param string   $matchedRouteName    The routematch name
     * @param Uri|null $uri                 Use other URI (Console environments)
     * @param array    $params              The params for the route
     * @param array    $options             The options for the router::assemble
     * @param boolean  $forceHttpRouter     Force to use the http router
     * @param boolean  $reuseMatchedParams  Whether to reuse matched parameters
     *
     * @return string
     */
    public function translateUrl(
        $matchedRouteName   = null,
        Uri $uri            = null,
        array $params       = array(),
        array $options      = array(),
        $forceHttpRouter    = false,
        $reuseMatchedParams = false
    ) {
        $routeMatch = $this->getMvcEvent()->getRouteMatch();
        $router     = $this->getMvcEvent()->getRouter();

        if ($forceHttpRouter) {
            $router = $this->getMvcEvent()
                ->getApplication()
                ->getServiceManager()
                ->get('httprouter');
        }

        if ($matchedRouteName === null) {
            $matchedRouteName = $routeMatch->getMatchedRouteName();

            if ($matchedRouteName === null) {
                throw new Exception\RuntimeException(
                    'RouteMatch does not contain a matched route name'
                );
            }
        }

        if ($reuseMatchedParams && $routeMatch !== null) {
            $routeMatchParams = $routeMatch->getParams();

            if (isset($routeMatchParams[ModuleRouteListener::ORIGINAL_CONTROLLER])) {
                $routeMatchParams['controller'] = $routeMatchParams[ModuleRouteListener::ORIGINAL_CONTROLLER];
                unset($routeMatchParams[ModuleRouteListener::ORIGINAL_CONTROLLER]);
            }

            if (isset($routeMatchParams[ModuleRouteListener::MODULE_NAMESPACE])) {
                unset($routeMatchParams[ModuleRouteListener::MODULE_NAMESPACE]);
            }

            $params = array_merge($routeMatchParams, $params);
        }

        $result = $this->translate($matchedRouteName, self::NAMESPACE_ROUTE_MATCH);

        if ($result !== $matchedRouteName) {
            foreach ($params as $key => $value) {
                if (array_key_exists($key, $result) === false) {
                    continue;
                }

                $paramKey = $key;
                if (array_key_exists($paramKey, $this->urlMapping)) {
                    $paramKey = $this->urlMapping[$paramKey];
                }

                if (($idx = array_search($value, $result[$paramKey]))) {
                    $params[$key] = $idx;
                }
            }
        }

        if ($uri === null) {
            $request = $this->getMvcEvent()->getRequest();
            if ($request instanceof \Zend\Http\Request) {
                $uri = $request->getUri();
            } else {
                $uri      = new \Zend\Uri\Http();
                $this->getEventManager()->triggerMissingUri(array(
                    'uri' => $uri,
                ));
            }
        }

        $options['name'] = $matchedRouteName;
        $options['uri']  = $uri;

        $options = new \ArrayIterator($options);
        $params  = new \ArrayIterator($params);

        $this->getEventManager()->triggerPreAssemble(
            array(
                'options' => $options,
                'params'  => $params,
            )
        );

        try {
            return $router->assemble($params->getArrayCopy(), $options->getArrayCopy());
        } catch (\Exception $e) {
            echo $e->getTraceAsString();
            exit;
        }
    }

    /**
     * @param MvcEvent $mvcEvent
     * @return Translator
     */
    public function setMvcEvent(MvcEvent $mvcEvent)
    {
        $this->mvcEvent = $mvcEvent;

        return $this;
    }

    /**
     * @return MvcEvent
     */
    public function getMvcEvent()
    {
        return $this->mvcEvent;
    }
}
