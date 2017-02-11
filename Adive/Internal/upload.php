<?php
// upload.php
// 'images' refers to your file input name attribute
if (empty($_FILES[$_POST['inputname']])) {
    echo json_encode(['error'=>'No files found for upload.']); 
    // or you can throw an exception 
    return; // terminate
}

// get the files posted
$images = $_FILES[$_POST['inputname']];

// a flag to see if everything is ok
$success = null;

// file paths to store
$paths= [];

// get file names
$filenames = $images['name'];

// loop and process files
if(count($filenames)>1) {
	for($i=0; $i < count($filenames); $i++){
		//$ext = explode('.', basename($filenames[$i]));
		$target = $_POST['uploadfolder']."/" . $filenames[$i];
		//$target = "uploads/upl_" . $filenames ;
		//if(move_uploaded_file($images['tmp_name'][$i], $target)) {
		if(file_exists($images['tmp_name'][$i])){
			$existe = "existe";
		}
		if(move_uploaded_file($images['tmp_name'][$i], $target)) {
			$success = true;
			$paths[] = $target;
		} else {
			$success = false;
			break;
		}
	}
} else {
		$target = $_POST['uploadfolder']."/" . $filenames;
		//$target = "uploads/upl_" . $filenames ;
		//if(move_uploaded_file($images['tmp_name'][$i], $target)) {
		if(file_exists($images['tmp_name'])){
			$existe = "existe";
		}
		if(move_uploaded_file($images['tmp_name'], $target)) {
			$success = true;
			$paths[] = $target;
		} else {
			$success = false;
		}
}

// check and process based on successful status 
if ($success === true) {
    // call the function to save all data to database
    // code for the following function `save_data` is not 
    // mentioned in this example
    //save_data($userid, $username, $paths);

    // store a successful response (default at least an empty array). You
    // could return any additional response info you need to the plugin for
    // advanced implementations.
    $output = [];
    // for example you can get the list of files uploaded this way
    // $output = ['uploaded' => $paths];
} elseif ($success === false) {
    $output = ['error'=>'Error while uploading images. Check permissions on /Adive/uploads folder.'];
    // delete any uploaded files
    foreach ($paths as $file) {
        //unlink($file);
    }
} else {
    $output = ['error'=>'No files were processed.'];
}

// return a json encoded response for plugin to process successfully
echo json_encode($output);
