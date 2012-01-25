<?php
class ImageResizeProcess extends ProcessAbstract
{
    public function run($constraints)
    {
        $db = get_db();
        $storage = Zend_Registry::get('storage');
        
        // Set the constraint options.
        foreach ($constraints as $key => $constraint) {
            if ($constraint) {
                set_option($key, $constraint);
            }
        }
        
        // Iterate all image files in the archive.
        $sql = "SELECT * FROM {$db->File} WHERE has_derivative_image = 1";
        foreach ($db->query($sql)->fetchAll() as $imageFile) {
            
            // Iterate the constraints.
            foreach ($constraints as $key => $constraint) {
                if (!$constraint) {
                    continue;
                }
                $oldPath = FILES_DIR . '/' . $imageFile['archive_filename'];
                switch ($key) {
                    case 'square_thumbnail_constraint':
                        $newFileName = Omeka_File_Derivative_Image::createImage($oldPath, $constraint, 'square_thumbnail');
                        $source = FILES_DIR . '/square_thumbnail_' . $newFileName;
                        $dest = $storage->getPathByType($newFileName, 'square_thumbnails');
                        break;
                    case 'thumbnail_constraint':
                        $newFileName = Omeka_File_Derivative_Image::createImage($oldPath, $constraint, 'thumbnail');
                        $source = FILES_DIR . '/thumbnail_' . $newFileName;
                        $dest = $storage->getPathByType($newFileName, 'thumbnails');
                        break;
                    case 'fullsize_constraint':
                        $newFileName = Omeka_File_Derivative_Image::createImage($oldPath, $constraint, 'fullsize');
                        $source = FILES_DIR . '/fullsize_' . $newFileName;
                        $dest = $storage->getPathByType($newFileName, 'fullsize');
                        break;
                    default:
                        break;
                }
                $storage->store($source, $dest);
            }
        }
    }
}
