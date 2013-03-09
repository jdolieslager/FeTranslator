<?php
namespace FeTranslator;

use Zend\ModuleManager\Feature;
use Zend\Mvc\MvcEvent;

/**
 * @category Translate
 */
class Module implements
    Feature\AutoloaderProviderInterface,
    Feature\ConfigProviderInterface,
    Feature\ServiceProviderInterface,
    Feature\ViewHelperProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        /** @var \Zend\EventManager\EventManager */
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'));
    }

    public function onRoute(MvcEvent $e)
    {
        /** @var \FeTranslator\Translator\Translator */
        $translator = $e->getApplication()
            ->getServiceManager()
            ->get('FeTranslator\Translator');

        $translator->translateRouteMatch($e->getRouteMatch());
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    /**
     * {@inheritdoc}
     */
    public function getAutoloaderConfig()
    {
        return include __DIR__ . '/../../config/autoloader.config.php';
    }

    /**
     * {@inheritdoc}
     */
    public function getServiceConfig()
    {
        return include __DIR__ . '/../../config/service.config.php';
    }

    /**
     * {@inheritdoc}
     */
    public function getViewHelperConfig()
    {
        return include __DIR__ . '/../../config/viewhelper.config.php';
    }
}
