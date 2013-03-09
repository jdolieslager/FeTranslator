<?php
namespace FeTranslator\View\Helper;

use Zend\I18n\Translator\Translator;

/**
 * @category    FeTranslator
 * @package     View
 * @subpackage  Helper
 */
class Translate extends \Zend\I18n\View\Helper\AbstractTranslatorHelper
{
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
}