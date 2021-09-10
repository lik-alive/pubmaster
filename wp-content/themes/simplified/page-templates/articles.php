<?php
/*
		Template Name: Articles
	*/
get_header();
wp_enqueue_script('articles', get_template_directory_uri() . '/js/articles.js');

$authors = authors_list_min();
$years = articles_list_years();
$indexes = array(array('Name' => 'Web of Science', 'Value' => 8), array('Name' => 'Scopus', 'Value' => 4), array('Name' => 'ВАК', 'Value' => 2), array('Name' => 'РИНЦ', 'Value' => 1));
$keywords = articles_list_keywords();
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
				} ?>
			</div>
		</div>
		<div>
			<div class='search-category'>Год публикации:</div>
			<div id='years' class='search-items smooth-scroll min-scroll' style='max-height: 75px'>
				<?php foreach ($years as $val) {
					$name = $val['Year'];
					$value = $val['Year'];
					echo "<label class='cb-container'>{$name}<input type='checkbox' class='search-item' name='{$name}' value='{$value}'><span class='cb-checkmark'></span></label>";
				} ?>
			</div>
		</div>
		<div>
			<div class='search-category'>Индексация:</div>
			<div id='indexes' class='search-items smooth-scroll min-scroll' style='max-height: 50px'>
				<?php foreach ($indexes as $val) {
					$name = $val['Name'];
					$value = $val['Value'];
					echo "<label class='cb-container'>{$name}<input type='checkbox' class='search-item' name='{$name}' value='{$value}'><span class='cb-checkmark'></span></label>";
				} ?>
			</div>
		</div>
		<div>
			<a href='' class='search-collapser' data-toggle='collapse' data-target="#kwpanel">
				<div class='search-category'>Ключевые слова:<i class='zmdi zmdi-chevron-down' style='float: right; margin-top: 4px; margin-right: 5px; font-weight: bold'></i>
				</div>
			</a>
			<div id='kwpanel' class='collapse'>
				<input id='kwsearch' type='text' placeholder='Поиск слов...' style='margin-bottom:2px' />
				<div id='keywords' class='search-items smooth-scroll' style='max-height:200px'>
					<?php foreach ($keywords as $key => $val) {
						$name = $key;
						$value = $key;
						echo "<label class='cb-container'>{$name} ({$val})<input type='checkbox' class='search-item' name='{$name}' value='{$value}'><span class='cb-checkmark'></span></label>";
					} ?>
				</div>
			</div>
		</div>
	</div>

	<div class='widget'>
		<div class='widget-title'>Дополнительно</div>
		<button id='newarticle' class='btn btn-warning'>
			<i class='zmdi zmdi-plus'></i> Добавить статью
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
	<div class='scrollable' style='overflow-x:auto; min-width:540px'>
		<?php include 'tables/articles-table.php'; ?>
	</div>
</div>


<?php get_footer(); ?>