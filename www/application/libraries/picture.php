<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Picture
{
    // Dimensions of picture thumbnails.
    const THUMBNAIL_WIDTH =  125,
          THUMBNAIL_HEIGHT = 150;
    
    /**
     * Processes an uploaded picture by resizing it and moving it from the uploads to the the 
     * picture folder. The uploaded file will be deleted afterwards.
     * 
     * @param string $picFile The path of the uploaded image, including file name.
     * 
     * @return string The filename of the picture, excluding its path (which is 'img/pictures').
     * 	              On failure, null is returned.
     */
    public function process($picFile)
    {	    
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
	
	/**
	 * Uploads a file in the POST-body and processes it.
	 * 
	 * @return string Same as the function process($picFile).
	 */
	public function uploadAndProcess($fieldname = 'picture')
	{
	    // Configure and load the upload library.
	    $config = array(
	    	'upload_path' => './uploads/',
    	    'allowed_types' => 'gif|jpg|png',
    	    'max_size' => '1000',
    	    'allowed_types' => 'jpg' // Only jpegs are supported.
	    );

	    $ci =& get_instance();
	    $ci->load->library('upload');
	    $ci->upload->initialize($config);
	    
	    // Do the actual upload.
	    $success = $ci->upload->do_upload($fieldname);
	    
	    if($success)
	    {
	        $data = $ci->upload->data();
	        
	        // Process the uploaded file.
	        return $this->process($data['full_path']);
	    }
	    else
	    {
	        //echo $ci->upload->display_errors();
	        return null;
	    }
	}
}