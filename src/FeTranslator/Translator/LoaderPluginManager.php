<?php
namespace FeTranslator\Translator;

use Zend\I18n\Translator\LoaderPluginManager as ZendLoaderPluginManager;

/**
 * Plugin manager implementation for translation loaders.
 *
 * Enforces that loaders retrieved are either instances of
 * Loader\FileLoaderInterface or Loader\RemoteLoaderInterface. Additionally,
 * it registers a number of default loaders.
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
