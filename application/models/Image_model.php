<?php

class Image_model extends CI_Model
{
	function makedirs($folder='', $mode=DIR_WRITE_MODE, $defaultFolder='uploads/'){

        if(!@is_dir(FCPATH . $defaultFolder)) {

            mkdir(FCPATH . $defaultFolder, $mode);
        }
        if(!empty($folder)) {

            if(!@is_dir(FCPATH . $defaultFolder . '/' . $folder)){
                mkdir(FCPATH . $defaultFolder . '/' . $folder, $mode,true);
            }
        } 
    }//End Function
    function makedirsBk($folder='', $mode=DIR_WRITE_MODE, $defaultFolder='../uploads/'){

        if(!@is_dir(FCPATH . $defaultFolder)) {

            mkdir(FCPATH . $defaultFolder, $mode);
        }
        if(!empty($folder)) {

            if(!@is_dir(FCPATH . $defaultFolder . '/' . $folder)){
                mkdir(FCPATH . $defaultFolder . '/' . $folder, $mode,true);
            }
        } 
    }//End Function
    
    function updateMedia($profileImage,$folder,$hieght=600,$width=600,$path=FALSE){

        if($path){
        	 $this->makedirsBk($folder);
        }else{
        	 $this->makedirs($folder);
        }
       	$realpath = $path ?'../uploads/':'uploads/';

		$allowed_types = "gif|jpg|png|jpeg|PNG|JPG|JPEG|GIF"; 
		
		$config = array(
			'upload_path'   	=> $realpath.$folder,
			'allowed_types' 	=> $allowed_types,
			'max_size' 			=> "5048000",// Can be set to particular file size , here it is 2 MB(2048 Kb)
			'max_width'			=> "7000",
			'max_height'		=> "7000",
			'min_width'			=> "200",
			'min_height'		=> "200",
			'encrypt_name'		=> TRUE,
			'overwrite'		 	=> false,
			'remove_spaces'		=> TRUE,
			'quality'			=> '100%',
		);
		
		$this->load->library('upload');
		$this->upload->initialize($config);

	  	if(!$this->upload->do_upload($profileImage)){

   			$error = array('error' => $this->upload->display_errors());
			return $error;

		} else {

			$image_data = $this->upload->data(); 
		
			$this->load->library('image_lib');

			
			$folder_thumb = $folder.'/thumb/';//for thumb image
			if($path){
				$this->makedirsBk($folder_thumb);
			}else{
				$this->makedirs($folder_thumb);
			}
			

			$resize['image_library'] 	= 'gd2';
			$resize['source_image'] 	= $image_data['full_path'];
			$resize['new_image'] 		= realpath(FCPATH .$realpath .$folder_thumb);
			$resize['maintain_ratio'] 	= FALSE;
			$resize['width'] 			= 150;
			$resize['height'] 			= 150;
			$resize['quality'] 			= '100%';

			$this->image_lib->initialize($resize);
			$this->image_lib->resize();//thumb image end


			$folder_resize = $folder.'/medium/';//for medium image 
			if($path){
				$this->makedirsBk($folder_resize);
			}else{
				$this->makedirs($folder_resize);
			}
			

			$resize1['source_image'] 	= $image_data['full_path'];
			$resize1['new_image'] 		= realpath(FCPATH .$realpath.$folder_resize);
			$resize1['maintain_ratio'] 	= FALSE;
			$resize1['width'] 			= $width;
			$resize1['height'] 			= $hieght;
			$resize1['quality'] 		= '100%';

			$this->image_lib->initialize($resize1);
			$this->image_lib->resize();//medium image end


			$folder_large = $folder.'/large/';//for large image 
			if($path){
				$this->makedirsBk($folder_large);
			}else{
				$this->makedirs($folder_large);
			}
			

			$resize2['source_image'] 	= $image_data['full_path'];
			$resize2['new_image'] 		= realpath(FCPATH .$realpath.$folder_large);
			$resize2['maintain_ratio'] 	= FALSE;
			$resize2['width'] 			= 1024;
			$resize2['height'] 			= 768;
			$resize2['quality'] 		= '100%';

			$this->image_lib->initialize($resize2);
			$this->image_lib->resize();//large image end

			$this->image_lib->clear();

			return $image_data['file_name'];
		}

	} // End Function
	function updateGallery($fileName,$folder,$hieght=250,$width=250)
	{
		  	$this->makedirs($folder);

			$storedFile 		= array();
			$allowed_types 		= "gif|jpg|png|jpeg|PNG|JPG|JPEG|GIF"; 
			$files 				= $_FILES[$fileName];
			$number_of_files 	= sizeof($_FILES[$fileName]['tmp_name']);

			// we first load the upload library
			$this->load->library('upload');
			// next we pass the upload path for the images
			$configG['upload_path'] 		= 'uploads/'.$folder;
			$configG['allowed_types'] 		= $allowed_types;
			$configG['max_size']    		= '2048000';
			$configG['encrypt_name']  		= 'TRUE';
			$configG['quality'] 			= '100%';
	   
			// now, taking into account that there can be more than one file, for each file we will have to do the upload
			for ($i = 0; $i < $number_of_files; $i++)
			{
				$_FILES[$fileName]['name'] 		= $files['name'][$i];
				$_FILES[$fileName]['type'] 		= $files['type'][$i];
				$_FILES[$fileName]['tmp_name'] 	= $files['tmp_name'][$i];
				$_FILES[$fileName]['error'] 	= $files['error'][$i];
				$_FILES[$fileName]['size'] 		= $files['size'][$i];

				//now we initialize the upload library
				$this->upload->initialize($configG);
				if ($this->upload->do_upload($fileName))
				{
					$savedFile = $this->upload->data();//upload the image
				
					$folder_thumb = $folder.'/thumb/';
					$this->makedirs($folder_thumb);
					//your desired config for the resize() function
					$config1 = array(
						'image_library' 	=> 'gd2',
						'source_image' 		=> $savedFile['full_path'], //get original image
						'maintain_ratio' 	=> false,
						'create_thumb' 		=> TRUE,
						'width' 			=> 100,
						'height' 			=> 100,
						'new_image' 		=> realpath(FCPATH .'uploads/'.$folder_thumb),
						'quality'			=> '100%'
					);	
					$this->load->library('image_lib'); //load image_library
					$this->image_lib->initialize($config1);
					$this->image_lib->resize();
					$folder_resize = $folder.'/resize/';
					$this->makedirs($folder_resize);

					$resize1['source_image'] 	= $savedFile['full_path'];
					$resize1['new_image'] 		= realpath(FCPATH .'uploads/'.$folder_resize);
					$resize1['maintain_ratio'] 	= FALSE;
					$resize1['width'] 			= $width;
					$resize1['height'] 			= $hieght;
					$resize1['quality'] 		= '100%';

					$this->image_lib->initialize($resize1);
					$this->image_lib->resize();

						$storedFile[$i]['name'] = $savedFile['file_name'];
						$storedFile[$i]['type'] = $savedFile['file_type'];
					
					$this->image_lib->clear();


				}
				else
				{
					$storedFile[$i]['error'] = $this->upload->display_errors();
				}
			} // END OF FOR LOOP
		 
		return $storedFile;
		  
	}//FUnction


	function unlinkFile($path,$file){
		$main 	= $path.$file;
		$thumb 	= $path.'thumb/'.$file;
		$resize = $path.'resize/'.$file;

		if(file_exists(FCPATH.$main)):
			unlink( FCPATH.$main);
		endif;
		if(file_exists(FCPATH.$thumb)):
			unlink( FCPATH.$thumb);
		endif;
		if(file_exists(FCPATH.$resize)):
			unlink( FCPATH.$resize);
		endif;
		return TRUE;
	}//End function

}// End of class Image_model

?>
