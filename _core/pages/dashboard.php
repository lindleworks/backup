<div class="container">
	<div class="row">
		<div class="col-md-6">
			<div class="well dashboard-widget">
				<div class="row">
					<div class="col-md-12">
						<div class="callout">
							<h2 style="margin-top:0;margin-bottom:0;">Upcoming Scheduled Backups</h2>
						</div>
					</div>
				</div>
				<?php
					$JobFactory = new JobFactory();
					$JobArray = $JobFactory->GetAll(" where ScheduleInterval != 'Manual' and NextRunDate >= datetime('now') order by NextRunDate limit 0, 10 ");
					if (count($JobArray) > 0)
					{
				?>
				<div class="row">
					<div class="col-md-6">
						<strong>Job</strong>
					</div>
					<div class="col-md-6">
						<strong>Next Run Date</strong>
					</div>
				</div>
				<?php
						foreach ($JobArray as &$Job)
						{
							?>
							<div class="row">
								<div class="col-md-6">
									<?php
										echo $Job->Name;
									?>
								</div>
								<div class="col-md-6">
									<?php $date = new DateTime($Job->NextRunDate); echo $date->format("F j, Y, g:i a"); ?>
								</div>
							</div>
							<?php
						}
						?>
						<div class="row">
							<div class="col-md-12" style="text-align:center;">
								<br />
								<i><strong>Current server date: </strong> <?php echo gmdate("F j, Y, g:i a", time()); ?></i>
							</div>
						</div>
						<?php
					}
					else 
					{
				?>
				<div class="row">
					<div class="col-md-12" style="text-align:center;">
						<strong>No upcoming jobs exist!</strong>
					</div>
				</div>
				<?php	
					}
				?>
			</div>
		</div>
		<div class="col-md-6">
			<div class="well dashboard-widget">
				<div class="row">
					<div class="col-md-12">
						<div class="callout">
							<h2 style="margin-top:0;margin-bottom:0;">Latest Backups</h2>
						</div>
					</div>
				</div>
				<?php
					$JobLogFactory = new JobLogFactory();
					$JobLogArray = $JobLogFactory->GetAll(" order by DateRan desc limit 0, 10 ");
					if (count($JobLogArray) > 0)
					{
				?>
				<div class="row">
					<div class="col-md-5">
						<strong>Date</strong>
					</div>
					<div class="col-md-4">
						<strong>Job</strong>
					</div>
					<div class="col-md-3">
						<strong>Status</strong>
					</div>
				</div>
				<?php
						foreach ($JobLogArray as &$JobLog)
						{
							?>
							<div class="row">
							<div class="col-md-5">
								<?php $date = new DateTime($JobLog->DateRan); echo $date->format("F j, Y, g:i a"); ?>
							</div>
							<div class="col-md-4">
								<?php
									$JobFactory = new JobFactory();
									$Job = $JobFactory->GetOne($JobLog->JobId);
									echo $Job->Name;
								?>
							</div>
							<div class="col-md-3">
								<?php echo ($JobLog->Success == 1) ? "<span style=\"color:#008000;\">OK</span>" : "<span style=\"color:#DD0000;\">FAILED</span>"; ?>
							</div>
							</div>
							<?php
						}
					}
					else 
					{
				?>
				<div class="row">
					<div class="col-md-12" style="text-align:center;">
						<strong>No jobs exist!</strong>
					</div>
				</div>
				<?php	
					}
				?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		
	});
</script>