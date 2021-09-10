<?php

use PHPHtmlParser\Dom;

/**
 * Get list of RSCI papers
 */
function getRSCIPapersHTML_TH($authorid)
{
	$dom = new Dom;
	$dom->setOptions([
		'removeScripts' => true,
		'removeStyles' => true
	]);
	$papers = [];

	// Collect papers
	for ($pagenum = 1; true; $pagenum++) {
		$dom->loadFromUrl("https://elibrary.ru/author_items.asp?authorid=$authorid&pagenum=$pagenum", ['followLocation' => true], null);
		$trs = $dom->find('#restab tr');

		// Break on empty table
		if (sizeof($trs) < 4) break;

		// First three rows are the header and delimeters
		for ($i = 3; $i < sizeof($trs); $i++) {
			$tr = $trs[$i];
			$td = $tr->find('td', 1);

			$title = $td->firstChild();
			// Skip empty space before tag
			if ($title instanceof PHPHtmlParser\Dom\TextNode) $title = $title->nextSibling();

			$paper = [];
			if ($title->tag->name() === 'a') {
				$paper['Title'] = trim($title->find('span')->innerHtml);
				$paper['Link'] = 'https://www.elibrary.ru/' . $title->getAttribute('href');
			} else $paper['Title'] = trim($title->find('font')->innerHtml);
			$paper['Title'] = mb_substr($paper['Title'], 0, 1) . mb_strtolower(mb_substr($paper['Title'], 1));

			$authors = $title->nextSibling()->nextSibling();
			$paper['Authors'] = trim(strip_tags($authors->innerHtml));

			$journal = $authors->nextSibling()->nextSibling()->nextSibling();
			$paper['Journal'] = trim(strip_tags($journal->innerHtml));

			$papers[] = (object) $paper;
		}
	}
	return $papers;
}

/**
 * Get list of Scopus papers
 */
function getScopusPapersHTML_TH($authorid)
{
	$USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.96 Safari/537.36';
	$COOKIE_FILE = get_temp_dir() . 'cookie.txt';

	$headerValues = array(
		'Accept: application/json',
		'X-ELS-APIKey: ' . ELS_KEY
	);

	//Initiate cURL.
	$curl = curl_init();

	//The action URL of the login form.
	curl_setopt($curl, CURLOPT_URL, "https://api.elsevier.com/content/search/scopus");

	//Tell cURL that we want to carry out a POST request.
	curl_setopt($curl, CURLOPT_POST, true);

	//Set header fields
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headerValues);

	//We don't want any HTTPS errors.
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

	//Where our cookie details are saved.
	curl_setopt($curl, CURLOPT_COOKIEJAR, $COOKIE_FILE);

	//Sets the user agent.
	curl_setopt($curl, CURLOPT_USERAGENT, $USER_AGENT);

	//Tells cURL to return the output once the request has been executed.
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	//Do we want to follow any redirects?
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

	//Collect papers
	$papers = [];
	$start = 0;
	$count = 25;
	while (true) {
		$postValues = array(
			'query' => "AU-ID($authorid)",
			'start' => $start,
			'count' => $count,
			'view' => 'COMPLETE'
		);

		//Set our post fields / date (from the array above).
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postValues));


		//Execute the request.
		$result =  curl_exec($curl);

		$json = json_decode($result, true);
		$entries = $json['search-results']['entry'];

		if (is_null($entries) || !sizeof($entries)) break;

		foreach ($entries as $entry) {
			$paper = [];
			$paper['Title'] = $entry['dc:title'];
			$paper['Link'] = $entry['link'][2]['@href'];

			$paper['Authors'] = '';
			foreach ($entry['author'] as $author) {
				if (!empty($paper['Authors'])) $paper['Authors'] .= ', ';

				$paper['Authors'] .= $author['authname'];
			}

			$paper['Journal'] = $entry['prism:publicationName'] . ', ' . mb_substr($entry['prism:coverDate'], 0, 4) . '.';

			if (isset($entry['prism:volume'])) $paper['Journal'] .= ' Vol. ' . $entry['prism:volume'];
			if (isset($entry['prism:issueIdentifier'])) $paper['Journal'] .= ', Iss. ' . $entry['prism:issueIdentifier'];

			$papers[] = (object) $paper;
		}

		$start += $count;
	}

	curl_close($curl);

	return $papers;
}

