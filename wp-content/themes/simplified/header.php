<!DOCTYPE html>
<html>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?php echo get_template_directory_uri() ?>/resources/logo-icon.png">

	<?php wp_enqueue_style('datatable-style', get_template_directory_uri() . '/assets/plugins/DataTables-1.10.18/datatables.css'); ?>
	<?php wp_enqueue_style('jqueryui-style', get_template_directory_uri() . '/assets/plugins/jQueryUI-1.12.1/jquery-ui.min.css'); ?>
	<?php wp_enqueue_style('material-design-style', get_template_directory_uri() . '/assets/plugins/material-design/css/material-design-iconic-font.min.css'); ?>
	<?php wp_head(); ?>

</head>

<?php wp_enqueue_script('datatable', get_template_directory_uri() . '/assets/plugins/DataTables-1.10.18/datatables.min.js'); ?>
<?php wp_enqueue_script('jqueryui', get_template_directory_uri() . '/assets/plugins/jQueryUI-1.12.1/jquery-ui.min.js'); ?>

<?php wp_enqueue_script('general', get_template_directory_uri() . '/js/general.js'); ?>


<script type="text/javascript">
	var SITE_URL = "<?php echo get_site_url(); ?>";
	var ADMIN_URL = "<?php echo admin_url('admin-ajax.php'); ?>";
	var TEMPLATE_URL = "<?php echo get_template_directory_uri(); ?>";
</script>

<!-- For jQuery support in php -->
<script type='text/javascript' src='<?php echo get_template_directory_uri(); ?>/assets/plugins/jQuery-3.3.1/jquery-3.3.1.min.js'></script>

<script>
	$(document).ready(function() {

		//Door animation
		var intro = localStorage.getItem('intro');
		if (intro === null) {
			setTimeout(function() {
				$('#curtain').css('opacity', 0);
			}, 100);
			setTimeout(function() {
				$('#curtain').hide();
			}, 500);
		} else {
			$('#sitescreen').show();
			$('#intro').hide();
		}


		var max_rotation = 85;
		var stop_change = max_rotation - 10;
		var door_cur_deg = 0;
		var door_forward = false;
		var opening = false;

		$('#stud').on('mouseover', function() {
			if (door_forward) return;
			door_forward = true;

			$({
				rotation: door_cur_deg
			}).animate({
				rotation: max_rotation
			}, {
				duration: 700,
				easing: 'linear',
				step: function() {
					if (door_forward) transformDoor(this.rotation);
				}
			});
		});

		$('#stud').on('mouseleave', function() {
			if (opening) return;

			door_forward = false;

			$({
				rotation: door_cur_deg
			}).animate({
				rotation: 0
			}, {
				duration: 400,
				easing: 'linear',
				step: function() {
					if (!door_forward) transformDoor(this.rotation);
				}
			});
		});

		//Process door close/open animation
		function transformDoor(rotation) {
			door_cur_deg = rotation;

			$('#door').css('transform', 'perspective(1000px) rotateY(' + rotation + 'deg)');

			$('#wall-light').css('opacity', rotation / stop_change);

			var width = $('#door')[0].getBoundingClientRect().width;
			var height = $('#door')[0].getBoundingClientRect().height;
			var butt_width = Math.round(rotation / stop_change * 6);

			$('#butt').css('width', butt_width + 'px');
			$('#butt').css('height', height + 'px');
			$('#butt').css('left', 143 - width - (butt_width - 2) * (rotation / stop_change) + 'px');
			$('#butt').css('top', (308 - height) / 2 + 'px');

			$('#way').css('opacity', rotation / stop_change);
			if (rotation > stop_change) {
				$('#way').css('pointer-events', 'all');
				$('#way').trigger('show');
			} else {
				$('#way').css('pointer-events', 'none');
			}
		}

		//Zoom-in main panel
		$('#way').click(function() {
			opening = true;
			//Save intro status in session
			localStorage.setItem('intro', 1);

			$('#sitescreen').show();
			$({
				value: 0
			}).animate({
				value: 1
			}, {
				duration: 1000,
				easing: 'swing',
				step: function() {
					if (this.value > 0.9) {
						$('#sitescreen').css('transform', '');
						$('#intro').hide();
					} else {
						$('#sitescreen').css('transform', 'translateY(-40px) translateX(-6px) scale(' + Math.min(Math.pow(this.value, 3) / 0.7, 1) + ')');
					}
				}
			});
		});
	});
</script>

<body>
	<div id='intro' style='z-index:0' <?php if (substr(get_page_link(), -11) !== '/pubmaster/') echo 'hidden'; ?>>
		<div id='screen'>
			<img id='wall' width='1920px' height='1080px' src='<?php echo get_template_directory_uri() ?>/resources/wall-inv-dark.png'></img>
			<img id='wall-light' width='1920px' height='1080px' src='<?php echo get_template_directory_uri() ?>/resources/wall-inv-light.png'></img>
			<div id='stud'>
				<i id='way' class='zmdi zmdi-open-in-browser'></i>
				<img id='door' width='143px' height='308px' src='<?php echo get_template_directory_uri() ?>/resources/door-inv.png)'>
				</img>
				<div id='butt'></div>
			</div>
		</div>
		<div id='curtain'>
		</div>
	</div>

	<div id='sitescreen' class='d-flex flex-column' style='height:100%; <?php if (substr(get_page_link(), -11) === '/pubmaster/') echo 'display:none!important'; ?>'>
		<nav class="navbar fixed-top navbar-toggleable navbar-expand-md scrolling-navbar double-nav navbar-light bg-white">
			<a class='navbar-brand' href='<?php echo esc_url(home_url('/')); ?>'>
				<img src='<?php echo get_template_directory_uri() ?>/resources/logo-full-tr.png' width="227" height="50" class="d-inline-block align-top" alt="" />
			</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="nav navbar-nav ml-auto dropdown">
					<?php $url = get_permalink(); ?>
					<li class="nav-item">
						<?php $href = esc_url(home_url('/')) . 'articles/'; ?>
						<a class="nav-link <?php if ($url === $href) echo 'active' ?>" href='<?php echo $href ?>'>Публикации</a>
					</li>
					<li class="nav-item">
						<?php $href = esc_url(home_url('/')) . 'conferences/'; ?>
						<a class="nav-link <?php if ($url === $href) echo 'active' ?>" href='<?php echo $href ?>'>Доклады</a>
					</li>
					<li class="nav-item">
						<?php $href = esc_url(home_url('/')) . 'programs/'; ?>
						<a class="nav-link <?php if ($url === $href) echo 'active' ?>" href='<?php echo $href ?>'>Свидетельства</a>
					</li>
				</ul>
			</div>
		</nav>

		<div id='status-bar' class='status-panel'></div>

		<div class='d-flex flex-row flex-fill flex-wrap' style='align-content: start'>