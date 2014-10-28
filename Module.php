<?php

namespace OnyxRest;

use OnyxRest\Model\RestResource;
use OnyxRest\Model\RestResourceTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Imagine\Gd\Imagine;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'RestResourceTable' =>  function($sm) {
                    $tableGateway = $sm->get('RestResourceTableGateway');
                    $table = new RestResourceTable($tableGateway);
                    return $table;
                },
                'RestResourceTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new RestResource());
                    return new TableGateway('onyx_rest_resource', $dbAdapter, null, $resultSetPrototype);
                },
                 
            ),
            
        );
    }
}