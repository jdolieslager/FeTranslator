<?php
namespace FeTranslator\Translator;

use Zend\I18n\Translator\TextDomain as ZendTextDomain;
use Zend\I18n\Exception;

/**
 * {@inheritdoc}}
 */
class TextDomain extends ZendTextDomain
{
    /**
     * {@inheritdoc}
     */
    public function merge(ZendTextDomain $textDomain)
    {
        if ($this->getPluralRule()->getNumPlurals() !== $textDomain->getPluralRule()->getNumPlurals()) {
            throw new Exception\RuntimeException('Plural rule of merging text domain is not compatible with the current one');
        }

        $this->exchangeArray(
            array_replace_recursive(
                $this->getArrayCopy(),
                $textDomain->getArrayCopy()
            )
        );

        return $this;
    }
}
