<?php

namespace Contato\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
//use Zend\Service\Manager\Service\LocatorInterface;
//use Zend\Event\Manager\Event\ManagerAware;

class HomeController extends AbstractActionController {

    public function indexAction() {
        // get cache service
        $cache = $this->getServiceLocator()->get('cache');
//// set unique Cache key
//        $key = 'unique-cache-key';
//// get the Cache data
//        $result = $cache->getItem($key, $success);
//        if (!$success) {
//// if not set the data for next request
//            $result = 'arjun';
//            $cache->setItem($key, $result);
//        }
//// result
//        echo $result;

        /**
         * Uso de cache
         */
//        $cache->setItem('nome', 'igor');
        
        if (!$cache->hasItem('nome')) {
            var_dump(
                    "Registro de Cache Agora", $cache->setItem('nome', 'igor')
            );
        } else {
            var_dump(
                    "Cache Existente", $cache->getItem('nome')
            );
        }
        return new ViewModel();
    }

    /**
     * action sobre
     * @return \Zend\View\Model\ViewModel
     */
    public function sobreAction() {
        return new ViewModel();
    }

}
