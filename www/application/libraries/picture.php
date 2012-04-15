<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Picture
{
    // Dimensions of picture thumbnails.
    const THUMBNAIL_WIDTH =  250,
          THUMBNAIL_HEIGHT = 300;
    
    /**
     * Processes an uploaded picture by resizing it and moving it from the uploads to the the 
     * picture folder.
     * 
     * @param string $picFile The filename of the uploaded image, which should be loocated in the
     *                        uploads folder.
     * 
     * @return string The filename of the picture, excluding its path (which is 'img/pictures').
     */
    public function process($picFile)
    {
	    // TODO: Remove this line if CodeIgniter already prepends uploads path.
        $picFile = "uploads/$picFile";
	    
	    // Copy 250x300 version of uploaded picture to img-folder, using GD.
	    $source = imagecreatefromjpeg($picFile);
	    list($sourceWidth, $sourceHeight) = getimagesize($picFile);
	    $dest = imagecreatetruecolor(self::THUMBNAIL_WIDTH, self::THUMBNAIL_HEIGHT);
	    
	    $success = imagecopyresampled($dest, $source, 0, 0, 0, 0, 
	                  self::THUMBNAIL_WIDTH, self::THUMBNAIL_HEIGHT, $sourceWidth, $sourceHeight);
	    
	    if($success)
	    {
	        // Generate picture filename (16 random lowercase characters + .jpg).
	        $picture = '';
	        for($i = 0; $i < 16; ++$i)
	        {
	            $picture .= chr(rand(ord('a'), ord('z')));
	        }
	        $picture .= '.jpg';
	        
	        // Save image.
	        $success = imagejpeg($dest, 'img/pictures/' . $picture);
	    }
	    
	    // Delete image upload.
	    unlink($picFile);
	    
	    if($success)
	    {
	        // Add Image filename to data.
	        return $picture;
	    }
	    else
	    {
	        return null;
	    }
	}
}