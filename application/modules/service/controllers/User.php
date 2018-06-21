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
        $currentUserId = $this->authData->userId;
        if($userId == $currentUserId){
            $res = $this->User_model->myProfile($userId); 
        }else{
            $res = $this->User_model->otherProfile($userId,$currentUserId); 
        }
        if($res){
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(507),'data'=>$res);
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
        $removedAffiliates = $this->post('removedAffiliates');
        $addAffiliates = $this->post('addAffiliates');
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
        $wh = array('user_id'=>$userId);
        if(!empty($removedAffiliates)){

            $remove = explode(',',$removedAffiliates);
            if(!empty($addAffiliates)){
                $add = explode(',',$addAffiliates);
                $common = array_intersect($add, $remove);
                $newRemove = array_diff($remove, $common);
            }else{
                $newRemove = $remove;
            }
            if(!empty($newRemove)){
                $this->db->where_in('affiliate_name',$newRemove);
                $this->db->delete(USER_AFFILIATES,$wh);

                $q = $this->db->select('userAffiliateId')->where_in('affiliate_name', $newRemove)->where($wh)->get(USER_AFFILIATES);

                if($q->num_rows()){

                    $resu = $q->result_array();
                    foreach ($resu as $key => $value) {
                        $aff[] = $value['userAffiliateId'];
                    }
                    $this->db->where_in('affiliate_id',$aff);
                    $this->db->delete(ACTIVITY_JOIN);

                    $this->db->where_in('affiliate_id',$aff);
                    $this->db->delete(ACTIVITY_CONFIRM);  
                }
            }  
        }

        if(!empty($addAffiliates)){
            $add = explode(',',$addAffiliates);
            foreach ($add as $k => $val) {
                
                $affi['user_id'] = $userId;
                $affi['affiliate_name'] = $val;
                $isExist = $this->common_model->is_data_exists(USER_AFFILIATES,$affi);
                if(!$isExist){
                    $affi['crd'] = $affi['upd'] = date('Y-m-d H:i:s');
                    $this->common_model->insert_data(USER_AFFILIATES,$affi);
                }
            }
        }

        if(!empty($data) && isset($data)){
            $this->common_model->updateFields(USERS,$data,$where);
        }
        $this->common_model->deleteData(USER_SKILLS,$wh);
        $this->common_model->deleteData(USER_INTERESTS,$wh);
            
        if(!empty($skills) || !empty($interests)){

            $userMeta = array('skills'=>$skills,'interests'=>$interests,'userId'=>$userId);
            $this->User_model->updateUserMeta($userMeta);
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


    //Function for adding club member as favorite or unfavorite by club owner
    function updateContact_post(){

        $this->form_validation->set_rules('clubUserId','club user id','trim|required|numeric');
        $this->form_validation->set_rules('isFavorite','isfavorite','trim|required|numeric');

        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }

        $clubUserId = $this->post('clubUserId');
        $where = array('clubUserId'=>$clubUserId);
        $isclubUserExist = $this->common_model->is_data_exists(CLUB_USER_MAPPING,$where);
        if(!$isclubUserExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(533));
            $this->response($response); 
        }
        $update['is_favorite'] = $this->post('isFavorite');
        $update['upd'] = date('Y-m-d H:i:s');
        $this->common_model->updateFields(CLUB_USER_MAPPING,$update,$where);
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(507),'isFavorite'=>$update['is_favorite']);
        $this->response($response); 

    }//End function


     //Function for getting  contact list by club owner
    function contactList_get(){

        $userId = $this->authData->userId;
        $data['offset'] = $this->get('offset');
        $data['limit'] = $this->get('limit');
        $res = $this->User_model->contactList($userId,$data); 
        if($res){
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(302),'data'=>$res);
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(509));
        }
        $this->response($response);     
        
    }//End function



} //End class


