<div class="navbar navbar-fixed-top navbar-inverse" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo FULL_URL; ?>secure/"><?php echo SYSTEM_NAME; ?></a>
		</div>
		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<?php 
				if (isset($_SESSION['LoggedUserId']))
				{
					?>
					<li><a href="<?php echo FULL_URL; ?>secure/">Dashboard</a></li>
					<li><a href="<?php echo FULL_URL; ?>secure/jobs/">Jobs</a></li>
			        <li><a href="<?php echo FULL_URL; ?>secure/backups/">Backups</a></li>
			        <li><a href="<?php echo FULL_URL; ?>secure/settings/">Settings</a></li>
			        <li><a href="<?php echo FULL_URL; ?>logout.php">Logout</a></li>
			        <?php
		        }
		        ?>
			</ul>
		</div><!-- /.nav-collapse -->
	</div><!-- /.container -->
</div><!-- /.navbar -->
<script type="text/javascript">
$(document).ready(function() {
	MarkActiveNav();
});
function MarkActiveNav() {
	$('.nav').find('li').each(function() {
		if ($(this).find('a').attr('href') == window.location.href.split('?')[0]) {
			$(this).addClass('active');
			$(this).parent().parent().addClass('active');
		}
		else {
			var otherLinks = $(this).find('a').attr('otherlinks');
			if (otherLinks != null && otherLinks.indexOf(window.location.href.split('?')[0]) > -1) {
				$(this).addClass('active');
				$(this).parent().parent().addClass('active');
			}
			else {
				$(this).removeClass('active');
				$(this).parent().parent().removeClass('active');
			}
		}
	});
}
</script>