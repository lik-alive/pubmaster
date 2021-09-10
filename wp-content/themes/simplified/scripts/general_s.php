<?php
	
	//-----Logging
	
	//Log event
	function g_lev($event, $function){
		$location = array('function' => $function);
			
		db_add_log(1, $event, $location, null, null, wp_get_current_user()->ID);
	}
	
	//Log DB event
	function g_ldv($event, $function, $table, $id, $new, $old){
		$location = array(
			'function' => $function,
			'table' => $table,
			'id' => $id);
			
		db_add_log(1, $event, $location, $new, $old, wp_get_current_user()->ID);
	}
	
	//Log error
	function g_ler($event, $function){
		$location = array('function' => $function);
			
		db_add_log(2, $event, $location, null, null, wp_get_current_user()->ID);
	}
	
	//Log DB error
	function g_ldr($event, $data){
		$location = array(
			'function' => $data['function'],
			'table' => $data['table'],
			'id' => $data['id']);
			
		db_add_log(2, $event.' ['.$data['details'].']', $location, $data['new'], $data['old'], wp_get_current_user()->ID);
	}
	
	//-----String functions
	
	//Secure input for DB search
	function g_si($data) {
		if (is_null($data)) return null;
	
		$data = trim($data);
		$data = stripslashes($data);
		$data = str_replace("'", "\'", $data);
		return $data;
	}
	
	//Clear POST data
	function g_cpd($arr) {
		//Remove all '' values
		foreach (array_keys($arr, '', true) as $key) {
			$arr[$key] = null;
		}
		//Mass strip slashes
		foreach ($arr as $key => $val) {
			if (!is_null($val)) $arr[$key] = trim(stripslashes($val));
		}
		
		return $arr;
	}
	
	//Check if string is mostly in russian
	function g_sir($str) {
		$rus = array();
		preg_match('/[а-яА-ЯёЁ ]+/', $str, $rus);
		$eng = array();
		preg_match('/[a-zA-Z ]+/', $str, $eng);
		$maxrus = 0;
		foreach ($rus as $one) {
			if (mb_strlen($one) > $maxrus) $maxrus = mb_strlen($one);
		}
		$maxeng = 0;
		foreach ($eng as $one) {
			if (mb_strlen($one) > $maxeng) $maxeng = mb_strlen($one);
		}
		return $maxrus > $maxeng;
	}
	
	//Change keyboard layout
	function g_ckl($data) {
		$str_rus = array(
			"й","ц","у","к","е","н","г","ш","щ","з","х","ъ",
			"ф","ы","в","а","п","р","о","л","д","ж","э",
			"я","ч","с","м","и","т","ь","б","ю","ё",
			"Й","Ц","У","К","Е","Н","Г","Ш","Щ","З","Х","Ъ",
			"Ф","Ы","В","А","П","Р","О","Л","Д","Ж","Э",
			"Я","Ч","С","М","И","Т","Ь","Б","Ю","Ё"
		);
		$str_eng = array(
			"q","w","e","r","t","y","u","i","o","p","[","]",
			"a","s","d","f","g","h","j","k","l",";","'",
			"z","x","c","v","b","n","m",",",".","`",
			"Q","W","E","R","T","Y","U","I","O","P","[","]",
			"A","S","D","F","G","H","J","K","L",";","'",
			"Z","X","C","V","B","N","M",",",".","~"
		);
		if (g_sir($data)) $revert = str_replace($str_rus, $str_eng, $data);
		else $revert = str_replace($str_eng, $str_rus, $data);
		return $revert;
	}
	
	//Is Russian char
	function g_irc($c) {
		$rus = "абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ";
		return mb_strpos($rus, $c) !== false;
	}
	
	//Is English char
	function g_iec($c) {
		$eng = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		return mb_strpos($eng, $c) !== false;
	}
	
	//Is Char
	function g_ic($c) {
		return g_irc($c) || g_iec($c);
	}
	
	//Is Number
	function g_in($c) {
		$eng = "0123456789";
		return mb_strpos($eng, $c) !== false;
	}
	
	//Transliterate
	function g_trs($st) {
		$cyr = [
			'ё','ж','ц','ч','ш','щ','ю','я',
			'Ё','Ж','Ц','Ч','Ш','Щ','Ю','Я',
			'а','б','в','г','д','е','з',
			'и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ъ','ы','ь','э',
			'ц','й','к','в','з',
            'А','Б','В','Г','Д','Е','З',
			'И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ъ','Ы','Ь','Э',
			'Ц','Й','К','В','З'
		];
		$lat = [
			'io','zh','ts','ch','sh','sht','yu','ya',
			'Io','Zh','Ts','Ch','Sh','Sht','Yu','Ya',
			'a','b','v','g','d','e','z',
			'i','y','k','l','m','n','o','p','r','s','t','u','f','h','a','i','y','e',
			'c','j','q','w','x',
            'A','B','V','G','D','E','Z',
			'I','Y','K','L','M','N','O','P','R','S','T','U','F','H','A','I','Y','e',
			'C','J','Q','W','X',
		];
		
		if (g_sir($st)) $st = str_replace($cyr, $lat, $st);
		else  $st = str_replace($lat, $cyr, $st);
		
		return $st;
	}
	
	//-----Other functions
	
	//Convert to json
	function g_ctj($data) {
		$rowscount = sizeof($data);
		$json_data = array(
			"draw"            => intval( $_REQUEST['draw'] ), 
			"recordsTotal"    => intval( $rowscount ),
			"recordsFiltered" => intval( $rowscount ), 
			"data"            => $data,
		);
		return json_encode($json_data);
	}
	
	//Redirect to 404
	function g_404() {
		global $wp_query;
		$wp_query->set_404();
		status_header(404);
		get_template_part(404); 
		exit();
	}
	
	//Translate date month
	function g_tdm($date) {
		$months = array(
			'January' => 'января',
			'February' => 'февраля',
			'March' => 'марта',
			'April' => 'апреля',
			'May' => 'мая',
			'June' => 'июня',
			'July' => 'июля',
			'August' => 'августа',
			'September' => 'сентября',
			'October' => 'октября',
			'November' => 'ноября',
			'December' => 'декабря');
			
		$month = date('F', strtotime($date));
		return $months[$month];
	}
	
	//Get array element by key safe
	function g_aes($arr, $key) {
		if (is_null($arr) || !array_key_exists($key, $arr)) return null;
		return $arr[$key];
	}
	
	//Custom exception with message for users
	class PublicException extends Exception { }
	
	//Custom exception with array parameters
	class DataException extends Exception
	{
		private $funname;
		private $data;

		public function __construct($message, $data = null) {
			parent::__construct($message);
			$this->data = $data; 
		}

		public function getData() { return $this->data; }
	}
	
	