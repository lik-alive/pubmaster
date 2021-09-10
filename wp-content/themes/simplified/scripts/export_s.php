<?php
	$EXPORT_COMS = array('%AT%', '%ATA%', '%JT%', '%JCP%', '%IV%', '%II%', '%IVI%', '%IY%', '%APF%', '%APL%', '%APP%', '%ADOI%', '%AKW%', '%AIN%', '%CT%', '%CCI%', '%CCO%', '%CDF%', '%CDL%', '%CD%', '%PRN%', '%PT%', '%PTR%', '%PTE%', '%PY%', '%PO%', '%AFS%', '%AFI%', '%AS%', '%ASI%', '%NUM%');
	
	
	//Get list of saved templates
	function export_templates_list() {
		$type = '';
		if (!is_null($_GET['ID_Articles'])) $type = 'A';
		else if (!is_null($_GET['ID_Conferences'])) $type = 'C';
		else if (!is_null($_GET['ID_Programs'])) $type = 'P';
		
		global $wpdb;
		$result =  $wpdb->get_results(
		"SELECT *
		FROM wp_ab_templates t
		ORDER BY 
			CASE t.Type
				WHEN '{$type}' THEN 0
				ELSE 1
			END, 
			t.ID_Template", 'ARRAY_A');
		
		return $result;
	}
	
	//Get template by id
	add_action('wp_ajax_export_get_template_json', 'export_get_template_json');
	function export_get_template_json() {
		$ID_Template = $_GET['id'];
		global $wpdb;
		$result =  $wpdb->get_row(
		"SELECT *
		FROM wp_ab_templates t
		WHERE t.ID_Template={$ID_Template}", 'ARRAY_A');
		
		echo g_ctj($result);
		exit();
	}
	
	//Create string representation
	add_action('wp_ajax_export_process_json', 'export_process_json');
	function export_process_json(){	
		$ids = array();
		$type = '';
		if (!empty($_GET['ID_Articles'])) {
			$ids = $_GET['ID_Articles'];
			$type = 'A';
		}
		else if (!empty($_GET['ID_Programs'])) {
			$ids = $_GET['ID_Programs'];
			$type = 'P';
		}
		
		//Create format array
		global $EXPORT_COMS;
		$str = stripslashes($_GET['Format']);
		$str = str_replace('\t', "\t", $str);
		$str = str_replace('\n', "\n", $str);
		
		$format = array();
		$word = '';
		for ($i = 0; $i < mb_strlen($str); $i++) {
			$ch = mb_substr($str, $i, 1);
			
			if ($ch === '%') {
				if (empty($word)) $word = $ch;
				else {
					//Add command
					if (in_array($word.$ch, $EXPORT_COMS)) {
						$format[] = $word.$ch;
						$word = '';
					}
					//Add % symbol
					else {
						$format[] = $word;
						$word = $ch;
					}
				}
			}
			else $word .= $ch;
		}
		if (!empty($word)) $format[] = $word;
		
		//Parse string
		global $COUNTRIES_ENG;
		$text = '';
		$i = 0;
		foreach ($ids as $id) {
			$i++;
			if ($type === 'A') $ent = articles_list_ext(array('ID_Article' => $id))[0];
			else if ($type === 'P')  $ent = programs_list_ext(array('ID_Program' => $id))[0];
			
			$authors = $ent['Authors'];
			
			$strs = array();
			
			for ($j = 0; $j < sizeof($format); $j++) {
				switch($format[$j]) {	
					//Article
					case '%AT%': 
						$strs[] = $ent['Title'];
						break;
					case '%ATA%':  
						$strs[] = $ent['TitleAlt'];
						break;
					case '%JT%':  
						$strs[] = $ent['JTitle'];
						$rus = g_sir($ent['JTitle']);
						break;
					case '%JCP%':  
						$city = $ent['City'];
						$unset = false;
						if (is_null($city)) $unset = true;
						else if ($city === 'M' || $city === 'М' || $city === 'СПб') $city .= '.';
						
						if (is_null($ent['Publisher'])) $unset = true;
						//Remove previous separator
						if ($unset) array_pop($strs);
						else $strs[] = $city.': '.$ent['Publisher'];
						break;
					case '%IV%': 
						$strs[] = $ent['VolumeNo'];
						break;
					case '%II%':  
						$strs[] = $ent['IssueNo'];
						break;
					case '%IVI%': 
						$unsetV = false;
						$vol = '';
						if (!is_null($ent['VolumeNo'])) {
							if ($rus) $vol .= 'Т. ';
							else $vol .= 'Vol. ';
							$vol .= $ent['VolumeNo'];
						} else $unsetV = true;
				
						$unsetI = false;
						$iss = '';
						if (!is_null($ent['IssueNo'])) {
							if ($rus) $iss .= '№ ';
							else $iss .= 'Iss. ';
							$iss .= $ent['IssueNo'];
						} else $unsetI = true;
						
						if ($unsetV && $unsetI) array_pop($strs);
						else if ($unsetV) $strs[] = $iss;
						else if ($unsetI) $strs[] = $vol;
						else $strs[] = $vol.', '.$iss;
						break;
					case '%IY%':
						$strs[] = $ent['Year'];
						break;
					case '%APF%':  
						$strs[] = $ent['PageFrom'];
						break;
					case '%APL%':  
						$strs[] = $ent['PageTo'];
						break;
					case '%APP%':  
						if ($rus) $tmp = 'С.';
						else $tmp = 'P.';
						$strs[] = $tmp.' '.$ent['PageFrom'].'-'.$ent['PageTo'];
						break;
					case '%ADOI%':  
						$strs[] = $ent['DOI'];
						break;
					case '%AKW%':  
						$strs[] = str_replace("\n", '; ', $ent['Keywords']);
						break;
					case '%AIN%':
						$tmp = '';
						if ($ent['Indexing'] & 1) $tmp .= 'РИНЦ, ';
						if ($ent['Indexing'] & 2) $tmp .= 'ВАК, ';
						if ($ent['Indexing'] & 4) $tmp .= 'Scopus, ';
						if ($ent['Indexing'] & 8) $tmp .= 'WoS, ';
						
						if (!empty($tmp)) $strs[] = mb_substr($tmp, 0, mb_strlen($tmp)-2);
						break;
					
					//Conference
					case '%CT%':  
						$strs[] = $ent['CTitle'];
						$rus = g_sir($ent['CTitle']);
						break;
					case '%CCI%':  
						$strs[] = $ent['CCity'];
						break;
					case '%CCO%':  
						$country = $COUNTRIES_ENG[$ent['Country']];
						if ($ent['Country'] === 'RUS' && g_sir($ent['CTitle'])) $country = 'Россия';
						$strs[] = $country;
						break;
					case '%CD%':  
						$datestr = '';
						if (!is_null($ent['DateFrom'])) {
							$dayFrom = date('j', strtotime($ent['DateFrom']));
							$monthFrom = date('F', strtotime($ent['DateFrom']));
							$year = date('Y', strtotime($ent['DateFrom']));
							
							$dayTo = date('j', strtotime($ent['DateTo']));
							$monthTo = date('F', strtotime($ent['DateTo']));
							$year = date('Y', strtotime($ent['DateTo']));
							
							if ($rus) {								
								$monthFrom = g_tdm($monthFrom);
								$monthTo = g_tdm($monthTo);
								
								if ($monthTo !== $monthFrom) 
									$datestr .= $dayFrom.' '.$monthFrom.' - '.$dayTo.' '.$monthTo.' ';
								else {
									if ($dayFrom !== $dayTo) $datestr .= $dayFrom.' - '.$dayTo.' '.$monthTo.' ';
									else $datestr .= $dayFrom.' '.$monthTo.' ';
								}
							}
							else {
								if ($monthTo !== $monthFrom) 
									$datestr .= $monthFrom.' '.$dayFrom.' - '.$monthTo.' '.$dayTo.', ';
								else {
									if ($dayFrom !== $dayTo) $datestr .= $monthTo.' '.$dayFrom.' - '.$dayTo.', ';
									else $datestr .= $monthTo.' '.$dayFrom.', ';
								}
							}
							
							$datestr .= $year;
						}	
						$strs[] = $datestr;
						break;
					case '%CDF%':  
						if (!is_null($ent['DateFrom'])) $strs[] = date('d.m.Y', strtotime($ent['DateFrom']));
						else $strs[] = '';
						break;
					case '%CDL%':  
						if (!is_null($ent['DateTo'])) $strs[] = date('d.m.Y', strtotime($ent['DateTo']));
						else $strs[] = '';
					
					//Program
					case '%PRN%':  
						$strs[] = $ent['RegNo'];
						break;
					case '%PT%':  
						$fulltitle = $ent['TitleRus'];
						if (!is_null($ent['TitleEng'])) $fulltitle .= ' “' . $ent['TitleEng'] . '“';
						$strs[] = $fulltitle;
						break;
					case '%PTR%':  
						$strs[] = $ent['TitleRus'];
						break;
					case '%PTE%':  
						$strs[] = $ent['TitleEng'];
						break;
					case '%PY%':  
						$strs[] = $ent['Year'];
						break;
					case '%PO%':  
						$strs[] = $ent['Owner'];
						break;
						
					//Authors
					case '%AFS%':  
						$strs[] = $authors[0]['Surname'];
						break;
					case '%AFI%':  
						$strs[] = $authors[0]['Initials'];
						break;
					case '%AS%':  
						$tmp = ', ';
						for ($k = 0; $k < sizeof($authors); $k++) {
							if ($k === sizeof($authors) - 1) $tmp = '';
							$strs[] = $authors[$k]['Initials'].' '.$authors[$k]['Surname'].$tmp;
						}
						break;
					case '%ASI%':  
						$tmp = ', ';
						for ($k = 0; $k < sizeof($authors); $k++) {
							if ($k === sizeof($authors) - 1) $tmp = '';
							$strs[] = $authors[$k]['Surname'].' '.$authors[$k]['Initials'].$tmp;
						}
						break;
					case '%NUM%':  
						$strs[] = $i;
						break;
						
					default:
						$strs[] = $format[$j];
						break;
				}
			}
			
			foreach ($strs as $str) $text .= $str;
			$text .= "\n";
		}
		
		echo g_ctj($text);
		exit();
	}
	
	