<?php
	/*
		Template Name: Download
	*/
	
	//Decode ids
	$ids = array();
	$type = '';
	if (!is_null($_GET['ID_Articles'])) {
		$ids = json_decode(str_replace("\\", "", $_GET['ID_Articles']));
		$type = 'A';
	}
	else if (!is_null($_GET['ID_Programs'])) {
		$ids = json_decode(str_replace("\\", "", $_GET['ID_Programs']));
		$type = 'P';
	}
	
	//Find all files
	$files = array();
	foreach ($ids as $id) {
		if ($type === 'A') $file = files_get_article_pdf($id);
		else if ($type === 'P')  $file = files_get_program_pdf($id);
		
		if (!is_null($file)) $files[] = $file;
	}
	
	//Combine files
	if (sizeof($files) === 1) $result = files_get_absolute_path($files[0]['path']);
	else if (sizeof($files) > 1) {
		try {
			$archive = files_zip_files_TH($files, date('Y-m-d H-i-s'));
		} 
		catch(Exception $e) {
		}
		$result = files_get_absolute_path($archive);
	}
	
	
	//Download result
	if (!is_null($result)) {
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="'.basename($result).'"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($result));
		readfile($result);
		exit();
	}
	
	get_header(); 
?>

<h1 style='text-align:center; width: 100%'><?php if (is_null($result)) echo 'А скачивать нечего...'; ?></h1>

<?php get_footer(); ?>