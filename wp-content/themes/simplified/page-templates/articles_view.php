<?php
/*
		Template Name: Articles View
	*/
get_header();

$info = articles_list_ext(array('ID_Article' => $_GET['id']));
//Redirect to 404 page on wrong link
if (!sizeof($info)) g_404();
else $info = $info[0];

$imgok = "<i class='zmdi zmdi-check-circle' style='font-size: 1.5em; vertical-align: middle;'></i>";
$imgno = "<i class='zmdi zmdi-circle-o' style='font-size: 1.5em; vertical-align: middle;'></i>";
?>

<script>
	$(document).ready(function() {
		var ID_Article = parseInt(searchParams.get('id'));

		$('.edit').on('click', function() {
			window.location.href = SITE_URL + '/articles/edit/?id=' + ID_Article;
		});

		//Export data
		$('.export').click(function() {
			RedirectWithData('get', SITE_URL + '/export', 'ID_Articles', [ID_Article], '_blank');
		});

		//Add new article
		$('#newarticle').on('click', function() {
			window.location.href = SITE_URL + "/articles/wizard"
		});
	});
</script>

<div class='main-panel scrollable'>
	<div class='info-panel'>
		<div class='row'>
			<div class='col-md-3 info-header'>Название</div>
			<div class='col-md'>
				<h5><?php echo $info['Title']; ?></h5>
			</div>
		</div>
		<div class='row'>
			<div class='col-md-3 info-header'>Авторы</div>
			<div class='col-md'><?php
													$aus = '';
													foreach ($info['Authors'] as $a) {
														if (!empty($aus)) $aus .= ', ';
														$aus .= "<a class='author' href='" . get_site_url() . "/authors/view/?id={$a['ID_Main']}'>{$a['FullName']}</a>";
													}
													echo $aus; ?></div>
		</div>
		<div class='row'>
			<div class='col-md-3 info-header'>Журнал</div>
			<div class='col-md'><?php echo $info['JTitle']; ?></div>
		</div>
		<?php if (!empty($info['City'])) { ?>
			<div class='row'>
				<div class='col-md-3 info-header'>Город</div>
				<div class='col-md'><?php echo $info['City']; ?></div>
			</div>
		<?php } ?>
		<?php if (!empty($info['Publisher'])) { ?>
			<div class='row'>
				<div class='col-md-3 info-header'>Издатель</div>
				<div class='col-md'><?php echo $info['Publisher']; ?></div>
			</div>
		<?php } ?>
		<div class='row'>
			<div class='col-md-3 info-header'>Год</div>
			<div class='col-md'><?php echo $info['Year']; ?></div>
		</div>
		<?php if (!empty($info['VolumeNo'])) { ?>
			<div class='row'>
				<div class='col-md-3 info-header'>Том</div>
				<div class='col-md'><?php echo $info['VolumeNo']; ?></div>
			</div>
		<?php } ?>
		<?php if (!empty($info['IssueNo'])) { ?>
			<div class='row'>
				<div class='col-md-3 info-header'>Номер</div>
				<div class='col-md'><?php echo $info['IssueNo']; ?></div>
			</div>
		<?php } ?>
		<div class='row'>
			<div class='col-md-3 info-header'>Страницы</div>
			<div class='col-md'><?php echo $info['PageFrom']; ?>-<?php echo $info['PageTo']; ?></div>
		</div>
		<?php if (!empty($info['DOI'])) { ?>
			<div class='row'>
				<div class='col-md-3 info-header'>DOI</div>
				<div class='col-md'><?php echo $info['DOI']; ?></div>
			</div>
		<?php } ?>
		<div class='row'>
			<div class='col-md-3 info-header' style='padding-top: 5px'>Базы</div>
			<div class='col-md'><?php
													$ind = $info['Indexing'];


													echo ($ind & 8) ? $imgok : $imgno;
													echo "<label class='info-vtext'>WoS</label>";
													echo ($ind & 4) ? $imgok : $imgno;
													echo "<label class='info-vtext'>Scopus</label>";
													echo ($ind & 2) ? $imgok : $imgno;
													echo "<label class='info-vtext'>ВАК</label>";
													echo ($ind & 1) ? $imgok : $imgno;
													echo "<label class='info-vtext'>РИНЦ</label>"; ?></div>
		</div>
		<?php if (!empty($info['TitleAlt'])) { ?>
			<div class='row'>
				<div class='col-md-3 info-header'>Альт. название</div>
				<div class='col-md'><?php echo $info['TitleAlt']; ?></div>
			</div>
		<?php } ?>
		<?php if (!empty($info['Keywords'])) { ?>
			<div class='row'>
				<div class='col-md-3 info-header'>Ключевые слова</div>
				<div class='col-md'><?php
														$kws = str_replace("\n", "; ", $info['Keywords']);
														echo $kws;
														?></div>
			</div>
		<?php } ?>
		<?php if (!empty($info['CTitle'])) { ?>
			<div class='row'>
				<div class='col-md-3 info-header'>Выступление</div>
				<div class='col-md'><?php echo $info['CTitle'] . '. ' . $info['CCity'] . ', ' . $info['CountryName'] . '. ' . date('d.m.Y', strtotime($info['DateFrom'])) . ' - ' . date('d.m.Y', strtotime($info['DateTo'])); ?></div>
			</div>
		<?php } ?>
		<?php if (!empty($info['PDF'])) { ?>
			<div class='row'>
				<div class='col-md-3 info-header'>PDF</div>
				<div class='col-md'><a href='<?php echo $info['PDF'] ?>' target='_blank'>Файл</a></div>
			</div>
			<br />
			<div>
				<object id='pdffile' data='<?php echo $info['PDF'] ?>' type='application/pdf' style='width: 100%; height: 900px'>
				</object>
			</div>
		<?php } ?>
	</div>
</div>

<div class='widget-panel'>
	<div class='widget'>
		<button class='btn btn-primary edit'>
			<i class='zmdi zmdi-edit'></i> Редактировать
		</button>
		<button class='btn btn-dark export exportall'>
			<i class='zmdi zmdi-case'></i> Экспорт
		</button>
		<button id='newarticle' class='btn btn-warning'>
			<i class='zmdi zmdi-plus'></i> Добавить статью
		</button>
	</div>
</div>



<?php get_footer(); ?>