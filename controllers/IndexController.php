<?php
class ImageResize_IndexController extends Omeka_Controller_Action
{
    /**
     * Display the resize form.
     */
    public function indexAction()
    {
        // Only resize images on systems using the filesystem storage adapter.
        if (Zend_Registry::get('storage')->getAdapter() instanceof Omeka_Storage_Adapter_Filesystem) {
            $this->view->assign('usingFilesystemAdapter', true);
        } else {
            $this->view->assign('usingFilesystemAdapter', false);
        }
        
        // Set the previous image resize processes.
        $processes = $this->getTable('Process')->findByClass('ImageResizeProcess');
        $this->view->assign('processes', $processes);
    }
    
    /**
     * Handle the resize form and run the resize process.
     */
    public function resizeAction()
    {
        $constraints = array('fullsize_constraint' => false, 
                             'thumbnail_constraint' => false, 
                             'square_thumbnail_constraint' => false);
        
        // Iterate the expected constraints.
        foreach ($constraints as $key => $constraint) {
            
            // Only images marked for resizing will be processed.
            if (!$_POST["resize_$key"]) {
                continue;
            }
            
            // Validate the form. Fail on the first error.
            if (!is_numeric($_POST[$key]) || !preg_match('/^[1-9][0-9]*$/', $_POST[$key])) {
                $this->flashError('Size constraints must be positive integers. No images were resized.');
                $this->redirect->gotoSimple('index');
            }
            
            // Set the constraints.
            $constraints[$key] = $_POST[$key];
        }
        
        // Do not resize images if changes were made to the form. 
        if (!$constraints['fullsize_constraint'] 
         && !$constraints['thumbnail_constraint'] 
         && !$constraints['square_thumbnail_constraint']) {
            $this->flash('No changes were made. Images were not resized.');
            $this->redirect->gotoSimple('index');
        }
        
        // Set the Omeka constraint options.
        foreach ($constraints as $key => $constraint) {
            if ($constraint) {
                set_option($key, $constraint);
            }
        }
        
        // Run the process, even if the constraints did not change.
        ProcessDispatcher::startProcess('ImageResizeProcess', null, $constraints);
        $this->flashSuccess('Resizing images. This process may take a while. Refresh this page to update the process status.');
        $this->redirect->gotoSimple('index');
    }
}
