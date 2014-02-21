<!-- Grid Begin -->
<div class="container well well-sm">
	<h1>JobLog</h1>
</div>
<div id="edit-container"></div> <!-- /edit-container -->
<div class="container well">
	<table class="datatable" id="datatable" data-page-size="5">
		<thead>
			<tr>
				<th data-toggle="true" data-class="expand">
					JobId
				</th>
				<th data-hide="phone">
					DateRan
				</th>
				<th data-hide="phone">
					Status
				</th>
				<th data-hide="phone">
					DbBackupFilename
				</th>
				<th data-hide="phone">
					DbBackupFilesize
				</th>
				<th data-hide="phone">
					FileBackupFilename
				</th>
				<th data-hide="phone">
					FileBackupFilesize
				</th>
				<th data-hide="phone">
					Success
				</th>
				<th data-hide="phone" data-sort-ignore="true" style="width:120px;" class="nosort">
					<a class="btn btn-default btn-primary pull-center" href="javascript:void(0);" onclick="LoadRecord(0);ShowEdit();">Add</a>
				</th>
			</tr>
		</thead>
		<tbody></tbody>
		<tfoot class="hide-if-no-paging">
			<tr>
				<td colspan="8">
					<div class="pagination pagination-centered"></div>
				</td>
			</tr>
		</tfoot>
	</table> <!-- end datatable -->
