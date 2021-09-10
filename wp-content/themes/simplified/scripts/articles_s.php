<?php

//Get number of articles
function articles_count()
{
	global $wpdb;
	$result =  $wpdb->get_var(
		"SELECT COUNT(*)
		FROM wp_ab_articles"
	);
	return $result;
}

//Get number of articles for each index DB
function articles_index_count()
{
	global $wpdb;
	$result =  $wpdb->get_results(
		"SELECT j.Indexing
		FROM 
			wp_ab_articles a 
			INNER JOIN wp_ab_issues i ON a.ID_Issue = i.ID_Issue 
			INNER JOIN wp_ab_journals j ON i.ID_Journal = j.ID_Journal"
	);

	$ic = array(0, 0, 0, 0);
	foreach ($result as $row) {
		if ($row->Indexing & 1) $ic[0]++;
		if ($row->Indexing & 2) $ic[1]++;
		if ($row->Indexing & 4) $ic[2]++;
		if ($row->Indexing & 8) $ic[3]++;
	}

	return $ic;
}

//Get number of uploaded files
function articles_files_count()
{
	global $wpdb;
	$result =  $wpdb->get_results(
		"SELECT a.ID_Article
		FROM wp_ab_articles a"
	);

	$count = 0;
	foreach ($result as $row) {
		$file = files_get_article_pdf_url($row->ID_Article);
		if (!is_null($file)) $count++;
	}
	return $count;
}

//Get extra-info list of articles
function articles_list_ext($filter)
{
	$con = '';
	if (array_key_exists('ID_Article', $filter)) {
		$id = g_si($filter['ID_Article']);
		$con = "WHERE a.ID_Article={$id}";
	}

	$data = array();
	global $wpdb;
	$result =  $wpdb->get_results(
		"SELECT 
			c.*,
			i.*,
			j.*,
			a.*,
			j.Title as JTitle,
			c.Title as CTitle,
			c.City as CCity,
			YEAR(c.DateFrom) as CYear
		FROM 
			wp_ab_articles a 
			INNER JOIN wp_ab_issues i ON a.ID_Issue = i.ID_Issue 
			INNER JOIN wp_ab_journals j ON i.ID_Journal = j.ID_Journal
			LEFT JOIN wp_ab_conferences c ON c.ID_Conference = a.ID_Conference
		{$con}
		ORDER BY
			i.Year DESC, a.ID_Article DESC",
		'ARRAY_A'
	);


	global $COUNTRIES_ENG;

	foreach ($result as $row) {
		//Add authors
		$authors = $wpdb->get_results(
			"SELECT 
				a.*,
				l.*,
				CONCAT(a.Surname, ' ', a.Initials) as FullName,
				IFNULL(t.ID_Main, a.ID_Author) as ID_Main
			FROM 
				wp_ab_authors a 
				INNER JOIN wp_ab_articles_links l ON a.ID_Author = l.ID_Author 
				LEFT JOIN wp_ab_twins t ON a.ID_Author = t.ID_Alias
			WHERE
				l.ID_Article = {$row['ID_Article']}
			ORDER BY
				l.SeqNo",
			'ARRAY_A'
		);

		$row['Authors'] = $authors;

		//Set country name
		$row['CountryName'] = NULL;
		if (isset($row['Country'])) {
			$row['CountryName'] = $COUNTRIES_ENG[$row['Country']];
			if ($row['Country'] === 'RUS' && g_sir($row['CTitle'])) $row['CountryName'] = 'Россия';
		}

		//Add pdf file
		$row['PDF'] = files_get_article_pdf_url($row['ID_Article']);

		//Filter data
		$hasAuthor = true;
		if (isset($filter) && array_key_exists('authors', $filter)) {
			foreach ($filter['authors'] as $sval) {
				$hasAuthor = false;
				foreach ($authors as $author) {
					if ($author['ID_Main'] == $sval) {
						$hasAuthor = true;
						break;
					}
				}
				if ($hasAuthor) break;
			}
		}

		$hasYear = true;
		if (isset($filter) && array_key_exists('years', $filter)) {
			foreach ($filter['years'] as $sval) {
				$hasYear = false;
				if ($row['Year'] == $sval) {
					$hasYear = true;
					break;
				}
			}
		}

		$hasIndex = true;
		if (isset($filter) && array_key_exists('indexes', $filter)) {
			foreach ($filter['indexes'] as $sval) {
				$hasIndex = false;
				if ($row['Indexing'] & (int) $sval) {
					$hasIndex = true;
					break;
				}
			}
		}

		$hasKeyword = true;
		if (isset($filter) && array_key_exists('keywords', $filter)) {
			foreach ($filter['keywords'] as $sval) {
				$hasKeyword = false;
				$arr = explode("\n", $row['Keywords']);
				if (in_array($sval, $arr)) {
					$hasKeyword = true;
					break;
				}
			}
		}

		$hasConference = true;
		if (isset($filter) && array_key_exists('conference', $filter)) {
			$hasConference = !is_null($row['ID_Conference']);
		}

		if ($hasAuthor && $hasYear && $hasIndex && $hasKeyword && $hasConference) $data[] = $row;
	}
	return $data;
}

