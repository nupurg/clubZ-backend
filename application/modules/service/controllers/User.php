<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CommonService {

    public function __construct(){

        parent::__construct();
        $this->check_lang();
        if(!$this->check_service_auth()){ //check for auth 
            $this->response($this->token_error_msg(), SERVER_ERROR); //authentication failed 
        }
        $this->load->model('User_model');
    }
    
    // For searching interest or skills 
    function autoSearch_get(){ 

        $data['searchType'] = $this->get('searchType');//interest or skills
        $data['searchText'] = $this->get('searchText');
        $res = $this->User_model->autoSearch($data);
        if(!empty($res) && is_array($res)){
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(302),'data'=>$res);
        }elseif(is_string($res) && $res == 'NF'){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(509));
        }elseif(is_string($res) && $res == 'IT'){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(508));
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
        }
        $this->response($response); 
          
    }//End function


    //For update users affilaites,interest and skills 
    function updateUserMeta_post(){

        $userId = $this->authData->userId;
        $data['interests'] = !empty($this->post('interests')) ? $this->post('interests') : '';
        $data['skills'] = !empty($this->post('skills')) ? $this->post('skills') : '';
        $data['affiliates'] = !empty($this->post('affiliates')) ? $this->post('affiliates') : '';
        $data['userId'] = $userId;
        $res = $this->User_model->updateUserMeta($data); 
        if($res){
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(512));
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
        }
        $this->response($response);                            

    }//End function


     //For getting user profile
    function userProfile_get(){

        $userId = $this->get('userId');
        $res = $this->User_model->userProfile($userId); 
        if($res){
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(512),'data'=>$res);
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
        }
        $this->response($response);     

    }//End function


     //For getting user profile
    function updateProfile_post(){

        $userId = $this->authData->userId;
        $fullName = $this->post('fullName');
        $aboutMe = $this->post('aboutMe');
        $contactNo = $this->post('contactNo');
        $countryCode = $this->post('countryCode');
        $dob = $this->post('dob');
        $email = $this->post('email');
        $affiliates = $this->post('affiliates');
        $skills = $this->post('skills');
        $interests = $this->post('interests');
        $aboutMeVisibility = $this->post('aboutMeVisibility');
        $dobVisibility = $this->post('dobVisibility');
        $contactNoVisibility = $this->post('contactNoVisibility');
        $emailVisibility = $this->post('emailVisibility');
        $affiliatesVisibility = $this->post('affiliatesVisibility');
        $skillsVisibility = $this->post('skillsVisibility');
        $interestVisibility = $this->post('interestVisibility');

        $where = array('userId'=>$userId);
        if(!empty($fullName)){
            $data['full_name'] = $fullName;
        }
        if(!empty($aboutMe)){
            $data['about_me'] = $aboutMe;
        }
        if(!empty($contactNo) || !empty($countryCode)){
            $data['contact_no'] = $contactNo;
            $data['country_code'] = $countryCode;
        }
        if(!empty($dob)){
            $data['dob'] = date('Y-m-d', strtotime($dob));
        }
        if(!empty($email)){
            $data['email'] = $email;
        }
       
        if($aboutMeVisibility != ""){
            $data['about_me_visibility'] = $aboutMeVisibility;
        }
        if($dobVisibility != ""){
            $data['dob_visibility'] = $dobVisibility;
        }
        if($contactNoVisibility != ""){
            $data['contact_no_visibility'] = $contactNoVisibility;
        }
        if($emailVisibility != ""){
            $data['email_visibility'] = $emailVisibility;
        }
        if($affiliatesVisibility != ""){
            $data['affiliates_visibility'] = $affiliatesVisibility;
        }
        if($skillsVisibility != ""){
            $data['skills_visibility'] = $skillsVisibility;
        }
        if($interestVisibility != ""){
            $data['interest_visibility'] = $interestVisibility;
        }
        if(!empty($_FILES['profileImage']['name'])){

            $this->load->model('image_model');
            $folder     = 'profile';
            $hieght = $width = 600;
            $profileImage = $this->image_model->updateMedia('profileImage',$folder,$hieght,$width,FALSE);
            if(is_array($profileImage) && array_key_exists("error",$profileImage) && !empty($profileImage['error'])){
                $response = array('status' => FAIL, 'message' =>strip_tags($profileImage['error']));
                $this->response($response);
            } 
            $data['is_profile_url'] = "0";
            $data['profile_image'] = $profileImage;
        }

        if(!empty($data) && isset($data)){
            $this->common_model->updateFields(USERS,$data,$where);
        }
        if(!empty($affiliates) || !empty($skills) || !empty($interests)){
           $this->User_model->updateUserMeta($affiliates,$skills,$interests,$userId);
        }
        $response = array('status'=>SUCCESS,'message'=>'Profile updated successfully');
        $this->response($response);

    }//End function


    //For on off notifications from settings
    function updateNotificationStatus_post(){

        $this->form_validation->set_rules('notificationType','notificationType','trim|required');
        $this->form_validation->set_rules('status','status','trim|required|numeric');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $notificationType = $this->post('notificationType');
        $status = $this->post('status');
        $noti = $status == 1 ? 0 : 1;
        switch($notificationType){

            case 'chat':
            $update['chat_notifications'] = $noti;
            break;

            case 'news':
            $update['news_notifications'] = $noti;
            break;

            case 'activities':
            $update['activities_notifications'] = $noti;
            break;

            case 'ads':
            $update['ads_notifications'] = $noti;
            break;

            default:
            $update['chat_notifications'] = $noti;
            $update['news_notifications'] = $noti;
            $update['activities_notifications'] = $noti;
            $update['ads_notifications'] = $noti;
        }
        $where = array('userId'=>$this->authData->userId);
        $this->common_model->updateFields(USERS,$update,$where);
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(507),'updatedStatus'=>$noti);
        $this->response($response);

    }//End function


    //function for updating privacy
    function updatePrivacy_post(){

        $this->form_validation->set_rules('privacyType','privacy type','trim|required');
        $this->form_validation->set_rules('status','status','trim|required|numeric');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $privacyType = $this->post('privacyType');
        $status = $this->post('status');
        $privacy = $status == 1 ? 0 : 1;

        switch($privacyType){

            case 'showProfile':
            $update['show_profile'] = $privacy;
            break;

            case 'allowAnyOne':
            $update['allow_anyone'] = $privacy;
            break;

            case 'syncWiFi':
            $update['sync_wifi'] = $privacy;
            break;

            default:
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            $this->response($response);   
        }

        $where = array('userId'=>$this->authData->userId);
        $this->common_model->updateFields(USERS,$update,$where);
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(507),'updatedStatus'=>$privacy);
        $this->response($response);

    }//End function


    //For updating account info setting
    function updateAccount_post(){

        $this->form_validation->set_rules('type','type','trim|required');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $type = $this->post('type');
        $userId = $this->authData->userId;
        $where = array('userId'=>$userId);

        switch($type){

            case 'location':
            $update['latitude'] = !empty($this->post('latitude')) ? $this->post('latitude') : '';
            $update['longitude'] = !empty($this->post('longitude')) ? $this->post('longitude') : '';
            $update['city'] = !empty($this->post('city')) ? $this->post('city') : '';
            $this->common_model->updateFields(USERS,$update,$where);
            break;

            case 'language':
            $update['language'] = !empty($this->post('language')) ? $this->post('language') : '';
            $this->common_model->updateFields(USERS,$update,$where);
            break;


            case 'contactNo':
            $update['contact_no'] = $this->post('contactNo');
            $update['country_code'] = $this->post('countryCode');
            $this->common_model->updateFields(USERS,$update,$where);
            break;

            case 'deleteAccount':
            $wh = array('leader_id'=>$userId);
            $this->common_model->deleteData(USERS,$where);
            $this->common_model->deleteData(ACTIVITIES,$where);
            break;

            default:
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            $this->response($response);   
        }
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(507));
        $this->response($response);

    }//End function


    //Function for getting list of silenced users by current user
    function silencedUsers_get(){

        $userId = $this->authData->userId;
        $data['offset'] = $this->get('offset');
        $data['limit'] = $this->get('limit');
        $res = $this->User_model->silencedUsers($userId,$data); 
        if($res){
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(302),'data'=>$res);
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(509));
        }
        $this->response($response);     

    }//End function



} //End class


