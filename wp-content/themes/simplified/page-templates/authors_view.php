<?php
/*
		Template Name: Authors View
	*/

$info = authors_list_ext(array('ID_Author' => $_GET['id']));

//Redirect to 404 page on wrong link
if (!sizeof($info)) g_404();
else $info = $info[0];

//Redirect to Main author
if ($info['ID_Main'] !== $_GET['id']) header('Location: ' . get_site_url() . '/authors/view/?id=' . $info['ID_Main']);

get_header();
?>

<script>
	var ID_Author = <?php echo $_GET['id'] ?>;
</script>

<div class='main-panel scrollable'>
	<div class='info-panel'>
		<div class='text-center'>
			<h5><?php echo $info['FullName']; ?></h5>
		</div>
		<?php if (!empty($info['Twins'])) { ?>
			<div class='row'>
				<div class='col-md-3 info-header'>Псевдонимы</div>
				<div class='col-md'><?php
									$aus = '';
									foreach ($info['Twins'] as $a) {
										if (!empty($aus)) $aus .= ', ';
										$aus .= $a['FullName'];
									}
									echo $aus; ?></div>
			</div>
		<?php } ?>
		<div class='row'>
			<div class='col-md-3 info-header'>РИНЦ-ID</div>
			<div class='col-md'><?php
								echo is_null($info['RSCI']) ? '-отсутствует-' : "<a href='https://www.elibrary.ru/author_items.asp?authorid={$info['RSCI']}' target='_blank'>{$info['RSCI']}</a>"; ?></div>
		</div>
		<div class='row'>
			<div class='col-md-3 info-header'>Scopus-ID</div>
			<div class='col-md'><?php
								echo is_null($info['Scopus']) ? '-отсутствует-' : "<a href='https://www.scopus.com/authid/detail.uri?authorId={$info['Scopus']}' target='_blank'>{$info['Scopus']}</a>"; ?></div>
		</div>

	</div>
	<br />
	<div class='info-panel'>
		<legend>Непривязанные работы</legend>
		<?php include 'tables/unlinked-table.php'; ?>
	</div>
	<div class='info-panel'>
		<legend>Статьи</legend>
		<?php include 'tables/articles-table.php'; ?>
	</div>
	<br />
	<div class='info-panel'>
		<legend>Конференции</legend>
		<?php include 'tables/conferences-table.php'; ?>
	</div>
	<br />
	<div class='info-panel'>
		<legend>Свидетельства</legend>
		<?php include 'tables/programs-table.php'; ?>
	</div>
</div>

<div class='widget-panel'>
	<div class='widget'>
		<button class='btn btn-primary edit'>
			<i class='zmdi zmdi-edit'></i> Редактировать
		</button>
	</div>
</div>

<?php get_footer(); ?>

<script>
	$(document).ready(function() {
		dt_filter.authors = [ID_Author];

		var articles = $('#articlestable').DataTable();
		articles.columns([0, 4, 5]).visible(false);
		articles.page.len(5);

		var conferences = $('#conferencestable').DataTable();
		conferences.columns([0]).visible(false);
		conferences.page.len(5);

		var programs = $('#programstable').DataTable();
		programs.columns([0]).visible(false);
		programs.page.len(5);

		$('.edit').on('click', function() {
			window.location.href = SITE_URL + '/authors/edit/?id=' + ID_Author;
		});
	});
</script>