//cURL autologin to EasyChair and retrieve HTML code of all papers page
// function getECPapersHTML_TH()
// {
// 	global $USERNAME, $PASSWORD;
// 	$USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.96 Safari/537.36';
// 	$COOKIE_FILE = get_temp_dir() . 'cookie.txt';
// 	$LOGIN_FORM_URL = 'https://easychair.org/account/signin';
// 	$LOGIN_ACTION_URL = 'https://easychair.org/account/verify';

// 	$postValues = array(
// 		'name' => $USERNAME,
// 		'password' => $PASSWORD
// 	);

// 	//Initiate cURL.
// 	$curl = curl_init();

// 	//The action URL of the login form.
// 	curl_setopt($curl, CURLOPT_URL, $LOGIN_ACTION_URL);

// 	//Tell cURL that we want to carry out a POST request.
// 	curl_setopt($curl, CURLOPT_POST, true);

// 	//Set our post fields / date (from the array above).
// 	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postValues));

// 	//We don't want any HTTPS errors.
// 	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
// 	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

// 	//Where our cookie details are saved.
// 	curl_setopt($curl, CURLOPT_COOKIEJAR, $COOKIE_FILE);

// 	//Sets the user agent.
// 	curl_setopt($curl, CURLOPT_USERAGENT, $USER_AGENT);

// 	//Tells cURL to return the output once the request has been executed.
// 	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// 	//Allows us to set the referer header.
// 	curl_setopt($curl, CURLOPT_REFERER, $LOGIN_FORM_URL);

// 	//Do we want to follow any redirects?
// 	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

// 	//Execute the login request.
// 	$result =  curl_exec($curl);

// 	//Check for errors!
// 	if (curl_errno($curl)) {
// 		throw new Exception(curl_error($curl));
// 	}

// 	//We are inside!

// 	curl_setopt($curl, CURLOPT_URL, 'https://easychair.org/conferences/submissions?a=21135267');
// 	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
// 	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
// 	curl_setopt($curl, CURLOPT_COOKIEJAR, $COOKIE_FILE);
// 	curl_setopt($curl, CURLOPT_USERAGENT, $USER_AGENT);

// 	//Execute the GET request and print out the result.
// 	$result =  curl_exec($curl);
// 	curl_close($curl);

// 	return $result;
// }

//[Throwable]
//Parse array of papers [id, title]
// function parseECPapers()
// {
// 	$html = getECPapersHTML();

// 	$dom = new DOMDocument;
// 	@$dom->loadHTML($html);

// 	//Get base table
// 	$table = $dom->getElementById('ec:table1');
// 	if (!isset($table)) return null;

// 	//Get table body
// 	$tableBody = $table->childNodes->item(1);
// 	if (!isset($tableBody)) return null;

// 	//Retrieve paper data
// 	$papers = array();
// 	foreach ($tableBody->getElementsByTagName('tr') as $tr) {
// 		$tds = $tr->getElementsByTagName('td');

// 		//Skip headers
// 		if ($tds->length < 2) continue;

// 		//Skip rejected papers
// 		if ($tds->item(14)->nodeValue === 'REJECT') continue;

// 		$id = $tds->item(0)->nodeValue;
// 		$authors = $tds->item(1)->nodeValue;
// 		$title = $tds->item(2)->nodeValue;
// 		$link = $tds->item(3)->getElementsByTagName('a')->item(0)->getAttribute('href');


// 		$papers[$id] = array('Authors' => $authors, 'Title' => $title, 'Link' => $link);
// 	}

// 	return $papers;
// }

//Update papers table using current data from EC
// function updateECPapers()
// {
// 	try {
// 		global $wpdb;
// 		$wpdb->query('TRUNCATE TABLE zi_ab_papers');

// 		$newpapers = parseECPapers();

// 		foreach ($newpapers as $id => $value) {
// 			$newpaper = array(
// 				'ID_Paper' => $id,
// 				'Authors' => $value['Authors'],
// 				'Title' => $value['Title']
// 			);

// 			$result = dbhandler_add_entity('zi_ab_papers', $newpaper);
// 			if (is_nan($result))
// 				throw new Exception('Ошибка добавления данных в таблицу zi_ab_papers ' . dbhandler_get_last_error());
// 		}
// 		g_lev('Таблица статей из EasyChair обновлена', __FUNCTION__);
// 	} catch (Exception $e) {
// 		g_ler($e->getMessage(), __FUNCTION__);
// 	}
// }

