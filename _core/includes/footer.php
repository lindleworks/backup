<div id="footer">
	<div class="container">
		<div class="center" style="padding-top:10px;padding-bottom:20px;">
		&copy; <a href="http://www.lindleworks.com" target="_blank">Lindleworks, LLC</a> - v <?php echo VERSION; ?>
		</div>
	</div>
</div>
<div id="modal-loader"></div>
<!-- scripts -->
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="<?php echo FULL_URL; ?>_core/js/plugins/lodash/lodash.min.js"></script>
<script src="<?php echo FULL_URL; ?>_core/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo FULL_URL; ?>_core/js/plugins/jvalidate/jquery.jvalidate.js"></script>
<script src="<?php echo FULL_URL; ?>_core/js/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo FULL_URL; ?>_core/js/plugins/datatables/DT_bootstrap.js"></script>
<script src="<?php echo FULL_URL; ?>_core/js/plugins/datatables/datatables.responsive.js"></script>
<script src="<?php echo FULL_URL; ?>_core/js/plugins/numeric/jquery.numeric.js"></script>
<script src="<?php echo FULL_URL; ?>_core/js/plugins/fineuploader/jquery.fineuploader-3.1.1.min.js"></script>
<script src="<?php echo FULL_URL; ?>_core/js/site.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$('.dataTables_filter').find('input').each(function(){
			$(this).addClass('form-control');
			$(this).attr('placeholder','Search');
			$(this).attr('style','margin-bottom:2px;');
	    });
	    $(".decimal").numeric();
	    $(".numeric").numeric();
		$(".integer").numeric(false, function() { alert("Integers only"); this.value = ""; this.focus(); });
		$(".positive").numeric({ negative: false }, function() { alert("No negative values"); this.value = ""; this.focus(); });
		$(".positive-integer").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });
	});
</script>
<!-- end scripts -->