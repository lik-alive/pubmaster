$(document).ready(function() {
	console.log('asd');
	/*var imgok = "<img src='/COMaster/resources/check.png' width='32' height='32' style='margin:auto;' />";
	var imgno = "<img src='/COMaster/resources/cross.png' width='32' height='32' style='margin:auto;' />";*/
	
	var authors = [];
	var years = [];
	var indexes = [];
	
	/*<?php
	$aus = $_GET['au'];
	$yes = $_GET['ye'];
	$ins = $_GET['in'];
	
	for ($i = 0; !is_null($aus) && $i < sizeof($aus); $i++) {
		$val = $aus[$i];
		echo 'authors.push("';
		echo $val;
		echo '");';
	}
	
	for ($i = 0; !is_null($yes) && $i < sizeof($yes); $i++) {
		$val = $yes[$i];
		echo 'years.push("';
		echo $val;
		echo '");';
	}
	for ($i = 0; !is_null($ins) && $i < sizeof($ins); $i++) {
		$val = $ins[$i];
		echo 'indexes.push("';
		echo $val;
		echo '");';
	}
	?>*/
	
	
	/*
	var table = $('#datatable').DataTable( {
		"bAutoWidth": false,
		"ordering": true,
		"bInfo" : false,
		"pagingType": 'numbers',
		"pageLength": 20,
		"serverSide": false,
		"processing": false,				
		"language": {
			'emptyTable': "<div style='text-align: center; font-size:12pt;'>Объектов не обнаружено</div>"
		} ,
		"ajax":{
			url: "<?php echo admin_url( 'admin-ajax.php' ); ?>?action=archive_list&" + getcondstr(),
			type: "post",
			dataType : "json",
			contentType: "application/json; charset=utf-8",
		} ,
		
		"columns": [
			{ "defaultContent": '', "orderable": false, 'class': 'centered', 
			'render':function() {return "<input type='checkbox' class='checker subchecker' >" }},
			{ "data": "h_ida", 'visible': false },
			{ "defaultContent": '', "orderable": false, 'class': 'centered' },				
			{ "data": "h_tit" },
			{ "data": "h_aut" },
			{ "data": "h_iss" },
			{ "data": "h_ind", 'class': 'centered',
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
			{ "data": "h_ind", 'class': 'centered',
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
			{ "data": "h_fip", 'class': 'centered',
				"render": function (data, type, JsonResultRow, meta) {
					if ( type === 'sort' || type === 'type' ) {
						return data === '' ? 0 : 1;
					}
					else {
						if (data !== '') return "<input type='button' class='filepdf' style='width:40px; height:40px;' />";
						else return "<img src='/PUBMaster/resources/nofile.png' title='Помоги товарищам - загрузи файл :)' style='width:40px; height:40px;' />";
					}
				} 
			},
			{ "data": "h_fid", 'class': 'centered',
				"render": function (data, type, JsonResultRow, meta) {
					if ( type === 'sort' || type === 'type' ) {
						return data === '' ? 0 : 1;
					}
					else {
						if (data !== '') return "<input type='button' class='filedoc' style='width:40px; height:40px;' />";
						else return "<img src='/PUBMaster/resources/nofile.png' title='Помоги товарищам - загрузи файл :)' style='width:40px; height:40px;' />";
					}
				} 
			}
		],
		
		"drawCallback": function ( settings ) {
			this.api().column(2).nodes().each( function (cell, i) { cell.innerHTML = i+1; } );
		}
	});	
	
	InitMouseClick(table, 1, '/PUBMaster/archive/view/?id=');
	
	//Hide pagination list
	$('#datatable_wrapper .dataTables_length')[0].style.display='none';
	
	$('#searchInput').keyup(function(){
		table.search($(this).val()).draw();
	});
	
	table.on('click', '.filepdf', function () {
		filepath = table.row($(this).closest('tr')).data().h_fip;
		window.open(filepath,'_blank');
	});	
	
	table.on('click', '.filedoc', function () {
		filepath = table.row($(this).closest('tr')).data().h_fid;
		window.open(filepath,'_blank');
	});
	
	table.on('change', '.subchecker', function () {
		$status = null;
		$mixed = false;
		$('.subchecker').each(function() {
			$cur = $(this).prop('checked');
			if ($status === null) $status = $cur;
			else if ($status !== $cur) $mixed = true;
		});
		
		$('#mainchecker').prop('indeterminate', $mixed);
		$('#mainchecker').prop('checked', $status);
	});
	
	$('#addentity').on('click', function() {
		window.location.href='/PUBMaster/wizard'
	});
	
	$('#mainchecker').change(function() {
		var check = $(this).prop('checked');
		$('.subchecker').each(function() {
			$(this).prop('checked', check);
		});
	});
	
	loadSearchItems();
	
	function loadSearchItems() {
		$.ajax({
			type: 'POST',
			url: '<?php echo admin_url( 'admin-ajax.php' ); ?>?action=archive_list_authors',					
			data: null,
			contentType: false,
			processData: false,
			success: function(response){
				var html = '';
				var data = JSON.parse(response).data;
				for (var i = 0; i < data.length; i++) {							
					var checked = '';
					for (var j = 0; j < authors.length; j++)
					if (data[i].h_sur == authors[j]) {
						checked = ' checked ';
						break;
					}
					html += "<label ><input type='checkbox' name='" + data[i].h_sur + "' class='searchByAuthor' " + checked + " />&nbsp;" + data[i].h_sur + "</label></br>"
				}
				$('#searchByAuthor').html(html);
			}
		});
		
		$.ajax({
			type: 'POST',
			url: '<?php echo admin_url( 'admin-ajax.php' ); ?>?action=archive_list_years',					
			data: null,
			contentType: false,
			processData: false,
			success: function(response){
				var html = '';
				var data = JSON.parse(response).data;
				for (var i = 0; i < data.length; i++) {		
					var checked = '';
					for (var j = 0; j < years.length; j++)
					if (data[i].h_yea == years[j]) {
						checked = ' checked ';
						break;
					}
					html += "<label ><input type='checkbox' name='" + data[i].h_yea + "' class='searchByYear' " + checked + " />&nbsp;" + data[i].h_yea + "</label></br>"
				}
				$('#searchByYear').html(html);
			}
		});
		
		var html = '';
		var checked4 = '', checked8 = '';
		for (var j = 0; j < indexes.length; j++)
		if (4 == indexes[j]) checked4 = ' checked ';
		else if (8 == indexes[j]) checked8 = ' checked ';
		
		html += "<label ><input type='checkbox' name='" + 4 + "' class='searchByIndex' " + checked4 + " />&nbsp;" + "Scopus" + "</label></br>";
		html += "<label ><input type='checkbox' name='" + 8 + "' class='searchByIndex' " + checked8 + " />&nbsp;" + "WoS" + "</label></br>";
		$('#searchByIndex').html(html);
	}
	
	
	$('#searchbyitems').click(function() {
		authors.length = 0;
		years.length = 0;
		indexes.length = 0;
		
		$('.searchByAuthor').each(function() {
			if ($(this).prop('checked')) authors.push($(this).prop('name'));
		});
		
		$('.searchByYear').each(function() {
			if ($(this).prop('checked')) years.push($(this).prop('name'));
		});
		
		$('.searchByIndex').each(function() {						
			if ($(this).prop('checked')) indexes.push($(this).prop('name'));
		});
		
		window.location = '/PUBMaster/archive?' + getcondstr();
	});
	
	function getcondstr() {
		conds = '';
		for (var i = 0; i < authors.length; i++) {
			if (conds !== '') conds += '&';
			conds += 'au[]=' + authors[i];
		}
		for (var i = 0; i < years.length; i++) {
			if (conds !== '') conds += '&';
			conds += 'ye[]=' + years[i];
		}
		for (var i = 0; i < indexes.length; i++) {
			if (conds !== '') conds += '&';
			conds += 'in[]=' + indexes[i];
		}
		
		return conds;
	}
	
	$('.export').click(function() {
		//$('#exportDialog').modal('toggle');
		
		var fd = new FormData();
		
		fd.append('action', 'archive_export'); 
		
		var form = document.createElement("form");
		form.setAttribute("method", "post");
		form.setAttribute("action", 'export');
		form.setAttribute("target", '_blank');
		
		var allFlag = $(this).hasClass('exportAll');
		if ($(this).hasClass('exportAll')) {
			var data = table.data();
			for (var i = 0; i < data.length; i++) {
				var val = data[i].h_ida;
				var input = document.createElement('input');
				input.type = 'hidden';
				input.name = i;
				input.value = val;
				form.appendChild(input);
			}
		}
		else {
			var i = 0;
			$('.subchecker').each(function() {
				if ($(this).prop('checked')) {
					var val = table.row($(this).closest('tr')).data().h_ida;
					var input = document.createElement('input');
					input.type = 'hidden';
					input.name = i++;
					input.value = val;
					form.appendChild(input);
				}
			});
		}
		
		document.body.appendChild(form);
		form.submit();
		document.body.removeChild(form);
	});*/
});