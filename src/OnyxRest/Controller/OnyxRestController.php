<?php

namespace OnyxRest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;

class OnyxRestController extends AbstractRestfulController
{
    protected $collectionOptions = array('GET', 'POST');
    protected $resourceOptions = array('GET', 'PUT', 'DELETE');
    
    protected $serviceFactory;
    protected $modelTable;
    protected $restResourceTable;


    public function onDispatch( MvcEvent $e ){
        if($this->params()->fromRoute('model', false)){
            $restResourceTable = $this->getRestResourceTable();
            $restResource = $restResourceTable->fetchByName($this->params()->fromRoute('model'));
            
            if($restResource){
                $this->serviceFactory = $restResource->factory;
            }else{
                // method not allowed
                $response = $this->getResponse();
                $response->setStatusCode(405);
                return $response;
            }
        }
        
        //if($this->params()->fromRoute('id', false)){
            // we have and ID, return specific item
        //    return $this->resourceOptions;
        //}
        return parent::onDispatch($e);
    }

    

    public function _getOptions(){
        if($this->params()->fromRoute('id', false)){
            // we have an ID, return specific item
            return $this->resourceOptions;
        }
        // no ID return collection
        return $this->collectionOptions;
    }
    
    public function options(){
        $response = $this->getResponse();
        
        // if in Options Array, Allow
        $response->getHeaders()
                 ->addHeaderLine('Allow', implode(',', $this->_getOptions()));
        
        return $response;
    }
    
    /**
     * Set the event manager instance used by this context
     *
     * @param  EventManagerInterface $events
     * @return AbstractController
     */
    public function setEventManager(EventManagerInterface $events)
    {
        parent::setEventManager($events);
        
        // Register the listener and callback methods with a priority of 10
        $events->attach('dispatch',Array($this,'checkOptions'),10); 

        return $this;
        
        
    }
    
    public function checkOptions($e){
        if(in_array($e->getRequest()->getMethod(), $this->_getOptions())){
            // Method allowed, nothing to do
            return;
        }
        
        // method not allowed
        $response = $this->getResponse();
        $response->setStatusCode(405);
        return $response;
    }

    public function getList()
    {
        $results = $this->getModelTable()->fetchAll();
        $data = array();
        foreach($results as $result) {
            $data[] = $result;
        }

        return new JsonModel(array(
            'data' => $data,
        ));
    }

    public function get($id)
    {
        $modelTable = $this->getModelTable();
        
        $data = $modelTable->getById($id);

        return new JsonModel(array(
            'data' => $data,
        ));
    }

    public function create($data){    
        //$modelAPRService = $this->getServiceLocator()->get();
        
        /*$form = new AlbumForm();
        $album = new Album();
        $form->setInputFilter($album->getInputFilter());
        $form->setData($data);
        if ($form->isValid()) {
            $album->exchangeArray($form->getData());
            $id = $this->getAlbumTable()->saveAlbum($album);
        }

        return new JsonModel(array(
            'data' => $this->get($id),
        ));
         */
    }

    public function update($id, $data)
    {
        /*
        $data['id'] = $id;
        $album = $this->getAlbumTable()->getAlbum($id);
        $form  = new AlbumForm();
        $form->bind($album);
        $form->setInputFilter($album->getInputFilter());
        $form->setData($data);
        if ($form->isValid()) {
            $id = $this->getAlbumTable()->saveAlbum($form->getData());
        }

        return new JsonModel(array(
            'data' => $this->get($id),
        ));
         * 
         */
    }

    public function delete($id)
    {
        /*
        $this->getAlbumTable()->deleteAlbum($id);

        return new JsonModel(array(
            'data' => 'deleted',
        ));
         * 
         */
    }
    
    public function deleteList() {
        $response = $this->getResponse();
        $response->setStatusCode(400);
        
        $result = array(
            'Error' => array(
                'HTTP Status' => '400',
                'Code'        => '123',
                'Message'     => 'An ID is require to perform a delete',
                'More Info'   => 'docs link',
            ),
        );
        
        return new JsonModel($result);
    }
    
    private function getModelTable(){
        if (!$this->modelTable) {
            $sm = $this->getServiceLocator();
            $this->modelTable = $sm->get($this->serviceFactory);
        }
        return $this->modelTable;
    }
    
    private function getRestResourceTable(){
        if (!$this->restResourceTable) {
            $sm = $this->getServiceLocator();
            $this->restResourceTable = $sm->get('RestResourceTable');
        }
        return $this->restResourceTable;
    }
}