  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Club Detail
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo site_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?php echo site_url('club/allClub');?>">Clubs</a></li>
        <li class="active">Club Detail</li>
      </ol>
      <button type="button" onclick="window.history.back();" class="btn bg-olive btn-flat margin pull-right">Back</button>
    </section>
    <!-- Main content -->
    <section class="content">

      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile m-t-40">
              <img class="profile-user-img img-responsive img-circle" src="<?php echo $club->club_image; ?>" alt="User profile picture" style="height:100px;width:100px;">

              <h3 class="profile-username text-center"><?php echo display_placeholder_text(ucwords($club->club_name)); ?></h3>

              <p class="text-muted text-center"><?php
                  $club_type = '';
                  if($club->club_type == '1'){
                    $club_type = 'Public Club';
                  }elseif($club->club_type == '2'){
                    $club_type = 'Private Club';
                  }else if($club->club_type == '3'){
                    $club_type = 'Default Club';
                  }
               ?>
               <?php echo $club_type; ?>
              </p>

              <ul class="list-group list-group-unbordered">
                <li class="list-group-item">
                  <b>Members Joined</b> <a class="pull-right"><?php echo $club->joined_users; ?></a>
                </li>
                  <li class="list-group-item">
                  <b>Members pending</b> <a class="pull-right"><?php echo $club->pending_users; ?></a>
                </li>
               
              </ul>

              <!-- <a href="#" class="btn btn-primary btn-raised btn-block"><b>Follow</b></a> -->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">About Club</h3>
            </div>
            <!-- /.box-header -->
             <div class="box-body">
              <strong><i class="fa fa-tags margin-r-5"></i>Club Category</strong>

              <p class="text-muted">
               <?php echo display_placeholder_text($club->club_category_name); ?>
              </p>

              <hr>

               <strong><i class="fa fa-user margin-r-5"></i>Club Owner</strong>

              <p class="text-muted">
               <a href="<?php echo base_url().'admin/users/userDetail/'.encoding($club->ownerId); ?>"><?php echo display_placeholder_text($club->ownerName); ?></a>
              </p>

              <hr>

              <strong><i class="fa fa-envelope margin-r-5"></i>Email</strong>

              <p class="text-muted">
               <?php echo display_placeholder_text($club->club_email); ?>
              </p>

              <hr>

              <strong><i class="fa fa-globe margin-r-5"></i>Website</strong>

              <p class="text-muted">
               <?php echo display_placeholder_text($club->club_website); ?>
              </p>

              <hr>


              <strong><i class="fa fa-map-marker margin-r-5"></i> Address</strong>

              <p class="text-muted">
                <?php echo display_placeholder_text($club->club_address); ?>
              </p>

              <hr>

              <strong><i class="fa fa-phone margin-r-5"></i>Phone No.</strong>

              <p class="text-muted">
               <?php echo $club->club_country_code.'-'.$club->club_contact_no; ?>
              </p>

              <hr>


              <strong><i class="fa fa-file-text-o margin-r-5"></i> Description</strong>

              <p><?php echo  display_placeholder_text($club->club_description); ?></p>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom" id="clubIdDiv" data-clubid="<?php echo $club->clubId; ?>">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#clubMembers" data-toggle="tab">Club Members</a></li>
              <li><a href="#newsFeeds" data-toggle="tab">News Feeds</a></li>
            </ul>
            <div class="tab-content">

              <div class="active tab-pane" id="clubMembers">
                  <div class="box-left">
                    <div class="tab-content ">
                      <div class="">
                        <table class="table table-striped" id="clubMembersList" style="margin-left:5px;margin-right: 5px;width:100%;">
                          <thead>
                            <th>S.no</th>
                            <th>Profile Image</th>
                            <th>Full Name</th>
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


              <div class="tab-pane" id="newsFeeds">
              
                  <div class="box-left">
                    <div class="tab-content">
                     <!-- start feed list -->
                        <div id="mainData">
                        <?php if(!empty($club->feeds)){ 
                        foreach ($club->feeds as $detail) { ?>

                        <div class="row">
                          
                            <div class="col-md-10 col-md-offset-1">
                              <div class="box box-widget">
                                <a href="<?php echo base_url().'admin/club/newsFeedDetail/'.encoding($detail->newsFeedId); ?>">
                                <div class="box-footer box-comments">
                                  <div class="box-comment">
                                    <img class="img-circle img-sm" src="<?php echo $detail->club_icon; ?>" alt="club icon">

                                    <div class="comment-text">
                                      <span class="username">
                                        <?php echo $detail->news_feed_title; ?>
                                        <span class="text-muted pull-right">   
                                       <?php echo time_elapsed_string($detail->crd); ?>
                                      </span>
                                      </span>
                                      
                                      <span class="cmtText">
                                      <?php
                                        $description = $detail->news_feed_description;
                                        echo strlen($description) > 90 ? wordwrap(substr($description,0,50), 50, "<br />\n").'.....' : $description;
                                       ?>
                                         
                                       </span>
                                    </div>
                                  </div>
                                </div>
                                </a>
                              </div>
                            </div>
                         
                        </div>
                        <?php  } ?> </div><?php  }else{ ?>

                        <div class="noRev">
                        <h3>No News Feeds available !</h3>
                        </div>
                        <?php } ?>
                        
                        <div id="loadMoreDiv" class="text-center" >
                         <?php if($club->isNext == '1'){ ?> 
                          <button class="btn themeBtn load loadMore" data-offset = "<?php echo $club->nextOffset; ?>" data-clubid = "<?php echo $club->clubId; ?>">Load More</button>
                          <div class="loaderUrl"></div>
                        <?php } ?>
                        </div>
                    </div>
                 
                  </div>
              </div>
            </div>
          </div>
          </div>
    
      </div>
   
    </section>
    <!-- /.content -->
  </div>