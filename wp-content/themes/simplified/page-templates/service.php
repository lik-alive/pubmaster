<?php
/*
		Template Name: Service
	*/
get_header();

//Redirect to 404 page on wrong user
if (wp_get_current_user()->user_login !== 'secret') g_404();

?>

<script>
	$(document).ready(function() {

	});
</script>

<?php
checkDuplicates();

function combineKeywords()
{
	global $wpdb;

	$res = $wpdb->get_results(
		"SELECT *
		FROM wp_ab_articles
		"
	);

	$info = @file('D:/2.txt');
	$text = '';
	foreach ($info as $line) {
		$line = trim($line);
		if (empty($line)) continue;

		$arr = explode("\t", $line);

		$a = array(
			'Title' => $arr[0],
			'DOI' => $arr[1],
			'KW' => $arr[2]
		);


		$ids = '';
		$exclude = array(35, 118, 119, 120);
		foreach ($res as $e) {
			if (in_array($e->ID_Article, $exclude)) continue;

			similar_text(mb_strtolower($e->Title), mb_strtolower($a['Title']), $percent1);
			similar_text(mb_strtolower($e->TitleAlt), mb_strtolower($a['Title']), $percent2);
			if ($percent1 > 99 || $percent2 > 99) $ids .= $e->ID_Article . "\t";
		}

		if (empty($a['KW'])) continue;
		if (!empty($ids)) $text .= mb_strtolower($a['KW']) . "\t" . $ids . "\r\n";
	}

	file_put_contents('D:/2_f.txt', $text);
}







