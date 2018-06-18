  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User Detail
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo site_url('Users/allUsers');?>"></a></li>
        <li class="active">Users</li>
      </ol>
      <button type="button" onclick="window.history.back();" class="btn bg-yellow btn-flat margin pull-right">Back</button>
    </section>
    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">
          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile m-t-40">
              <img class="profile-user-img img-responsive img-circle" src="<?php echo $detail->profile_image; ?>" alt="User profile picture">

              <h3 class="profile-username text-center"><?php echo display_placeholder_text(ucwords($detail->full_name)); ?></h3>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">About User</h3>
            </div>
            <!-- /.box-header -->
             <div class="box-body">
             
            <div class="box-body">

              <strong><i class="fa fa-users margin-r-5"></i>Affiliates</strong>
              <?php $color = ['danger','success','info','warning','primary','success','info','danger','primary','warning','danger','success','info','primary','warning']; ?>
              <p class="m-t-8">
                <?php if(!empty($detail->affiliates)) {
                  foreach ($detail->affiliates as $key => $value) { ?>
                    <span class="label label-<?php echo $color[$key]; ?>"><?php echo $value; ?></span>
                      <?php }
                  }else{
                    echo 'NA';
                  }  ?>
              </p>
              <hr>

               <strong><i class="fa fa-thumbs-up margin-r-5"></i>Interests</strong>
              <?php $colors = ['info','warning','primary','success','info','danger','primary','warning','danger','success','info','primary','warning','success','info']; ?>
              <p>
              <?php if(!empty($detail->interests)) {
                      foreach ($detail->interests as $k => $v) { ?>
                        <span class="label label-<?php echo $colors[$k]; ?>"><?php echo $v; ?></span>
                      <?php } 
                    }else{
                      echo 'NA';
                    }  ?>
              </p>
              <hr>

               <strong><i class="fa fa-asterisk margin-r-5"></i>Skills</strong>
              <?php $colors = ['info','warning','primary','success','info','danger','primary','warning','danger','success','info','primary','warning','success','info']; ?>
              <p>
              <?php if(!empty($detail->skills)) {
                      foreach ($detail->skills as $k1 => $v1) { ?>
                        <span class="label label-<?php echo $colors[$k1]; ?>"><?php echo $v1; ?></span>
                      <?php } 
                    }else{
                      echo 'NA';
                    }  ?>
              </p>
              <hr>

              <strong><i class="fa fa-envelope margin-r-5"></i>Email</strong>
              <p class="text-muted">
               <?php echo display_placeholder_text($detail->email); ?>
              </p>
              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i>Address</strong>
              <p><?php echo display_placeholder_text($detail->address); ?></p>
              <hr>

              <strong><i class="fa fa-phone margin-r-5"></i>Contact No.</strong>
              <p><?php echo display_placeholder_text($detail->contact_no); ?></p>
              <hr>

              <strong><i class="fa fa-user margin-r-5"></i>Status</strong>
              <p class="text-muted">
               <?php 
                 $req = status_color($detail->status); 
                 $status = $detail->status ? 'Active' : 'Inactive'; ?>
                 <span style="color:<?php echo $req; ?>"><?php echo $status; ?></span>
              </p>

            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        </div>
        <!-- /.col -->
         <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom" id="userIdDiv" data-userid="<?php echo $detail->userId; ?>">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#myClubs" data-toggle="tab">My Clubs</a></li>
              <li><a href="#joinedClubs" data-toggle="tab">Joined Clubs</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="myClubs">
              
                  <div class="box-left">
                    <div class="tab-content ">
                      <div class="">
                        <table class="table table-striped" id="myClubsList" style="margin-left:5px;margin-right: 5px;width:100%;">
                          <thead>
                            <th>S.no</th>
                            <th>Club Image</th>
                            <th>Club Name</th>
                            <th style="width: 12%">Action</th>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  <!-- /.tab-content -->
                  </div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="joinedClubs">
                    <div class="box-left">
                    <div class="tab-content ">
                      <div class="">
                    
                        <table class="table table-striped" id="joinedClubsList" style="margin-left:5px;margin-right: 5px;width:100%;">
                          <thead>
                            <th>S.no</th>
                            <th>Club Image</th>
                            <th>Club Name</th>
                            <th style="width: 12%">Action</th>
                          </thead>
                          <tbody>

                          </tbody>
                        </table>

                      </div>
                    </div>
                  <!-- /.tab-content -->
                  </div>
              </div>

            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

    </section>
    <!-- /.content -->
  </div>