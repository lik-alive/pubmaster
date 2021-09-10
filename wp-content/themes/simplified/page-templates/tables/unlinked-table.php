<script>
	$(document).ready(function() {
		//-----Init
		{
			var dt = $('#unlinkedtable');
			dt.find('.mainchecker').prop('checked', false);
		}

		//*****Main table
		var table = dt.DataTable({
			"bAutoWidth": false,
			"ordering": true,
			"bInfo": false,
			"pagingType": 'numbers',
			"pageLength": 5,
			"displayStart": 0,
			"bStateSave": true,
			"serverSide": false,
			"processing": false,
			"language": {
				'emptyTable': "<div style='text-align: center'>Статей не обнаружено</div>",
				'zeroRecords': "<div style='text-align: center'>Совпадений не найдено</div>",
				'loadingRecords': "<div style='text-align: center'>Загружается...</div>"
			},
			"ajax": {
				url: ADMIN_URL + "?action=articles_unlinked_list_json",
				dataType: "json",
				contentType: "application/json; charset=utf-8",
				data: function(d) {
					return $.extend({}, d, {
						"ID_Author": ID_Author
					});
				}
			},

			"columns": [{
					'name': 'no',
					"defaultContent": '',
					"orderable": false,
					'class': 'dt-center top-align'
				},
				{
					'name': 'title',
					"data": "Title",
					'class': 'sorting',
					'render': function(data, type, jrow, meta) {
						let title = `<span class='article'>${data}</span>`;
						if (jrow.Link !== null) {
							title = `<a class='article' href='${jrow.Link}' target='_blank'>${data}</a>`;
						}
						return `
							${title}
							<div class='author'>${jrow.Authors}</i></div>
							<div class='other'>${jrow.Journal}</div>
							<div class='text-right'>
								<button class='btn btn-warning add mb-1'><i class='zmdi zmdi-plus'></i> Добавить</button>
								<button class='btn btn-secondary hide mb-1'><i class='zmdi zmdi-eye-off'></i> Скрыть</button>
							</div>
				`;
					}
				}
			],

			"drawCallback": function(settings) {
				this.api().column('no:name').nodes().each(function(cell, i) {
					cell.innerHTML = i + 1;
				});
			}
		});

		//Hide pagination list
		$('#unlinkedtable_wrapper .dataTables_length')[0].style.display = 'none';

		/**
		 * Create new article
		 */
		table.on('click', '.add', function() {
			let data = table.row($(this).closest('tr')).data();
			if (data.Journal.includes('Свидетельство')) {
				RedirectWithData('POST', SITE_URL + '/programs/create', 'Recognize', `${data.Title}. ${data.Authors} `, '_blank');
			}
			else {
				RedirectWithData('POST', SITE_URL + '/articles/wizard', 'Recognize', `${data.Title}. ${data.Authors} ${data.Journal}`, '_blank');
			}
		});

		/**
		 * Hide the article from unlinked
		 */
		table.on('click', '.hide', function() {
			let r = confirm("Скрыть статью из списка непривязанных работ?");
			if (r) {
				let row = table.row($(this).closest('tr'));
				let data = row.data();
				
				$.post(ADMIN_URL, { action: 'articles_hide_json', Title: data.Title }, function(response) {
					let data = JSON.parse(response).data;
					
					if (data[0] === 1) row.remove().draw(false);

					AddStatusMsg(data);
				});
			}
		});
	});
</script>

<table id='unlinkedtable' class='mydataTable stripe hover'>
	<thead>
		<tr>
			<th width='30px' style='min-width:30px'>№</th>
			<th width='100%' style='text-align:center'>Сведения</th>
		</tr>
	</thead>
</table>