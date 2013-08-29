<?php
namespace FeTranslator\Translator;

use Zend\I18n\Translator\LoaderPluginManager as ZendLoaderPluginManager;

/**
 * {@inheritdoc}
 */
class LoaderPluginManager extends ZendLoaderPluginManager
{
    /**
     * Default set of loaders.
     *
     * @var array
     */
    protected $invokableClasses = array(
        'gettext'  => 'Zend\I18n\Translator\Loader\Gettext',
        'ini'      => 'Zend\I18n\Translator\Loader\Ini',
        'phparray' => 'FeTranslator\Translator\Loader\PhpArray',
    );
}
