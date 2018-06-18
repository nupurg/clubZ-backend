<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Welcome Admin
        
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
     
      <div class="row">
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <a href="<?php echo base_url(); ?>admin/users/allUsers">
              <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Users</span>
                <span class="info-box-number"><?php echo $this->common_model->get_total_count(USERS); ?></span>
              </div>
             </a>
          </div>
        </div>


        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
          <a href="<?php echo base_url(); ?>admin/club/allClub">
            <span class="info-box-icon bg-red"><i class="fa fa-cc-diners-club"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Clubs</span>
              <span class="info-box-number"><?php echo $this->common_model->get_total_count(CLUBS,array()); ?></span>
            </div>
          </a>
          </div>
        </div>
        
      </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->