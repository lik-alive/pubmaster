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
			if ($td->find('span')->count() > 0) {
				$td = $td->find('span', 0);
			}

			$title = $td->find('b', 0);

			$paper = [];
			$paper['Title'] = trim($title->find('span', 0)->innerHtml);
			if ($title->parent->tag->name() === 'a') {
				$paper['Link'] = 'https://www.elibrary.ru/' . $title->parent->getAttribute('href');
			}
			$paper['Title'] = mb_substr($paper['Title'], 0, 1) . mb_strtolower(mb_substr($paper['Title'], 1));

			$authors = $td->find('i', 0);
			$paper['Authors'] = trim(strip_tags($authors->innerHtml));

			$journal = $td->lastChild();
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

	// $headerValues = array(
	// 	'Accept: application/json',
	// 	"X-ELS-APIKey: " . ELS_KEY
	// );

	//Initiate cURL.
	$curl = curl_init();

	//The action URL of the login form.
	curl_setopt($curl, CURLOPT_URL, "https://api.elsevier.com/content/search/scopus");

	//Tell cURL that we want to carry out a POST request.
	curl_setopt($curl, CURLOPT_POST, false);

	//Set header fields
	// curl_setopt($curl, CURLOPT_HTTPHEADER, $headerValues);

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
			'apiKey' => ELS_KEY
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

			$paper['Authors'] = $entry['dc:creator'];

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
