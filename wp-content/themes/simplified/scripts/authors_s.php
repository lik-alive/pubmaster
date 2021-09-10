<?php

//Get extra-info list of authors
function authors_list_ext($filter)
{
	$con = '';
	if (array_key_exists('ID_Author', $filter)) {
		$id = g_si($filter['ID_Author']);
		$con = "WHERE au.ID_Author={$id}";
	}

	global $wpdb;
	$result =  $wpdb->get_results(
		"SELECT 
			au.*,
			CONCAT(au.Surname, ' ', au.Initials) as FullName,
			IFNULL(t.ID_Main, au.ID_Author) as ID_Main
		FROM wp_ab_authors au
			LEFT JOIN wp_ab_twins t ON au.ID_Author = t.ID_Alias
		{$con}
		ORDER BY
			au.ID_Author",
		'ARRAY_A'
	);

	$data = array();
	foreach ($result as $row) {
		//Add Aliases
		$twins = $wpdb->get_results(
			"SELECT 
				t.*,
				au.Surname,
				au.Initials,
				CONCAT(au.Surname, ' ', au.Initials) as FullName
			FROM 
				wp_ab_twins t 
				INNER JOIN wp_ab_authors au ON au.ID_Author = t.ID_Alias 
			WHERE
				t.ID_Main = {$row['ID_Author']}
			ORDER BY
				t.ID_Twin DESC",
			'ARRAY_A'
		);

		$row['Twins'] = $twins;

		$data[] = $row;
	}
	return $data;
}

//Get ext list of authors
add_action('wp_ajax_authors_list_json', 'authors_list_json');
function authors_list_json()
{
	$filter = $_GET['filter'];
	$result = authors_list_ext($filter);

	echo g_ctj($result);
	exit();
}

//Get list of authors (brief info)
function authors_list_min()
{
	global $wpdb;
	$result =  $wpdb->get_results(
		"SELECT 
			a.ID_Author, 
			CONCAT(a.Surname, ' ', a.Initials) as FullName
		FROM 
			wp_ab_authors a
		WHERE
			a.ID_Author NOT IN (SELECT t.ID_Alias FROM wp_ab_twins t)
		ORDER BY
        	CASE a.ID_Author 
            	WHEN 13 THEN 0
                WHEN 14 THEN 1
                WHEN 32 THEN 2
                WHEN 82 THEN 3
                WHEN 19 THEN 4
                WHEN 30 THEN 5
                WHEN 33 THEN 6
                WHEN 14 THEN 7
                WHEN 31 THEN 8
				WHEN 84 THEN 9
                ELSE a.Surname 
            END",
		'ARRAY_A'
	);

	return $result;
}

//Find authors by keyword
add_action('wp_ajax_authors_find_json', 'authors_find_json');
function authors_find_json()
{
	$kw = g_si($_GET['kw']);
	$kwI = g_ckl($kw);

	global $wpdb;
	$myrows =  $wpdb->get_results(
		"SELECT 
			au.*,
			CONCAT(au.Surname, ' ', au.Initials) as FullName
		FROM wp_ab_authors au
		WHERE au.Surname LIKE '%{$kw}%' OR au.Surname LIKE '%{$kwI}%'
		ORDER BY au.Surname"
	);

	echo g_ctj($myrows);
	exit();
}

//Get all aliases for the author
function authors_all_twins($ID_Author)
{
	global $wpdb;
	$result = $wpdb->get_results(
		"SELECT *
			FROM wp_ab_twins t
			WHERE t.ID_Main={$ID_Author}"
	);

	return $result;
}

//Check if the author already registered as alias
function is_alias($ID_Author)
{
	global $wpdb;
	$var = $wpdb->get_var(
		"SELECT *
			FROM wp_ab_twins t
			WHERE t.ID_Alias={$ID_Author}"
	);
	return !is_null($var);
}

//Check if the author already registered as main
function is_main($ID_Author)
{
	global $wpdb;
	$var = $wpdb->get_var(
		"SELECT *
			FROM wp_ab_twins t
			WHERE t.ID_Main={$ID_Author}"
	);
	return !is_null($var);
}

//Update the author
add_action('wp_ajax_authors_update_json', 'authors_update_json');
function authors_update_json()
{
	global $wpdb;
	$wpdb->query('START TRANSACTION');

	try {
		//Add or modify author
		$author = g_cpd(array(
			'Surname' => $_POST['MSurname'],
			'Initials' => str_replace('. ', '.', $_POST['MInitials']),
			'RSCI' => $_POST['MRSCI'],
			'Scopus' => $_POST['MScopus']
		));

		$ID_Author = $_POST['ID_Author'];

		db_set_entity_TH('wp_ab_authors', $author, 'ID_Author', $ID_Author);

		//Update aliases
		if (!is_alias($isAlias)) {
			$akas = json_decode(str_replace("\\", "", $_POST['Twins']));
			$akasIds = array_unique(array_map(function ($o) {
				return $o->ID_Alias;
			}, $akas));

			$twins = authors_all_twins($ID_Author);

			//For removed aliases
			foreach ($twins as $twin) {
				if (in_array($twin->ID_Alias, $akasIds)) unset($akasIds[array_search($twin->ID_Alias, $akasIds)]);
				else db_delete_entity_TH('wp_ab_twins', 'ID_Twin', $twin->ID_Twin);
			}

			//For added aliases
			foreach ($akasIds as $akaId) {
				$twin = array(
					'ID_Main' => $ID_Author,
					'ID_Alias' => $akaId
				);

				if (!is_main($akaId) && ($akaId !== $ID_Author)) db_add_entity_TH('wp_ab_twins', $twin);
			}
		}

		echo g_ctj(array(1, 'Автор отредактирован', $ID_Author));
		$wpdb->query('COMMIT');
	} catch (DataException $e) {
		echo g_ctj(array(2, 'Ошибка редактирования автора', $ID_Author));
		$wpdb->query('ROLLBACK');

		g_ldx($e->getMessage(), $e->getData());
	}
	exit();
}

//Get list of 12 top authors
function authors_list_top($lang)
{
	if ($lang === 'ru') $langreg = '[а-я]';
	else $langreg = '[a-z]';

	global $wpdb;
	$result =  $wpdb->get_results(
		"SELECT 
			a.*,
			CONCAT(a.Surname, ' ', a.Initials) as FullName,
			COUNT(l.ID_Author) as RCount
		FROM wp_ab_authors a
			INNER JOIN wp_ab_articles_links l ON a.ID_Author = l.ID_Author
		WHERE a.Surname REGEXP '{$langreg}'
		GROUP BY a.ID_Author
		ORDER BY RCount DESC
		LIMIT 12"
	);

	return $result;
}

//Get list of 12 top authors
add_action('wp_ajax_authors_list_top_json', 'authors_list_top_json');
function authors_list_top_json()
{
	$result =  authors_list_top($_GET['lang']);

	echo g_ctj($result);
	exit();
}