//Get list of articles
add_action('wp_ajax_articles_list_json', 'articles_list_json');
function articles_list_json()
{
	$filter = $_GET['filter'];
	$result = articles_list_ext($filter);

	echo g_ctj($result);
	exit();
}

//Find journals by keyword
add_action('wp_ajax_articles_journals_find_json', 'articles_journals_find_json');
function articles_journals_find_json()
{
	$kw = g_si($_GET['kw']);
	$kwI = g_ckl($kw);

	global $wpdb;
	$myrows =  $wpdb->get_results(
		"SELECT *
		FROM wp_ab_journals j
		WHERE j.Title LIKE '%{$kw}%' OR j.Title LIKE '%{$kwI}%'
		ORDER BY j.Title DESC"
	);

	echo g_ctj($myrows);
	exit();
}

//Find issues by keyword
add_action('wp_ajax_articles_issues_find_json', 'articles_issues_find_json');
function articles_issues_find_json()
{
	$jtitle = g_si($_GET['jt']);
	$kw = g_si($_GET['kw']);

	global $wpdb;
	$myrows =  $wpdb->get_results(
		"SELECT *
		FROM wp_ab_issues i INNER JOIN wp_ab_journals j ON i.ID_Journal = j.ID_Journal
		WHERE 			
			j.Title='{$jtitle}' 
			AND (i.Year LIKE '%{$kw}%' OR i.VolumeNo LIKE '%{$kw}%' OR i.IssueNo LIKE '%{$kw}%')
		ORDER BY
			i.Year DESC"
	);


	echo g_ctj($myrows);
	exit();
}

//Find conference by keyword
add_action('wp_ajax_articles_conferences_find_json', 'articles_conferences_find_json');
function articles_conferences_find_json()
{
	$kw = g_si($_GET['kw']);
	$kwI = g_ckl($kw);

	global $wpdb;
	$myrows =  $wpdb->get_results(
		"SELECT *
		FROM wp_ab_conferences c
		WHERE c.Title LIKE '%{$kw}%' OR c.Title LIKE '%{$kwI}%'
		ORDER BY c.DateFrom DESC"
	);

	echo g_ctj($myrows);
	exit();
}