//Update authors table using current data from EC
// function updateECAuthors()
// {
// 	global $USERNAME, $PASSWORD;
// 	$USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.96 Safari/537.36';
// 	$COOKIE_FILE = get_temp_dir() . 'cookie.txt';
// 	$LOGIN_FORM_URL = 'https://easychair.org/account/signin';
// 	$LOGIN_ACTION_URL = 'https://easychair.org/account/verify';

// 	$postValues = array(
// 		'name' => $USERNAME,
// 		'password' => $PASSWORD
// 	);

// 	//Initiate cURL.
// 	$curl = curl_init();

// 	//The action URL of the login form.
// 	curl_setopt($curl, CURLOPT_URL, $LOGIN_ACTION_URL);

// 	//Tell cURL that we want to carry out a POST request.
// 	curl_setopt($curl, CURLOPT_POST, true);

// 	//Set our post fields / date (from the array above).
// 	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postValues));

// 	//We don't want any HTTPS errors.
// 	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
// 	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

// 	//Where our cookie details are saved.
// 	curl_setopt($curl, CURLOPT_COOKIEJAR, $COOKIE_FILE);

// 	//Sets the user agent.
// 	curl_setopt($curl, CURLOPT_USERAGENT, $USER_AGENT);

// 	//Tells cURL to return the output once the request has been executed.
// 	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// 	//Allows us to set the referer header.
// 	curl_setopt($curl, CURLOPT_REFERER, $LOGIN_FORM_URL);

// 	//Do we want to follow any redirects?
// 	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

// 	//Execute the login request.
// 	$result =  curl_exec($curl);

// 	//Check for errors!
// 	if (curl_errno($curl)) {
// 		throw new Exception(curl_error($curl));
// 	}

// 	//We are inside!

// 	try {
// 		global $wpdb;
// 		$wpdb->query('TRUNCATE TABLE zi_ab_authors');

// 		$papers = parseECPapers();

// 		foreach ($papers as $id => $value) {
// 			$link = $value['Link'];

// 			curl_setopt($curl, CURLOPT_URL, 'https://easychair.org' . $link);
// 			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
// 			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
// 			curl_setopt($curl, CURLOPT_COOKIEJAR, $COOKIE_FILE);
// 			curl_setopt($curl, CURLOPT_USERAGENT, $USER_AGENT);

// 			//Execute the GET request and print out the result.
// 			$html =  curl_exec($curl);

// 			$dom = new DOMDocument;
// 			@$dom->loadHTML($html);

// 			//Get base table
// 			$table = $dom->getElementById('ec:table2');
// 			if (!isset($table)) throw new Exception('Table not found');

// 			//Get table body
// 			$tableBody = $table->childNodes->item(0);
// 			if (!isset($tableBody)) throw new Exception('Table body not found');

// 			//Retrieve paper data
// 			foreach ($tableBody->getElementsByTagName('tr') as $tr) {
// 				$tds = $tr->getElementsByTagName('td');

// 				//Skip headers
// 				if ($tds->length < 2) continue;
// 				if ($tds->item(2)->nodeValue === 'email') continue;

// 				$firstname = $tds->item(0)->nodeValue;
// 				$lastname = $tds->item(1)->nodeValue;
// 				$email = $tds->item(2)->nodeValue;
// 				$country = $tds->item(3)->nodeValue;
// 				$organization = $tds->item(4)->nodeValue;
// 				$iscorresponding = empty($tds->item(6)->nodeValue) ? 'N' : 'Y';

// 				$author = array(
// 					'ID_Paper' => $id,
// 					'FirstName' => $firstname,
// 					'LastName' => $lastname,
// 					'EMail' => $email,
// 					'Country' => $country,
// 					'Organization' => $organization,
// 					'IsCorresponding' => $iscorresponding
// 				);

// 				$result = dbhandler_add_entity('zi_ab_authors', $author);
// 				if (is_nan($result))
// 					throw new Exception('Ошибка добавления данных в таблицу zi_ab_authors ' . dbhandler_get_last_error());

// 				//throw new Exception('break');
// 			}
// 		}
// 		g_lev('Таблица авторов из EasyChair обновлена', __FUNCTION__);
// 	} catch (Exception $e) {
// 		g_ler($e->getMessage() . ': ' . $link, __FUNCTION__);
// 	}
// 	curl_close($curl);
// 	return 1;
// }

