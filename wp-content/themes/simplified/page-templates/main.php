<?php
	/*
		Template Name: Main
	*/
	get_header(); 
?>

<script>
	$(document).ready(function() {
		//-----Init
		{
			loadAcknowledgements();
		}
		
		//Load last gratz
		function loadAcknowledgements() {
			$.get(ADMIN_URL, { action: 'articles_acknowledgements_json'}, function(response){
				var data = JSON.parse(response).data;
				if (data !== null) {
					$('#gratzRus').val(data.TextRus);
					$('#gratzEng').val(data.TextEng);
					$('#datetime').html(revertDateTime(data.DateTime));
				}
			});
		}
		
		$('#edit').click(function() {
			setEditing(true);
		});
		
		$('#cancel').click(function() {
			setEditing(false);
		});
		
		//Update grarz
		$('#save').click(function() {
			var fd = new FormData($('#createForm')[0]);
			fd.append('action', 'articles_acknowledgements_add_json');
			
			$.ajax({
				type: 'POST',
				url: ADMIN_URL,
				data: fd,
				contentType: false,
				processData: false,
				success: function(response){
					var data = JSON.parse(response).data;
					if (data[0] != 1) AddStatusMsg(data);
					else {
						AddStatusMsg(data);
						loadAcknowledgements();
						setEditing(false);
					}
				}
			});
		});
		
		//Enable or disable editing
		function setEditing(edit) {
			$('#gratzRus').attr('readonly', !edit);
			$('#gratzEng').attr('readonly', !edit);
			$('#edit').toggle(!edit);
			$('#save').toggle(edit);
			$('#cancel').toggle(edit);
		}
		
		//Copy to clipboard
		$('#copyRus').click(function() {
			$('#gratzRus').select();
			document.execCommand('copy');
			$('#copyRus').focus();
			AddStatusMsg([1, 'Благодарности на русском скопированы в буфер обмена']);
		});
		
		$('#copyEng').click(function() {
			$('#gratzEng').select();
			document.execCommand('copy');
			$('#copyEng').focus();
			AddStatusMsg([1, 'Благодарности на английском скопированы в буфер обмена']);
		});
	});  
	
</script>

<div class='text-center mb-4 fullwidth-panel'>
	<h2 style='font-family: Verdana, fantasy;letter-spacing: 0.01em; font-weight: bold;'>Добро пожаловать!</h2>
</div>

<div style='opacity:0.4; position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: -1; background-image: url(<?php echo get_template_directory_uri() ?>/resources/books.jpg)' >
</div>

<div class='main-panel scrollable'>
	<div class='info-panel'>
		<div class='text-center'><h5>Благодарности</h5></div>
		<form id='createForm'>
			<div class='mb-1'>Версия от: <span id='datetime'></span></div>
			<div class='font-weight-bold'>На русском</div>
			<textarea id='gratzRus' rows='8' name='TextRus' class='nolinebreaks' readonly></textarea>
			<div class='row'>
				<div class='col-12 col-md-6'></div>
				<div class='col-12 col-md-6'>
					<button id='copyRus' class='btn btn-warning btn-action' type='button' style='min-width:210px'>
						<i class='zmdi zmdi-copy'></i> Скопировать в буфер
					</button>
				</div>
			</div>
			<div class='font-weight-bold'>На английском</div>
			<textarea id='gratzEng' rows='8' name='TextEng' class='nolinebreaks' readonly></textarea>
			<div class='row'>
				<div class='col-12 col-md-6'></div>
				<div class='col-12 col-md-6'>
					<button id='copyEng' class='btn btn-warning btn-action' type='button' style='min-width:210px'>
						<i class='zmdi zmdi-copy'></i> Скопировать в буфер
					</button>
				</div>
			</div>
			<button id='edit' class='btn btn-success btn-action mt-4' type='button'>
				<i class='zmdi zmdi-edit'></i> Редактировать
			</button>
			<div class='row'>
				<div class='col'>
					<button id='save' class='btn btn-primary btn-action mt-4' style='display:none' type='button'>
						<i class='zmdi zmdi-check'></i> Сохранить
					</button>
				</div>
				<div class='col'>
					<button id='cancel' class='btn btn-secondary btn-action mt-4' style='display:none' type='button'>
						<i class='zmdi zmdi-close'></i> Отмена
					</button>
				</div>
			</div>
		</form>
	</div>
</div>

<div class='widget-panel'>
	<div class='widget'>
		<div class='info-panel'>
			<div class='text-center'><h5>Статистика архива</h5></div>
			<div class='row mb-1'>
				<div class='col text-center'><b><i>Общее количество</i></b></div>
			</div>
			<div class='row mb-1'>
				<div class='col-7 text-center'>Публикаций:</div>
				<div class='col-5 text-center'><?php $ac = articles_count(); echo $ac;  ?></div>
			</div>
			<div class='row mb-1'>
				<div class='col-7 text-center'>Докладов:</div>
				<div class='col-5 text-center'><?php echo conferences_count();  ?></div>
			</div>
			<div class='row mb-2'>
				<div class='col-7 text-center'>Свидетельств:</div>
				<div class='col-5 text-center'><?php $pc = programs_count(); echo $pc;  ?></div>
			</div>
			<div class='row'>
				<div class='col text-center'><b><i>Размещение в базах</i></b></div>
				<?php $ic = articles_index_count(); ?>
			</div>
			<div class='row mb-1'>
				<div class='col-7 text-center'>РИНЦ:</div>
				<div class='col-5 text-center'><?php echo $ic[0];  ?></div>
			</div>
			<div class='row mb-1'>
				<div class='col-7 text-center'>ВАК:</div>
				<div class='col-5 text-center'><?php echo $ic[1];  ?></div>
			</div>
			<div class='row mb-1'>
				<div class='col-7 text-center'>SCOPUS:</div>
				<div class='col-5 text-center'><?php echo $ic[2];  ?></div>
			</div>
			<div class='row mb-1'>
				<div class='col-7 text-center'>WoS:</div>
				<div class='col-5 text-center'><?php echo $ic[3];  ?></div>
			</div>
			<div class='row'>
				<div class='col text-center'><b><i>Загружено файлов</i></b></div>
			</div>
			<div class='row mb-1'>
				<div class='col-7 text-center'>Публикаций:</div>
				<div class='col-5 text-center'><?php $afc = articles_files_count(); echo $afc.' ('.round($afc/$ac*100).'%)' ?></div>
			</div>
			<div class='row mb-1'>
				<div class='col-7 text-center'>Свидетельств:</div>
				<div class='col-5 text-center'><?php $pfc = programs_files_count(); echo $pfc.' ('.round($pfc/$pc*100).'%)'  ?></div>
			</div>
		</div>
	</div>
	<?php include 'widget-logs.php'; ?>
</div>

<?php get_footer(); ?>