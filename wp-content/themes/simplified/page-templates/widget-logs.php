<script type="text/javascript">
	$(document).ready(function() {
		var widget_logtable = $('#widget-logtable').DataTable( {
			"bAutoWidth": false,
			"bInfo" : false,
			"bLengthChange": false,
			"serverSide": false,
			"processing": false,
			"paging": false,
			"ordering": false,
			"ajax":{
				url: ADMIN_URL + "?action=db_list_20logs_json",
				type: "post",
				dataType : "json",
				contentType: "application/json; charset=utf-8",
			},
			
			"columns": [
			{ "data": "DateTime", 
				"render": function (data, type, JsonResultRow, meta) {
					var str = '[' + JsonResultRow.DateTime + ']<br/>'
					+ JsonResultRow.ID_User + ': '
					+ JsonResultRow.Event;
					var loc = JSON.parse(JsonResultRow.Location);
					if (loc !== null && loc.id !== null && loc.table !== null) {
						var href = '';
						if (loc.table === 'wp_ab_articles') {
							href = SITE_URL + '/articles/view/?id=' + loc.id;
						}
						else if (loc.table === 'wp_ab_authors') {
							href = SITE_URL + '/authors/view/?id=' + loc.id;
						}
						else if (loc.table === 'wp_ab_conferences') {
							href = SITE_URL + '/conferences/view/?id=' + loc.id;
						}
						else if (loc.table === 'wp_ab_programs') {
							href = SITE_URL + '/programs/view/?id=' + loc.id;
						}
						
						if (href !== '') str += "</br><a style='color:var(--bg)' href='" + href + "'>Перейти</href>";
						else str += '(' + loc.table + ')';
					}
					return str;
				}
			},
			{ "data": "Status"}
			],
			
			"columnDefs": [
			{
				"targets":  [1],
				"visible": false
			}
			],
			
			"drawCallback": function ( settings ) {
				var api = this.api();
				var rows = api.rows();
				
				//Colorize rows
				for (var i = 0; i < api.rows().count(); i++) {
					var status = api.cell(i, 1).data();
					if (status == 2) $(api.row(i).node()).addClass('alarm');
					else $(api.row(i).node()).addClass('cool');
				}
			}
		});
		
		//Reload table after 1min
		function widget_logreload(){
			setTimeout(function(){ 
				widget_logtable.ajax.reload();
				widget_logreload();
			}, 60000);
		};
		widget_logreload();
	});
</script>

<?php if (wp_get_current_user()->user_login === 'secret') { ?>
	<div class='widget'>
		<div class='info-panel'>
			<div class='text-center'>
				<h5>Логи</h5>
			</div>
			
			<div style='height:470px; overflow-y:scroll; overflow-x:hidden;'>
				<table id='widget-logtable'></table>
			</div>
		</div>
	</div>
<?php } ?>