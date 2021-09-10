<script>
	$(document).ready(function() {
		//-----Init
		{
			var dt = $('#articlestable');
			dt.find('.mainchecker').prop('checked', false);
		}
		
		//*****Main table
		var table = dt.DataTable( {
			"bAutoWidth": false,
			"ordering": true,
			"bInfo" : false,
			"pagingType": 'numbers',
			"pageLength": 20,
			"displayStart": 0,
			"bStateSave": true,
			"serverSide": false,
			"processing": false,
			"language": {
				'emptyTable': "<div style='text-align: center'>Статей не обнаружено</div>",
				'zeroRecords': "<div style='text-align: center'>Совпадений не найдено</div>",
				'loadingRecords': "<div style='text-align: center'>Загружается...</div>"
			},
			"ajax":{
				url: ADMIN_URL + "?action=articles_list_json",
				dataType : "json",
				contentType: "application/json; charset=utf-8",
				data: function (d) {
					return $.extend( {}, d, {
						"filter": dt_filter
					} );
				}
			},
			
			"columns": [
			{ 'name': 'check', "defaultContent": '', "orderable": false, 'class': 'dt-center top-align',
				'render':function() {
					//Clear main checked after data reloaded
					if (dt.find('.mainchecker').prop('checked')) dt.find('.mainchecker').prop('checked', false);
					return "<label class='cb-container large mt-1'><input type='checkbox' class='subchecker'><span class='cb-checkmark'></span></label>"
				}
			},
			{ 'name': 'id', "data": "ID_Article", 'visible': false },
			{ 'name': 'no', "defaultContent": '', "orderable": false, 'class': 'dt-center top-align' },
			{ 'name': 'title', "data": "Title", 'class': 'sorting',
				"render": function (data, type, JsonResultRow, meta) {
					if ( type === 'sort' || type === 'type' ) {
						return data;
					}
					else {
						var str = "<a class='article' href='view/?id=" + JsonResultRow['ID_Article'] + "'>" + JsonResultRow['Title'] + '</a><br/>';
						
						var aus = '';
						JsonResultRow['Authors'].forEach(a => {
							if (aus !== '') aus += ', ';
							aus += "<a class='author' href='" + SITE_URL + "/authors/view/?id=" + a.ID_Main + "'>" + a.FullName + '</a>';
						});
						str += aus + '<br/>';
						
						str += '<span class="other">'+ JsonResultRow['JTitle'] + ', '
						+ JsonResultRow['Year'] + '. ';
						
						var rus = containsRussianChars(JsonResultRow['JTitle']);
						var voliss = '';
						if (JsonResultRow['VolumeNo'] != null) {
							if (rus) voliss += 'Т. ';
							else voliss += 'Vol. ';
							voliss += JsonResultRow['VolumeNo'];
							if (JsonResultRow['IssueNo'] != null) voliss += ', ';
						}
						if (JsonResultRow['IssueNo'] != null) {
							if (rus) voliss += '№ ';
							else voliss += 'Iss. ';
							voliss += JsonResultRow['IssueNo'];
						}
						if (voliss !== '') str += voliss + '.';
						str += '</span>';
						
						return str;
					}
				}  
			},
			{ 'name': 'scopus', "data": "Indexing", 'class': 'dt-center sorting', 'orderSequence': ['desc', 'asc'],
				"render": function (data, type, JsonResultRow, meta) {
					if ( type === 'sort' || type === 'type' ) {
						return data & 4;
					}
					else {
						if (data & 4) return imgok;
						else return imgno;
					}
				} 
			},
			{ 'name': 'wos', "data": "Indexing", 'class': 'dt-center sorting', 'orderSequence': ['desc', 'asc'],
				"render": function (data, type, JsonResultRow, meta) {
					if ( type === 'sort' || type === 'type' ) {
						return data & 8;
					}
					else {
						if (data & 8) return imgok;
						else return imgno;
					}
				} 
			},
			{ 'name': 'pdf', "data": "PDF", 'class': 'dt-center sorting',
				"render": function (data, type, JsonResultRow, meta) {
					if ( type === 'sort' || type === 'type' ) {
						return data != null;
					}
					else {
						if (data == null) return nofile;
						return file;
					}
				} 
			},
			{ 'name': 'kw', "data": "Keywords", 'class': 'dt-center sorting', 'visible': false,
				"render": function (data, type, JsonResultRow, meta) {
					if ( type === 'sort' || type === 'type' ) {
						return data != null;
					}
					else {
						if (data == null) return bad;
						return good;
					}
				} 
			},
			{ 'name': 'doi', "data": "DOI", 'class': 'dt-center sorting', 'visible': false,
				"render": function (data, type, JsonResultRow, meta) {
					if ( type === 'sort' || type === 'type' ) {
						return data != null;
					}
					else {
						if (data == null) return bad;
						return good;
					}
				} 
			}
			],
			
			"order": [[ 3, "asc" ]],
			
			"drawCallback": function ( settings ) {
				this.api().column('no:name').nodes().each( function (cell, i) { cell.innerHTML = i+1; } );
			}
		});
		
		//Open PDF
		table.on('click', '.open-action', function() {
			window.open(table.row($(this).closest('tr')).data().PDF, '_blank');
		});
		
		//Upload PDF
		table.on('click', '.upload-action', function() {
			window.open('edit/?id=' + table.row($(this).closest('tr')).data().ID_Article, '_blank');
		});
		
		//Hide pagination list
		$('#articlestable_wrapper .dataTables_length')[0].style.display='none';
		
		//Set subcheckers' status based on main checker change
		dt.find('.mainchecker').change(function() {
			if ($(this).prop('indeterminate')) return;
			
			var check = $(this).prop('checked');
			table.column('check:name').nodes().each(function(el) {
				$(el).find('.subchecker').prop('checked', check);
			});
		});
		
		//Set main checker status (in header) on subchecker change
		table.on('change', '.subchecker', function () {
			$status = null;
			$mixed = false;
			table.column('check:name').nodes().each(function(el) {
				$cur = $(el).find('.subchecker').prop('checked');
				if ($status === null) $status = $cur;
				else if ($status !== $cur) $mixed = true;
			});
			
			dt.find('.mainchecker').prop('indeterminate', $mixed);
			dt.find('.mainchecker').prop('checked', $status);
			dt.find('.mainchecker').trigger('change');
		});
	});
</script>

<table id='articlestable' class='mydataTable stripe hover'>
	<thead>
		<tr>
			<th width='30px'><label class='cb-container large mb-1'><input type='checkbox' class='mainchecker'><span class='cb-checkmark'></span></label></th>
			<th style='display:none'>ID</th>
			<th width='30px' style='min-width:30px'>№</th>
			<th width='100%' style='text-align:center'>Сведения</th>
			<th width='30px'>Scopus</th>
			<th width='30px'>WoS</th>
			<th width='30px'>PDF</th>
			<th width='30px'>KW</th>
			<th width='30px'>DOI</th>
		</tr>
	</thead>
</table>