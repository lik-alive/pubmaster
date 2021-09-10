<?php

//Get number of programs
function programs_count()
{
	global $wpdb;
	$result =  $wpdb->get_var(
		"SELECT COUNT(*)
		FROM wp_ab_programs"
	);
	return $result;
}

//Get number of uploaded files
function programs_files_count()
{
	global $wpdb;
	$result =  $wpdb->get_results(
		"SELECT p.ID_Program
		FROM wp_ab_programs p"
	);

	$count = 0;
	foreach ($result as $row) {
		$file = files_get_program_pdf_url($row->ID_Program);
		if (!is_null($file)) $count++;
	}
	return $count;
}

//Get extra-info list of programs
function programs_list_ext($filter)
{
	$con = '';
	if (array_key_exists('ID_Program', $filter)) {
		$id = g_si($filter['ID_Program']);
		$con = "WHERE p.ID_Program={$id}";
	}

	$data = array();
	global $wpdb;
	$result =  $wpdb->get_results(
		"SELECT *
		FROM 
			wp_ab_programs p 
		{$con}
		ORDER BY
			p.Year DESC, p.ID_Program DESC",
		'ARRAY_A'
	);

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
				INNER JOIN wp_ab_programs_links l ON a.ID_Author = l.ID_Author 
				LEFT JOIN wp_ab_twins t ON a.ID_Author = t.ID_Alias
			WHERE
				l.ID_Program = {$row['ID_Program']}
			ORDER BY
				l.SeqNo",
			'ARRAY_A'
		);

		$row['Authors'] = $authors;

		//Add pdf file
		$row['PDF'] = files_get_program_pdf_url($row['ID_Program']);

		//Filter data
		$hasAuthor = true;
		if (array_key_exists('authors', $filter)) {
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
		if (array_key_exists('years', $filter)) {
			foreach ($filter['years'] as $sval) {
				$hasYear = false;
				if ($row['Year'] == $sval) {
					$hasYear = true;
					break;
				}
			}
		}

		if ($hasAuthor && $hasYear) $data[] = $row;
	}
	return $data;
}

//Get list of programs
add_action('wp_ajax_programs_list_json', 'programs_list_json');
function programs_list_json()
{
	$filter = $_GET['filter'];
	$result = programs_list_ext($filter);

	echo g_ctj($result);
	exit();
}

//Create or update the programs
add_action('wp_ajax_programs_create_or_update_json', 'programs_create_or_update_json');
function programs_create_or_update_json()
{
	$isCreate = is_null($_POST['ID_Program']) || empty($_POST['ID_Program']);

	global $wpdb;
	$wpdb->query('START TRANSACTION');

	try {
		//Add authors if they not exist
		$authors = json_decode(str_replace("\\", "", $_POST['Authors']));
		$ID_Authors = array();
		foreach ($authors as $cur) {
			$author = g_cpd(array(
				'Surname' => $cur->Surname,
				'Initials' => $cur->Initials
			));

			$ID_Author =  $wpdb->get_var(
				"SELECT a.ID_Author
					FROM wp_ab_authors a
					WHERE a.Surname = '" . g_si($author['Surname']) . "' AND a.Initials = '" . g_si($author['Initials']) . "'"
			);

			if (is_null($ID_Author)) $ID_Author = db_add_entity_TH('wp_ab_authors', $author);

			$ID_Authors[] = array($ID_Author, $cur->SeqNo);
		}

		//Add or modify program
		$program = g_cpd(array(
			'RegNo' => $_POST['RegNo'],
			'TitleRus' => $_POST['TitleRus'],
			'TitleEng' => $_POST['TitleEng'],
			'Year' => $_POST['Year'],
			'Owner' => $_POST['Owner']
		));

		$ID_Program = $_POST['ID_Program'];

		if ($isCreate) $ID_Program = db_add_entity_TH('wp_ab_programs', $program);
		else db_set_entity_TH('wp_ab_programs', $program, 'ID_Program', $ID_Program);

		//Remove links for removed authors
		$auids = '';
		foreach ($ID_Authors as $id) {
			if (!empty($auids)) $auids .= ',';
			$auids .= $id[0];
		}
		$links = $wpdb->get_results(
			"SELECT l.ID_Link
				FROM wp_ab_programs_links l
				WHERE l.ID_Program={$ID_Program} AND l.ID_Author NOT IN ({$auids})"
		);
		foreach ($links	as $link) {
			db_delete_entity_TH('wp_ab_programs_links', 'ID_Link', $link->ID_Link);
		}

		//Add or modify links
		foreach ($ID_Authors as $cur) {
			$link = array(
				'ID_Program' => $ID_Program,
				'ID_Author' => $cur[0],
				'SeqNo' => $cur[1]
			);

			$ID_Link = $wpdb->get_var(
				"SELECT l.ID_Link
					FROM wp_ab_programs_links l
					WHERE l.ID_Program={$ID_Program} AND l.ID_Author={$cur[0]}"
			);

			if (is_null($ID_Link)) $ID_Link = db_add_entity_TH('wp_ab_programs_links', $link);
			else db_set_entity_TH('wp_ab_programs_links', $link, 'ID_Link', $ID_Link);
		}

		//Upload files
		if (!is_null($_FILES['pdffile'])) {
			$pdffile = array(
				'tmp_name' => $_FILES['pdffile']['tmp_name'],
				'name' => $ID_Program . '.pdf'
			);

			files_copy_uploaded_file_TH($pdffile, 'programs/' . $ID_Program);
			g_ldv('Файл загружен', __FUNCTION__, 'wp_ab_programs', $ID_Program, null);
		}

		if ($isCreate) echo g_ctj(array(1, 'Свидетельство добавлено', $ID_Program));
		else echo g_ctj(array(1, 'Свидетельство отредактировано', $ID_Program));
		$wpdb->query('COMMIT');
	} catch (DataException $e) {
		if ($isCreate) echo g_ctj(array(2, 'Ошибка добавления свидетельства'));
		else echo g_ctj(array(1, 'Ошибка редактирования свидетельства', $ID_Program));
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
function programs_list_years()
{
	global $wpdb;
	$result =  $wpdb->get_results(
		"SELECT 
			p.Year
		FROM 
			wp_ab_programs p
		GROUP BY
			p.Year
		ORDER BY
			p.Year DESC",
		'ARRAY_A'
	);

	return $result;
}
