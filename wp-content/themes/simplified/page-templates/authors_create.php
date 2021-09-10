<?php
	/*
		Template Name: Authors Create
	*/
	$info = authors_list_ext(array('ID_Author' => $_GET['id']));
	
	//Redirect to 404 page on wrong link
	if (!sizeof($info)) g_404();
	else $info = $info[0];
	
	//Redirect to Main author
	if ($info['ID_Main'] !== $_GET['id']) header('Location: '.get_site_url().'/authors/edit/?id='.$info['ID_Main']);
	
	get_header();
	wp_enqueue_script('authors_create', get_template_directory_uri().'/js/authors_create.js');
?>

<div class='main-panel scrollable'>
	<form id='createForm' method='post' class='info-panel'>
		<input type='hidden' name='ID_Author' value='<?php echo g_aes($info, 'ID_Author');?>' required />
		<fieldset>
			<legend>Данные об авторе</legend>
			<div class='row mb-1'>
				<div class='col-md-3 info-header'>
					<span class='required'>*</span>Фамилия:
				</div>
				<div class='col-md-9 info-header'>
					<input type='text' id='MSurname' name='MSurname' value='<?php echo g_aes($info, 'Surname');?>' style='width:100%' required />
				</div>
			</div>
			<div class='row mb-1'>
				<div class='col-md-3 info-header'>
					<span class='required'>*</span>Инициалы:
				</div>
				<div class='col-md-9 info-header'>
					<input type='text' id='MInitials' name='MInitials' value='<?php echo g_aes($info, 'Initials');?>' style='width:100%' required />
				</div>
			</div>
			<div class='row mb-1'>
				<div class='col-md-3 info-header'>
					РИНЦ-ID:
				</div>
				<div class='col-md-9 info-header'>
					<input type='text' id='MRSCI' name='MRSCI' value='<?php echo g_aes($info, 'RSCI');?>' style='width:100%' />
				</div>
			</div>
			<div class='row mb-1'>
				<div class='col-md-3 info-header'>
					Scopus-ID:
				</div>
				<div class='col-md-9 info-header'>
					<input type='text' id='MScopus' name='MScopus' value='<?php echo g_aes($info, 'Scopus');?>' style='width:100%' />
				</div>
			</div>
			<div class='row mb-1'>
				<div class='col-md-3 info-header'>
					Псевдонимы:
				</div>
				<?php $aus = g_aes($info, 'Twins');?>
				<div class='col-md-9'>
					<input id='authorSearch' type='text' style='width:100%;' placeholder='Поиск по фамилии автора' />
					<table id='authors' class='mb-2 mt-2' style='<?php if (empty($aus)) echo 'display:none'?>'>
						<thead>
							<th style='width:25px'></th>
							<th class='text-center' style='width:25px'>№</th>
							<th class='text-center' style='width:130px'>Фамилия</th>
							<th class='text-center' style='width:50px'>И.О.</th>
						</thead>
						<tbody>
							<?php if (!is_null($aus)) {
								$seqNo = 1;
								foreach ($aus as $a) {
									?>
									<tr style='height:35px'>
										<td><i class='zmdi zmdi-close-circle zmdi-action' style='vertical-align:middle;font-size:1.3em'></i></td>
										<td class='text-center'><?php echo $seqNo++ ?><input type='hidden' name='ID_Alias' value='<?php echo $a['ID_Alias'] ?>' /></td>
										<td><input type='text' name='Surname' value='<?php echo $a['Surname'] ?>'/></td>
										<td><input type='text' name='Initials' value='<?php echo $a['Initials'] ?>'/></td>
									</tr>
									<?php }
							}?>
						</tbody>
					</table>
				</div>
			</div>
		</fieldset>
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
