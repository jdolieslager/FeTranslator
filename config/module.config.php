<?php
return array(
    'fe_translator' => array(
        'default_locale'  => 'nl_NL',
        'fallback_locale' => 'nl_NL',
        'translations'    => array(),
    ),
    'router' => array(
        'routes' => array(
            'fetranslate' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/translate[/:namespace][/:locale]',
                    'defaults' => array(
                        'controller' => 'FeTranslator\Controller\Translate',
                        'action'     => 'translate',
                        'namespace'  => 'default',
                    ),
                ),
            ),
        ),
    ),
);
