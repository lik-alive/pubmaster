<?php
/*
		Template Name: Programs View
	*/
get_header();

$info = programs_list_ext(array('ID_Program' => $_GET['id']));
//Redirect to 404 page on wrong link
if (!sizeof($info)) g_404();
else $info = $info[0];
?>

<script>
	$(document).ready(function() {
		var ID_Program = searchParams.get('id');

		$('.edit').on('click', function() {
			window.location.href = SITE_URL + '/programs/edit/?id=' + ID_Program;
		});

		//Export data
		$('.export').click(function() {
			RedirectWithData('get', SITE_URL + '/export', 'ID_Programs', [ID_Program], '_blank');
		});

		//Add new certificate
		$('#newprogram').on('click', function() {
			window.location.href = SITE_URL + "/programs/create"
		});
	});
</script>

<div class='main-panel scrollable'>
	<div class='info-panel'>
		<div class='row'>
			<div class='col-md-3 info-header'>Название русское</div>
			<div class='col-md'>
				<h5><?php echo $info['TitleRus']; ?></h5>
			</div>
		</div>
		<div class='row'>
			<div class='col-md-3 info-header'>Название английское</div>
			<div class='col-md'><?php echo $info['TitleEng']; ?></div>
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
			<div class='col-md-3 info-header'>Рег. номер</div>
			<div class='col-md'><?php echo $info['RegNo']; ?></div>
		</div>
		<div class='row'>
			<div class='col-md-3 info-header'>Владелец</div>
			<div class='col-md'><?php echo $info['Owner']; ?></div>
		</div>
		<div class='row'>
			<div class='col-md-3 info-header'>Год рег.</div>
			<div class='col-md'><?php echo $info['Year']; ?></div>
		</div>

		<?php if (!empty($info['PDF'])) { ?>
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
		<button id='newprogram' class='btn btn-warning'>
			<i class='zmdi zmdi-plus'></i> Добавить свидет-во
		</button>
	</div>
</div>



<?php get_footer(); ?>