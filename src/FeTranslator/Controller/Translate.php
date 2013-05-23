<?php
namespace FeTranslator\Controller;

use Zend\View\Model\JsonModel;

class Translate extends \Zend\Mvc\Controller\AbstractActionController
{
    /**
     * Translate
     *
     * @return JsonModel
     */
    public function translateAction()
    {
        $model     = new JsonModel();
        $namespace = $this->getEvent()->getRouteMatch()->getParam('namespace', 'default');
        $locale    = $this->getEvent()->getRouteMatch()->getParam('locale', null);

        if ($this->getRequest()->getMethod() === 'GET') {
            $params = $this->getRequest()->getQuery();

            if (array_key_exists('callback', $params)) {
                $model->setJsonpCallback($params['callback']);
            }
        } else {
            $params = $this->getRequest()->getPost();
        }

        if (!isset($params['key'])) {
            $model->setVariable('error', 'No key[] given');
        }

        if (!is_array($params['key'])) {
            $params['key'] = array($params['key']);
        }

        foreach ($params['key'] as $key) {
            $model->setVariable(
                $key,
                $this->getTranslator()->translate($key, $namespace, $locale)
            );
        }

        return $model;
    }

    /**
     * @return \FeTranslator\Translator\Translator
     */
    public function getTranslator()
    {
        return $this->getServiceLocator()->get('FeTranslator\Translator');
    }
}
