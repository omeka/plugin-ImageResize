<?php
class ImageResize_IndexController extends Omeka_Controller_Action
{
    public function indexAction()
    {
        // Only resize images on systems using the filesystem storage adapter.
        if (Zend_Registry::get('storage')->getAdapter() instanceof Omeka_Storage_Adapter_Filesystem) {
            $this->view->assign('usingFilesystemAdapter', true);
        } else {
            $this->view->assign('usingFilesystemAdapter', false);
        }
        
        if (isset($_POST['image_resize_submit'])) {
            
            $constraints = array('fullsize_constraint' => false, 
                                 'thumbnail_constraint' => false, 
                                 'square_thumbnail_constraint' => false);
            
            foreach ($constraints as $key => $constraint) {
                
                // Only images marked for resizing will be processed.
                if (!$_POST["resize_$key"]) {
                    continue;
                }
                
                // Validate the form.
                if (!preg_match('/^[1-9][0-9]*$/', $_POST[$key])) {
                    $this->flashError('Size constraints must be positive integers. No images were resized.');
                    $validationError = true;
                    break;
                }
                
                // Set the constraints.
                $constraints[$key] = $_POST[$key];
            }
            
            if (!isset($validationError)) {
                // Run the process, even if the constraints did not change.
                ProcessDispatcher::startProcess('ImageResizeProcess', null, $constraints);
                $this->flashSuccess('Resizing images. This process may take a while.');
            }
        }
        
        $processes = $this->getTable('Process')->findByClass('ImageResizeProcess');
        $this->view->assign('processes', $processes);
    }
}
