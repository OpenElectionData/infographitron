<?php

/**
 * Class UploaderModel
 *
 */
class UploaderModel
{
	private static function slugify($text)
	{
	  // replace non letter or digits by -
	  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

	  // transliterate
	  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

	  // remove unwanted characters
	  $text = preg_replace('~[^-\w]+~', '', $text);

	  // trim
	  $text = trim($text, '-');

	  // remove duplicate -
	  $text = preg_replace('~-+~', '-', $text);

	  // lowercase
	  $text = strtolower($text);

	  if (empty($text)) {
	    return 'n-a';
	  }

	  return $text;
	}

	public static function uploadAsset($filename, $assetType, $permissions, $tags = null) {

		$safeFilename = UploaderModel::slugify($filename);
		$safeFilename = time().'-'.$safeFilename;

		if($assetType == "fonts") {
			$uploaddir = Config::get('PATH_ASSETS')."fonts/";
			$uploadfile = $uploaddir . basename($_FILES['file']['name']);

			if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
			    // Save asset in the database
		        UploaderModel::saveAssetDB($assetType, $filename, basename($_FILES['file']['name']), $permissions);
			} else {
			    echo "Possible file upload attack!\n";
			}
		}
		else {
			$image = new Bulletproof\Image($_FILES);

			if($tags) {
				$tags = implode(",",$tags);
			}

			if($image["file"]){
				// set max width/height limits (in pixels)
				$image->setDimension(1000, 1000);

				// define min/max size limits for upload (size in bytes) 
				$image->setSize(0, 5000000); 

				// define acceptable mime types
				$image->setMime(array("jpeg", "gif", "png")); 

				// pass name (and optional chmod) to create folder for storage
				$image->setLocation(Config::get('PATH_ASSETS').$assetType, 777);

				// Set Name
				$image->setName($safeFilename);


			    $upload = $image->upload(); 

			    if($upload){
			        // Save asset in the database
			        UploaderModel::saveAssetDB($assetType, $filename, $image->getName().".".$image->getMime(), $permissions, $tags);

			    }
			    else {
			    	Session::add('feedback_negative', $image["error"]);
			            // return false;
			        // echo $image["error"]; 
			        // die();
			    }
			}
		}
	}

	private static function saveAssetDB($assetType, $filename, $asset, $permissions, $tags = null) {

		// Format Dates
        $date = date("Y-m-d H:i:s", time());

        // Permissions
        if($permissions == "public") {
        	$permission = -1;
        }
        else {
        	$permission = Session::get('user_id');
        }

		$database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO assets (type, title, file, uploaded_by, viewable_by, created_date, tags) VALUES (:type, :title, :file, :uploaded_by, :viewable_by, :created_date, :tags)";
        $query = $database->prepare($sql);
        $query->execute(array(':type' => $assetType, ':title' => $filename, ':file' => $asset, ':uploaded_by' => Session::get('user_id'), ':viewable_by' => $permission, ':created_date' => $date, ':tags' => $tags));

        if ($query->rowCount() == 1) {
        	Session::add('feedback_positive', Text::get('FEEDBACK_INFOGRAPHIC_CREATION_SUCCEEDED'));
            return true;
        }

        // default return
        Session::add('feedback_negative', Text::get('FEEDBACK_INFOGRAPHIC_CREATION_FAILED'));
        return false;
	}

	public static function reArrayFiles(&$file_post) {
	    $file_ary = array();
	    $file_count = count($file_post['name']);
	    $file_keys = array_keys($file_post);

	    for ($i=0; $i<$file_count; $i++) {
	        foreach ($file_keys as $key) {
	            $file_ary[$i][$key] = $file_post[$key][$i];
	        }
	    }

	    return $file_ary;
	}

}
