FeTranslator
============

ZF2 Module for translating routematches and messages


### Installation

1. `./composer.phar require jdolieslager/fetranslator`
2. Add `FeTranslator` to your `application.config.php`


### Default Settings
You can override the default language. Add `data/fe_translator.local.php.dist` to your `config/autoload` folder

### Add translations
To add translations you can add the following section to your config.
The translator will search for <locale>.php in the given directory. 
(Example: `nl_NL.php` or `en_US.php`)
```php
<?php
return array(
    'fe_translator' => array(
        'translations' => array(
            // Config below will be used to translate messages for given namespace
            array(
                'base_dir'    => __DIR__ . '/../translations/module/',
                'namespace'   => 'application', // Application indicates the namespace
            ),
            // Config below will be used to translate route matches
            array(
                'base_dir'    => __DIR__ . '/../translations/route_match/',
                'namespace'   => FeTranslator\Translator\Translator::NAMESPACE_ROUTE_MATCH,
            )
        ),
    ),
);
```

### Normal translation file example
```php
<?php
return array(
    'head' => array(
        'title' => 'My application title',
    ),
    'level1' => array(
        'level2' => 'My value',
    ),
);
```

### RouteMatch translation file example
```php
<?php
return array(
    'home/application'  => array(
        'controller'    => array(
            'Application\Controller\Gebruiker' => 'Application\Controller\User',
        ),
        '__CONTROLLER__' => array(
            'gebruiker' => 'user',
        ),
        'action'        => array(
            'inloggen'  => 'login',
            'uitloggen' => 'logout',
        ),
    ),
);
```

### Use Translations in controllers and services
You can add a trait for faster access to the Translator service. Requirements is: `php >= 5.4` and the class
should have a method `getServiceLocator()`

```php
<?php
class Controller extends AbstractActionController
{
    use \FeTranslator\ServiceManager\ServiceTrait;
    
    public function indexAction()
    {
        $translator = $this->getFeTranslateService();
        
        // Translate a message defined in nl_NL.php as array('level1' => array('level2' => 'value'));
        // The translations are loaded in the application namespace
        $translation = $translator->translate('level1.level2', 'application')
        
        //Get an translated URL
        // Routematch is: application/home with a controller User and with the action Login
        $translatedUrl = $translator->translateUrl(
            'application/home', 
            array('controller' => 'user', 'action' => 'login')
        );
    }
}
```

### Use Translations in templates
```html
    <?php
        $url = $this->feTranslateUrl('application/home', array('controller' => 'User', 'action' => 'login'));
    ?>
    <html>
        <head>
            <title><?= $this->feTranslate('head.title', 'application'); ?></title>
        </head>
        <body>
            <a href="<?= $url; ?>"><?= $this->feTranslate('my_link'); ?></a>
        </body>
    </html>
```