//Create or update the article
add_action('wp_ajax_articles_create_or_update_json', 'articles_create_or_update_json');
function articles_create_or_update_json()
{
	$isCreate = is_null($_POST['ID_Article']) || empty($_POST['ID_Article']);

	global $wpdb;
	$wpdb->query('START TRANSACTION');

	try {
		//Add or modify journal
		$journal = g_cpd(array(
			'Title' => $_POST['JTitle'],
			'City' => $_POST['JCity'],
			'Publisher' => $_POST['JPublisher'],
			'Indexing' => (($_POST['JIndexingRSCI'] == 'on') ? 1 : 0) +
				(($_POST['JIndexingVAK'] == 'on') ? 2 : 0) +
				(($_POST['JIndexingScopus'] == 'on') ? 4 : 0) +
				(($_POST['JIndexingWoS'] == 'on') ? 8 : 0)
		));

		$ID_Journal =  $wpdb->get_var(
			"SELECT j.ID_Journal
				FROM wp_ab_journals j
				WHERE j.Title = '" . g_si($journal['Title']) . "'"
		);

		if (is_null($ID_Journal)) $ID_Journal = db_add_entity_TH('wp_ab_journals', $journal);
		else db_set_entity_TH('wp_ab_journals', $journal, 'ID_Journal', $ID_Journal);

		//Add or modify issue
		$issue = g_cpd(array(
			'ID_Journal' => $ID_Journal,
			'Year' => $_POST['IYear'],
			'VolumeNo' => $_POST['IVolumeNo'],
			'IssueNo' => $_POST['IIssueNo']
		));

		$cond = '';
		if (!is_null($issue['VolumeNo'])) $cond .= " AND i.VolumeNo = '" . g_si($issue['VolumeNo']) . "'";
		if (!is_null($issue['IssueNo'])) $cond .= " AND i.IssueNo = '" . g_si($issue['IssueNo']) . "'";

		$ID_Issue =  $wpdb->get_var(
			"SELECT i.ID_Issue
				FROM wp_ab_issues i
				WHERE i.ID_Journal = {$ID_Journal} AND i.Year = '" . g_si($issue['Year']) . "' {$cond}"
		);

		if (is_null($ID_Issue)) $ID_Issue = db_add_entity_TH('wp_ab_issues', $issue);
		else db_set_entity_TH('wp_ab_issues', $issue, 'ID_Issue', $ID_Issue);

		//Add or modify conference
		if ($_POST['AConference'] !== 'on') $ID_Conference = null;
		else {
			$conference = g_cpd(array(
				'Title' => $_POST['CTitle'],
				'Country' => $_POST['CCountry'],
				'City' => $_POST['CCity'],
				'DateFrom' => $_POST['CDateFrom'],
				'DateTo' => $_POST['CDateTo']
			));

			$ID_Conference =  $wpdb->get_var(
				"SELECT c.ID_Conference
					FROM wp_ab_conferences c
					WHERE c.Title = '" . g_si($conference['Title']) . "' AND c.DateFrom = '" . g_si($conference['DateFrom']) . "'"
			);

			if (is_null($ID_Conference)) $ID_Conference = db_add_entity_TH('wp_ab_conferences', $conference);
			else db_set_entity_TH('wp_ab_conferences', $conference, 'ID_Conference', $ID_Conference);
		}

		//Add authors if they not exist
		$authors = json_decode(str_replace("\\", "", $_POST['Authors']));
		$ID_Authors = array();
		foreach ($authors as $cur) {
			$author = array(
				'Surname' => $cur->Surname,
				'Initials' => str_replace('. ', '.', $cur->Initials)
			);

			$ID_Author =  $wpdb->get_var(
				"SELECT a.ID_Author
					FROM wp_ab_authors a
					WHERE a.Surname = '" . g_si($author['Surname']) . "' AND a.Initials = '" . g_si($author['Initials']) . "'"
			);

			if (is_null($ID_Author)) $ID_Author = db_add_entity_TH('wp_ab_authors', $author);

			$ID_Authors[] = array($ID_Author, $cur->SeqNo);
		}

		//Add or modify article
		$article = g_cpd(array(
			'ID_Issue' => $ID_Issue,
			'Title' => $_POST['ATitle'],
			'PageFrom' => $_POST['APageFrom'],
			'PageTo' => $_POST['APageTo'],
			'DOI' => $_POST['ADOI'],
			'TitleAlt' => $_POST['ATitleAlt'],
			'Keywords' => $_POST['AKeywords'],
			'ID_Conference' => $ID_Conference
		));

		//Format Keywords
		if (!is_null($article['Keywords'])) {
			$kws = explode("\n", $article['Keywords']);
			$kws_str = '';
			foreach ($kws as $kw) {
				if (!empty($kws_str)) $kws_str .= "\n";
				$kws_str .= mb_strtolower(trim($kw));
			}
			$article['Keywords'] = $kws_str;
		}

		$ID_Article = $_POST['ID_Article'];

		if ($isCreate) $ID_Article = db_add_entity_TH('wp_ab_articles', $article);
		else db_set_entity_TH('wp_ab_articles', $article, 'ID_Article', $ID_Article);

		//Remove links for removed authors
		$auids = '';
		foreach ($ID_Authors as $id) {
			if (!empty($auids)) $auids .= ',';
			$auids .= $id[0];
		}
		$links = $wpdb->get_results(
			"SELECT l.ID_Link
				FROM wp_ab_articles_links l
				WHERE l.ID_Article={$ID_Article} AND l.ID_Author NOT IN ({$auids})"
		);
		foreach ($links	as $link) {
			db_delete_entity_TH('wp_ab_articles_links', 'ID_Link', $link->ID_Link);
		}

		//Add or modify links
		foreach ($ID_Authors as $cur) {
			$link = array(
				'ID_Article' => $ID_Article,
				'ID_Author' => $cur[0],
				'SeqNo' => $cur[1]
			);

			$ID_Link = $wpdb->get_var(
				"SELECT l.ID_Link
					FROM wp_ab_articles_links l
					WHERE l.ID_Article={$ID_Article} AND l.ID_Author={$cur[0]}"
			);

			if (is_null($ID_Link)) $ID_Link = db_add_entity_TH('wp_ab_articles_links', $link);
			else db_set_entity_TH('wp_ab_articles_links', $link, 'ID_Link', $ID_Link);
		}

		/*//Delete files
			if (!$isCreate && is_null($_POST['pdffile'])) {
				files_delete_file_TH($ID_Article, $ID_Article);
			}*/

		//Upload files
		if (!is_null($_FILES['pdffile'])) {
			$pdffile = array(
				'tmp_name' => $_FILES['pdffile']['tmp_name'],
				'name' => $ID_Article . '.pdf'
			);

			files_copy_uploaded_file_TH($pdffile, 'articles/' . $ID_Article);
			g_ldv('Файл загружен', __FUNCTION__, 'wp_ab_articles', $ID_Article, null);
		}

		if ($isCreate) echo g_ctj(array(1, 'Статья добавлена', $ID_Article));
		else echo g_ctj(array(1, 'Статья отредактирована', $ID_Article));
		$wpdb->query('COMMIT');
	} catch (DataException $e) {
		if ($isCreate) echo g_ctj(array(2, 'Ошибка добавления статьи'));
		else echo g_ctj(array(2, 'Ошибка редактирования статьи', $ID_Article));
		$wpdb->query('ROLLBACK');

		g_ldx($e->getMessage(), $e->getData());
	} catch (Exception $e) {
		echo g_ctj(array(2, 'Ошибка загрузки файла'));
		$wpdb->query('ROLLBACK');

		g_ler($e->getMessage(), __FUNCTION__);
	}
	exit();
}

