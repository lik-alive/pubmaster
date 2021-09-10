<?php
/*
		Template Name: Programs Create
	*/
get_header();
wp_enqueue_script('programs_create', get_template_directory_uri() . '/js/programs_create.js');
wp_enqueue_script('file-manager', get_template_directory_uri() . '/js/file-manager.js');


$info = [];

if (isset($_GET['id'])) {
	$info = programs_list_ext(array('ID_Program' => $_GET['id']));

	//Redirect to 404 page on wrong link
	if (empty($info)) g_404();
	$info = $info[0];
}
?>

<div class='main-panel scrollable'>
	<form id='createForm' method='post'>
		<input type='hidden' name='ID_Program' value='<?php echo g_aes($info, 'ID_Program'); ?>' required />
		<div class='info-panel'>
			<fieldset>
				<legend>Данные о свидетельстве</legend>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						<span class='required'>*</span>Рег. номер:
					</div>
					<div class='col-md-9 info-header'>
						<input type='text' id='RegNo' name='RegNo' value='<?php echo g_aes($info, 'RegNo'); ?>' style='width:100%' required />
					</div>
				</div>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						<span class='required'>*</span>Название русское:
					</div>
					<div class='col-md-9'>
						<textarea id='TitleRus' name='TitleRus' rows='3' class='nolinebreaks' style='width:100%; height:100%; line-height:1;' required><?php echo g_aes($info, 'TitleRus'); ?></textarea>
					</div>
				</div>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						Название английское:
					</div>
					<div class='col-md-9'>
						<textarea id='TitleEng' name='TitleEng' rows='2' class='nolinebreaks' style='width:100%; height:100%; line-height:1;'><?php echo g_aes($info, 'TitleEng'); ?></textarea>
					</div>
				</div>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						<span class='required'>*</span>Владелец:
					</div>
					<div class='col-md-9 info-header'>
						<input type='text' id='Owner' name='Owner' value='<?php echo g_aes($info, 'Owner'); ?>' style='width:100%' required />
					</div>
				</div>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						<span class='required'>*</span>Год рег.:
					</div>
					<div class='col-md-9 info-header'>
						<input type='number' step='1' id='Year' name='Year' value='<?php echo g_aes($info, 'Year'); ?>' style='width:100%' required />
					</div>
				</div>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						<span class='required'>*</span>Авторы:
					</div>
					<?php $aus = g_aes($info, 'Authors'); ?>
					<div class='col-md-9'>
						<input id='authorSearch' type='text' style='width:100%;' placeholder='Поиск по фамилии автора' />
						<div id='authorPop' class='mb-2'>
							<?php
							$popaus = authors_list_top('ru');
							foreach ($popaus as $a) {
							?>
								<button type='button' data-surname='<?php echo $a->Surname ?>' data-initials='<?php echo $a->Initials ?>' class='btn btn-outline-info mt-1 mr-1 addauthor'><?php echo $a->FullName ?></button>
							<?php } ?>
						</div>
						<table id='authors' class='mb-2 mt-2' style='<?php if (is_null($aus)) echo 'display:none' ?>'>
							<thead>
								<th style='width:25px'></th>
								<th class='text-center' style='width:25px'>№</th>
								<th class='text-center' style='width:130px'><span class='required'>*</span>Фамилия</th>
								<th class='text-center' style='width:50px'><span class='required'>*</span>И.О.</th>
							</thead>
							<tbody>
								<?php if (!is_null($aus)) {
									foreach ($aus as $a) {
								?>
										<tr style='height:35px'>
											<td><i class='zmdi zmdi-close-circle zmdi-action' style='vertical-align:middle;font-size:1.3em'></i></td>
											<td class='text-center'><?php echo $a['SeqNo'] ?></td>
											<td><input type='text' name='Surname' value='<?php echo $a['Surname'] ?>' required /></td>
											<td><input type='text' name='Initials' value='<?php echo $a['Initials'] ?>' required /></td>
										</tr>
								<?php }
								} ?>
							</tbody>
						</table>
					</div>
				</div>
			</fieldset>
		</div>

		<div class='info-panel'>
			<fieldset>
				<legend>Файлы свидетельства</legend>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						PDF-файл:
					</div>
					<div class='col-md-9'>
						<div id='pdffile'></div>
					</div>
				</div>
			</fieldset>
		</div>
	</form>
</div>

<div class='widget-panel'>
	<div class='widget'>
		<button class='btn btn-primary' form='createForm'>
			<i class='zmdi zmdi-check'></i> Принять
		</button>
		<button class='btn btn-secondary cancel'>
			<i class='zmdi zmdi-close'></i> Отмена
		</button>
	</div>
</div>


<?php get_footer(); ?>