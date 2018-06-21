<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity extends CommonService {

    public function __construct(){

        parent::__construct();
        $this->check_lang();
        if(!$this->check_service_auth()){ //check for auth 
            $this->response($this->token_error_msg(), SERVER_ERROR); //authentication failed 
        }
    }

    //For create new activity
    public function createActivity_post(){

    	$this->form_validation->set_rules('name','name','trim|required');
        $this->form_validation->set_rules('location','location','trim|required');
        $this->form_validation->set_rules('feeType','fee type','trim|required');
        $this->form_validation->set_rules('clubId','club id','trim|required');
        $this->form_validation->set_rules('minUsers','minimum users','trim|required|numeric');
        $this->form_validation->set_rules('maxUsers','maximum users','trim|required|numeric');
        $this->form_validation->set_rules('description','description','trim|required');
        $this->form_validation->set_rules('termsConditions','terms and conditions','trim|required');
        $this->form_validation->set_rules('userRole','user role','trim|required');

        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $data['name'] = $this->post('name');
        $data['leader_id'] = $this->post('leaderId');
        $data['club_id'] = $this->post('clubId');
        $data['creator_id'] = $this->authData->userId;
        $data['location'] = $this->post('location');
        $data['latitude'] = $this->post('latitude');
        $data['longitude'] = $this->post('longitude');
        $data['fee_type'] = $this->post('feeType');
        $data['fee'] = $this->post('fee');
        $data['min_users'] = $this->post('minUsers');
        $data['max_users'] = $this->post('maxUsers');
        $data['description'] = $this->post('description');
        $data['terms_conditions'] = $this->post('termsConditions');
        $data['user_role'] = $this->post('userRole');
        $data['crd'] = $data['upd'] = date('Y-m-d H:i:s');
        $image = '';
    	
        $isClubExist = $this->common_model->is_data_exists(CLUBS,array('clubId'=>$data['club_id']));
        if(!$isClubExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(526));
            $this->response($response); 
        }

        if(!empty($_FILES['image']['name'])){
        
            $this->load->model('image_model');
            $folder     = 'activity_image';
            $hieght = $width = 600;
            $image = $this->image_model->updateMedia('image',$folder,$hieght,$width,FALSE);
            if(is_array($image) && !empty($image['error'])){

                $response = array('status' => FAIL, 'message' =>strip_tags($image['error']));
                $this->response($response);
            }
        }
        $data['image'] = isset($image) && is_string($image) ? $image : '';
        $res = $this->common_model->insert_data(ACTIVITIES,$data);
        if($res){
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(535));
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
        }
        $this->response($response); 
        
    }//End function


    //For get all leaders list for the activity
    public function activityLeaderList_get(){

        $this->load->model('activity_model');
        $userId = $this->authData->userId;
        $data['clubId'] = $this->get('clubId');
        $isClubExist = $this->common_model->is_data_exists(CLUBS,array('clubId'=>$data['clubId']));
        if(!$isClubExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(526));
            $this->response($response); 
        }
        $res = $this->activity_model->activityLeaderList($userId,$data);
        if($res){ 
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(302),'data'=>$res);
            $this->response($response);  
                 
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(509));
            $this->response($response); 
        }

    }//End function

    //For get all activity list created by me
    public function myActivityList_get(){

        $this->load->model('activity_model');
        $userId = $this->authData->userId;
        $data['offset'] = $this->get('offset');
        $data['limit'] = $this->get('limit');
        
        $res = $this->activity_model->myActivityList($userId,$data);
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(507),'data'=>$res);
        $this->response($response);

    }//End function


    //For add new event(date) in particular activity
    public function addActivityEvent_post(){

        $this->form_validation->set_rules('eventTitle','title','trim|required');
        $this->form_validation->set_rules('eventDate','event date','trim|required');
        $this->form_validation->set_rules('eventTime','event time','trim|required');
        $this->form_validation->set_rules('location','location','trim|required');
        $this->form_validation->set_rules('latitude','latitude','trim|required|numeric');
        $this->form_validation->set_rules('longitude','longitude','trim|required|numeric');
        $this->form_validation->set_rules('activityId','activity id','trim|required|numeric');

        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $data['location'] = $this->post('location');
        $data['latitude'] = $this->post('latitude');
        $data['longitude'] = $this->post('longitude');
        $data['event_time'] = date('H:i:s',strtotime($this->post('eventTime')));
        $data['event_date'] = date('Y-m-d',strtotime($this->post('eventDate')));
        $data['activity_id'] = $this->post('activityId');
        $data['event_title'] = $this->post('eventTitle');
        $data['crd'] = $data['upd'] = date('Y-m-d H:i:s');
    
        $isActivityExist = $this->common_model->is_data_exists(ACTIVITIES,array('activityId'=>$data['activity_id']));
        if(!$isActivityExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(539));
            $this->response($response); 
        } 
        $totalEvents = $this->common_model->get_total_count(ACTIVITY_EVENTS,array('activity_id'=>$data['activity_id']));
        if($totalEvents > 9){ //Check total count should not be greater than  9
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(537));
            $this->response($response);
        }
        $res = $this->common_model->insert_data(ACTIVITY_EVENTS,$data);
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(536));
        $this->response($response); 

    }//End function

    //For get all activity list 
    public function activityList_get(){

        $this->load->model('activity_model');
        $userId = $this->authData->userId;
        $data['listType'] = $this->get('listType');
        $data['offset'] = $this->get('offset');
        $data['limit'] = $this->get('limit');
        
        $res = $this->activity_model->activityList($userId,$data);
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(507),'data'=>$res);
        $this->response($response);

    }//End function


    //For get activity detail
    public function activityDetail_get(){

        $this->load->model('activity_model');
        $userId = $this->authData->userId;
        $activityId = $this->get('activityId');
        $isActivityExist = $this->common_model->is_data_exists(ACTIVITIES,array('activityId'=>$activityId));
        if(!$isActivityExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(539));
            $this->response($response); 
        }
        $res = $this->activity_model->activityDetail($userId,$activityId);
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(507),'data'=>$res);
        $this->response($response);
        
    }//End function


    //For get user affilates for joining the activity
    public function userJoinAffiliates_get(){

        $this->load->model('activity_model');
        $activityId = $this->get('activityId');
        $userId = $this->get('userId');
        $res = $this->activity_model->getJoinedUser($userId,$activityId);
        if(!$res){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            $this->response($response); 
        }
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(507),'data'=>$res);
        $this->response($response);
        
    }//End function


    //For get user affilates for confirm the activity
    public function userConfirmAffiliates_get(){

        $this->load->model('activity_model');
        $activityEventId = $this->get('activityEventId');
        $activityId = $this->get('activityId');
        $userId = $this->get('userId');
        $res = $this->activity_model->getConfirmUser($userId,$activityEventId,$activityId);
        if(!$res){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            $this->response($response); 
        }
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(507),'data'=>$res);
        $this->response($response);
        
    }//End function



     //For join the activity
    public function joinActivity_post(){

        $this->form_validation->set_rules('activityId','activity id','trim|required|numeric');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $userId = $this->post('userId');
        $currentUserId = $this->authData->userId;
        $affiliateId = $this->post('affiliateId');
        $activityId = $this->post('activityId');
        $isActivityExist = $this->common_model->is_data_exists(ACTIVITIES,array('activityId'=>$activityId));
        if(!$isActivityExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(539));
            $this->response($response); 
        }
        $dltWhr['activity_id'] = $activityId;
        $dltWhr['user_id'] = empty($userId) ? $currentUserId : $userId;

        $this->common_model->deleteData(ACTIVITY_JOIN,$dltWhr);
        $this->common_model->deleteData(ACTIVITY_CONFIRM,$dltWhr);

        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(544));
        $date = date('Y-m-d H:i:s');
        if(!empty($userId)){
            $finalData[] = array('user_id'=>$userId,'activity_id'=>$activityId,'affiliate_id'=>0,'crd'=>$date,'upd'=>$date);
        }
        if(!empty($affiliateId)){
            $affiliates = explode(',',$affiliateId);
            foreach ($affiliates as $key => $value) {
                $return = array();
                $return['user_id'] = $dltWhr['user_id'];
                $return['activity_id'] = $activityId;
                $return ['affiliate_id'] = $value; 
                $return['crd'] = $return['upd'] = $date;
                $finalData[] = $return;
            }    
        }
        //if any of the user id and affiliate id found then insert 
        if(isset($finalData) && !empty($finalData)){
            $this->db->insert_batch(ACTIVITY_JOIN,$finalData);
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(542));
        }
        $this->response($response);
            
    }//End function
    

     //To confirm the activity
    public function confirmActivity_post(){

        $this->form_validation->set_rules('activityId','activity id','trim|required|numeric');
        $this->form_validation->set_rules('activityEventId','activity event id','trim|required|numeric');

        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $userId = $this->post('userId');
        $affiliateId = $this->post('affiliateId');
        $activityId = $this->post('activityId');
        $activityEventId = $this->post('activityEventId');
        $currentUserId = $this->authData->userId;
        $whereActivity = array('activityId'=>$activityId);

        $isActivityExist = $this->common_model->is_data_exists(ACTIVITIES,$whereActivity);
        $isActivityEventExist = $this->common_model->is_data_exists(ACTIVITY_EVENTS,array('activityEventId'=>$activityEventId));

        if(!$isActivityExist || !$isActivityEventExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(539));
            $this->response($response); 
        }
        $dltWhr['activity_id'] = $activityId;
        $dltWhr['activity_event_id'] = $activityEventId;
        $dltWhr['user_id'] = empty($userId) ? $currentUserId : $userId;

        $this->common_model->deleteData(ACTIVITY_CONFIRM,$dltWhr);

        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(545));
        $date = date('Y-m-d H:i:s');
        if(!empty($userId)){ //when user individually confirmed
            $finalData[] = array('user_id'=>$userId,'activity_id'=>$activityId,'activity_event_id'=>$activityEventId,'affiliate_id'=>0,'crd'=>$date,'upd'=>$date);
        }
        if(!empty($affiliateId)){ //when any of the affiliates confirmed
            $affiliates = explode(',',$affiliateId);
            foreach ($affiliates as $key => $value) {
                $return = array();
                $return['user_id'] = $dltWhr['user_id'];
                $return['activity_id'] = $activityId;
                $return['activity_event_id'] = $activityEventId;
                $return ['affiliate_id'] = $value; 
                $return['crd'] = $return['upd'] = $date;
                $finalData[] = $return;
            }    
        }
        //if any of the user id and affiliate id found then insert 
        if(isset($finalData) && !empty($finalData)){

            $appliedUsers = count($finalData);
            $existingUsers = $this->common_model->get_total_count(ACTIVITY_CONFIRM,array('activity_id'=>$activityId,'activity_event_id'=>$activityEventId));
            $actData = $this->common_model->getsingle(ACTIVITIES,$whereActivity);
            $maxUsers = $actData->max_users;
            $totalUsers = $appliedUsers + $existingUsers;

            if($existingUsers == $maxUsers){ //if no vacant place
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(549));
                $this->response($response); 
            }elseif($totalUsers > $maxUsers){ //check the total users should not be grater than the max users
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(547));
                $this->response($response); 
            }
            $this->db->insert_batch(ACTIVITY_CONFIRM,$finalData);
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(543));
        }
        $this->response($response);
             
    }//End function


    //To hide the activity
    public function hideActivity_post(){

        $this->form_validation->set_rules('activityId','activity id','trim|required|numeric');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $activityId = $this->post('activityId');
        $where = array('activityId'=>$activityId);
        $isActivityExist = $this->common_model->check_data(ACTIVITIES,$where);
        if(!$isActivityExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(539));
            $this->response($response); 
        }
        $isUpdate = 1;//hide
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(538),'is_hide'=>$isUpdate);
        $isHide = $isActivityExist->is_hide;
        if($isHide == 1){ //check if unhide already
            $isUpdate = 0;//unhide
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(540),'is_hide'=>$isUpdate);
        }
        $update = array('is_hide'=>$isUpdate,'upd'=>date('Y-m-d H:i:s'));
        $this->common_model->updateFields(ACTIVITIES,$update,$where);
        $this->response($response);

    }//End function


    //To remove the activity
    public function deleteActivity_post(){

        $this->form_validation->set_rules('activityId','activity id','trim|required|numeric');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $userId = $this->authData->userId;
        $activityId = $this->post('activityId');
        $where = array('activityId'=>$activityId);
        $isActivityExist = $this->common_model->is_data_exists(ACTIVITIES,$where);
        if(!$isActivityExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(539));
            $this->response($response); 
        }
        $res = $this->common_model->deleteData(ACTIVITIES,$where);
        if(!$res){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            $this->response($response); 
        }
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(541));
        $this->response($response);

    }//End function



    //For get all the members of activity
    public function activityMembersList_get(){

        $this->load->model('activity_model');
        $data['activityId'] = $this->get('activityId');
        $data['offset'] = $this->get('offset');
        $data['limit'] = $this->get('limit');
        
        $res = $this->activity_model->activityMembersList($data);
        if($res){
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(302),'data'=>$res);
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(509));
        }
        $this->response($response);

    }//End function

}
    