//Get full list of publishing years
function articles_list_years()
{
	global $wpdb;
	$result =  $wpdb->get_results(
		"SELECT 
			i.Year
		FROM 
			wp_ab_issues i
		GROUP BY
			i.Year
		ORDER BY
			i.Year DESC",
		'ARRAY_A'
	);

	return $result;
}

//Get full list of keywords
function articles_list_keywords()
{
	global $wpdb;
	$result =  $wpdb->get_results(
		"SELECT 
			a.Keywords
		FROM 
			wp_ab_articles a"
	);

	$kws = array();
	foreach ($result as $row) {
		$str = $row->Keywords;
		if (!is_null($str)) {
			$arr = explode("\n", $str);
			$kws = array_merge($kws, $arr);
		}
	}

	$counts = array_count_values($kws);
	arsort($counts);

	return $counts;
}

//Load last acknowledgements
add_action('wp_ajax_articles_acknowledgements_json', 'articles_acknowledgements_json');
function articles_acknowledgements_json()
{
	global $wpdb;
	$result = $wpdb->get_row(
		"SELECT *
		FROM wp_ab_gratz g
		ORDER BY g.DateTime DESC"
	);

	echo g_ctj($result);
	exit();
}

//Add new acknowledgements
add_action('wp_ajax_articles_acknowledgements_add_json', 'articles_acknowledgements_add_json');
function articles_acknowledgements_add_json()
{
	global $wpdb;
	$wpdb->query('START TRANSACTION');

	try {
		//Add or modify journal
		$gratz = g_cpd(array(
			'TextRus' => $_POST['TextRus'],
			'TextEng' => $_POST['TextEng'],
			'DateTime' => date('Y-m-d H:i:s')
		));

		$ID_Gratz = db_add_entity_TH('wp_ab_gratz', $gratz);

		$filename = 'D:/GDrive/Shared401/Публикации/Благодарности_' . date('Y-m-d_H-i-s') . '.txt';

		file_put_contents($filename, $_POST['TextRus'] . "\r\n\r\n" . $_POST['TextEng']);

		echo g_ctj(array(1, 'Благодарности обновлены', $ID_Gratz));
		$wpdb->query('COMMIT');
	} catch (DataException $e) {
		echo g_ctj(array(2, 'Ошибка обновления благодарностей'));
		$wpdb->query('ROLLBACK');

		g_ldx($e->getMessage(), $e->getData());
	}
	exit();
}

