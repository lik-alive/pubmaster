<?php
/*
		Template Name: Articles Wizard
	*/
get_header();
wp_enqueue_script('articles_wizard', get_template_directory_uri() . '/js/articles_wizard.js');
wp_enqueue_script('file-manager', get_template_directory_uri() . '/js/file-manager.js');

$info = [];

if (isset($_GET['id'])) {
	$info = articles_list_ext(array('ID_Article' => $_GET['id']));

	//Redirect to 404 page on wrong link
	if (empty($info)) g_404();
	$info = $info[0];
}

$hasConference = !is_null(g_aes($info, 'ID_Conference'));

// Load title from POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['Recognize'])) {
	$P_recognize = json_decode(stripslashes($_POST['Recognize']));
}
?>

<div class='main-panel scrollable'>
	<div id='autorecognize' class='info-panel' <?php if (!empty($_GET['id'])) echo "style='display:none'" ?>>
		<button class="btn btn-info btn-action collapser <?php if (is_null($P_recognize)) echo 'collapsed' ?>" data-toggle="collapse" href="#collapse">
			Автораспознавание
		</button>

		<div id='collapse' class='col-md-12 collapse <?php if (isset($P_recognize)) echo 'show' ?>'>
			<div class='mt-4 mb-2'>
				<textarea id='text' rows='7' style='width:100%; line-height:1;'><?php echo $P_recognize ?? "" ?></textarea>
				<div class='row'>
					<div class='col-md-8'></div>
					<div class='col-md-4'>
						<button id='recognize' class='btn btn-success btn-action'>
							<i class='zmdi zmdi-eye'></i> Распознать
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>


	<form id='createForm' method='post'>
		<input type='hidden' name='ID_Article' value='<?php echo g_aes($info, 'ID_Article'); ?>' required />

		<div class='info-panel'>
			<fieldset>
				<legend>Данные о статье</legend>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						<span class='required'>*</span>Название статьи:
					</div>
					<div class='col-md-9'>
						<textarea id='ATitle' name='ATitle' rows='3' class='nolinebreaks' style='width:100%; height:100%; line-height:1;' required><?php if (isset($P_title)) echo json_decode(stripslashes($P_title));
																																																																				else echo g_aes($info, 'Title'); ?></textarea>
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
							$title = g_aes($info, 'Title');
							if (is_null($title) || g_sir($title)) $poplang = 'ru';
							else $poplang = 'en';

							$popaus = authors_list_top($poplang);
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
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						<span class='required'>*</span>Первая страница:
					</div>
					<div class='col-md-9'>
						<input type='number' id='APageFrom' name='APageFrom' value='<?php echo g_aes($info, 'PageFrom'); ?>' required />
					</div>
				</div>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						<span class='required'>*</span>Последняя страница:
					</div>
					<div class='col-md-9'>
						<input type='number' id='APageTo' name='APageTo' value='<?php echo g_aes($info, 'PageTo'); ?>' required />
					</div>
				</div>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						DOI:
					</div>
					<div class='col-md-9 info-header'>
						<input type='text' id='ADOI' name='ADOI' value='<?php echo g_aes($info, 'DOI'); ?>' style='width:100%' />
					</div>
				</div>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						Альт. название (перевод):
					</div>
					<div class='col-md-9'>
						<textarea id='ATitleAlt' name='ATitleAlt' rows='3' class='nolinebreaks' style='width:100%; line-height:1; margin-bottom:2px;'><?php echo g_aes($info, 'TitleAlt'); ?></textarea>
					</div>
				</div>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						Ключевые слова:
					</div>
					<div class='col-md-9'>
						<textarea id='AKeywords' name='AKeywords' rows='5' style='width:100%; line-height:1; margin-bottom:2px;'><?php echo g_aes($info, 'Keywords'); ?></textarea>
					</div>
				</div>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						Выступление:
					</div>
					<div class='col-md-9'>
						<label class='cb-container mb-1'>конференционный доклад<input type='checkbox' id='AConference' name='AConference' <?php if ($hasConference) echo 'checked'; ?>><span class='cb-checkmark'></span></label>
					</div>
				</div>
			</fieldset>
		</div>

		<div id='conference' class='info-panel' style='<?php if (!$hasConference) echo 'display:none' ?>'>
			<fieldset>
				<legend>
					<div class='row'>
						<div class='col-6'>
							Данные о конференции
						</div>
						<div class='col'>
							<input id='conferenceSearch' type='text' style='font-size:0.9em;' placeholder='Поиск по названию' />
						</div>
					</div>
				</legend>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						<span class='required'>*</span>Название:
					</div>
					<div class='col-md-9'>
						<textarea id='CTitle' name='CTitle' rows='3' class='nolinebreaks' style='width:100%; line-height:1; margin-bottom:2px;' <?php if ($hasConference) echo 'required' ?>><?php echo g_aes($info, 'CTitle'); ?></textarea>
					</div>
				</div>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						<span class='required'>*</span>Страна:
					</div>
					<div class='col-md-9'>
						<select id='CCountry' name='CCountry' <?php if ($hasConference) echo 'required' ?>>
							<?php
							global $COUNTRIES_ENG;
							$countries = array_merge(
								array('' => 'Выберите страну', 'RUS' => $COUNTRIES_ENG['RUS']),
								$COUNTRIES_ENG
							);

							if (g_sir(g_aes($info, 'CTitle'))) $countries['RUS'] = 'Россия';

							foreach ($countries as $key => $val) {
							?>
								<option value='<?php echo $key; ?>' <?php if (g_aes($info, 'Country') === $key) echo 'selected'; ?>><?php echo $val; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						<span class='required'>*</span>Город:
					</div>
					<div class='col-md-9'>
						<input type='text' id='CCity' name='CCity' value='<?php echo g_aes($info, 'CCity'); ?>' <?php if ($hasConference) echo 'required' ?> />
					</div>
				</div>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						<span class='required'>*</span>Дата начала:
					</div>
					<div class='col-md-9'>
						<input type='date' id='CDateFrom' name='CDateFrom' value='<?php echo g_aes($info, 'DateFrom'); ?>' <?php if ($hasConference) echo 'required' ?> />
					</div>
				</div>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						<span class='required'>*</span>Дата конца:
					</div>
					<div class='col-md-9'>
						<input type='date' id='CDateTo' name='CDateTo' value='<?php echo g_aes($info, 'DateTo'); ?>' <?php if ($hasConference) echo 'required' ?> />
					</div>
				</div>
			</fieldset>
		</div>

		<div class='info-panel'>
			<fieldset>
				<legend>
					<div class='row'>
						<div class='col-6'>
							Данные о журнале
						</div>
						<div class='col'>
							<input id='journalSearch' type='text' style=' font-size:0.9em;' placeholder='Поиск по названию журнала' />
						</div>
					</div>
				</legend>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						<span class='required'>*</span>Название журнала:
					</div>
					<div class='col-md-9'>
						<textarea id='JTitle' name='JTitle' rows='3' class='nolinebreaks' style='width:100%; line-height:1;' required><?php echo g_aes($info, 'JTitle'); ?></textarea>
					</div>
				</div>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						Город изд-ва:
					</div>
					<div class='col-md-9'>
						<input type='text' id='JCity' name='JCity' value='<?php echo g_aes($info, 'City'); ?>' />
					</div>
				</div>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						Изд-во:
					</div>
					<div class='col-md-9'>
						<input type='text' id='JPublisher' name='JPublisher' style='width:100%' value='<?php echo g_aes($info, 'Publisher'); ?>' />
					</div>
				</div>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						Базы:
					</div>
					<div class='col-md-9'>
						<?php $ind = g_aes($info, 'Indexing'); ?>
						<label class='cb-container mb-1'>WoS<input type='checkbox' id='JIndexingWoS' name='JIndexingWoS' <?php if ($ind & 8) echo 'checked'; ?>><span class='cb-checkmark'></span></label>
						<label class='cb-container mb-1'>Scopus<input type='checkbox' id='JIndexingScopus' name='JIndexingScopus' <?php if ($ind & 4) echo 'checked'; ?>><span class='cb-checkmark'></span></label>
						<label class='cb-container mb-1'>ВАК<input type='checkbox' id='JIndexingVAK' name='JIndexingVAK' <?php if ($ind & 2) echo 'checked'; ?>><span class='cb-checkmark'></span></label>
						<label class='cb-container mb-1'>РИНЦ<input type='checkbox' id='JIndexingRSCI' name='JIndexingRSCI' <?php if ($ind & 1) echo 'checked'; ?>><span class='cb-checkmark'></span></label>
					</div>
				</div>
			</fieldset>

			<fieldset>
				<legend>
					<div class='row'>
						<div class='col-6'>
							Данные о номере
						</div>
						<div class='col'>
							<input id='issueSearch' type='text' style='font-size:0.9em;' placeholder='Поиск по году, тому или выпуску' />
						</div>
					</div>
				</legend>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						<span class='required'>*</span>Год издания:
					</div>
					<div class='col-md-9'>
						<input type='number' step='1' id='IYear' name='IYear' value='<?php echo g_aes($info, 'Year'); ?>' required />
					</div>
				</div>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						Номер тома:
					</div>
					<div class='col-md-9'>
						<input type='text' id='IVolumeNo' name='IVolumeNo' value='<?php echo g_aes($info, 'VolumeNo'); ?>' />
					</div>
				</div>
				<div class='row mb-1'>
					<div class='col-md-3 info-header'>
						Номер выпуска:
					</div>
					<div class='col-md-9'>
						<input type='text' id='IIssueNo' name='IIssueNo' value='<?php echo g_aes($info, 'IssueNo'); ?>' />
					</div>
				</div>
			</fieldset>
		</div>

		<div class='info-panel'>
			<fieldset>
				<legend>Файлы статьи</legend>
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