<?php
	$FILESFOLDER = ABSPATH.'files/';
	$FILESURL = 'files/';
	$TEMPDIR = 'temp/';
	
	//-----General file functions
	
	//Secure file name
	function secureFileNaming($filename) {
		$result = mb_ereg_replace('[ ]', '_', $filename);
		$result = mb_ereg_replace('[^а-яА-ЯёЁa-zA-Z0-9\-_.]', '', $result);
		return $result;
	}
	
	//Get filesystem path
	function files_get_absolute_path($subpath) {
		global $FILESFOLDER;
		if (false === mb_strpos($subpath, '.')) 
			if ($subpath[mb_strlen($subpath) - 1] !== '/') $subpath .= '/';
		return $FILESFOLDER.$subpath;
	}
	
	//Get url path
	function files_get_url_path($subpath) {
		global $FILESURL;
		return get_site_url(null, $FILESURL.$subpath);
	}
	
	//Check is file exists
	function isFileExists($filename) {
		if (is_null($filename) || empty($filename)) return false;
		
		global $FILESFOLDER;
		return file_exists($FILESFOLDER.$filename);
	}
	
	//Get file info
	function getFileInfo($filename) {
		if (isFileExists($filename)) return array(
			'name' => basename($filename),
			'size' => filesize(files_get_absolute_path($filename)),
			'path' => $filename
		);
		else return null;
	}
	
	//Get all files
	function getAllFiles($subfolder) {
		global $FILESFOLDER;
		$folder = files_get_absolute_path($subfolder);
		$result = array();
		$files = glob($folder.'*');
		foreach($files as $file){
			if (is_file($file)) $result[] = array(
				'name' => basename($file),
				'size' => filesize($file),
				'path' => mb_substr($file, mb_strlen($FILESFOLDER))
			);
		}
		return $result;
	}
	
	//Make directory
	function files_make_directory_TH($subfolder) {
		global $FILESFOLDER;
		if (!file_exists($FILESFOLDER.$subfolder)) {
			$res = mkdir($FILESFOLDER.$subfolder, 0777, true);
			if (!$res) throw new Exception("Ошибка создания папки '".$FILESFOLDER.$subfolder."'");
		}
	}
	
	//Write text to file (in FILE directory)
	function files_write_text_TH($text, $filename, $encoding = 'UTF-8') {
		global $FILESFOLDER;
		$result = file_put_contents(
			$FILESFOLDER.$filename, 
			mb_convert_encoding($text, $encoding)
		);
		if (!$result) throw new Exception("Ошибка создания файла '{$filename}'");
	}
	
	//Copy file
	function copyFile_TH($frompath, $topath) {
		$fr = fopen($frompath, "r");
		if (!$fr) throw new Exception("Ошибка открытия файла '{$frompath}'");
		$contents = fread($fr, filesize($frompath));
		fclose($fr);
			
		$fw = fopen($topath, "w");
		if (!$fw) throw new Exception("Ошибка создания файла '{$topath}'");
		fwrite($fw, $contents);
		fclose($fw);
	}
	
	//Rearrange file array
	function files_rearrange($files) {
		$result = array(); 
		foreach($files as $key1 => $value1) 
			foreach($value1 as $key2 => $value2) 
				$result[$key2][$key1] = $value2; 
		return $result; 
	}
	
	//Copy uploaded files
	function files_copy_uploaded_files_TH($uploaded, $subfolder){
		$folder = files_get_absolute_path($subfolder);
		$partCount = sizeof($uploaded['name']);
		
		files_make_directory_TH($subfolder);
		
		$files = array();
		for ($partNo = 0; $partNo < $partCount; $partNo++){
			$filename = secureFileNaming($uploaded['name'][$partNo]);
			copyFile_TH($uploaded['tmp_name'][$partNo], $folder.$filename);
			$files[] = getFileInfo($subfolder.$filename);
		}	
		
		return $files;
	}
	
	//Copy uploaded files to temp directory
	function files_copy_uploaded_files_to_temp_TH($uploaded) {
		global $TEMPDIR;
		return files_copy_uploaded_files_TH($uploaded, $TEMPDIR);
	}
	
	//Copy uploaded file
	function files_copy_uploaded_file_TH($uploaded, $subfolder){
		$folder = files_get_absolute_path($subfolder);
		files_make_directory_TH($subfolder);
		copyFile_TH($uploaded['tmp_name'], $folder.secureFileNaming($uploaded['name']));
	}
	
	//Update file (delete old version)
	function files_update_TH($uploaded, $ID_Article) {
		$subfolder = "{$ID_Article}/";
		$uploaded['name'] = $ID_Article.'.pdf';
		clearFolder_TH($subfolder);
		files_copy_uploaded_file_TH($uploaded, $subfolder);
	}
	
	//Zip files
	function files_zip_files_TH($files, $zipname) {
		global $TEMPDIR;
		$folder = files_get_absolute_path($TEMPDIR);
		
		files_make_directory_TH($TEMPDIR);
		
		$zip = new ZipArchive();
		$res = $zip->open($folder.$zipname.'.zip', ZipArchive::CREATE);
		if ($res !== true) throw new Exception("Ошибка создания архива '{$folder}{$zipname}.zip'");
		
		foreach ($files as $file) {
			$res = $zip->addFile(files_get_absolute_path($file['path']), secureFileNaming($file['name']));
			if (!$res) throw new Exception("Ошибка добавления файла '".secureFileNaming($file['name'])."' в архив");
		}
			
		$zip->close();
		
		return $TEMPDIR.$zipname.'.zip';
	}
	
	//Zip uploaded files
	function files_zip_uploaded_files_TH($uploaded, $subfolder, $zipname){
		$folder = files_get_absolute_path($subfolder);
		$partCount = sizeof($uploaded['name']);
		
		files_make_directory_TH($subfolder);
		
		//Check if the file is already an archive
		$archives = array('rar', 'zip');
		$extension = mb_strtolower(pathinfo($uploaded['name'][0], PATHINFO_EXTENSION));
		if ($partCount === 1 && in_array($extension, $archives)) {
			copyFile_TH($uploaded['tmp_name'][0], $folder.str_replace('zip', $extension, $zipname));
			return;
		}
		
		$zip = new ZipArchive();
		$res = $zip->open($folder.$zipname, ZipArchive::CREATE);
		if ($res !== true) throw new Exception("Ошибка создания архива '{$folder}{$zipname}'");
		
		for ($partNo = 0; $partNo < $partCount; $partNo++){
			$res = $zip->addFile($uploaded['tmp_name'][$partNo], secureFileNaming($uploaded['name'][$partNo]));
			if (!$res) throw new Exception("Ошибка добавления файла '".secureFileNaming($uploaded['name'][$partNo])."' в архив");
		}
			
		$zip->close();
	}
	
	//Delete file
	function files_delete_file_TH($subfolder, $filename){
		$folder = files_get_absolute_path($subfolder);
		$file = $folder.$filename;
		if(is_file($file) && file_exists($file)) {
			$res = unlink($file);
			if (!$res) throw new Exception("Ошибка удаления файла '{$file}'");
		}
	}
	
	//Delete all files in the folder
	function clearFolder_TH($subfolder, $mask = '*') {
		$folder = files_get_absolute_path($subfolder);
		$files = glob($folder.$mask);
		foreach($files as $file){
			if(is_file($file)) {
				$res = unlink($file);
				if (!$res) throw new Exception("Ошибка удаления файла '{$file}'");
			}
		}
	}
	
	//Delete directory
	function deleteDirectory_TH($subfolder){
		$folder = files_get_absolute_path($subfolder);
		if (file_exists($folder)) innerDeleteAll_TH($folder);
	}
	
	//Delete directory inner
	function innerDeleteAll_TH($folder){
		global $GARR;
		$files = glob($folder.'*');
		foreach($files as $file){
			if(is_file($file)) {
				$res = unlink($file);
				if (!$res) throw new Exception("Ошибка удаления файла '{$file}'");
			}
			else innerDeleteAll_TH($file.'/');
		}
		$res = rmdir($folder);
		if (!$res) throw new Exception("Ошибка удаления папки '{$folder}'");
	}
	
	//-----Specialized file functions
	
	//Get article pdf-file url
	function files_get_article_pdf_url($ID_Article) {
		$file = files_get_article_pdf($ID_Article);
		if (!is_null($file)) return files_get_url_path($file['path']);
		return null;
	}
	
	//Get program pdf-file url
	function files_get_program_pdf_url($ID_Program) {
		$file = files_get_program_pdf($ID_Program);
		if (!is_null($file)) return files_get_url_path($file['path']);
		return null;
	}
	
	//Get article pdf-file
	function files_get_article_pdf($ID_Article) {
		$filename = "articles/{$ID_Article}/{$ID_Article}.pdf";
		return getFileInfo($filename);
	}
	
	//Get program pdf-file
	function files_get_program_pdf($ID_Program) {
		$filename = "programs/{$ID_Program}/{$ID_Program}.pdf";
		return getFileInfo($filename);
	}
	
	//Get article pdf-file 
	add_action('wp_ajax_files_get_article_pdf_json', 'files_get_article_pdf_json');
	function files_get_article_pdf_json() {
		$ID_Article = $_GET['id'];
		
		echo g_ctj(files_get_article_pdf($ID_Article));
		exit();
	}
	