/**
 * Format single title before comparison
 */
function formatTitle($title)
{
	$pattern = '[^\w]|[ёЁеЕ]';
	return mb_strtolower(mb_ereg_replace($pattern, '', $title));
}

/**
 * Format titles before comparison
 */
function formatTitles(&$titles, $hasAlt = false)
{
	foreach ($titles as $title) {
		$title->FTitle = formatTitle($title->Title);

		if ($hasAlt) {
			$title->FTitleAlt = formatTitle($title->TitleAlt);
		}
	}
}

/**
 * Check for a twin title in the lists
 */
function hasTwinTitle($row, $articles, $programs, $hides)
{
	$min = 99.9;

	// Search in hide
	foreach ($hides as $hide) {
		similar_text($hide->Title, $row->Title, $perc);
		if ($perc > $min) {
			return true;
		}
	}

	// Search in articles
	foreach ($articles as $article) {
		similar_text($article->FTitle, $row->FTitle, $perc);
		if ($perc > $min) {
			return true;
		}
		similar_text($article->FTitleAlt, $row->FTitle, $perc);
		if ($perc > $min) {
			return true;
		}
	}

	// Search in programs
	foreach ($programs as $program) {
		similar_text($program->FTitle, $row->FTitle, $perc);
		if ($perc > $min) {
			return true;
		}
	}
	return false;
}

/**
 * Function missing RSCI papers
 */
function missingRSCI($author, $articles, $programs)
{
	if (is_null($author->RSCI)) {
		return [];
	}

	$res = getRSCIPapersHTML_TH($author->RSCI);
	formatTitles($res);

	$hides = db_get_entities('wp_ab_hides');

	$missing = [];
	foreach ($res as $row) {
		$found = hasTwinTitle($row, $articles, $programs, $hides);
		if (!$found) {
			$missing[] = ['Title' => $row->Title, 'Authors' => $row->Authors, 'Journal' => $row->Journal, 'Link' => $row->Link];
		}
	}
	return $missing;
}

/**
 * Function missing Scopus papers
 */
