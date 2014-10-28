<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace OnyxRest\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class OnyxUploadController extends AbstractActionController
{
    protected $eventIdentifier = 'Onyx\Service\EventManger';
    
    public function onDispatch( \Zend\Mvc\MvcEvent $e ){
        return parent::onDispatch($e);
    }
    
    public function indexAction()
    {
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
        
        $return = array(
                "success" => false,
                "message" => '',
            );
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            echo "ispost";
            // Make certain to merge the files info!
            $post = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
        }

        var_dump($post);
        exit();

        if(isset($_FILES["file"]["name"])){

            
            $target_dir = "images/";
            $target_dir = $target_dir . basename( $_FILES["file"]["name"]);
            $uploadOk=1;

            // Check if file already exists
            if (file_exists($target_dir . $_FILES["file"]["name"])) {
                $return['message'] = "Sorry, file file exists.";
                $uploadOk = 0;
            }

            // Check file size
            if ($uploadFile_size > (1024 * 1024 * 100)) {
                $return['message'] = "Sorry, your file is too large.";
                $uploadOk = 0;
            }


            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                $return['message'] = "Sorry, your file was not uploaded.";
            // if everything is ok, try to upload file
            } else { 
                if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir)) {
                    if(!file_exists($target_dir)) {
                        $return['message'] = "Sorry, your file was not uploaded to: ".$target_dir;
                     }
                    chmod($target_dir, 0777);

                    $return = array(
                        "success" => true,
                        "imageUrl" => 'http://vshare.co.nz/images/'.$_FILES["uploadFile"]["name"]
                    );

                } else {
                    $return['message'] =  "Sorry, there was an error uploading your file.";
                }
            }
            
        }
        echo json_encode($return);
            exit();
    }
    
    
}
