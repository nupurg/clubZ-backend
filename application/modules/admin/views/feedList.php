 <?php if(!empty($feeds)){ 
  foreach ($feeds as $detail) { ?>
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
<?php } } ?>