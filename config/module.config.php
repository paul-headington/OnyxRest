<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'OnyxRest\Controller\OnyxRest' => 'OnyxRest\Controller\OnyxRestController',
            'OnyxRest\Controller\OnyxUpload' => 'OnyxRest\Controller\OnyxUploadController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'onyx-rest' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/api[/:model][/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                        'model'  => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        '__NAMESPACE__' => 'OnyxRest\Controller',
                        'controller'    => 'OnyxRest',
                    ),
                ),
            ),
            'onyx-rest-upload' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/api-upload',
                    'defaults' => array(
                        '__NAMESPACE__' => 'OnyxRest\Controller',
                        'controller'    => 'OnyxUpload',
                        'action'        => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);