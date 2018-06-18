<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Users
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Users</li>
      </ol>
       <?php if($this->session->flashdata('success') != null) : ?>  <!-- for Delete -->
               
                <div class="">
                    <div class="alert alert-success" id="success-alert">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h4>  <i class="icon fa fa-check"></i> Success!</h4>
                        <?php  echo $this->session->flashdata('success');?>
                    </div>
                </div><!-- /.box-body -->
                 <script>
              setTimeout(function() {
              $('.alert-success').fadeOut('fast');
              }, 1000);
              </script>
      <?php endif; ?>
    </section>
     

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          
          <!-- /.box -->

          <div class="box">
            <div class="pull-right col-md-3 noMargin">               
            </div>
            <div class="pull-right div-select col-md-3 noMargin"> 
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="userList" class="table table-bordered table-striped">
                <thead>
                <th>S.No.</th>
                <th>Profile Image</th>
                <th>Name</th>
                <th>Email</th>
                <th>Login Type</th>
                <th>status</th>
                <th style="width: 12%">Action</th>
                </thead>
                <tbody>
                    
                </tbody>
                <tfoot>
                
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
   <div id="form-modal-box"></div>
 