<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'OnyxRest\Controller\OnyxRest' => 'OnyxRest\Controller\OnyxRestController',
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
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
    ),
);