// function massFileDownload_TH()
// {
// 	$chapters = array(
// 		//'1' => 'https://easychair.org/proceedings/paper_all.cgi?a=22534239',
// 		'2' => 'https://easychair.org/proceedings/paper_all.cgi?a=22534240',
// 		//'3' => 'https://easychair.org/proceedings/paper_all.cgi?a=22534241',
// 		'4' => 'https://easychair.org/proceedings/paper_all.cgi?a=22534244'
// 	);

// 	$curl = getAuthorizedCURL_TH();

// 	foreach ($chapters as $id => $url) {
// 		downloadChapter($curl, $url, $id);
// 	}
// 	curl_close($curl);

// 	//return $result;
// }

// function downloadChapter($curl, $url, $folder)
// {
// 	curl_setopt($curl, CURLOPT_URL, $url);
// 	//Execute the GET request and print out the result.
// 	$html =  curl_exec($curl);

// 	$dom = new DOMDocument;
// 	@$dom->loadHTML($html);

// 	//Get base table
// 	$table = $dom->getElementById('ec:table1');
// 	if (!isset($table)) throw new Exception('Table not found');

// 	//Get table body
// 	$tableBody = $table->childNodes->item(1);
// 	if (!isset($tableBody)) throw new Exception('Table body not found');

// 	//Retrieve paper data
// 	$stat = '';
// 	$count = 0;
// 	foreach ($tableBody->getElementsByTagName('tr') as $tr) {
// 		$tds = $tr->getElementsByTagName('td');

// 		//Skip headers
// 		if ($tds->length < 2) continue;

// 		$id = $tds->item(0)->nodeValue;
// 		$authors = $tds->item(1)->nodeValue;
// 		$title = $tds->item(2)->nodeValue;

// 		$stat .= "{$id}\t{$authors}\t{$title}\t";

// 		if ($tds->item(5)->getElementsByTagName('a')->length > 0) {
// 			$link = $tds->item(5)->getElementsByTagName('a')->item(0)->getAttribute('href');

// 			//Check if the file already loaded
// 			$files = glob('D:/ITNT/' . $folder . '/' . $id . '.*');
// 			if (sizeof($files) > 0) {
// 				$filename = $files[0];
// 			} else {
// 				//Load file
// 				curl_setopt($curl, CURLOPT_URL, 'https://easychair.org' . $link);
// 				$res =  curl_exec($curl);

// 				$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
// 				$header = substr($res, 0, $header_size);
// 				$body = substr($res, $header_size);

// 				preg_match('/filename=\"(.+)\\"/', $header, $matches);

// 				//Write file
// 				$filename = 'D:/ITNT/' . $folder . '/' . $id . '.' . pathinfo($matches[1], PATHINFO_EXTENSION);
// 				file_put_contents($filename, $body);
// 			}

// 			$stat .= $filename;
// 		}
// 		$stat .= "\r\n";
// 	}
// 	file_put_contents('D:/ITNT/' . $folder . '/stat.txt', $stat);
// }

// function getAuthorizedCURL_TH()
// {
// 	global $USERNAME, $PASSWORD;
// 	$USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.96 Safari/537.36';
// 	$COOKIE_FILE = get_temp_dir() . 'cookie.txt';
// 	$LOGIN_FORM_URL = 'https://easychair.org/account/signin';
// 	$LOGIN_ACTION_URL = 'https://easychair.org/account/verify';

// 	$postValues = array(
// 		'name' => $USERNAME,
// 		'password' => $PASSWORD
// 	);

// 	//Initiate cURL.
// 	$curl = curl_init();

// 	//The action URL of the login form.
// 	curl_setopt($curl, CURLOPT_URL, $LOGIN_ACTION_URL);

// 	//Tell cURL that we want to carry out a POST request.
// 	curl_setopt($curl, CURLOPT_POST, true);

// 	//Set our post fields / date (from the array above).
// 	curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postValues));

// 	//We don't want any HTTPS errors.
// 	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
// 	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

// 	//Where our cookie details are saved.
// 	curl_setopt($curl, CURLOPT_COOKIEJAR, $COOKIE_FILE);

// 	//Sets the user agent.
// 	curl_setopt($curl, CURLOPT_USERAGENT, $USER_AGENT);

