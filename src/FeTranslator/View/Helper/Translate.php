<?php
namespace FeTranslator\View\Helper;

use Zend\I18n\Translator\Translator;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @category    FeTranslator
 * @package     View
 * @subpackage  Helper
 */
class Translate extends \Zend\I18n\View\Helper\AbstractTranslatorHelper implements
    ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * Constructor
     *
     * @param Translator $translator
     */
    public function __construct(Translator $translator)
    {
        $this->setTranslator($translator);
    }

    /**
     * Get the translator
     * @return Translator
     */
    public function getTranslator()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('FeTranslator\Translator');
    }

    /**
     * Translate a message
     *
     * @param string      $message
     * @param string|null $namespace
     * @param string|null $locale
     * @return string
     */
    public function __invoke($message, $namespace = null, $locale = null)
    {
        $namespace = $namespace ?: $this->getTranslatorTextDomain();
        return $this->getTranslator()->translate($message, $namespace, $locale);
    }

    /**
     * Get the service locator
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Set the service locator
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Translate
     */
    public function setServiceLocator(
        ServiceLocatorInterface $serviceLocator
    ) {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
}
