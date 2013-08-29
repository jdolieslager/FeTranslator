<?php
namespace FeTranslator\Translator;

use Zend\I18n\Translator\TextDomain as ZendTextDomain;

/**
 * Text domain.
 */
class TextDomain extends ZendTextDomain
{
    /**
     * Merge another text domain with the current one.
     *
     * The plural rule of both text domains must be compatible for a successful
     * merge. We are only validating the number of plural forms though, as the
     * same rule could be made up with different expression.
     *
     * @param  TextDomain $textDomain
     * @return TextDomain
     * @throws Exception\RuntimeException
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
