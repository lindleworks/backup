<!-- Grid Begin -->
<div class="container well well-sm">
	<h1>Jobs</h1>
</div>
<div id="edit-container"></div> <!-- /edit-container -->
<div id="run-container"></div> <!-- /run-container -->
<div class="container well">
	<table class="datatable" id="datatable" data-page-size="5">
		<thead>
			<tr>
				<th data-toggle="true" data-class="expand">
					Name
				</th>
				<th data-hide="phone">
					Db Backup
				</th>
				<th data-hide="phone">
					File Backup
				</th>
				<th data-hide="phone">
					Backup Destination Type
				</th>
				<th data-hide="phone" data-sort-ignore="true" style="width:150px;" class="nosort">
					<a class="btn btn-default btn-primary pull-center" href="javascript:void(0);" onclick="LoadRecord(0);ShowEdit();">Add</a>
				</th>
			</tr>
		</thead>
		<tbody></tbody>
		<tfoot class="hide-if-no-paging">
			<tr>
				<td colspan="4">
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
			"sAjaxSource": "<?php echo FULL_URL; ?>_core/handlers/Job.DataTables.Ajax.gen.php",
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
				{ "iDataSort": 1, "mData": "Name", sDefaultContent: "" }, //Name
				{ "iDataSort": 2, "mData": function ( source, type, val ) { //DbBackup
					if (type === 'set') {
						//convert to readable value
						if (source["DbBackup"] == 0) {
							source["DbBackup"] = "NO";
						}
						else {
							source["DbBackup"] = "YES";
						}
						return;
					}
					else if (type === 'display') {
						return source["DbBackup"];
					}
					else if (type === 'filter') {
						return source["DbBackup"];
					}
					// 'sort', 'type' and undefined all just use the integer
					return source["DbBackup"];
				}},
				{ "iDataSort": 3, "mData": function ( source, type, val ) { //FileBackup
					if (type === 'set') {
						//convert to readable value
						if (source["FileBackup"] == 0) {
							source["FileBackup"] = "NO";
						}
						else {
							source["FileBackup"] = "YES";
						}
						return;
					}
					else if (type === 'display') {
						return source["FileBackup"];
					}
					else if (type === 'filter') {
						return source["FileBackup"];
					}
					// 'sort', 'type' and undefined all just use the integer
					return source["FileBackup"];
				}},
				{ "iDataSort": 4, "mData": "BackupDestinationType", sDefaultContent: "" }, //BackupDestinationType
				{ "mData": function ( source, type, val ) {
					if (type === 'set') {
						return "<a href=\"javascript:void(0);\" onclick=\"LoadRunJob(" + source["JobId"] + ");ShowRun();\" class=\"play\"></a> <a href=\"javascript:void(0);\" onclick=\"LoadRecord(" + source["JobId"] + ");ShowEdit();\" class=\"pencil\"></a> <a href=\"javascript:void(0);\" onclick=\"if(!confirm(\'Are you sure you want delete this item?\')) return false;Delete(" + source["JobId"] + ");\" class=\"trash\"></a>";
					}
					else if (type === 'display') {
						return "<a href=\"javascript:void(0);\" onclick=\"LoadRunJob(" + source["JobId"] + ");ShowRun();\" class=\"play\"></a> <a href=\"javascript:void(0);\" onclick=\"LoadRecord(" + source["JobId"] + ");ShowEdit();\" class=\"pencil\"></a> <a href=\"javascript:void(0);\" onclick=\"if(!confirm(\'Are you sure you want delete this item?\')) return false;Delete(" + source["JobId"] + ");\" class=\"trash\"></a>";
					}
					else if (type === 'filter') {
						return "<a href=\"javascript:void(0);\" onclick=\"LoadRunJob(" + source["JobId"] + ");ShowRun();\" class=\"play\"></a> <a href=\"javascript:void(0);\" onclick=\"LoadRecord(" + source["JobId"] + ");ShowEdit();\" class=\"pencil\"></a> <a href=\"javascript:void(0);\" onclick=\"if(!confirm(\'Are you sure you want delete this item?\')) return false;Delete(" + source["JobId"] + ");\" class=\"trash\"></a>";
					}
					// 'sort', 'type' and undefined all just use the integer
						return "<a href=\"javascript:void(0);\" onclick=\"LoadRunJob(" + source["JobId"] + ");ShowRun();\" class=\"play\"></a> <a href=\"javascript:void(0);\" onclick=\"LoadRecord(" + source["JobId"] + ");ShowEdit();\" class=\"pencil\"></a> <a href=\"javascript:void(0);\" onclick=\"if(!confirm(\'Are you sure you want delete this item?\')) return false;Delete(" + source["JobId"] + ");\" class=\"trash\"></a>";
				},
					"sClass": "CenteredColumn" }
				]
		});
	});
	function ReloadMethods() {
		$('#edit-container').jvalidate();
		$('#txtJobId').focus();
		$('#txtJobId').select();
		$(".decimal").numeric();
	    $(".numeric").numeric();
		$(".integer").numeric(false, function() { alert("Integers only"); this.value = ""; this.focus(); });
		$(".positive").numeric({ negative: false }, function() { alert("No negative values"); this.value = ""; this.focus(); });
		$(".positive-integer").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });
		//hide/show methods
		HideShowFTP();
		HideShowInterval();
		HideShowDb();
		HideShowFile();
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
		HideRun();
		ClearFields();
		$('#edit-container').slideDown();
		AnimateScroll('edit-container');
	}
	function ShowRun() {
		$('#run-container').slideDown();
		AnimateScroll('run-container');
	}
	function HideRun() {
		$('#run-container').slideUp(400,function() {
			AnimateScroll('datatable');
		});
	}
	function LoadRunJob(Id) {
		HideEdit();
		$('#modal-loader').show();
		$.post('<?php echo FULL_URL; ?>_core/handlers/Job.ajax.handler.php', {
			Method: 'load-run',
			JobId: Id
			}, function(output) {
				$('#run-container').html(output).show();
				$('#modal-loader').hide();
		});
	}
	function RunJob(Id) {
		HideEdit();
		$('#modal-loader').show();
		$.post('<?php echo FULL_URL; ?>_core/handlers/Job.ajax.handler.php', {
			Method: 'run-job',
			JobId: Id
			}, function(output) {
				$('#job-output').html(output).show();
				$('#modal-loader').hide();
		});
	}
	function LoadRecords() {
		$table = $('#datatable').dataTable();
		$table.fnDraw();
	}
	function LoadRecord(Id) {
		$('#modal-loader').show();
		$.post('<?php echo FULL_URL; ?>_core/handlers/Job.ajax.handler.php', {
			Method: 'load',
			JobId: Id
			}, function(output) {
				$('#edit-container').html(output).show();
				$('#modal-loader').hide();
				ReloadMethods();
		});
	}
	function ClearFields() {
		
	}
	function Save() {
		$('#modal-loader').show();
		var DbBackup = 0;
		if ($('#chbDbBackup').is(":checked"))
		{
			DbBackup = 1;
		}
		var FileBackup = 0;
		if ($('#chbFileBackup').is(":checked"))
		{
			FileBackup = 1;
		}
		if (ValidateSave()) {
			$.post('<?php echo FULL_URL; ?>_core/handlers/Job.ajax.handler.php', {
				Method: 'save',
				JobId: $('#hdnJobId').val(),
				Name: $('#txtName').val(),
				DbBackup: DbBackup,
				FileBackup: FileBackup,
				BackupDestinationType: $('#ddlBackupDestinationType').val(),
				Email: $('#txtEmail').val(),
				BackupFolder: $('#txtBackupFolder').val(),
				BackupStorageFolder: $('#txtBackupStorageFolder').val(),
				ScheduleInterval: $('#ddlScheduleInterval').val(),
				ScheduleDayOfMonth: $('#ddlScheduleDayOfMonth').val(),
				ScheduleDayOfWeek: $('#ddlScheduleDayOfWeek').val(),
				ScheduleHour: $('#ddlScheduleHour').val(),
				ScheduleMinute: $('#ddlScheduleMinute').val(),
				FtpServer: $('#txtFtpServer').val(),
				FtpUsername: $('#txtFtpUsername').val(),
				FtpPassword: $('#txtFtpPassword').val(),
				FtpFolder: $('#txtFtpFolder').val(),
				MaximumNumberOfBackups: $('#txtMaximumNumberOfBackups').val(),
				DbPassword: $('#txtDbPassword').val(),
				DbUsername: $('#txtDbUsername').val(),
				DbServer: $('#txtDbServer').val(),
				DbName: $('#txtDbName').val()
			}, function(output) {
				CancelEdit();
				LoadRecords();
				$('#modal-loader').hide();
			});
		}
		else {
			$('#modal-loader').hide();
		}
	}
	function ValidateSave() {
		var methodCount = 0;
		var DbBackup = 0;
		if ($('#chbDbBackup').is(":checked"))
		{
			DbBackup = 1;
			methodCount++;
		}
		var FileBackup = 0;
		if ($('#chbFileBackup').is(":checked"))
		{
			FileBackup = 1;
			methodCount++;
		}
		if (methodCount > 0) {
			return true;
		}
		else {
			alert("Select at least one thing to backup!");
			return false;
		}
	}
	function Delete(Id) {
		$('#modal-loader').show();
		CancelEdit();
		$.post('<?php echo FULL_URL; ?>_core/handlers/Job.ajax.handler.php', {
			Method: 'delete',
			JobId: Id
		}, function(output) {
			LoadRecords();
			$('#modal-loader').hide();
		});
	}
	function HideShowFTP() {
		if ($('#ddlBackupDestinationType').val() == "FTP") {
			$('#txtBackupStorageFolder').removeClass('required');
			$('.local-backup').hide();
			$('#txtFtpServer').addClass('required');
			$('#txtFtpUsername').addClass('required');
			$('#txtFtpPassword').removeClass('required');
			$('#txtFtpFolder').addClass('required');
			$('#edit-container').jvalidate();
			$('.ftp-backup').show();
		}
		else {
			$('#txtBackupStorageFolder').addClass('required');
			$('.local-backup').show();
			$('#txtFtpServer').removeClass('required');
			$('#txtFtpUsername').removeClass('required');
			$('#txtFtpPassword').removeClass('required');
			$('#txtFtpFolder').removeClass('required');
			$('#edit-container').jvalidate();
			$('.ftp-backup').hide();
		}
	}
	function HideShowInterval() {
		var intervalType = $('#ddlScheduleInterval').val();
		switch (intervalType) {
			case "Hourly":
				$('.monthly-interval').hide();
				$('.daily-interval').hide();
				$('.hourly-interval').show();
				$('.weekly-interval').hide();
				$('#txtScheduleDayOfMonth').removeClass('required');
				$('#txtScheduleDayOfWeek').removeClass('required');
				$('#txtScheduleHour').removeClass('required');
				$('#txtScheduleMinute').addClass('required');
				break;
			case "Daily":
				$('.monthly-interval').hide();
				$('.daily-interval').show();
				$('.hourly-interval').show();
				$('.weekly-interval').hide();
				$('#txtScheduleDayOfMonth').removeClass('required');
				$('#txtScheduleDayOfWeek').removeClass('required');
				$('#txtScheduleHour').addClass('required');
				$('#txtScheduleMinute').addClass('required');
				break;
			case "Weekly":
				$('.monthly-interval').hide();
				$('.daily-interval').show();
				$('.hourly-interval').show();
				$('.weekly-interval').show();
				$('#txtScheduleDayOfMonth').removeClass('required');
				$('#txtScheduleDayOfWeek').addClass('required');
				$('#txtScheduleHour').addClass('required');
				$('#txtScheduleMinute').addClass('required');
				break;
			case "Monthly":
				$('.monthly-interval').show();
				$('.daily-interval').show();
				$('.hourly-interval').show();
				$('.weekly-interval').hide();
				$('#txtScheduleDayOfMonth').addClass('required');
				$('#txtScheduleDayOfWeek').removeClass('required');
				$('#txtScheduleHour').addClass('required');
				$('#txtScheduleMinute').addClass('required');
				break;
			default:
				$('.monthly-interval').hide();
				$('.daily-interval').hide();
				$('.hourly-interval').hide();
				$('.weekly-interval').hide();
				$('#txtScheduleDayOfMonth').removeClass('required');
				$('#txtScheduleDayOfWeek').removeClass('required');
				$('#txtScheduleHour').removeClass('required');
				$('#txtScheduleMinute').removeClass('required');
				break;
		}
		$('#edit-container').jvalidate();
	}
	function HideShowDb() {
		var DbBackup = 0;
		if ($('#chbDbBackup').is(":checked"))
		{
			DbBackup = 1;
		}
		if (DbBackup == 1) {
			$('.database-backup').show();
			$('#txtDbPassword').removeClass('required');
			$('#txtDbName').addClass('required');
			$('#txtDbServer').addClass('required');
			$('#txtDbUsername').addClass('required');
		}
		else {
			$('.database-backup').hide();
			$('#txtDbPassword').removeClass('required');
			$('#txtDbName').removeClass('required');
			$('#txtDbServer').removeClass('required');
			$('#txtDbUsername').removeClass('required');
		}
		$('#edit-container').jvalidate();
	}
	function HideShowFile() {
		var FileBackup = 0;
		if ($('#chbFileBackup').is(":checked"))
		{
			FileBackup = 1;
		}
		if (FileBackup == 1) {
			$('.files-backup').show();
			$('#txtBackupFolder').addClass('required');
		}
		else {
			$('.files-backup').hide();
			$('#txtBackupFolder').removeClass('required');
		}
		$('#edit-container').jvalidate();
	}
</script>