function missingScopus($author, $articles, $programs)
{
	if (is_null($author->Scopus)) {
		return [];
	}

	$res = getScopusPapersHTML_TH($author->Scopus);
	formatTitles($res);

	$hides = db_get_entities('wp_ab_hides');

	$missing = [];
	foreach ($res as $row) {
		$found = hasTwinTitle($row, $articles, $programs, $hides);
		if (!$found) {
			$missing[] = ['Title' => $row->Title, 'Authors' => $row->Authors, 'Journal' => $row->Journal, 'Link' => $row->Link];
		}
	}
	return $missing;
}

/**
 * Get articles of the author
 */
function getAuthorArticles($author)
{
	global $wpdb;
	$articles = $wpdb->get_results("
		SELECT 
			ar.Title as Title, ar.TitleAlt as TitleAlt
		FROM 
			wp_ab_authors a 
				JOIN wp_ab_articles_links l ON a.ID_Author=l.ID_Author 
				JOIN wp_ab_articles ar ON ar.ID_Article=l.ID_Article
				LEFT JOIN wp_ab_twins t ON a.ID_Author=t.ID_Alias
		WHERE 
			t.ID_Main={$author->ID_Author} OR a.ID_Author={$author->ID_Author}
	");
	formatTitles($articles, true);
	return $articles;
}

/**
 * Get programs of the author
 */
function getAuthorPrograms($author)
{
	global $wpdb;
	$programs = $wpdb->get_results("
		SELECT 
			CONCAT(p.TitleRus, ' \"', p.TitleEng, '\"') as Title
		FROM 
			wp_ab_authors a 
				JOIN wp_ab_programs_links l ON a.ID_Author=l.ID_Author 
				JOIN wp_ab_programs p ON p.ID_Program=l.ID_Program
				LEFT JOIN wp_ab_twins t ON a.ID_Author=t.ID_Alias
		WHERE 
			t.ID_Main={$author->ID_Author} OR a.ID_Author={$author->ID_Author}
	");
	formatTitles($programs);
	return $programs;
}

/**
 * Get the list of unlinked articles
 */
add_action('wp_ajax_articles_unlinked_list_json', 'articles_unlinked_list_json');
function articles_unlinked_list_json()
{
	$ID_Author = (int) $_GET['ID_Author'];

	$author = db_get_entity('wp_ab_authors', 'ID_Author', $ID_Author);

	$articles = getAuthorArticles($author);
	$programs = getAuthorPrograms($author);

	$missingR = missingRSCI($author, $articles, $programs);
	$missingS = missingScopus($author, $articles, $programs);

	echo g_ctj(array_merge($missingR, $missingS));
	exit();
}

/**
 * Add title to hide table
 */
add_action('wp_ajax_articles_hide_json', 'articles_hide_json');
function articles_hide_json()
{
	try {
		$title = g_cpd(array(
			'Title' => $_POST['Title']
		));

		db_add_entity_TH('wp_ab_hides', $title);

		echo g_ctj(array(1, 'Статья скрыта'));
	} catch (DataException $e) {
		echo g_ctj(array(2, 'Ошибка сокрытия статьи'));
		g_ldx($e->getMessage(), $e->getData());
	}
	exit();
}

/**
 * Test title for twins
 */
add_action('wp_ajax_articles_test_title_json', 'articles_test_title_json');
function articles_test_title_json()
{
	$G_Title = formatTitle($_GET['Title']);

	global $wpdb;
	$articles = $wpdb->get_results("
		SELECT Title, TitleAlt
		FROM wp_ab_articles
	");
	formatTitles($articles, true);

	$maxsim = 0;
	$maxtitle = '';
	foreach ($articles as $a) {
		similar_text($a->FTitle, $G_Title, $perc);
		if ($perc > $maxsim) {
			$maxsim = $perc;
			$maxtitle = $a->Title;
		}

		similar_text($a->FTitleAlt, $G_Title, $perc);
		if ($perc > $maxsim) {
			$maxsim = $perc;
			$maxtitle = $a->TitleAlt;
		}
	}

	echo json_encode(['Title' => $maxtitle, 'Similarity' => $maxsim]);
	exit();
}
