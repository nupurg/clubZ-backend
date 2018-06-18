
<footer class="main-footer">
    <div class="pull-right hidden-xs">
      
    </div>
    <strong><?php echo COPYRIGHT; ?> All rights reserved.
  </footer>
  </div> <!-- ./wrapper -->
<?php
    $backend_assets =  base_url().'backend_asset/';
?>
  <div id="tl_admin_loader" class="tl_loader" style="display: none;"></div> <!-- Loader -->
  <script>
      var base_url = '<?php echo base_url();?>';
  </script>
  
  <!-- jQuery 3.2.1 -->
<script src="<?php echo $backend_assets ?>plugins/jQuery/jquery-3.2.1.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="<?php echo $backend_assets ?>bootstrap/js/bootstrap.min.js"></script>
<!-- Material Design -->
<script src="<?php echo $backend_assets ?>dist/js/material.min.js"></script>
<script src="<?php echo $backend_assets ?>dist/js/ripples.min.js"></script>
<script>
    $.material.init();
</script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>

<!-- Sparkline -->
<script src="<?php echo $backend_assets ?>plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="<?php echo $backend_assets ?>plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo $backend_assets ?>plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="<?php echo $backend_assets ?>plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo $backend_assets ?>plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="<?php echo $backend_assets ?>plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo $backend_assets ?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="<?php echo $backend_assets ?>plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo $backend_assets ?>plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo $backend_assets ?>dist/js/app.min.js"></script>

<!-- AdminLTE for demo purposes -->
<script src="<?php echo $backend_assets ?>dist/js/demo.js"></script>

<!-- DataTables -->
<script src="<?php echo $backend_assets ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo $backend_assets ?>plugins/datatables/dataTables.bootstrap.min.js"></script>

<script src="<?php echo $backend_assets ?>plugins/bootbox/bootbox.min.js"></script>
<script src="<?php echo $backend_assets ?>plugins/validation/jquery.validate.js"></script>
<script src="<?php echo $backend_assets ?>plugins/toastr/toastr.min.js"></script>
<script src="<?php echo $backend_assets ?>custom/js/admin_common.js"></script>
<?php  if(!empty($admin_scripts)) load_admin_js($admin_scripts);  //load required admin page scripts  ?>
</body>
</html>
