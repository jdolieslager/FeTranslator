<?php
use Zend\View\HelperPluginManager;

return array(
    'factories' => array(
        'feTranslate' => function(HelperPluginManager $hp) {
            $translator = $hp->getServiceLocator()->get('FeTranslator\Translator');
            return new \FeTranslator\View\Helper\Translate($translator);
        },
        'feTranslateUrl' => function(HelperPluginManager $hp) {
            $translator = $hp->getServiceLocator()->get('FeTranslator\Translator');
            $routeMatch = $hp->getServiceLocator()
                ->get('Application')
                ->getMvcEvent()
                ->getRouteMatch();

            return new \FeTranslator\View\Helper\TranslateUrl(
                $translator,
                $routeMatch
            );
        },
    ),
);
