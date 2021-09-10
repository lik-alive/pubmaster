$(document).ready(function() {
	var ID_Author = searchParams.get('id');
	
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
						var id = jsondata.data[i].ID_Author;
						authors.push({surname:surname, initials:initials, label:name, id:id});
					}
					if (!data.length) authors.push({label: 'Автор не найден'});
					
					response(authors);
			}, 'json');
		},
		select: function (event, ui) {
			event.preventDefault();
			if (ui.item.id === ID_Author) AddStatusMsg([2, 'Нельзя добавить себя']);
			else if ($("#authors tbody input[name='ID_Alias'][value='" + ui.item.id + "']").length !== 0) AddStatusMsg([2, 'Данный автор уже добавлен']);
			else if (ui.item.surname !== undefined) addAuthor(ui.item);
			
			$('#authorSearch').val('');
		}
	});
	
	//Add author input fields
	function addAuthor(item) {
		if ($('#authors tbody tr').length === 0) $('#authors').show();
		
		seqno = $('#authors tbody').children().length + 1;
		
		$('#authors tbody').append("<tr style='height:35px'>" + 
			"<td><i class='zmdi zmdi-close-circle zmdi-action' style='vertical-align:middle;font-size:1.3em'></i></td>" +
			"<td class='text-center'>" + seqno + "<input type='hidden' name='ID_Alias' value='" + item.id + "' /></td>" +
			"<td><input type='text' name='Surname' value='" + item.surname + "' readonly /></td>" +
			"<td><input type='text' name='Initials' value='" + item.initials + "' readonly /></td>" +
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
	
	//Create author
	$('#createForm').on('submit', function (event) {
		event.preventDefault();
				
		var fd = new FormData($('#createForm')[0]);
		
		var twins = [];
		$('#authors tbody tr').each(function() {
			var id = $(this).children().find('input[name=ID_Alias]').val();
			twins.push({ID_Alias:id})
		});
		fd.append('Twins', JSON.stringify(twins));
		
		fd.append('action', 'authors_update_json');
			
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
					window.location.href = SITE_URL + '/authors/view/?id=' + data[2];
				}
			}
		});
	});
	
	//Cancel
	$('.cancel').click(function() {
		window.history.back();
	});
});  