<?php
	function at_author($lexemes, $pos){
		$spos = $pos;
		$stateid = array('0' => 0, '1a' => 1, '2a' => 2, '3a' => 3, '4a' => 4, '5a' => 5, 
						'1b' => 6, '2b' => 7, '3b' => 8, '4b' => 9);
		$lextypeid = array('t' => 0, 'st' => 1,  ',' => 2, '.' => 3, ' ' => 4, 'oth' => 5);
		$matrix = array (
			array('1a', '1b',  'E',  'E',  'E',  'E'),//0
			array( 'E',  'E', '2a',  'E', '2a',  'E'),//1a 
			array( 'E', '3a',  'E',  'E', '2a',  'E'),//2a
			array( 'E',  'E',  'E', '4a',  'E',  'E'),//3a
			array('FR', '5a', 'FR',  'E', '4a',  'E'),//4a
			array('FD', 'FD', 'FD',  'F', 'FD', 'FD'),//5a
			array( 'E',  'E',  'E', '2b',  'E',  'E'),//1b
			array( 'F', '3b',  'E',  'E', '2b',  'E'),//2b
			array( 'E',  'E',  'E', '4b',  'E',  'E'),//3b
			array( 'F',  'E',  'E',  'E', '4b',  'E') //4b
		);
		
		$state = '0';
		$authorS = '';
		$authorN = '';
		$authorP = '';
		while ($pos < sizeof($lexemes)) {
			$lextype = $lexemes[$pos][0];
			if ($lextype === 't' && mb_strlen($lexemes[$pos][1]) <= 2) $lextype = 'st';
			$col = $lextypeid[$lextype];
			if ($col === null) $col = $lextypeid['oth'];
			
			$row = $stateid[$state];
			
			$state = $matrix[$row][$col];
			
			if ($state === '1a') $authorS = $lexemes[$pos][1];
			if ($state === '3a') $authorN = $lexemes[$pos][1].'.';
			if ($state === '5a') $authorP = $lexemes[$pos][1].'.';
			if ($state === '1b') $authorN = $lexemes[$pos][1].'.';
			if ($state === '3b') $authorP = $lexemes[$pos][1].'.';
			
			if ($state === 'E') return null;
			if ($state === 'FR') {
				$authorP = '';
				$pos -= 1;
				$state = 'F';
			}
			if ($state === 'FD') {
				$authorP = '';
				$pos -= 2;
				$state = 'F';
			}
			if ($state === 'F') {
				if ($authorS === '') $authorS = $lexemes[$pos][1];
				return array($spos, $pos, $authorS, $authorN.$authorP);
			}
			
			$pos++;
		}
		return null;
	}
	
	function at_authors($lexemes, $pos){
		$spos = $pos;
		$stateid = array('0' => 0, '1' => 1);
		$lextypeid = array('au' => 0, ',' => 1, '.' => 2, ' ' => 3, 'oth' => 4);
		$matrix = array (
			array('1', 'E', 'E', '0', 'F'),//0
			array('1', '0', '0', '0', 'F')//1
		);
		
		$state = '0';
		$no = 1;
		$authors = array();
		while ($pos < sizeof($lexemes)) {
			$res = at_author($lexemes, $pos);
			if ($res !== null) {
				$lextype = 'au';
				$pos = $res[1];
			}
			else $lextype = $lexemes[$pos][0];
			$col = $lextypeid[$lextype];
			if ($col === null) $col = $lextypeid['oth'];
			
			$row = $stateid[$state];
			
			$state = $matrix[$row][$col];
			
			if ($state === '1') $authors[] = array('SeqNo' => $no++,'Surname' => $res[2],'Initials' => $res[3]);
			
			if ($state === 'E') return null;
			if ($state === 'F') {
				return array($spos, $pos - 1, $authors);
			}
			
			$pos++;
		}
		return null;
	}
	
	function at_title($lexemes, $pos){
		$spos = $pos;
		$stateid = array('0' => 0, '1' => 1, '2' => 2);
		$lextypeid = array('/' => 0, '.' => 1, '[' => 2, ']' => 3, 'oth' => 4);
		$matrix = array (
			array('F', 'F', '1', 'F', '0'),//0
			array('1', '1', '1', '2', '1'),//1
			array('F', 'F', '0', '0', '0') //2
		);
		
		$state = '0';
		$title = '';
		while ($pos < sizeof($lexemes)) {
			$lextype = $lexemes[$pos][0];
			$col = $lextypeid[$lextype];
			if ($col === null) $col = $lextypeid['oth'];
			
			$row = $stateid[$state];
			
			$state = $matrix[$row][$col];
			
			if ($state === '0') $title .= $lexemes[$pos][1];
			
			if ($state === 'E') return null;
			if ($state === 'F') {
				return array($spos, $pos + 1, trim($title));
			}
			
			$pos++;
		}
		return null;
	}
	
	function at_city($lexemes, $pos){
		$spos = $pos;
		$stateid = array('0' => 0, '1' => 1, '2' => 2);
		$lextypeid = array('t' => 0, '.' => 1, ':' => 2, 'oth' => 3);
		$matrix = array (
			array( '1', 'E', 'E', 'E'),//0
			array( 'E', '2', 'F', 'E'),//1
			array( 'E', 'E', 'F', 'E') //2
		);
		
		$state = '0';
		$city = '';
		while ($pos < sizeof($lexemes)) {
			$lextype = $lexemes[$pos][0];
			$col = $lextypeid[$lextype];
			if ($col === null) $col = $lextypeid['oth'];
			
			$row = $stateid[$state];
			
			$state = $matrix[$row][$col];
			
			if ($state === '1') $city = $lexemes[$pos][1];
			
			if ($state === 'F') {
				//NOTE fast-fix
				if ($city === 'Physics') return null;
				return array($spos, $pos, $city);
			}
			
			$pos++;
		}
		return null;
	}
	
	function at_year($lexemes, $pos) {
		$spos = $pos;
		if ($lexemes[$pos][0] === 'n') {
			$val = intval($lexemes[$pos][1]);
			if ($val > 1900 && $val < 2100)
				return array($spos, $pos, $lexemes[$pos][1]);
		}
		return null;
	}
	
	function at_volume($lexemes, $pos){
		$spos = $pos;
		$stateid = array('0' => 0, '1a' => 1, '2a' => 2);
		$lextypeid = array('kw' => 0, '.' => 1, ' ' => 2, 'n' => 3, 'oth' => 4);
		$matrix = array (
			array('1a',  'E',  'E',  'E',  'E'),//0
			array( 'E', '2a', '2a',  'F',  'E'),//1a
			array( 'E',  'E', '2a',  'F',  'E')//2a
		);
		
		$keywords = array('Vol' => 0, 'V' => 1, 'Т' => 2);
		
		$state = '0';
		while ($pos < sizeof($lexemes)) {
			$lextype = $lexemes[$pos][0];
			$col = $lextypeid[$lextype];
			if ($keywords[$lexemes[$pos][1]] !== null) $col = $lextypeid['kw'];			
			if ($col === null) $col = $lextypeid['oth'];
			
			$row = $stateid[$state];
			
			$state = $matrix[$row][$col];
			
			if ($state === 'F') {
				return array($spos, $pos - 1, trim($lexemes[$pos][1]));
			}
			
			$pos++;
		}
		return null;
	}
	
	function at_issue($lexemes, $pos){
		$spos = $pos;
		$stateid = array('0' => 0, '1a' => 1, '2a' => 2);
		//NOTE add end for others
		$lextypeid = array('kw' => 0, '.' => 1, ' ' => 2, 'n' => 3, 'oth' => 4, 'end' => 5);
		$matrix = array (
			array('1a',  'E',  'E',  'E',  'E', 'E'),//0
			array( 'E', '1a', '1a', '2a',  'E', 'E'),//1a
			array( 'E',  'F',  'F', '2a', '2a', 'F') //2a
		);
		
		$keywords = array('No' => 0, 'Iss' => 1, 'Is' => 2, '№' => 3);
		
		$state = '0';
		$issueno = '';
		while ($pos <= sizeof($lexemes)) {
			$lextype = $lexemes[$pos][0];
			$col = $lextypeid[$lextype];
			
			if ($keywords[$lexemes[$pos][1]] !== null) $col = $lextypeid['kw'];
			else if (is_null($lextype)) $col = $lextypeid['end'];
			
			if ($col === null) $col = $lextypeid['oth'];
			
			$row = $stateid[$state];
			
			$state = $matrix[$row][$col];
			
			if ($state === '2a') $issueno .= $lexemes[$pos][1];
			
			if ($state === 'F') {
				return array($spos, $pos, $issueno);
			}
			
			$pos++;
		}
		return null;
	}
	
	function at_pages($lexemes, $pos){
		$spos = $pos;
		$stateid = array('0' => 0, '1a' => 1, '2a' => 2, '3a' => 3, '4a' => 4);
		$lextypeid = array('kw' => 0, '.' => 1, ' ' => 2, 'n' => 3, '-' => 4, 'oth' => 5);
		$matrix = array (
			array('1a',  'E',  'E',  'E',  'E',  'E'),//0
			array( 'E', '2a', '2a',  'E',  'E',  'E'),//1a
			array( 'E',  'E', '2a', '3a',  'E',  'E'),//2a
			array( 'E',  'F',  'E',  'E', '4a',  'E'),//3a
			array( 'E',  'E',  'E',  'F',  'E',  'E') //4a
		);
		
		$keywords = array('pp' => 0, 'C' => 1, 'С' => 2, 'Р' => 3, 'P' => 4, 'стр' => 4);
		
		$state = '0';
		$pagefrom = '';
		$pageto = '';
		while ($pos < sizeof($lexemes)) {
			$lextype = $lexemes[$pos][0];
			$col = $lextypeid[$lextype];
			if ($keywords[$lexemes[$pos][1]] !== null) $col = $lextypeid['kw'];
			if ($col === null) $col = $lextypeid['oth'];
			
			$row = $stateid[$state];
			
			$state = $matrix[$row][$col];
			
			if ($state === '3a') $pagefrom = $lexemes[$pos][1];
			
			if ($state === 'E') return null;
			if ($state === 'F') {
				if ($lexemes[$pos][0] === 'n') $pageto = $lexemes[$pos][1];
				else $pageto = $pagefrom;
				return array($spos, $pos - 1, $pagefrom, $pageto);
			}
			
			$pos++;
		}
		if ($pagefrom !== '') {
			if ($pageto === '') $pageto = $pagefrom;
			return array($spos, $pos - 1, $pagefrom, $pageto);
		}
		
		return null;
	}
	
	function at_doi($lexemes, $pos){
		$spos = $pos;
		$stateid = array('0' => 0, '1' => 1, '2' => 2);
		$lextypeid = array('kw' => 0, ':' => 1, ' ' => 2, 'oth' => 3);
		$matrix = array (
			array('1', 'E', 'E', 'E'),//0
			array('E', '1', '1', '2'),//1
			array('E', '2', 'F', '2') //2
		);
		
		$keywords = array('DOI' => 0, 'doi' => 1);
		
		$state = '0';
		$doi = '';
		while ($pos < sizeof($lexemes)) {
			$lextype = $lexemes[$pos][0];
			$col = $lextypeid[$lextype];
			if ($keywords[$lexemes[$pos][1]] !== null) $col = $lextypeid['kw'];
			if ($col === null) $col = $lextypeid['oth'];
			
			$row = $stateid[$state];
			
			$state = $matrix[$row][$col];
			
			if ($state === '2') $doi .= $lexemes[$pos][1];
			
			if ($state === 'F' || ($pos === sizeof($lexemes) - 1 && $state === '2')) {
				if (mb_substr($doi, strlen($doi) - 1, 1) === '.')
					$doi = mb_substr($doi, 0, strlen($doi) - 1);
				return array($spos, $pos, $doi);
			}
			
			$pos++;
		}
		
		return null;
	}
	
	function at_trimcon($lexemes, $pos, $lpos){
		$spos = $pos;
		$stateid = array('0' => 0, '1' => 1, '2' => 2);
		$lextypeid = array('t' => 0, 'n' => 1, ')' => 2, 'oth' => 3);
		$matrix = array (
			array('1', '1', '1', '0'),//0
			array('1', '1', '1', '2'),//1
			array('1', '1', '1', '2') //2
		);
		
		$state = '0';
		$text = '';
		$tmp = '';
		while ($pos < $lpos) {
			$lextype = $lexemes[$pos][0];
			$col = $lextypeid[$lextype];
			if ($col === null) $col = $lextypeid['oth'];
			
			$row = $stateid[$state];
			
			$state = $matrix[$row][$col];
			
			if ($state === '1') {
				$text .= $tmp.$lexemes[$pos][1];
				$tmp = '';
			}
			else if ($state === '2') {
				$tmp .= $lexemes[$pos][1];
			}
			
			$pos++;
		}
		return array($spos, $lpos, $text);
	}
	
	function at_skipdelimeters($lexemes, $pos){
		$stateid = array('0' => 0);
		$lextypeid = array('t' => 0, 'n' => 1, 'oth' => 2);
		$matrix = array (
			array('F', 'F', '0')//0
		);
		
		$state = '0';
		while ($pos < sizeof($lexemes)) {
			$lextype = $lexemes[$pos][0];
			$col = $lextypeid[$lextype];
			if ($col === null) $col = $lextypeid['oth'];
			
			$row = $stateid[$state];
			
			$state = $matrix[$row][$col];
			
			if ($state === 'F') {
				return $pos;
			}
			
			$pos++;
		}
		return $pos;
	}
	
	function at_info($lexemes) {
		$pos = 0;
		
		//Clear leading numbers and tabs
		while ($pos < sizeof($lexemes)) {
			if ($lexemes[$pos][0] === 'n' 
				&& $lexemes[$pos + 1][0] === 't') break;
			
			if ($lexemes[$pos][0] !== 'n' && $lexemes[$pos][0] !== '.'
				&& $lexemes[$pos][0] !== ' ') break;
			 
			$pos++;
		}
		
		//Read Authors
		$authors = array();
		$pos = at_skipdelimeters($lexemes, $pos);
		$res = at_authors($lexemes, $pos);
		if ($res !== null) {
			$pos = $res[1] + 1;
			$authors = $res[2];
		}
		
		//Read Title
		$title = '';
		$pos = at_skipdelimeters($lexemes, $pos);
		$res = at_title($lexemes, $pos);
		if ($res !== null) {
			$pos = $res[1] + 1;
			$title = $res[2];
		}
		
		//Read Other Authors
		$pos = at_skipdelimeters($lexemes, $pos);
		$res = at_authors($lexemes, $pos);
		if ($res !== null) {
			$pos = $res[1] + 1;
			if (sizeof($authors) > 0) unset($res[2][0]);
			$authors = array_merge($authors, $res[2]);
		}
		
		//Hard part - need to find all lexemes from which can construct parts
		$pos = at_skipdelimeters($lexemes, $pos);
		
		//Possible Journal - between $pos and the next part(City or Year...)
		
		//Possible Cities
		$pcities = array();
		for ($p = $pos; $p < sizeof($lexemes); $p++) {
			$res = at_city($lexemes, $p);
			if ($res !== null) $pcities[] = $res;
		}
		
		//Possible Years
		$pyears = array();
		for ($p = $pos; $p < sizeof($lexemes); $p++) {
			$res = at_year($lexemes, $p);
			if ($res !== null) $pyears[] = $res;
		}
		
		//Possible Volumes
		$pvolumes = array();
		for ($p = $pos; $p < sizeof($lexemes); $p++) {
			$res = at_volume($lexemes, $p);
			if ($res !== null) $pvolumes[] = $res;
		}
		
		//Possible Issues
		$pissues = array();
		for ($p = $pos; $p < sizeof($lexemes); $p++) {
			$res = at_issue($lexemes, $p);
			if ($res !== null) $pissues[] = $res;
		}
		
		//Possible Pages
		$ppages = array();
		for ($p = $pos; $p < sizeof($lexemes); $p++) {
			$res = at_pages($lexemes, $p);
			if ($res !== null) $ppages[] = $res;
		}
		
		//Select properties from the end
		$lpos = sizeof($lexemes) - 1;
		
		//DOI
		$doi = '';
		for ($p = sizeof($lexemes) - 1; $p >= $pos; $p--) {
			$res = at_doi($lexemes, $p);
			if ($res !== null) {
				$lpos = $p;
				$doi = $res[2];
				break;
			}
		}
		
		//Select Pages
		$frompage = '';
		$topage = '';
		if (sizeof($ppages) > 0) {
			for ($p = sizeof($ppages) - 1; $p >= 0; $p--) {
				if ($ppages[$p][0] < $lpos) {
					$frompage = $ppages[$p][2];
					$topage = $ppages[$p][3];
					$lpos = $ppages[$p][0];
					break;
				}
			}
		}
		
		//Select Issue
		$issue = '';
		if (sizeof($pissues) > 0) {
			for ($p = sizeof($pissues) - 1; $p >= 0; $p--) {
				if ($pissues[$p][0] < $lpos) {
					$issue = $pissues[$p][2];
					$lpos = $pissues[$p][0];
					break;
				}
			}
		}
		
		//Select Volume
		$volume = '';
		if (sizeof($pvolumes) > 0) {
			for ($p = sizeof($pvolumes) - 1; $p >= 0; $p--) {
				if ($pvolumes[$p][0] < $lpos) {
					$volume = $pvolumes[$p][2];
					$lpos = $pvolumes[$p][0];
					break;
				}
			}
		}
		
		//Select Year
		$year = '';
		if (sizeof($pyears) > 0) {
			for ($p = sizeof($pyears) - 1; $p >= 0; $p--) {
				if ($pyears[$p][0] < $lpos) {
					$year = $pyears[$p][2];
					$lpos = $pyears[$p][0];
					break;
				}
			}
		}
		
		//Select City and Publisher
		$city = '';
		$publisher = '';
		if (sizeof($pcities) > 0) {
			for ($p = sizeof($pcities) - 1; $p >= 0; $p--) {
				if ($pcities[$p][0] < $lpos) {
					$city = $pcities[$p][2];
					$publisher = at_trimcon($lexemes, $pcities[$p][1] + 1, $lpos)[2];
					$lpos = $pcities[$p][0];
					break;
				}
			}
		}
		
		//Select Journal
		$journal = '';
		$journal = at_trimcon($lexemes, $pos, $lpos)[2];
		
		$result = (object)array(
			'ATitle' => $title,			
			'Authors' => $authors,
			'JTitle' => $journal,
			'JCity' => $city,
			'JPublisher' => $publisher,
			'IYear' => $year,
			'IVolumeNo' => $volume,
			'IIssueNo' => $issue,
			'APageFrom' => $frompage,
			'APageTo' => $topage,
			'ADOI' => $doi
			);
		
		return $result;
	}
	
	function at_parselex($str) {
		$stateid = array('0' => 0, '1' => 1, '2' => 2);
		$chartypeid = array('d' => 0, 'n' => 1, 't' => 2);
		$matrix = array (
			array('F', '1', '2'),//0
			array('F', '1', 'F'),//1
			array('F', 'F', '2') //2
		);
		
		$delimeters = array('.' => 0, ',' => 1, '/' => 2, ' ' => 3, '-' => 4, ':' => 5, '[' => 6, ']' => 7);
		
		$state = '0';
		$lexem = array('', '');
		$lexemes = array();
		$i = 0;
		while ($i < mb_strlen($str)) {
			$char = mb_substr($str, $i, 1);
			
			if ($delimeters[$char] !== null) $col = $chartypeid['d'];
			else if (g_in($char)) $col = $chartypeid['n'];
			else $col = $chartypeid['t'];
			
			$row = $stateid[$state];
			
			$state = $matrix[$row][$col];
			
			if ($state === '1') {
				$lexem[0] = 'n';
				$lexem[1] .= $char;
			}
			else if ($state === '2') {
				$lexem[0] = 't';
				$lexem[1] .= $char;
			}
			else if ($state === 'F') {
				if ($lexem[0] !== '') $lexemes[] = $lexem;
				
				if ($col === $chartypeid['d']) $lexemes[] = array($char, $char);
				else $i--;
				
				$state = 0;
				$lexem = array('', '');
			}
			
			$i++;
		}
		
		if ($lexem[0] !== '') $lexemes[] = $lexem;
		
		return $lexemes;
	}
	
	function at_parse_info($str){
		$str = str_replace('–', '-', $str);
		$str = str_replace("\t", ' ', $str);
		$str = str_replace("\n", ' ', $str);
		$str = str_replace("\r", ' ', $str);
		$str = str_replace("  ", ' ', $str);
		$str = str_replace("  ", ' ', $str);
		$str = str_replace("В сборнике:", '', $str);
		$lexemes = at_parselex($str);
		
		return at_info($lexemes);
	}
	
	add_action('wp_ajax_articles_at_recognize_json', 'articles_at_recognize_json');
	function articles_at_recognize_json(){
		$str = g_si($_POST['str']);
		$res = at_parse_info($str);
		
		//Article Data
		$article = (object)array(
			'Title' => $res->ATitle,
			'PageFrom' => $res->APageFrom, 
			'PageTo' => $res->APageTo,
			'DOI' => $res->ADOI
			);
		
		//Search for Authors
		$authors = array();
		$found = false;	
		foreach ($res->Authors as $cur1) {
			$authors[] = (object)$cur1;
		}
		
		//Search for Journal
		$journal = null;
		$alljournals = db_get_entities('wp_ab_journals');
		foreach ($alljournals as $cur) {
			if ($cur->Title === $res->JTitle) {
				$journal = $cur;
				break;
			}
		}
		if ($journal === null) {
			$journal = (object)array(
				'Title' => $res->JTitle,
				'City' => $res->JCity,
				'Publisher' => $res->JPublisher
			);
		}
		
		//Search for Issue
		$issue = null;
		$issue = (object)array(
			'Year' => $res->IYear,
			'VolumeNo' => $res->IVolumeNo,
			'IssueNo' => $res->IIssueNo
		);
		
		//Combine all info about an article
		echo g_ctj((object)array(
			'Article' => $article, 
			'Authors' => $authors, 
			'Journal' => $journal,
			'Issue' => $issue
		));
		exit();
	}
