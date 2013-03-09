<?php
use Zend\Loader\AutoloaderFactory;
use Zend\Loader\StandardAutoloader;

return array(
    AutoloaderFactory::STANDARD_AUTOLOADER => array(
        StandardAutoloader::LOAD_NS => array(
            'FeTranslator' => __DIR__ . '/../src/FeTranslator',
        ),
    ),
);