</div> <!-- /content-container -->
<!-- Grid End -->
<script type="text/javascript">
	$(document).ready(function() {
		ReloadMethods();
		var responsiveHelper = undefined;
		var breakpointDefinition = {
			tablet: 1024,
			phone : 800
		};
		var tableElement = $('#datatable');
		tableElement.dataTable({
			"bLengthChange": false,
			"bFilter": true,
			"bSort": true,
			"bInfo": true,
			"bStateSave": false,
			"bProcessing": true,
			"sServerMethod": "GET",
			"bServerSide": true,
			"bAutoWidth": false,
			"sAjaxSource": "<?php echo FULL_URL; ?>_core/handlers/JobLog.DataTables.Ajax.gen.php",
			"sPaginationType": "full_numbers",
			"sDom": '<"row"<"col-md-6"l><"col-md-6"f>r>t<"row"<"col-md-6"i><"col-md-6"p>>',
			"oLanguage": {
				sLengthMenu: '_MENU_ records per page',
				sSearch: ''
			},
			"fnServerParams": function ( aoData ) {
				//aoData.push( { "name": 'txtSearchName', "value": $('#txtSearchName').val() } );
			},
			"aoColumnDefs": [
				{ "bSortable": false, "aTargets": ["nosort"] }
			],
			"fnPreDrawCallback": function () {
				// Initialize the responsive datatables helper once.
				if (!responsiveHelper) {
					responsiveHelper = new ResponsiveDatatablesHelper(tableElement, breakpointDefinition);
				}
			},
			"fnRowCallback": function (nRow) {
				responsiveHelper.createExpandIcon(nRow);
			},
			"fnDrawCallback": function (oSettings) {
				responsiveHelper.respond();
			},
			"aoColumns": [
				{ "iDataSort": 1, "mData": "JobId", sDefaultContent: "" }, //JobId
				{ "iDataSort": 2, "mData": function ( source, type, val ) { //DateRan
					if (type === 'set') {
						// Split timestamp into [ Y, M, D, h, m, s ]
						var t = source["DateRan"].split(/[- :]/);
						// Apply each element to the Date function
						var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
						source["DateRan"] = (d.getMonth() + 1) + '/' + d.getDate() + '/' + d.getFullYear();
						return;
					}
					else if (type === 'display') {
						return source["DateRan"];
					}
					else if (type === 'filter') {
						return source["DateRan"];
					}
					// 'sort', 'type' and undefined all just use the integer
					return source["DateRan"];
				}},
				{ "iDataSort": 3, "mData": "Status", sDefaultContent: "" }, //Status
				{ "iDataSort": 5, "mData": "DbBackupFilename", sDefaultContent: "" }, //DbBackupFilename
				{ "iDataSort": 7, "mData": "DbBackupFilesize", sDefaultContent: "" }, //DbBackupFilesize
				{ "iDataSort": 9, "mData": "FileBackupFilename", sDefaultContent: "" }, //FileBackupFilename
				{ "iDataSort": 11, "mData": "FileBackupFilesize", sDefaultContent: "" }, //FileBackupFilesize
				{ "iDataSort": 12, "mData": "Success", sDefaultContent: "" }, //Success
				{ "mData": function ( source, type, val ) {
					if (type === 'set') {
						return "<a href=\"javascript:void(0);\" onclick=\"LoadRecord(" + source["CREATE"] + ");ShowEdit();\" class=\"pencil\"></a> <a href=\"javascript:void(0);\" onclick=\"if(!confirm(\'Are you sure you want delete this item?\')) return false;Delete(" + source["CREATE"] + ");\" class=\"trash\"></a>";
					}
					else if (type === 'display') {
						return "<a href=\"javascript:void(0);\" onclick=\"LoadRecord(" + source["CREATE"] + ");ShowEdit();\" class=\"pencil\"></a> <a href=\"javascript:void(0);\" onclick=\"if(!confirm(\'Are you sure you want delete this item?\')) return false;Delete(" + source["CREATE"] + ");\" class=\"trash\"></a>";
					}
					else if (type === 'filter') {
						return "<a href=\"javascript:void(0);\" onclick=\"LoadRecord(" + source["CREATE"] + ");ShowEdit();\" class=\"pencil\"></a> <a href=\"javascript:void(0);\" onclick=\"if(!confirm(\'Are you sure you want delete this item?\')) return false;Delete(" + source["CREATE"] + ");\" class=\"trash\"></a>";
					}
					// 'sort', 'type' and undefined all just use the integer
						return "<a href=\"javascript:void(0);\" onclick=\"LoadRecord(" + source["CREATE"] + ");ShowEdit();\" class=\"pencil\"></a> <a href=\"javascript:void(0);\" onclick=\"if(!confirm(\'Are you sure you want delete this item?\')) return false;Delete(" + source["CREATE"] + ");\" class=\"trash\"></a>";
				},
					"sClass": "CenteredColumn" }
				]
		});
	});
	function ReloadMethods() {
		$('#edit-container').jvalidate();
		$('#txtCREATE').focus();
		$('#txtCREATE').select();
	}
	function CancelEdit() {
		ClearFields();
		HideEdit();
	}
	function HideEdit() {
		$('#edit-container').slideUp(400,function() {
			AnimateScroll('datatable');
		});
	}
	function ShowEdit() {
		ClearFields();
		$('#edit-container').slideDown();
		AnimateScroll('edit-container');
	}
	function LoadRecords() {
		$table = $('#datatable').dataTable();
		$table.fnDraw();
	}
	function LoadRecord(Id) {
		$('#modal-loader').show();
		$.post('<?php echo FULL_URL; ?>_core/handlers/JobLog.ajax.handler.php', {
			Method: 'load',
			CREATE: Id
			}, function(output) {
				$('#edit-container').html(output).show();
				$('#modal-loader').hide();
				ReloadMethods();
		});
	}
	function ClearFields() {
		$('#txtCREATE').val('');
		$('#txtJobId').val('');
		$('#txtDateRan').val('');
		$('#txtStatus').val('');
		$('#txtDbBackupId').val('');
		$('#txtDbBackupFilename').val('');
		$('#txtDbBackupFilepath').val('');
		$('#txtDbBackupFilesize').val('');
		$('#txtFileBackupId').val('');
		$('#txtFileBackupFilename').val('');
		$('#txtFileBackupFilepath').val('');
		$('#txtFileBackupFilesize').val('');
		$('#txtSuccess').val('');
	}
	function Save() {
		$('#modal-loader').show();
		$.post('<?php echo FULL_URL; ?>_core/handlers/JobLog.ajax.handler.php', {
			Method: 'save',
			CREATE: $('#txtCREATE').val(),
			JobId: $('#txtJobId').val(),
			DateRan: $('#txtDateRan').val(),
			Status: $('#txtStatus').val(),
			DbBackupId: $('#txtDbBackupId').val(),
			DbBackupFilename: $('#txtDbBackupFilename').val(),
			DbBackupFilepath: $('#txtDbBackupFilepath').val(),
			DbBackupFilesize: $('#txtDbBackupFilesize').val(),
			FileBackupId: $('#txtFileBackupId').val(),
			FileBackupFilename: $('#txtFileBackupFilename').val(),
			FileBackupFilepath: $('#txtFileBackupFilepath').val(),
			FileBackupFilesize: $('#txtFileBackupFilesize').val(),
			Success: $('#txtSuccess').val()
		}, function(output) {
			CancelEdit();
			LoadRecords();
			$('#modal-loader').hide();
		});
	}
	function Delete(Id) {
		$('#modal-loader').show();
		$.post('<?php echo FULL_URL; ?>_core/handlers/JobLog.ajax.handler.php', {
			Method: 'delete',
			CREATE: Id
		}, function(output) {
			LoadRecords();
			$('#modal-loader').hide();
		});
	}
</script>
