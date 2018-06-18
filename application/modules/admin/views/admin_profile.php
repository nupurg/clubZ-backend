<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Update Profile</h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo site_url('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Admin profile</li>
    </ol>
  </section>
    <!-- Main content -->
  <section class="content">
      <div class="row">
        <div class="col-md-3">
          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <img class="profile-user-img img-responsive img-circle" src="<?php if(!empty($userData->image)){ if(filter_var($userData->image, FILTER_VALIDATE_URL)){ echo $userData->image; } else { echo base_url().USER_AVATAR_PATH.$userData->image; } }else{echo base_url().USER_DEFAULT_AVATAR;}?>?>" alt="User profile picture">
              <h3 class="profile-username text-center"><?php echo $userData->fullName;?></h3>
              <p class="text-muted text-center"><?php echo $userData->email;?></p>
            </div>
            <!-- /.box-body -->
          </div>
          <div class="box box-primary">  
          </div>
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#profile" data-toggle="tab">Profile<div class="ripple-container"></div></a></li>
              <li><a href="#Password2" data-toggle="tab">Change Password</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="profile">
                <form class="form-horizontal" method="POST" id="editProfile" action="<?php echo base_url('admin/admin_update') ?>">
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                      <input type="hidden" name="admin_id" value="<?php echo $userData->id;?>">
                      <input type="text" class="form-control" name="fullName" id="fullName" placeholder="Name" value="<?php echo $userData->fullName;?>">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                      <input type="email" class="form-control" name="email"  placeholder="Email" value="<?php echo $userData->email;?>" readonly="">
                    </div>
                  </div>
                  <div class="form-group is-empty is-fileinput">
                    <label for="inputEmail" class="col-sm-2 control-label">Image</label>
                    <div class="col-sm-10">
                      <input class="form-control" type="text" placeholder="Browse..." readonly="">
                      <input type="file" name="image">
                    </div>
                    <input type="hidden" name="exit_image" value="<?php echo $userData->image;?>">
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-primary update_admin_profile pull-right">Update</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="Password2">
                <form class="form-horizontal" method="POST" id="editPassword" action="<?php echo base_url('admin/changePassword') ?>">
                  <div class="form-group is-empty">
                    <label for="inputName" class="col-sm-4 control-label" >Current Password</label>
                    <div class="col-sm-8">
                      <input type="password" class="form-control" name="password" id="inputName">
                    </div>
                  </div>
                  <div class="form-group is-empty">
                    <label for="inputEmail" class="col-sm-4 control-label">New Password</label>
                    <div class="col-sm-8">
                      <input type="password" class="form-control" name="npassword" id="inputEmail1">
                    </div>
                  </div>
                  <div class="form-group is-empty">
                    <label for="inputEmail" class="col-sm-4 control-label">Retype New Password</label>
                    <div class="col-sm-8">
                      <input type="password" class="form-control" name="rnpassword" id="inputEmail">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-primary change_password pull-right">Update</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
  </section>
    <!-- /.content -->
</div>
  <!-- /.content-wrapper -->
  

