<?php
namespace FeTranslator\Translator;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TranslatorServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $locator)
    {
        $config = $locator->get('config');

        $translatorConfig = array(
            'locale' => array(
                $config['fe_translator']['default_locale'],
                $config['fe_translator']['fallback_locale']
            ),
            'translation_file_patterns' => array()
        );

        foreach ($config['fe_translator']['translations'] as $translation) {
            $translatorConfig['translation_file_patterns'][] = array(
                'type'        => 'PhpArray',
                'base_dir'    => $translation['base_dir'],
                'pattern'     => '%s.php',
                'text_domain' => $translation['namespace'],
            );
        }

        // Inject MvcEvent object
        $translator  = Translator::factory($translatorConfig);
        $translator->setMvcEvent(
            $locator->get('Application')->getMvcEvent()
        );

        return $translator;
    }
}
