<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Club extends CommonService {

    public function __construct(){

        parent::__construct();
        $this->check_lang();
        if(!$this->check_service_auth()){ //check for auth 
            $this->response($this->token_error_msg(), SERVER_ERROR); //authentication failed 
        }
    }
    

    // For getting club category list 
    public function getAllClubCategory_get(){ 

        $offset = $this->get('offset'); 
        $limit = $this->get('limit'); 
        if(!isset($offset) || empty($limit)){
            $offset = 0; $limit= 10; 
        }
        $whr = array('status'=>'1');
        $res = $this->common_model->getAllWhr(CLUB_CATEGORY,'', '', 'clubCategoryId,club_category_name',$limit,$offset,'',$whr);

        if(!empty($res) && is_array($res)){
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(302),'data'=>$res);
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(509));
        }
        $this->response($response); 
          
    }//End function


    //For adding a club
    public function addClub_post(){

        $this->form_validation->set_rules('clubName','club name','trim|required');
        $this->form_validation->set_rules('clubDescription','club description','trim|required');
        $this->form_validation->set_rules('clubFoundationDate','club foundation date','trim|required');
        $this->form_validation->set_rules('clubEmail','club email','trim|required|valid_email');
        $this->form_validation->set_rules('clubContactNo','club contact no','trim|required');
        $this->form_validation->set_rules('clubCountryCode','club country code','trim|required|numeric');
        $this->form_validation->set_rules('clubWebsite','club website','trim|required');
        $this->form_validation->set_rules('clubLocation','club location','trim|required');
        $this->form_validation->set_rules('clubAddress','club address','trim|required');
        $this->form_validation->set_rules('clubLatitude','club latitude','trim|required|numeric');
        $this->form_validation->set_rules('clubLongitude','club longitude','trim|required|numeric');
        $this->form_validation->set_rules('city','city','trim|required');
        $this->form_validation->set_rules('clubType','club type','trim|required|numeric');
        $this->form_validation->set_rules('clubCategoryId','club category','trim|required|numeric');
        $this->form_validation->set_rules('termsConditions','terms conditions','trim|required');
        $this->form_validation->set_rules('userRole','user role','trim|required');

        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $data['club_name'] = $this->post('clubName');
        $data['club_description'] = $this->post('clubDescription');
        $data['club_foundation_date'] = date("Y-m-d", strtotime($this->post('clubFoundationDate'))) ;
        $data['club_email'] = $this->post('clubEmail');
        $data['club_contact_no'] = $this->post('clubContactNo');
        $data['club_country_code'] = $this->post('clubCountryCode');
        $data['club_website'] = $this->post('clubWebsite');
        $data['club_location'] = $this->post('clubLocation');
        $data['club_address'] = $this->post('clubAddress');
        $data['club_latitude'] = $this->post('clubLatitude');
        $data['club_longitude'] = $this->post('clubLongitude');
        $data['club_city'] = $this->post('city');
        $data['club_type'] = $this->post('clubType');
        $data['club_category_id'] = $this->post('clubCategoryId');
        $data['terms_conditions'] = $this->post('termsConditions');
        $data['user_role'] = $this->post('userRole');
        $data['user_id'] = $this->authData->userId;
        $data['crd'] = $data['upd'] = date('Y-m-d H:i:s');
        $clubImage = '';
        $clubIcon = '';
    
        if(!empty($_FILES['clubImage']['name'])){
        
            $this->load->model('image_model');
            $folder     = 'club_image';
            $hieght = $width = 600;
            $clubImage = $this->image_model->updateMedia('clubImage',$folder,$hieght,$width,FALSE);
            if(is_array($clubImage) && !empty($clubImage['error'])){

                $response = array('status' => FAIL, 'message' =>strip_tags($clubImage['error']));
                $this->response($response);
            }
        }
        if(!empty($_FILES['clubIcon']['name'])){
        
            $this->load->model('image_model');
            $folder     = 'club_icon';
            $hieght = $width = 600;
            $clubIcon = $this->image_model->updateMedia('clubIcon',$folder,$hieght,$width,FALSE);
            if(is_array($clubIcon) && !empty($clubIcon['error'])){

                $response = array('status' => FAIL, 'message' =>strip_tags($clubIcon['error']));
                $this->response($response);
            }
        }
        $data['club_image'] = isset($clubImage) && is_string($clubImage) ? $clubImage : '';
        $data['club_icon'] = isset($clubIcon) && is_string($clubIcon) ? $clubIcon : '';
        $isCatExist = $this->common_model->is_data_exists(CLUB_CATEGORY,array('clubCategoryId'=>$data['club_category_id']));
        if(!$isCatExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(528));
            $this->response($response); 
        }
        $res = $this->common_model->insert_data(CLUBS,$data);
        if($res){
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(514));
        }else{
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(118));
        }
        $this->response($response); 
    }//End function


   //for searching all club including my c;lub and near by
    public function searchClub_post(){ 

        $this->form_validation->set_rules('searchText','search text','trim|required');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }

        $this->load->model('club_model');
        $data['latitude'] = $this->post('latitude');
        $data['longitude'] = $this->post('longitude');
        $data['city'] = $this->post('city');
        $data['clubType'] = $this->post('clubType');//1 public or 2 private
        $data['searchText'] = $this->post('searchText');
        $data['offset'] = $this->post('offset');
        $data['limit'] = $this->post('limit');
        $userId = $this->authData->userId;
        $where = array('userId'=>$userId);

        if(empty($data['latitude']) || empty($data['longitude'])){

            $userData = $this->common_model->getsingle(USERS,$where);
            $data['latitude'] = !empty($userData->latitude) ? $userData->latitude : '';
            $data['longitude'] = !empty($userData->longitude) ? $userData->longitude : '';
            $data['city'] = !empty($userData->city) ? $userData->city : '';
        }
        $res = $this->club_model->searchClub($data,$userId);
        if(!empty($res)){
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(302),'data'=>$res);
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(509));
        }
        $this->response($response); 
          
    }//End function


    //For Near By Clubs list with pagination
    public function nearByClubsList_post(){

        $this->load->model('club_model');
        $data['latitude'] = !empty($this->post('latitude')) ? $this->post('latitude') : '';
        $data['longitude'] = !empty($this->post('longitude')) ? $this->post('longitude') : '';
        $data['city'] = $this->post('city');
        $data['userId'] = $this->authData->userId;
        $data['clubCategoryId'] = !empty($this->post('clubCategoryId')) ? $this->post('clubCategoryId') :'';
        $data['clubType'] = $this->post('clubType');//1 public or 2 private
        $data['searchText'] = !empty($this->post('searchText')) ? $this->post('searchText') :'';
        $data['offset'] = $this->post('offset');
        $data['limit'] = $this->post('limit');
        if(!empty($data['latitude']) && !empty($data['longitude']) && !empty($data['city'])){
           $this->common_model->updateFields(USERS,array('latitude'=>$data['latitude'],'longitude'=>$data['longitude'],'city'=>$data['city']),array('userId'=>$this->authData->userId)); 
        }
        $res = $this->club_model->nearByClubsList($data);
        if(!empty($res)){
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(302),'data'=>$res);
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(509));
        }
        $this->response($response); 

    }//End function


    //For Near By Clubs name without pagination
    public function nearByClubsName_post(){

        $this->load->model('club_model');
        $data['latitude'] = !empty($this->post('latitude')) ? $this->post('latitude') : '';
        $data['longitude'] = !empty($this->post('longitude')) ? $this->post('longitude') : '';
        $data['city'] = !empty($this->post('city')) ? $this->post('city') : '';
        $data['isMyClub'] = $this->post('isMyClub');//1 means created clubs, 0 means joined clubs, 2means all club
        $data['userId'] = $this->authData->userId;
        $data['limit'] = $this->post('limit');
        $data['offset'] = $this->post('offset');
        $data['searchText'] = $this->post('searchText');
        $data['clubType'] = $this->post('clubType');
       
        $res = $this->club_model->getClubNames($data);
        if(!empty($res)){
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(302),'data'=>$res);
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(509));
        }
        $this->response($response); 

    }//End function


    //For leave a club
    public function leaveClub_post(){

        $this->form_validation->set_rules('clubId','club id','trim|required|numeric');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }

        $userId = $this->authData->userId;
        $clubId = $this->post('clubId');
        $w = array('user_id'=>$userId,'club_id'=>$clubId);
        $isExist = $this->common_model->is_data_exists(CLUB_USER_MAPPING,$w);
        if(!$isExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(529));
            $this->response($response); 
        }
        $res = $this->common_model->deleteData(CLUB_USER_MAPPING,$w);
        if($res){
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(515));
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
        }
        $this->response($response); 

    }//End function


    //For create a news feed for club
    public function createNewsFeed_post(){

        $this->form_validation->set_rules('newsFeedTitle','news feed title','trim|required');
        $this->form_validation->set_rules('newsFeedDescription','news feed description','trim|required');
        $this->form_validation->set_rules('clubId','club_id','trim|required|numeric');
        $this->form_validation->set_rules('isCommentAllow','isCommentAllow','trim|required|numeric');

        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }

        $data['news_feed_title'] = $this->post('newsFeedTitle');
        $data['news_feed_description'] = $this->post('newsFeedDescription');
        $data['is_comment_allow'] = $this->post('isCommentAllow') == '0' ? '0' : '1';//0 means not allow, 1 means allow
        $data['club_id'] = $this->post('clubId');
        $data['crd'] = $data['upd'] = date('Y-m-d H:i:s');
        $tagName = $this->post('tagName');
        $attachment = '';

        $isClubExist = $this->common_model->is_data_exists(CLUBS,array('clubId'=>$data['club_id']));
        if(!$isClubExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(526));
            $this->response($response); 
        }
        if(!empty($_FILES['newsFeedAttachment']['name'])){
        
            $this->load->model('image_model');
            $folder     = 'news_feed_attachment';
            $hieght = $width = 600;
            $attachment = $this->image_model->updateMedia('newsFeedAttachment',$folder,$hieght,$width,FALSE);
            if(is_array($attachment) && !empty($attachment['error'])){

                $response = array('status' => FAIL, 'message' =>$attachment['error']);
                $this->response($response);
            }
        }
        $data['news_feed_attachment'] = isset($attachment) && is_string($attachment) ? $attachment : '';
        $res = $this->common_model->insert_data(NEWS_FEEDS,$data);
        if($res){
            //adding tags name to db
            if(!empty($tagName)){
                $this->load->model('club_model');
                $this->club_model->addNewsFilterTags($res,$tagName);
            }
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(516));
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
        }
        $this->response($response); 

    }//End function


    //For join  a club or request to join club 
    public function joinClub_post(){

        $this->form_validation->set_rules('clubId','club_id','trim|required|numeric');
        $this->form_validation->set_rules('clubUserStatus','club user status','trim|required');
        $this->form_validation->set_rules('isAllowFeeds','isAllowFeeds','trim|required|numeric');

        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $date = date('Y-m-d H:i:s');
        $data['user_id'] = $this->authData->userId;
        $data['club_id'] = $this->post('clubId');
        $data['club_user_status'] = $this->post('clubUserStatus');
        $data['is_allow_feeds'] = $this->post('isAllowFeeds') == '0' ? '0' : '1';
        $w = array('user_id'=>$data['user_id'],'club_id'=>$data['club_id']);
        $res = $this->common_model->is_data_exists(CLUB_USER_MAPPING,$w);
        if($res){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(517));//already appiled
            $this->response($response);
        }
        $data['upd'] = $data['crd'] = $date;  
        $query = $this->common_model->insert_data(CLUB_USER_MAPPING,$data);
        if(!$query){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            $this->response($response); 
        }
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(518));
        $this->response($response); 
        
    }//End function


    //For Accept or Reject the join request for the club 
    public function answerClubRequest_post(){

        $this->form_validation->set_rules('userId','user id','trim|required|numeric');
        $this->form_validation->set_rules('clubId','club id','trim|required|numeric');
        $this->form_validation->set_rules('answerStatus','answer status','trim|required|numeric');

        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $data['user_id'] = $this->post('userId');
        $data['club_id'] = $this->post('clubId');
        $answerStatus = $this->post('answerStatus');
        $w = array('user_id'=>$data['user_id'],'club_id'=>$data['club_id'],'club_user_status'=>'0');
        $res = $this->common_model->is_data_exists(CLUB_USER_MAPPING,$w);
        if(!$res){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            $this->response($response); 
        }
    
        if($answerStatus == '1') { //accept
            $update = array('club_user_status'=>'1','upd'=>date('Y-m-d H:i:s'));
            $this->common_model->updateFields(CLUB_USER_MAPPING,$update,$w);
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(519)); 
        }elseif($answerStatus == '2'){ //reject
            $this->common_model->deleteData(CLUB_USER_MAPPING,$w);
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(524)); 
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
        }
          
        $this->response($response); 
        

    }//End function


    //For like news feed
    public function newsFeedsLike_post(){

        $this->form_validation->set_rules('newsFeedId','news feed id','trim|required|numeric');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }

        $data['news_feed_id'] = $this->post('newsFeedId');
        $data['user_id'] = $this->authData->userId;
        $data['crd'] = $data['upd'] = date('Y-m-d H:i:s');
        $w = array('news_feed_id'=>$data['news_feed_id'],'user_id'=>$data['user_id']);
        $res = $this->common_model->is_data_exists(NEWS_FEEDS_LIKES,$w);
        if($res){

            $dlt = $this->common_model->deleteData(NEWS_FEEDS_LIKES,$w);
            if(!$dlt){

                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));//something going wrong
                $this->response($response); 
            }
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(126),'isLiked'=>'0');//unliked
            $this->response($response); 
        }else{
            $ins = $this->common_model->insert_data(NEWS_FEEDS_LIKES,$data);
            if(!$ins){
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
                $this->response($response); 
            }
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(126),'isLiked'=>'1');//liked
            $this->response($response); 
        } 
                 
    }//End function

     //For bookmark news feed
    public function newsFeedsBookmark_post(){

        $this->form_validation->set_rules('newsFeedId','news feed id','trim|required|numeric');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $data['news_feed_id'] = $this->post('newsFeedId');
        $data['user_id'] = $this->authData->userId;
        $data['crd'] = $data['upd'] = date('Y-m-d H:i:s');
        $w = array('news_feed_id'=>$data['news_feed_id'],'user_id'=>$data['user_id']);
        $res = $this->common_model->is_data_exists(NEWS_FEEDS_BOOKMARKS,$w);
        if($res){

            $dlt = $this->common_model->deleteData(NEWS_FEEDS_BOOKMARKS,$w);
            if(!$dlt){
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
                $this->response($response); 
            }
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(126),'isBookMarked'=>'0');//removed from bookmark
            $this->response($response); 
        }else{
            $ins = $this->common_model->insert_data(NEWS_FEEDS_BOOKMARKS,$data);
            if(!$ins){
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
                $this->response($response); 
            }
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(126),'isBookMarked'=>'1');//bookmarked
            $this->response($response); 
        } 
                 
    }//End function

    //For comment news feed but not in use currently, might be remove later
    public function newsFeedsComment_post(){

        $this->form_validation->set_rules('newsFeedId','news feed id','trim|required|numeric');
        $this->form_validation->set_rules('comment','comment','trim|required');

        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $data['news_feed_id'] = $this->post('newsFeedId');
        $data['user_id'] = $this->authData->userId;
        $data['crd'] = $data['upd'] = date('Y-m-d H:i:s');
        $data['comment'] = $this->post('comment');
        $isFeedExist = $this->common_model->is_data_exists(NEWS_FEEDS,array('newsFeedId'=>$data['news_feed_id']));
        if(!$isFeedExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(527));
            $this->response($response); 
        }
        $ins = $this->common_model->insert_data(NEWS_FEEDS_COMMENTS,$data);
        if(!$ins){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            $this->response($response); 
        }
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(126));
        $this->response($response);  
                 
    }//End function


    //For club detail
     public function clubDetail_get(){

        $this->load->model('club_model');
        $clubId = $this->get('clubId');
        $isExist = $this->common_model->is_data_exists(CLUBS,array('clubId'=>$clubId));
        if(!$isExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(526));
            $this->response($response); 
        }
        $res = $this->club_model->clubDetail($clubId);
        if($res){ 
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(507),'clubDetail'=>$res);
            $this->response($response);  
                 
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            $this->response($response); 
        }
      
    }//End function

    //For updating comment count
     public function updateCommentCount_post(){

        $this->form_validation->set_rules('newsFeedId','news feed id','trim|required|numeric');
        $this->form_validation->set_rules('commentCount','comment count','trim|required|numeric');

        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $clubId = $this->post('clubId');
        $commentCount = $this->post('commentCount');
        $update = array('comment_count'=>$commentCount,'upd'=>date('Y-m-d H:i:s'));
        $w = array('clubId'=>$clubId);
        $isClubExist = $this->common_model->is_data_exists(CLUBS,$w);
        if(!$isClubExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(526));
            $this->response($response); //club not exist
        }
        $res = $this->common_model->updateFields(CLUBS,$update,$w);
        if($res){ 
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(507));
            $this->response($response);  
                 
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            $this->response($response); 
        }
      
    }//End function

    //For get my clubs
    public function myClubs_post(){

        $this->load->model('club_model');
        $userId = $this->authData->userId;
        $data['offset'] = $this->post('offset');
        $data['limit'] = $this->post('limit');
        $data['clubType'] = $this->post('clubType');
        $searchText = $this->post('searchText');

        $res = $this->club_model->myClubs($userId,$data,$searchText);
        if($res){ 
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(302),'data'=>$res);
            $this->response($response);      
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(509));
            $this->response($response); 
        }
      
    }//End function

    
    //For getting news bookmark list 
    public function newsFeedBookmarkList_get(){

        $this->load->model('club_model');
        $userId = $this->authData->userId;
        $data['offset'] = $this->get('offset');
        $data['limit'] = $this->get('limit');
        $res = $this->club_model->newsFeedBookmarkList($userId,$data);
        if($res){ 
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(302),'data'=>$res);
            $this->response($response);  
                 
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(509));
            $this->response($response); 
        }

    }//End function


    //For getting the news feeds list
    public function getNewsFeedsList_post(){

        $this->load->model('club_model');
        $bookmarks = !empty($this->post('bookmarks')) ? $this->post('bookmarks') : '';
        $likes = !empty($this->post('likes')) ? $this->post('likes') : '';
        $comments = !empty($this->post('comments')) ? $this->post('comments') : '';
        $clubs = !empty($this->post('clubs')) ? $this->post('clubs') : '';
        $data['offset'] = $this->post('offset');
        $data['limit'] = $this->post('limit');
        $isMyFeed = !empty($this->post('isMyFeed')) ? $this->post('isMyFeed') : '';
        $res = $this->club_model->newsFeedsList($bookmarks,$likes,$comments,$clubs,$data,$isMyFeed);
        if($res){
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(302),'data'=>$res);
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(509));
        }

        $this->response($response); 
    }//End function


    //For cancel the sent request for joining the club
    public function cancelClubRequest_post(){

        $this->form_validation->set_rules('clubId','club id','trim|required|numeric');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $date = date('Y-m-d H:i:s');
        $userId = $this->authData->userId;
        $clubId = $this->post('clubId');
        
        $w = array('user_id'=>$userId,'club_id'=>$clubId,'club_user_status'=>'1');
        $res = $this->common_model->is_data_exists(CLUB_USER_MAPPING,$w);
        if($res){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(525));
            $this->response($response);
        }
        $where = array('user_id'=>$userId,'club_id'=>$clubId);
        $res = $this->common_model->deleteData(CLUB_USER_MAPPING,$where);
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(524));
        $this->response($response); 
    
    }//End function

    //For getting club members list
    public function getClubMembers_get(){

        $this->load->model('club_model');
        $userId = $this->authData->userId;
        $data['clubId'] = $this->get('clubId');
        $data['offset'] = $this->get('offset');
        $data['limit'] = $this->get('limit');
        $res = $this->common_model->is_data_exists(CLUBS,array('clubId'=>$data['clubId']));
        if(!$res){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(526));
            $this->response($response);
        }
        $res = $this->club_model->getClubMembers($userId,$data);
        if($res){ 
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(302),'data'=>$res);
            $this->response($response);        
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(509));
            $this->response($response); 
        }

    }//End function

    //For get all applicants for club
    public function getClubApplicants_get(){

        $clubId = $this->get('clubId');
        $data['offset'] = $this->get('offset');
        $data['limit'] = $this->get('limit');
        $where = array('clubId'=>$clubId);
        $res = $this->common_model->is_data_exists(CLUBS,$where);
        if(!$res){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(526));
            $this->response($response);
        }
        $this->load->model('club_model');
        $clubDetail = $this->common_model->getsingle(CLUBS,$where);
        $data['clubLatitude'] = !empty($clubDetail->club_latitude) ? $clubDetail->club_latitude : '';
        $data['clubLongitude'] = !empty($clubDetail->club_longitude) ? $clubDetail->club_longitude : '';
        $result = $this->club_model->getClubApplicants($clubId,$data);
        if($result){ 
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(302),'data'=>$result);
            $this->response($response);  
                 
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(509));
            $this->response($response); 
        }
    
    }//End function


    //For adding user tags
    public function addUserTag_post(){

        $this->form_validation->set_rules('tagName','tag name','trim|required');
        $this->form_validation->set_rules('clubUserId','club user id','trim|required');
        $this->form_validation->set_rules('userId','user id','trim|required');

        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $data['club_user_id'] = $this->post('clubUserId');
        $data['tag_name'] = ucwords($this->post('tagName'));
        $data['user_id'] = $this->post('userId');
        
        $isclubUserExist = $this->common_model->is_data_exists(CLUB_USER_MAPPING,array('clubUserId'=>$data['club_user_id']));
        if(!$isclubUserExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(533));
            $this->response($response); 
        }
        $isTagExist = $this->common_model->is_data_exists(USER_TAGS,$data);
        if($isTagExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(530));
            $this->response($response); 
        }
        $data['crd'] = $data['upd'] = date('Y-m-d H:i:s');
        $ins = $this->common_model->insert_data(USER_TAGS,$data);
        if(!$ins){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            $this->response($response); 
        }
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(531));
        $this->response($response);  
                 
    }//End function


    //For all user tags
    public function allUserTags_get(){

        $res = $this->common_model->getAllWhr(USER_TAGS,'','','*','','','',array('status'=>'1'));
        if(empty($res)){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(509));
            $this->response($response); 
        }
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(302),'data'=>$res);
        $this->response($response);

    }//End function


    //For updating club member status
    public function updateClubMemberStatus_post(){

        $this->form_validation->set_rules('clubUserId','club user id','trim|required|numeric');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $clubUserId = $this->post('clubUserId');
        $w = array('clubUserId'=>$clubUserId);
        $res = $this->common_model->is_data_exists(CLUB_USER_MAPPING,$w);
        if(!$res){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(533));
            $this->response($response);
        }
        $clubUserDetail = $this->common_model->getsingle(CLUB_USER_MAPPING,$w);
        $data['upd'] = date('Y-m-d H:i:s');
        if($clubUserDetail->member_status == '1'){
            $data['member_status'] = '0';//member silent
        }else{
            $data['member_status'] = '1';//member active
        }
        $res = $this->common_model->updateFields(CLUB_USER_MAPPING,$data,$w);
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(532),'member_status'=>$data['member_status']);
        $this->response($response); 

    }//End function
    

    //For getting all news filter tags
    public function allNewsFilterTags_post(){

        $this->form_validation->set_rules('searchText','search text','trim|required');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $this->load->model('club_model');
        $searchText = $this->post('searchText');
        $res = $this->club_model->allNewsFilterTags($searchText);
        if(empty($res)){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(509));
            $this->response($response); 
        }
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(302),'data'=>$res);
        $this->response($response);

    }//End function


    //For getting all joined clubs
    public function joinedClubs_get(){

        $this->load->model('club_model');
        $userId = $this->authData->userId;
        $data['offset'] = $this->get('offset');
        $data['limit'] = $this->get('limit');
        $res = $this->club_model->joinedClubs($userId,$data);
        if($res){ 
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(302),'data'=>$res);
            $this->response($response);  
                 
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(509));
            $this->response($response); 
        }

    }//End function


    //For getting feed detail
    public function newsFeedDetail_get(){

        $this->load->model('club_model');
        $newsFeedId = $this->get('newsFeedId');
        $isFeedExist = $this->common_model->is_data_exists(NEWS_FEEDS,array('newsFeedId'=>$newsFeedId));
        if(!$isFeedExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(527));
            $this->response($response); 
        }
        $res = $this->club_model->newsFeedDetail($newsFeedId);
        if($res){ 
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(302),'data'=>$res);
            $this->response($response);        
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(509));
            $this->response($response); 
        }

    }//End function


    //for updating club feeds allow status
    public function updateAllowFeedStatus_post(){

        $this->load->model('club_model');
        $clubUserId = $this->post('clubUserId');
        $w = array('clubUserId'=>$clubUserId);
        $res = $this->common_model->is_data_exists(CLUB_USER_MAPPING,$w);
        if(!$res){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(533));
            $this->response($response);
        }
        $detail = $this->common_model->getsingle(CLUB_USER_MAPPING,$w);
        $update['is_allow_feeds'] = '1';
        $update['upd'] = date('Y-m-d H:i:s');
        if($detail->is_allow_feeds == '1'){
            $update['is_allow_feeds'] = '0';
        }
        $this->common_model->updateFields(CLUB_USER_MAPPING,$update,$w);
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(534),'is_allow_feeds'=>$update['is_allow_feeds']);
        $this->response($response);        

    }//End function
    

    //for getting my created clubs name for activity creation
    public function myCreatedClubsName_get(){
        
        $this->load->model('club_model');
        $userId = $this->authData->userId;
        $res = $this->club_model->myCreatedClubsName($userId);
        if($res){ 
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(302),'data'=>$res);
            $this->response($response);      
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(509));
            $this->response($response); 
        }

    }//End function

    //for updating nickname of clubs member
    public function updateNickName_post(){
        
        $this->form_validation->set_rules('clubUserId','club user id','trim|required|numeric');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $clubUserId = $this->post('clubUserId');
        $userNickName = $this->post('userNickName');
        $w = array('clubUserId'=>$clubUserId);
        $res = $this->common_model->is_data_exists(CLUB_USER_MAPPING,$w);
        if(!$res){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(533));
            $this->response($response);
        }
        if(!empty($userNickName)){
           $update = array('user_nickname'=>$userNickName,'upd'=>date('Y-m-d H:i:s'));
           $this->common_model->updateFields(CLUB_USER_MAPPING,$update,$w); 
        }
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(546));
        $this->response($response);      

    }//End function


    //For update a news feed for club
    public function updateNewsFeed_post(){

        $this->form_validation->set_rules('newsFeedTitle','news feed title','trim|required');
        $this->form_validation->set_rules('newsFeedDescription','news feed description','trim|required');
        $this->form_validation->set_rules('clubId','club_id','trim|required|numeric');
        $this->form_validation->set_rules('isCommentAllow','isCommentAllow','trim|required|numeric');
        $this->form_validation->set_rules('newsFeedId','news feed id','trim|required|numeric');

        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }

        $data['news_feed_title'] = $this->post('newsFeedTitle');
        $data['news_feed_description'] = $this->post('newsFeedDescription');
        $data['is_comment_allow'] = $this->post('isCommentAllow') == '0' ? '0' : '1';//0 means not allow, 1 means allow
        $data['club_id'] = $this->post('clubId');
        $data['upd'] = date('Y-m-d H:i:s');
        $tagName = $this->post('tagName');
        $newsFeedId = $this->post('newsFeedId');
        $where = array('newsFeedId'=>$newsFeedId);

        $isClubExist = $this->common_model->is_data_exists(CLUBS,array('clubId'=>$data['club_id']));
        if(!$isClubExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(526));
            $this->response($response); 
        }

        $isFeedExist = $this->common_model->is_data_exists(NEWS_FEEDS,$where);
        if(!$isFeedExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(527));
            $this->response($response); 
        }

        if(!empty($_FILES['newsFeedAttachment']['name'])){
        
            $this->load->model('image_model');
            $folder     = 'news_feed_attachment';
            $hieght = $width = 600;
            $attachment = $this->image_model->updateMedia('newsFeedAttachment',$folder,$hieght,$width,FALSE);
            if(is_array($attachment) && !empty($attachment['error'])){
                $response = array('status' => FAIL, 'message' =>$attachment['error']);
                $this->response($response);
            }
            $data['news_feed_attachment'] = $attachment;
        }
        $this->common_model->updateFields(NEWS_FEEDS,$data,$where);
        $w = array('news_feed_id'=>$newsFeedId);
        $this->common_model->deleteData(NEWS_FEED_FILTER_TAGS_MAPPING,$w); 

        if(!empty($tagName)){
            
            $this->load->model('club_model');
            $this->club_model->addNewsFilterTags($newsFeedId,$tagName);
        }

        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(548));
        $this->response($response); 

    }//End function


    //for deleting the news feed
    public function deleteNewsFeed_post(){
        
        $this->form_validation->set_rules('newsFeedId','news feed id','trim|required|numeric');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $newsFeedId = $this->post('newsFeedId');
        $where = array('newsFeedId'=>$newsFeedId);
        $isFeedExist = $this->common_model->is_data_exists(NEWS_FEEDS,$where);
        if(!$isFeedExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(527));
            $this->response($response); 
        }
        $this->common_model->deleteData(NEWS_FEEDS,$where);
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(550));
        $this->response($response);      

    }//End function




} //End class


