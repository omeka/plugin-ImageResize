<?php
class ImageResizePlugin extends Omeka_Plugin_Abstract
{
    protected $_filters = array('admin_navigation_main');
    
    public function filterAdminNavigationMain($nav)
    {
        $nav['Image Resize'] = uri('image-resize');
        return $nav;
    }
}
