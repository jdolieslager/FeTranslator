<?php
namespace FeTranslator\View\Helper;

use FeTranslator\Translator\Translator;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\Router\RouteMatch;

/**
 * Helper for making easy links and getting urls
 *
 * @category    FeTranslator
 * @package     View
 * @subpackage  Helper
 */
class TranslateUrl extends \Zend\View\Helper\AbstractHelper
{
    /**
     * RouteInterface match returned by the router.
     *
     * @var RouteMatch
     */
    protected $routeMatch;

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * @param Translator $translator
     * @param RouteMatch $routeMatch
     */
    public function __construct(Translator $translator, RouteMatch $routeMatch)
    {
        $this->translator = $translator;
        $this->routeMatch = $routeMatch;
    }

    /**
     * Generates an url given the name of a route.
     *
     * @param  string  $name               Name of the route
     * @param  array   $params             Parameters for the link
     * @param  array   $options            Options for the route
     * @param  bool    $reuseMatchedParams Whether to reuse matched parameters
     * @return string                      For the link href attribute
     */
    public function __invoke(
        $name               = null,
        array $params       = array(),
        $options            = array(),
        $reuseMatchedParams = true
    ) {
        return $this->translator->translateUrl(
            $name,
            null,
            $params,
            $options,
            false,
            $reuseMatchedParams
        );
    }
}
