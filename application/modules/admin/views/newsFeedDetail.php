<div class="content-wrapper">
  <section class="content-header">
    <h1>
      News Feed Detail
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo site_url('admin/dashboard');?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">News Feed Detail</li>
    </ol>
    <button type="button" onclick="window.history.back();" class="btn bg-yellow btn-flat margin pull-right">Back</button>
  </section>
  <section class="content">
    <div class="row">
       <div class="col-md-2"></div>
      <div class="col-md-7">
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="user-block">
                <img class="img-circle" src="<?php echo $detail->club_icon; ?>" alt="">
                <span class="username">
                  <?php echo display_placeholder_text($detail->news_feed_title); ?>
                 
                </span>
                <span class="description"><?php echo $detail->club_name; ?></span>
              </div>
            </div>
            
            <div class="box-body">
              <?php if(!empty($detail->news_feed_attachment)){ ?> 
              <img id="feed-img" class="img-responsive pad" src="<?php echo $detail->news_feed_attachment; ?>" alt="Photo">  
              <?php } ?> 
              <p class="para-descripion"><?php echo $detail->news_feed_description; ?></p>
              <hr class="line">
              <div class = "row">
              <div class="col-md-7">
              <p class="para-like"><?php echo $detail->crd; ?></p>
              </div>
              <div class="col-md-5">
              <p class="para-like">
                <span>
                <i class = "ion ion-android-favorite icon-red-style"></i>
                <?php echo ' '.$detail->likes.' '.'Likes'; ?>
                </span>&nbsp;&nbsp;&nbsp;
                <span>
                <i class = "ion ion-android-chat icon-green-style" ></i><?php echo ' '.$detail->comments.' '.'Comments'; ?>
                </span>
              </p>
              </div>
              </div>
            
            </div>
        
            </div> 
          </div>
     
      </div>
      
      <div class="col-md-3"></div>
   
    </div>
  </section>
</div>