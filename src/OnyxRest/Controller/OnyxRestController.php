<?php

namespace OnyxRest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use Zend\InputFilter\Factory;
use Zend\InputFilter\FileInput;
use Zend\Validator;

class OnyxRestController extends AbstractRestfulController
{
    protected $collectionOptions = array('GET', 'POST');
    protected $resourceOptions = array('GET', 'PUT', 'DELETE');
    
    protected $serviceFactory;
    protected $serviceModelFactory;
    protected $modelTable;
    protected $model;
    protected $restResourceTable;
    
    protected $errorReturn = array(
            'Error' => array(
                'HTTP Status' => '400',
                'Code'        => '123',
                'Message'     => 'An ID is require to perform a delete',
                'More Info'   => 'docs link',
            ));
    
    protected $successReturn = array(
            'Success' => array(
                'Data'        => '400',
                'Message'     => 'The request was successful',
            ));


    public function onDispatch( MvcEvent $e ){
        if($this->params()->fromRoute('model', false)){
            $restResourceTable = $this->getRestResourceTable();
            $restResource = $restResourceTable->fetchByName($this->params()->fromRoute('model'));
            
            if($restResource){
                $this->serviceFactory = $restResource->factory;
                $this->serviceModelFactory = $restResource->modelfactory;
            }else{
                // method not allowed
                $response = $this->getResponse();
                $response->setStatusCode(405);
                return $response;
            }
        }else{
            // method not allowed
            $response = $this->getResponse();
            $response->setStatusCode(405);
            return $response;
        }
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

        $this->successReturn['Success']['Data'] = $data;

        return new JsonModel($this->successReturn);
    }

    public function get($id)
    {
        $modelTable = $this->getModelTable();        
        $data = $modelTable->getById($id);
        
        if($data === false){
            $response = $this->getResponse();
            $response->setStatusCode(400);

            $this->errorReturn['Error'] = array(
                    'HTTP Status' => '400',
                    'Code'        => '124',
                    'Message'     => 'invalid id',
                    'More Info'   => 'docs link',
            );

            return new JsonModel($this->errorReturn);
        }        
        
        $this->successReturn['Success']['Data'] = $data;
        
        return new JsonModel($this->successReturn);
    }

    public function create($data){  
        $model = $this->getModel();
        $factory = new Factory();
        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        
        $validators = $model->getValidation($dbAdapter);
       
        $inputFilter = $factory->createInputFilter($validators);        
        
        // Merge $_POST and $_FILES data together
        $request  = $this->getRequest();
        
        
        $validFiles = array();
        $postFiles = $request->getFiles();      

        foreach ($postFiles as $key => $file) {
            if ($file['error'] == UPLOAD_ERR_NO_FILE) continue;

            $validFiles[$key] = $file;
        }
        

        if (count($validFiles)) {
           $data = array_merge($data,  $validFiles);   
        }
              
      
        if (count($validFiles)) {
                
                // File Input
                $fileInput = new FileInput('file');
                $fileInput->setRequired(true);

                // You only need to define validators and filters
                // as if only one file was being uploaded. All files
                // will be run through the same validators and filters
                // automatically.
                $fileInput->getValidatorChain()
                    ->attachByName('filesize',      array('max' => 404800))
                    ->attachByName('filemimetype',  array('mimeType' => 'image/png,image/x-png,image/jpeg'))
                    ->attachByName('fileimagesize', array('maxWidth' => 1000, 'maxHeight' => 1000));
          
                // All files will be renamed, i.e.:
                //   ./public/tmpuploads/thumb_4b3403665fea6.png,
                //   ./public/tmpuploads/thumb_5c45147660fb7.png
                $fileInput->getFilterChain()->attachByName(
                    'filerenameupload',
                    array(
                        'target'    => './public/uploads/'.$data['file']['name'],
                        'randomize' => false,
                        'overwrite' => true,
                    )
                );
                $inputFilter->add($fileInput);
                $data['thumb'] = "/uploads/".$data['file']['name'];
                
        }
        $inputFilter->setData($data);
        
        if($inputFilter->isValid() ){            
            $inputFilter->getValues();
            
        }else{
            $response = $this->getResponse();
            $response->setStatusCode(400);
            
            $errors = array();
            
            foreach ($inputFilter->getInvalidInput() as $error) {
                $errors[] = $error->getMessages();
            }

            $this->errorReturn['Error'] = array(
                    'HTTP Status' => '400',
                    'Code'        => '125',
                    'Message'     => 'invalid data',
                    'Data'        => $errors,
                    'More Info'   => 'docs link',
            );

            return new JsonModel($this->errorReturn);
        }
        
        // Valid data try to save   
        try{
            $model->exchangeArray($data);
            $id = $this->getModelTable()->save($model);
            $model->setId($id);
        }  catch (\Exception $e){
            $response = $this->getResponse();
            $response->setStatusCode(400);
            

            $this->errorReturn['Error'] = array(
                    'HTTP Status' => '400',
                    'Code'        => '126',
                    'Message'     => 'error saving data -> '.$e->getMessage(),
                    'More Info'   => 'docs link',
            );

            return new JsonModel($this->errorReturn);
        }
       

        $this->successReturn['Success']['Data'] = $model;

        return new JsonModel($this->successReturn);
         
    }

    public function update($id, $data)
    {
        $model = $this->getModelTable()->getById($id);
        
        $data['id'] = $id;
        
        $factory = new Factory();
        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        
        $validators = $model->getValidation($dbAdapter);
       
        $inputFilter = $factory->createInputFilter($validators);        
        
        $inputFilter->setData($data);
        if($inputFilter->isValid() ){
            
        }else{
            $response = $this->getResponse();
            $response->setStatusCode(400);
            
            $errors = array();
            
            foreach ($inputFilter->getInvalidInput() as $error) {
                $errors[] = $error->getMessages();
            }

            $this->errorReturn['Error'] = array(
                    'HTTP Status' => '400',
                    'Code'        => '125',
                    'Message'     => 'invalid data',
                    'Data'        => $errors,
                    'More Info'   => 'docs link',
            );

            return new JsonModel($this->errorReturn);
        }
        
        // Valid data try to save   
        try{
            $model->exchangeArray($data);
            $id = $this->getModelTable()->save($model);
        }  catch (\Exception $e){
            $response = $this->getResponse();
            $response->setStatusCode(400);
            

            $this->errorReturn['Error'] = array(
                    'HTTP Status' => '400',
                    'Code'        => '126',
                    'Message'     => 'error saving data -> '.$e->getMessage(),
                    'More Info'   => 'docs link',
            );

            return new JsonModel($this->errorReturn);
        }
       

        $this->successReturn['Success']['Data'] = $model;

        return new JsonModel($this->successReturn);
    }

    public function delete($id)
    {
        
        $this->getModelTable()->delete($id);

        $this->successReturn['Success']['Data'] = "object with the id $id was deleted";

        return new JsonModel($this->successReturn);
    }
    
    public function deleteList() {
        $response = $this->getResponse();
        $response->setStatusCode(400);
        
        $this->errorReturn['Error'] = array(
                'HTTP Status' => '400',
                'Code'        => '123',
                'Message'     => 'this method is not allowed',
                'More Info'   => 'docs link',
        );
        
        return new JsonModel($this->errorReturn);
    }
    
    private function getModel(){
        if (!$this->model) {
            $sm = $this->getServiceLocator();
            $this->model = $sm->get($this->serviceModelFactory);
        }
        return $this->model;
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