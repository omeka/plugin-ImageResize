<?php
class ImageResizePlugin extends Omeka_Plugin_Abstract
{
    protected $_hooks = array('define_acl');

    protected $_filters = array('admin_navigation_main');

    public function hookDefineAcl($acl)
    {
        if (version_compare(OMEKA_VERSION, '2.0-dev', '>=')) {
            $acl->addResource('ImageResize_Index');
        } else {
            $resourceList = array('ImageResize_Index' => array('index', 'resize'));
            $acl->loadResourceList($resourceList);
        }
    }

    public function filterAdminNavigationMain($nav)
    {
        $nav['Image Resize'] = uri('image-resize');
        return $nav;
    }
}
