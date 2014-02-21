<!-- Grid Begin -->
<div class="container well well-sm">
	<h1>Backups</h1>
</div>
<div id="edit-container"></div> <!-- /edit-container -->
<div class="container well">
	<table class="datatable" id="datatable" data-page-size="5">
		<thead>
			<tr>
				<th data-toggle="true" data-class="expand">
					Date
				</th>
				<th data-hide="phone">
					Status
				</th>
				<th data-hide="phone">
					Db Backup Filename
				</th>
				<th data-hide="phone">
					Filesize
				</th>
				<th data-hide="phone">
					File Backup Filename
				</th>
				<th data-hide="phone">
					Filesize
				</th>
				<th data-hide="phone" data-sort-ignore="true" style="width:120px;" class="nosort">
					
				</th>
			</tr>
		</thead>
		<tbody></tbody>
		<tfoot class="hide-if-no-paging">
			<tr>
				<td colspan="6">
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
			"aaSorting": [[ 0, "desc" ]],
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
				{ "iDataSort": 2, "mData": function ( source, type, val ) { //DateRan
					if (type === 'set') {
						// Split timestamp into [ Y, M, D, h, m, s ]
						var t = source["DateRan"].split(/[- :]/);
						// Apply each element to the Date function
						var d = new Date(t[0], t[1] - 1, t[2], t[3], t[4], t[5]);
						//source["DateRan"] = d.toUTCString();
						source["DateRan"] = (d.getMonth() + 1) + '/' + d.getDate() + '/' + d.getFullYear() + " " + return12Hour(d.getHours()) + ":" + addZeros(d.getMinutes()) + ":" + addZeros(d.getSeconds()) + " " + returnAmPm(d.getHours());
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
				{ "iDataSort": 12, "mData": function ( source, type, val ) { //Success
					if (type === 'set') {
						if (source["Success"] == 1) {
							source["Success"] = "Succeeded";
						}
						else {
							source["Success"] = "Failed";
						}
						return;
					}
					else if (type === 'display') {
						return source["Success"];
					}
					else if (type === 'filter') {
						return source["Success"];
					}
					// 'sort', 'type' and undefined all just use the integer
					return source["Success"];
				}},
				{ "iDataSort": 5, "mData": "DbBackupFilename", sDefaultContent: "" }, //DbBackupFilename
				{ "iDataSort": 7, "mData": function ( source, type, val ) { //DbBackupFilesize
					if (type === 'set') {
						//convert to readable value
						if (source["DbBackupFilesize"] > 0) {
							source["DbBackupFilesize"] = smartFilesizeDisplay(source["DbBackupFilesize"]);
						}
						else {
							source["DbBackupFilesize"] = "";
						}
						return;
					}
					else if (type === 'display') {
						return source["DbBackupFilesize"];
					}
					else if (type === 'filter') {
						return source["DbBackupFilesize"];
					}
					// 'sort', 'type' and undefined all just use the integer
					return source["DbBackupFilesize"];
				}},
				{ "iDataSort": 9, "mData": "FileBackupFilename", sDefaultContent: "" }, //FileBackupFilename
				{ "iDataSort": 11, "mData": function ( source, type, val ) { //FileBackupFilesize
					if (type === 'set') {
						//convert to readable value
						source["FileBackupFilesize"] = smartFilesizeDisplay(source["FileBackupFilesize"]);
						return;
					}
					else if (type === 'display') {
						return source["FileBackupFilesize"];
					}
					else if (type === 'filter') {
						return source["FileBackupFilesize"];
					}
					// 'sort', 'type' and undefined all just use the integer
					return source["FileBackupFilesize"];
				}},
				{ "mData": function ( source, type, val ) {
					if (type === 'set') {
						return "<a href=\"javascript:void(0);\" onclick=\"LoadRecord(" + source["JobLogId"] + ");ShowEdit();\" class=\"view\"></a>";
					}
					else if (type === 'display') {
						return "<a href=\"javascript:void(0);\" onclick=\"LoadRecord(" + source["JobLogId"] + ");ShowEdit();\" class=\"view\"></a>";
					}
					else if (type === 'filter') {
						return "<a href=\"javascript:void(0);\" onclick=\"LoadRecord(" + source["JobLogId"] + ");ShowEdit();\" class=\"view\"></a>";
					}
					// 'sort', 'type' and undefined all just use the integer
						return "<a href=\"javascript:void(0);\" onclick=\"LoadRecord(" + source["JobLogId"] + ");ShowEdit();\" class=\"view\"></a>";
				},
					"sClass": "CenteredColumn" }
				]
		});
	});
	function ReloadMethods() {
		$('#edit-container').jvalidate();
		$('#txtJobLogId').focus();
		$('#txtJobLogId').select();
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
			Method: 'view',
			JobLogId: Id
			}, function(output) {
				$('#edit-container').html(output).show();
				$('#modal-loader').hide();
				ReloadMethods();
		});
	}
	function ClearFields() {
		$('#txtJobLogId').val('');
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
			JobLogId: $('#txtJobLogId').val(),
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
			JobLogId: Id
		}, function(output) {
			LoadRecords();
			$('#modal-loader').hide();
		});
	}
</script>
