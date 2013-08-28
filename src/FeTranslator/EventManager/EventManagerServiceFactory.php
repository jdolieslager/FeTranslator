<?php
namespace FeTranslator\EventManager;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @category    FeTranslator
 * @package     EventManager
 */
class EventManagerServiceFactory implements FactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createService(ServiceLocatorInterface $locator)
    {
        return new EventManager(new \Zend\EventManager\EventManager());
    }
}
