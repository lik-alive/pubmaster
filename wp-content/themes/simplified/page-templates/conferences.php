<?php
	/*
		Template Name: Conferences
	*/
	get_header(); 
	wp_enqueue_script('conferences', get_template_directory_uri().'/js/conferences.js');
	
	$authors = authors_list_min();
	$years = conferences_list_years();
?>


<div class='widget-panel'>
	<div class='widget fastsearch'>
		<input id='searchInput' type='text' autocomplete='off' placeholder='Быстрый поиск...' autofocus />
	</div>
	
	<div class='widget search-panel'>
		<i class="zmdi zmdi-chevron-right zmdi-action search-unroll"></i>
		<i class="zmdi zmdi-close zmdi-action search-roll"></i>
		<div class='widget-title'>Категории</div>
		<div>
			<div class='search-category'>Авторы:</div>
			<div id='authors' class='search-items smooth-scroll' style='max-height: 200px'>
				<?php foreach ($authors as $val) {
					$name = $val['FullName'];
					$value = $val['ID_Author'];
					echo "<label class='cb-container'>{$name}<input type='checkbox' class='search-item' name='{$name}' value='{$value}'><span class='cb-checkmark'></span></label>";
				}?>
			</div>
		</div>
		<div>
			<div class='search-category'>Год проведения:</div>
			<div id='years' class='search-items smooth-scroll min-scroll' style='max-height: 75px'>
				<?php foreach ($years as $val) {
					$name = $val['CYear'];
					$value = $val['CYear'];
					echo "<label class='cb-container'>{$name}<input type='checkbox' class='search-item' name='{$name}' value='{$value}'><span class='cb-checkmark'></span></label>";
				}?>
			</div>
		</div>
	</div>
	
	<div class='widget'>
		<div class='widget-title'>Дополнительно</div>
		<button id='newarticle' class='btn btn-warning'>
			<i class='zmdi zmdi-plus'></i> Добавить доклад
		</button>
		<button class='btn btn-dark export exportsel' disabled>
			<i class='zmdi zmdi-case-check'></i> Экспорт выделенных
		</button>
		<button class='btn btn-dark export exportall'>
			<i class='zmdi zmdi-case'></i> Экспорт всех
		</button>
		<button class='btn btn-info download downloadsel' disabled>
			<i class='zmdi zmdi-case-check'></i> Скачать выделенные
		</button>
		<button class='btn btn-info download downloadall'>
			<i class='zmdi zmdi-download'></i> Скачать все
		</button>
	</div>
</div>

<div class='main-panel scrollable fs-shiftable'>
	<?php include 'tables/conferences-table.php'; ?>
</div>

<?php get_footer(); ?>