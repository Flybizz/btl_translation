
<?php 

	/**
	* COMPRESSOR DE IMAGEM
	*/
	class App_tool_ImgCompressor
	{
		
		public function compress($source, $destination, $quality) {
 
		    $info = getimagesize($source);
		 
		    if ($info['mime'] == 'image/jpeg') 
		        $image = imagecreatefromjpeg($source);
		 
		    elseif ($info['mime'] == 'image/gif') 
		        $image = imagecreatefromgif($source);
		 
		    elseif ($info['mime'] == 'image/png') 
		        $image = imagecreatefrompng($source);
		 
		    imagejpeg($image, $destination, $quality);
		 
		    return $destination;
		}
			
			

		public function progressive($source, $destination, $quality) {
 		    
		    $im = imagecreatefromstring(file_get_contents($source));

			if ($im !== false) {

				$info = getimagesize($source);
			 
			    if ($info['mime'] == 'image/jpeg'): 
			        imagejpeg($im, $destination, $quality);
			        imagedestroy($im);				 
			    elseif ($info['mime'] == 'image/gif'): 
			        imagegif($im, $destination, $quality);
			        imagedestroy($im);				 
			    elseif ($info['mime'] == 'image/png'):
			    	$image = imagecreatefrompng($source);
			    	imagealphablending($image, false);
			    	imagesavealpha($image, true); 
			        imagepng($image, $destination, 9);
			        imagedestroy($image);	
				endif;		    
			}
			else {
			    echo 'An error occurred.';
			}

			return $destination;
		}

	}

?>


