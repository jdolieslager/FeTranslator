<?php
namespace FeTranslator\ServiceManager;

/**
 * @category    FeTranslator
 * @package     ServiceManager
 */
trait ServiceTrait
{
    abstract public function getServiceLocator();

    /**
     * @return \FeTranslator\Translator\Translator
     */
    public function getFeTranslateService()
    {
        return $this->getServiceLocator()->get('FeTranslator\Translator');
    }

    /**
     * @return \FeTranslator\EventManager\EventManager
     */
    public function getFeTranslateEventManagerService()
    {
        return $this->getServiceLocator()->get('FeTranslator\EventManager');
    }
}