function checkDuplicates()
{
	global $wpdb;

	echo 'Схожие статьи<br/>';
	$res = $wpdb->get_results(
		"SELECT *
	FROM wp_ab_articles
	"
	);

	$allowed = array(35, 40, 72, 99, 108, 110, 118, 119, 120, 123, 178, 186, 203, 233, 249);
	for ($i = 0; $i < sizeof($res); $i++) {
		$v = $res[$i];
		for ($j = $i + 1; $j < sizeof($res); $j++) {
			$w = $res[$j];
			similar_text($v->Title, $w->Title, $percent);

			if ($percent > 90) {
				if (in_array($w->ID_Article, $allowed)) continue;

				var_dump($percent);
				echo '<br/>';
				var_dump($v);
				echo '<br/>';
				var_dump($w);
				echo '<br/>';
				echo '<br/>';
			}
		}
	}
	echo '<br/>';

	echo 'Схожие конференции<br/>';
	$res = $wpdb->get_results(
		"SELECT *
	FROM wp_ab_conferences
	"
	);

	$allowed = array(14, 31, 34, 39, 40, 42, 44, 48, 51, 52, 56, 57, 58, 60, 61, 62);
	for ($i = 0; $i < sizeof($res); $i++) {
		$v = $res[$i];
		for ($j = $i + 1; $j < sizeof($res); $j++) {
			$w = $res[$j];
			similar_text($v->Title, $w->Title, $percent);

			if ($percent > 90) {
				if (in_array($w->ID_Conference, $allowed)) continue;

				var_dump($percent);
				echo '<br/>';
				var_dump($v);
				echo '<br/>';
				var_dump($w);
				echo '<br/>';
				echo '<br/>';
			}
		}
	}
	echo '<br/>';

	echo 'Схожие журналы<br/>';
	$res = $wpdb->get_results(
		"SELECT *
	FROM wp_ab_journals
	"
	);

	$allowed = array(24, 27, 46, 62, 63, 86, 89, 91, 93);
	for ($i = 0; $i < sizeof($res); $i++) {
		$v = $res[$i];
		for ($j = $i + 1; $j < sizeof($res); $j++) {
			$w = $res[$j];
			similar_text($v->Title, $w->Title, $percent);

			if ($percent > 90) {
				if (in_array($w->ID_Journal, $allowed)) continue;

				var_dump($percent);
				echo '<br/>';

				var_dump($v);
				echo '<br/>';
				$is = $wpdb->get_results(
					"SELECT *
				FROM wp_ab_issues i 
				WHERE i.ID_Journal = {$v->ID_Journal}
				"
				);
				var_dump($is);
				echo '<br/>';

				var_dump($w);
				echo '<br/>';
				$is = $wpdb->get_results(
					"SELECT *
				FROM wp_ab_issues i 
				WHERE i.ID_Journal = {$w->ID_Journal}
				"
				);
				var_dump($is);
				echo '<br/>';
				echo '<br/>';
			}
		}
	}
	echo '<br/>';

	echo 'Выпуски без статей<br/>';
	$res = $wpdb->get_results(
		"SELECT *
	FROM wp_ab_issues i
	WHERE i.ID_Issue NOT IN (
	SELECT a.ID_Issue
	FROM wp_ab_articles a)
	"
	);
	for ($i = 0; $i < sizeof($res); $i++) {
		$v = $res[$i];
		var_dump($v);
		echo '<br/>';
	}
	echo '<br/>';

	echo 'Журналы без выпусков<br/>';
	$res = $wpdb->get_results(
		"SELECT *
	FROM wp_ab_journals j
	WHERE j.ID_Journal NOT IN (
	SELECT i.ID_Journal
	FROM wp_ab_issues i)
	"
	);
	for ($i = 0; $i < sizeof($res); $i++) {
		$v = $res[$i];
		var_dump($v);
		echo '<br/>';
	}
	echo '<br/>';

	echo 'Конференции без статей<br/>';
	$res = $wpdb->get_results(
		"SELECT *
	FROM wp_ab_conferences c
	WHERE c.ID_Conference NOT IN (
	SELECT a.ID_Conference
	FROM wp_ab_articles a)
	"
	);
	for ($i = 0; $i < sizeof($res); $i++) {
		$v = $res[$i];
		var_dump($v);
		echo '<br/>';
	}
	echo '<br/>';

	echo 'Схожие свидетельства<br/>';
	$res = $wpdb->get_results(
		"SELECT *
	FROM wp_ab_programs
	"
	);

	$allowed = array();
	for ($i = 0; $i < sizeof($res); $i++) {
		$v = $res[$i];
		for ($j = $i + 1; $j < sizeof($res); $j++) {
			$w = $res[$j];
			similar_text($v->TitleRus, $w->TitleRus, $percent);

			if ($percent > 90) {
				if (in_array($w->ID_Program, $allowed)) continue;

				var_dump($percent);
				echo '<br/>';
				var_dump($v);
				echo '<br/>';
				var_dump($w);
				echo '<br/>';
				echo '<br/>';
			}
		}
	}
	echo '<br/>';


	echo 'Схожие авторы<br/>';
	$res = $wpdb->get_results(
		"SELECT 
		a.*,
		CONCAT(a.Surname, ' ', a.Initials) as FullName,
		IFNULL(t.ID_Main, a.ID_Author) as ID_Main
	FROM 
		wp_ab_authors a 
		LEFT JOIN wp_ab_twins t ON a.ID_Author = t.ID_Alias"
	);

	$allowed = array(23, 34, 47, 54, 60, 73, 85, 97, 104, 105, 114);
	for ($i = 0; $i < sizeof($res); $i++) {
		$v = $res[$i];
		for ($j = $i + 1; $j < sizeof($res); $j++) {
			$w = $res[$j];
			similar_text($v->FullName, $w->FullName, $percent1);
			similar_text($v->FullName, g_trs($w->FullName), $percent2);

			if ($percent1 > 80 || $percent2 > 80) {
				if (in_array($w->ID_Author, $allowed)) continue;
				if ($v->ID_Main === $w->ID_Main)  continue;

				var_dump($percent1);
				echo ' ';
				var_dump($percent2);
				echo '<br/>';
				var_dump($v);
				echo '<br/>';
				var_dump($w);
				echo '<br/>';
				echo '<br/>';
			}
		}
	}
	echo '<br/>';
}


?>



<?php get_footer(); ?>