<?php
	/*
		Template Name: Export
	*/
	
	get_header(); 
?>

<script>
	$(document).ready(function() {
		
		//-----Init
		{
			exportProcess();
		}
		
		//Load template on standard change
		$('#template').change(function() {
			loadTemplate();
		});
		
		function loadTemplate() {
			$.get(ADMIN_URL, { action: 'export_get_template_json', 'id': $('#template').val() }, function(response){
				var data = JSON.parse(response).data;
				$('#formatStr').val(data.Format);
				exportProcess();
			});
		}
		
		//Export process
		$('#formatStr').on('keyup', function() {
			exportProcess();
		});
		
		function exportProcess() {
			var ida = null, idp = null;
			if (searchParams.has('ID_Articles')) ida = JSON.parse(searchParams.get('ID_Articles'));
			else if (searchParams.has('ID_Conferences')) ida = JSON.parse(searchParams.get('ID_Conferences'));
			else if (searchParams.has('ID_Programs')) idp = JSON.parse(searchParams.get('ID_Programs'));
			
			$.get(ADMIN_URL, { action: 'export_process_json', 'Format': $('#formatStr').val(), 'ID_Articles': ida, 'ID_Programs': idp }, function(response){
				$('#exportStr').val(response.data);
			}, 'json');
		}
		
		//Copy to clipboard
		$('#copy').click(function() {
			$('#exportStr').select();
			document.execCommand('copy');
			$('#copy').focus();
			AddStatusMsg([1, 'Результат экспорта скопирован в буфер обмена']);
		});
	});  
</script>

<div class='main-panel scrollable'>
	<div class='info-panel'>
		<div class='text-center'><h5>Формат экспорта</h5></div>
		<div class='row mb-1'>
			<div class='col-md-3 info-header'>Стандартные</div>
			<div class='col-md'>
				<select id='template' style='width:100%'>
					<?php 
						$arr = export_templates_list(); 
						$flag = true;
						foreach ($arr as $t) {
							$val = $t['ID_Template'];
							$label = $t['Title'];
						?>
						<option value='<?php echo $val; ?>' <?php if ($flag) {$flag = false; echo 'selected';} ?> ><?php echo $label; ?></option>
					<?php } ?>
				</select>
			</div>
		</div>
		<div class='row mb-1'>
			<div class='col-md-3 info-header'>Шаблон</div>
			<div class='col-md'>
				<textarea id='formatStr' class='nolinebreaks'><?php echo $arr[0]['Format'] ?></textarea>
			</div>
		</div>
		
		<button class="btn btn-primary btn-info btn-action collapser collapsed" data-toggle="collapse" href="#collapse">
			Расшифровка обозначений
		</button>
		
		<div id='collapse' class='collapse pl-5 pb-2 pt-2 mt-2' style='background:#eee'>
			<?php 
				$str = "<b>Информация о статье</b><br/>";
				$str .= "%AT% - название статьи<br/>";
				$str .= "%ATA% - переводное название статьи<br/>";
				$str .= "%JT% - название журнала<br/>";
				$str .= "%JCP% - инфомрация об издателе, формат: <Город>: <Издатель><br/>";
				$str .= "%IV% - номер выпуска<br/>";
				$str .= "%II% - номер тома<br/>";
				$str .= "%IVI% - том и выпуск (автоопределение языка), формат: Т./Vol. 1, №/Iss. 3<br/>";
				$str .= "%IY% - год<br/>";
				$str .= "%APF% - первая страница<br/>";
				$str .= "%APL% - последняя страница<br/>";
				$str .= "%APP% - страницы, формат (автоопределение языка): С./P. 1-99<br/>";
				$str .= "%ADOI% - DOI<br/>";
				$str .= "%AKW% - ключевые слова<br/>";
				$str .= "%AIN% - индексация в базах<br/><br/>";
				$str .= "<b>Информация о конференции</b><br/>";
				$str .= "%CT% - название конференции<br/>";
				$str .= "%CCI% - город проведения<br/>";
				$str .= "%CCO% - страна проведения<br/>";
				$str .= "%CD% - даты конференции (автоопределение языка)<br/>";
				$str .= "%CDF% - дата начала<br/>";
				$str .= "%CDL% - дата окончания<br/><br/>";
				$str .= "<b>Информация о свидетельстве ЭВМ</b><br/>";
				$str .= "%PRN% - регистрационный номер<br/>";
				$str .= "%PT% - название полное (русское и английское)<br/>";
				$str .= "%PTR% - название русское<br/>";
				$str .= "%PTE% - название английское<br/>";
				$str .= "%PY% - год регистрации<br/>";
				$str .= "%PO% - владелец<br/><br/>";
				$str .= "<b>Информация об авторах</b><br/>";
				$str .= "%AFS% - фамилия первого автора<br/>";
				$str .= "%AFI% - инициалы первого автора<br/>";
				$str .= "%AS% - список всех авторов, формат: <И.А.> <Фамилия>, <И.А.> <Фамилия>,..<br/>";
				$str .= "%ASI% - список всех авторов, формат: <Фамилия> <И.А.>, <Фамилия> <И.А.>,..<br/><br/>";
				$str .= "<b>Прочее</b><br/>";
				$str .= "%NUM% - порядковая нумерация<br/>";
				$str .= '\t \n - спецсимволы<br/>';
				
				echo $str;
			?>
		</div>
	</div>
	
	<div class='info-panel'>
		<div class='text-center'><h5>Результат</h5></div>
		<textarea id='exportStr' rows='22' style='line-height:1; padding:10px;'></textarea>
		<button id='copy' class='btn btn-warning btn-action' type='button' style='min-width:210px'>
			<i class='zmdi zmdi-copy'></i> Скопировать в буфер
		</button>
	</div>
</div>


<?php get_footer(); ?>
