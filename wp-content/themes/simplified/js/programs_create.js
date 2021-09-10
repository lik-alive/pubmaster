$(document).ready(function() {
	var ID_Program = searchParams.get('id');
	
	var pdfFM = new FileManager(FileManagerOptions.Upload, FileManagerOptions.Closeable, FileManagerOptions.OnlyPdf);
	pdfFM.embedObject($('#pdffile'));
	
	//Clear form data
	function clearfields() {
		$('#createForm').find('input[type="text"], input[type="number"], select, textarea')
		.each(function() {
			$(this).val('');
		});
		$('#createForm').find('input[type="checkbox"]')
		.each(function() {
			$(this).prop('checked', false);
		});
		$('#authors tbody tr').each(function() {
			$(this).remove();
		});
		$('#authors').hide();
	};
	
	//Find author by name
	$('#authorSearch').autocomplete({
		minLength: 1,
		source: function (request, response) {
			$.get(ADMIN_URL, {action: 'authors_find_json', 'kw': request.term }, function(jsondata){
				var authors = [];
				var data = jsondata.data;
				for (var i = 0; i < data.length; i++) {
					var surname = jsondata.data[i].Surname;
					var initials = jsondata.data[i].Initials;
					var name = jsondata.data[i].FullName;
					authors.push({surname:surname, initials:initials, label:name});
				}
				authors.push({label: '+ Добавить нового автора'});
				
				response(authors);
			}, 'json');
		},
		select: function (event, ui) {
			event.preventDefault();
			if (ui.item.surname === undefined) {
				addAuthor({surname:$('#authorSearch').val()});
				$('#authors tbody input').last().focus();
			}
			else addAuthor(ui.item);
			$('#authorSearch').val('');
		}
	});
	
	//Add author input fields
	function addAuthor(item) {
		if ($('#authors tbody tr').length === 0) $('#authors').show();
		
		seqno = $('#authors tbody').children().length + 1;
		if (item.initials === undefined) item.initials = '';
		
		$('#authors tbody').append("<tr style='height:35px'>" + 
		"<td><i class='zmdi zmdi-close-circle zmdi-action' style='vertical-align:middle;font-size:1.3em'></i></td>" +
		"<td class='text-center'>" + seqno + "</td>" +
		"<td><input type='text' name='Surname' value='" + item.surname + "' required/></td>" +
		"<td><input type='text' name='Initials' value='" + item.initials + "' required/></td>" +
		"</tr>");
	};
	
	//Remove author from the list
	$('#authors').on('click', '.zmdi-action', function() {
		var tr = $(this).closest('tr');
		var no = tr.children().eq(1).html();
		
		tr.remove();
		if ($('#authors tbody tr').length === 0) $('#authors').hide();
		
		$('#authors tbody tr').each(function() {
			var curno = $(this).children().eq(1).html();
			if (curno > no) $(this).children().eq(1).html(curno - 1);
		});
	});
	
	//Autoidentify year
	$('input[name="RegNo"]').bind('input propertychange', function() {
		if ($(this).val().length > 3)
		$('input[name="Year"]').val($(this).val().substring(0, 4));
	});
	
	//Load most frequent authors
	$('body').on('click', '.addauthor', function() {
		addAuthor($(this).data());
	});
	
	//Create program
	$('#createForm').on('submit', function (event) {
		event.preventDefault();
		
		if ($('#authors tbody tr').length === 0) {
			AddStatusMsg([2, 'Задайте авторов']);
			return;
		}
		
		var fd = new FormData($('#createForm')[0]);
		var authors = [];
		var test = [];
		$('#authors tbody tr').each(function() {
			var seqNo = $(this).children().eq(1).html();
			var surname = $(this).children().find('input[name=Surname]').val();
			var initials = $(this).children().find('input[name=Initials]').val();
			initials = initials.replace('. ', '.');
			authors.push({SeqNo:seqNo, Surname:surname, Initials:initials});
			test.push(surname + '|' + initials);
		});
		if ((new Set(test)).size !== test.length) {
			AddStatusMsg([2, 'Задано два одинаковых автора']);
			return;
		}
		fd.append('Authors', JSON.stringify(authors));
		
		if (pdfFM.filesCount > 0) fd.append('pdffile', pdfFM.file);
		
		fd.append('action', 'programs_create_or_update_json');
		
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
					PushStatusMsgInSession(data);
					window.location.href = SITE_URL + '/programs/view/?id=' + data[2];
				}
			}
		});
	});
	
	//Cancel
	$('.cancel').click(function() {
		window.history.back();
	});
});  