// 	//Tells cURL to return the output once the request has been executed.
// 	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// 	//Allows us to set the referer header.
// 	curl_setopt($curl, CURLOPT_REFERER, $LOGIN_FORM_URL);

// 	//Do we want to follow any redirects?
// 	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);

// 	//Execute the login request.
// 	$result =  curl_exec($curl);

// 	//Check for errors!
// 	if (curl_errno($curl)) {
// 		throw new Exception(curl_error($curl));
// 	}

// 	//We are inside!
// 	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
// 	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
// 	curl_setopt($curl, CURLOPT_COOKIEJAR, $COOKIE_FILE);
// 	curl_setopt($curl, CURLOPT_USERAGENT, $USER_AGENT);
// 	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
// 	curl_setopt($curl, CURLOPT_VERBOSE, 1);
// 	curl_setopt($curl, CURLOPT_HEADER, 1);

// 	return $curl;
// }

// function loadMails_TH()
// {
// 	$chapters = array(
// 		//'1' => 'https://easychair.org/proceedings/paper_all.cgi?a=22534239',
// 		//'2' => 'https://easychair.org/proceedings/paper_all.cgi?a=22534240',
// 		//'3' => 'https://easychair.org/proceedings/paper_all.cgi?a=22534241',
// 		//'4' => 'https://easychair.org/proceedings/paper_all.cgi?a=22534244',
// 		't' => 'https://easychair.org/conferences/submissions?a=21135267'
// 	);

// 	$curl = getAuthorizedCURL_TH();

// 	foreach ($chapters as $id => $url) {
// 		loadAllAuthors($curl, $url, $id);
// 	}
// 	curl_close($curl);
// 	echo 'Finished';
// }

// function loadAllAuthors($curl, $url, $id)
// {
// 	curl_setopt($curl, CURLOPT_URL, $url);
// 	//Execute the GET request and print out the result.
// 	$html = curl_exec($curl);

// 	$dom = new DOMDocument;
// 	@$dom->loadHTML($html);

// 	//Get base table
// 	$table = $dom->getElementById('ec:table1');
// 	if (!isset($table)) throw new Exception('Table not found');

// 	//Get table body
// 	$tableBody = $table->childNodes->item(1);
// 	if (!isset($tableBody)) throw new Exception('Table body not found');

// 	$text = '';
// 	//Process each paper
// 	foreach ($tableBody->getElementsByTagName('tr') as $tr) {
// 		$tds = $tr->getElementsByTagName('td');

// 		//Skip headers
// 		if ($tds->length < 2) continue;

// 		$idP = $tds->item(0)->nodeValue;
// 		$link = $tds->item(3)->getElementsByTagName('a')->item(0)->getAttribute('href');

// 		//curl_setopt($curl, CURLOPT_URL, 'https://easychair.org/proceedings/'.$link);
// 		curl_setopt($curl, CURLOPT_URL, 'https://easychair.org' . $link);
// 		$res =  curl_exec($curl);

// 		$dom2 = new DOMDocument;
// 		@$dom2->loadHTML($res);

// 		//Get base table
// 		$table2 = $dom2->getElementById('ec:table2');
// 		if (!isset($table2)) throw new Exception('Table2 not found');

// 		//Get table body
// 		$tableBody2 = $table2->childNodes->item(0);
// 		if (!isset($tableBody2)) throw new Exception('Table body not found');

// 		//Retrieve all authors
// 		foreach ($tableBody2->getElementsByTagName('tr') as $tr2) {
// 			$tds2 = $tr2->getElementsByTagName('td');

// 			//Skip headers
// 			if ($tds2->length < 2) continue;
// 			if ($tds2->item(2)->nodeValue === 'email') continue;

// 			$name = $tds2->item(0)->nodeValue;
// 			$surname = $tds2->item(1)->nodeValue;
// 			$mail = $tds2->item(2)->nodeValue;
// 			$corr = $tds2->item(6)->nodeValue;

// 			if ($corr === '✔') $corr = 'Y';
// 			else $corr = 'N';

// 			$text .= "{$idP}\t{$name}\t{$surname}\t{$mail}\t{$corr}\r\n";
// 		}
// 	}

// 	$filename = "D:/ITNT/allauthors_{$id}.txt";
// 	file_put_contents($filename, $text);
// }
