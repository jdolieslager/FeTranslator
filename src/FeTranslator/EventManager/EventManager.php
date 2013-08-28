<?php
namespace FeTranslator\EventManager;

use Zend\ServiceManager\ServiceLocatorInterface;
use \Zend\Mvc\MvcEvent;

/**
 * @category    FeTranslator
 * @package     EventManager
 */
class EventManager
{
    const SERVICE_NAME       = __CLASS__,
          EVENT_PRE_ASSEMBLE = 'event.pre_assabmle',
          EVENT_MISSING_URI  = 'event.missing_uri';

    /**
     * @var \Zend\EventManager\EventManager
     */
    protected $eventManager;

    /**
     * Constructor
     */
    public function __construct(\Zend\EventManager\EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
    }

    /**
     * @return \Zend\EventManager\EventManager
     */
    public function getEventManager()
    {
        return $this->eventManager;
    }

    /**
     * Get the possibility to alter the parameters
     *
     * @param callable $callable
     * @param integer $priority
     * @return \Zend\Stdlib\CallbackHandler
     */
    public function attachPreAssemble($callable, $priority = 1)
    {
        return $this->getEventManager()->attach(
            self::EVENT_PRE_ASSEMBLE,
            $callable,
            $priority
        );
    }

    /**
     * Get possibility to alter the parameters
     *
     * @param array $params
     * @param callable | NULL $callback
     * @return \Zend\EventManager\ResponseCollection
     */
    public function triggerPreAssemble($params, $callback = null)
    {
        return $this->getEventManager()->trigger(
            self::EVENT_PRE_ASSEMBLE,
            $this,
            $params,
            $callback
        );
    }

    /**
     * Attach on the event to fill in the uri object
     * array('uri' => \Zend\Http\Uri())
     *
     * @param callable $callable
     * @param integer $priority
     * @return \Zend\Stdlib\CallbackHandler
     */
    public function attachMissingUri($callable, $priority = 1)
    {
        return $this->getEventManager()->attach(
            self::EVENT_MISSING_URI,
            $callable,
            $priority
        );
    }

    /**
     * Triggers an event to fill in a new uri object
     *
     * @param array $params
     * @param callable | null $callback
     * @return \Zend\EventManager\ResponseCollection
     */
    public function triggerMissingUri(array $params, $callback = null)
    {
        return $this->getEventManager()->trigger(
            self::EVENT_MISSING_URI,
            $this,
            $params,
            $callback
        );
    }
}
