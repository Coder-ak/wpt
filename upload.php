<?php
require("auth.php");

$output_dir = "gpx/";

function check_name($filename){
	global $output_dir;
	$i = 0;
	while (file_exists($output_dir.$filename)) {
		$file = pathinfo($filename);
		$curname = $file['filename'];
		if (preg_match("/(.+)?_\[(\d+)\]$/", $curname, $matches)){
			$i = $matches[2];
			$curname = $matches[1];
		}

		$filename = $curname."_[".++$i."].".$file['extension'];
	}
	return $filename;
}

if(isset($_FILES["myfile"]))
{
	$ret = array();

	$error =$_FILES["myfile"]["error"];
	//You need to handle  both cases
	//If Any browser does not support serializing of multiple files using FormData() 
	if(!is_array($_FILES["myfile"]["name"])) //single file
	{
 	 	$fileName = check_name($_FILES["myfile"]["name"]);
 		move_uploaded_file($_FILES["myfile"]["tmp_name"],$output_dir.$fileName);
    	$ret[]= $fileName;
	}
	else  //Multiple files, file[]
	{
	  $fileCount = count($_FILES["myfile"]["name"]);
	  for($i=0; $i < $fileCount; $i++)
	  {
	  	$fileName = $_FILES["myfile"]["name"][$i];
		move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$output_dir.$fileName);
	  	$ret[]= $fileName;
	  }
	
	}
    echo json_encode($ret);
 }
 ?>