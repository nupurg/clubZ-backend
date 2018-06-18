<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//General service API class 

class Service extends CommonService {

    public function __construct(){
        parent::__construct();
        $this->load->model('Image_model');
    }

    // for generating OTP 
    function generateOtp_post(){ 

        $this->form_validation->set_rules('contact_no','Contact Number','trim|required|numeric', array('required'=>$this->lang->line('')));
        $this->form_validation->set_rules('country_code','Country code','trim|required');

        if($this->form_validation->run() == FALSE){
            $response = array('status'=>FAIL,'message'=>strip_tags(validation_errors()));
            $this->response($response);
        }
        $otp = (rand(10, 99)).(rand(11, 99));
        $contact_no     = $this->post('contact_no');
        $country_code    = $this->post('country_code');
        $isNewUser = '1';
        //check if number exists 
        $where =  array('contact_no'=>$contact_no);
        $is_exist = $this->common_model->is_data_exists(USERS, $where);
        if($is_exist){
            $isNewUser = '0';//already exist
        }

        //number not verified or does not exist, proceed with sending OTP
        $this->load->library('twilio');
        $from 	= '+16179175884';// +15059336632 
        $to 	= $country_code.$contact_no;
        $message = 'The verification code for your Club Z account is: '.$otp;
        $twi_res = $this->twilio->sms($from, $to, $message);
        if($twi_res->IsError){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(520));  //$twi_res->ErrorMessage - use this for debuggin purpose only
        }else{ 
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(521),'otp'=>$otp,'step'=>2,'isNewUser'=>$isNewUser);
        }
        $this->response($response); 
        
    }//End function

    // for OTP verification -- Not used now as reg flow is changed. This fn might be removed later on.
    /*function otpVerify_post(){

        $ins_data['contact_no'] = $this->post('contact_no');
        $ins_data['country_code'] = "+".$this->post('country_code');
        $auth_token = $this->post('auth_token');
        
        //check if number exists and is already verified
        $where =  array('contact_no'=>$ins_data['contact_no']);
        $is_exist = $this->common_model->is_data_exists(USERS, $where);
        if($is_exist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(513));
            $this->response($response);
        }
        
        if(empty($auth_token)){
            //registration is normal
            
            
        } else{
            //registration is social
        }
       
        $isVerify = $this->service_model->contactVerify($ins_data['contact_no'],$ins_data['country_code']);

        if(is_array($isVerify)){

            switch ($isVerify['status']){

                case "1":
                $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(119),'step'=>2);
                break;
                case "0":
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(129),'step'=>1);
                break;
                default:
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            }

            $this->response($response);
        }else{

            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(109));
            $this->response($response);
        }
   
    }//End Function
     */
    
    //In social login case- Check if user exist
    function checkSocialRegister_post(){

        $this->form_validation->set_rules('social_type', 'Social type', 'trim|required');
        $this->form_validation->set_rules('social_id', 'Social user', 'trim|required');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }
        $social_id = $this->post('social_id');
        $social_type = $this->post('social_type');
        $device_type = $this->post('device_type');
        $device_token = $this->post('device_token');
        
        //check if user exists
        $where =  array('social_type'=>$social_type, 'social_id'=>$social_id);
        $user_id = $this->common_model->get_field_value(USERS, $where, 'userId');
        if(!$user_id){
            $response = array('status'=>SUCCESS,'step'=>1); //user does not exist proceed to step-1(phone verification)
            $this->response($response);
        }
        //user already exist, proceed with login
        $auth_token = $this->service_model->generate_token();
        $this->service_model->updateDeviceIdToken($user_id, $device_token, $auth_token, $device_type);
        $user_detail = $this->service_model->userInfo(array('userId'=>$user_id));
        $response = array('status'=>SUCCESS, 'message'=>ResponseMessages::getStatusCodeMessage(106), 'messageCode'=>'social_login', 'userDetail'=>$user_detail);
        $this->response($response);

    }//End Function

    //User registration 
    function registration_post(){
     
        if(empty($this->post('social_id')) && empty($this->post('social_type'))){
            //if not social login- make these fields mandatory
            $this->form_validation->set_rules('full_name', 'Name', 'trim|required|min_length[2]|max_length[100]|callback__alpha_spaces_check');
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[100]');  
        }

        $this->form_validation->set_rules('email', 'Email', 'is_unique[users.email]', array('is_unique' => ResponseMessages::getStatusCodeMessage(117)));
        $this->form_validation->set_rules('contact_no', 'Contact number', 'trim|required|numeric');
        $this->form_validation->set_rules('contact_no', 'Contact number', 'is_unique[users.contact_no]', array('is_unique' => ResponseMessages::getStatusCodeMessage(513)));
        $this->form_validation->set_rules('country_code', 'Country code', 'trim|required');
        
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }
        $profileImage = '';
        if(!empty($_FILES['profile_image']['name'])){

            $this->load->model('image_model');
            $folder     = 'profile';
            $hieght = $width = 600;
            $profileImage = $this->image_model->updateMedia('profile_image',$folder,$hieght,$width,FALSE);
        }
        if(is_array($profileImage) && array_key_exists("error",$profileImage) && !empty($profileImage['error'])){
            $response = array('status' => FAIL, 'message' =>strip_tags($profileImage['error']));
            $this->response($response);
        }   
        $auth_token = $this->service_model->generate_token();
        $data = array();
        $set = array('full_name','email','contact_no', 'country_code', 'device_token','device_type','social_type','social_id');
        foreach ($set as $key => $val) {
            $post= $this->post($val);
            $data[$val] = (isset($post) && !empty($post)) ? $post :''; 
        }
        $data['crd'] = $data['upd']  = datetime();
        $data['auth_token'] = $auth_token;
        $isProfileUrl = 0;
        if(is_string($profileImage) && !empty($profileImage)){
            $data['profile_image'] = $profileImage;
        }elseif(filter_var($this->input->post('profile_image'), FILTER_VALIDATE_URL)){
            $data['profile_image'] = $this->input->post('profile_image');
            $isProfileUrl = 1;
        }
        $data['auth_token'] = $auth_token; 
        $data['is_profile_url'] = $isProfileUrl;
        $result = $this->service_model->registration($data);

        if(is_array($result)){
            switch ($result['regType']){    
                //normal registration
                case "NR":
                $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(110),'userDetail'=>$result['returnData'], 'messageCode'=>'normal_reg', 'step'=>4);
                break;
                case "NA":
                 $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(121),'userDetail'=>array());   
                break;
                case "AE": 
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(117),'userDetail'=>array());
                break;
                case "SL":
                $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(106),'userDetail'=>$result['returnData'], 'messageCode'=>'social_login');
                break;
                case "SR":
                $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(110),'userDetail'=>$result['returnData'],'step'=>4, 'messageCode'=>'social_reg');
                break;
                case "NV":
                $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(506),'userDetail'=>$result['returnData']);
                break;
                default:
                $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            }
        } else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
        }
        $this->response($response);
        
    }//End function

    //User login
    function login_post(){

        $this->form_validation->set_rules('country_code','Country code','trim|required');
        $this->form_validation->set_rules('contact_no','Contact number','trim|required');
       
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }

        $auth_token = $this->service_model->generate_token();
        $country_code   = $this->post('country_code');
        $contact_no    = $this->post('contact_no');
        $device_token   = $this->post('device_token');
        $device_type    = $this->post('device_type');
        
        $where =  array('country_code'=>$country_code, 'contact_no'=>$contact_no);
        $user_id = $this->common_model->get_field_value(USERS, $where, 'userId');
        if(!$user_id){
            $response = array('status'=>FAIL,'message'=>'Number not registered'); //number not registered
            $this->response($response);
        }

        $this->service_model->updateDeviceIdToken($user_id,$device_token,$auth_token,$auth_token);
        $user_detail = $this->service_model->userInfo(array('userId'=>$user_id)); //get user info
        $response = array('status'=>SUCCESS, 'message'=>ResponseMessages::getStatusCodeMessage(106), 'messageCode'=>'normal_login', 'userDetail'=>$user_detail);
        $this->response($response);

    } //End Function
    
    //Generate OTP on login
    function loginOtp_post(){

        $this->form_validation->set_rules('country_code','Country code','trim|required');
        $this->form_validation->set_rules('contact_no','Contact number','trim|required');
        
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => strip_tags(validation_errors()));
            $this->response($response);
        }
        
        $country_code   = $this->post('country_code');
        $contact_no    = $this->post('contact_no');
        $where =  array('contact_no'=>$contact_no);
        $is_exist = $this->common_model->is_data_exists(USERS, $where);
        if(!$is_exist){
            $response = array('status'=>FAIL,'message'=>'Number not registered'); //number not registered
            $this->response($response);
        }
        //number exists, proceed with sending otp
        $otp = (rand(10, 99)).(rand(11, 99));
        $this->load->library('twilio');
        $from       = '+15059336632';
        $to         = $country_code.$contact_no;
        $message    = 'Club Z - Your OTP for login is: '.$otp ;
        $twi_res   = $this->twilio->sms($from, $to, $message);
        if($twi_res->IsError){
            $response = array('status'=>FAIL,'message'=>'Unable to send OTP. Please try again.'); //$twi_res->ErrorMessage - use this for debugging purpose only
        }else{ 
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(128),'otp'=>$otp,'step'=>2);
        }
        $this->response($response);
    }

    //For forgot password
    public function forgotPassword_post(){

        $this->load->library('form_validation'); 
        $this->form_validation->set_rules('email','email','required|valid_email');
        
        if($this->form_validation->run() == FALSE){
            $errors = strip_tags(validation_errors());
            $response = array('status'=>FAIL,'message'=>preg_replace("/[\\n\\r]+/", " ", $errors));
            $this->response($response);

        }else{
            $email['email'] = $this->post('email');            
            $resultPass = $this->service_model->resetPassword($email);
            if($resultPass['emailType'] == 'ES'){
                $responseArray = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(511));
                $status = OK;                   
            }elseif($resultPass == 1) {
                $responseArray = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(510));
                $status = OK;
            }else{
                $responseArray = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
                $status = OK;
            }
            $response = $this->generate_response($responseArray);
            $this->response($response,$status); 
        }
    }//End function
    
    //validation callback for checking alpha_spaces
    function _alpha_spaces_check($string){

        if(alpha_spaces($string)){
            return true;
        }
        else{
            $this->form_validation->set_message('_alpha_spaces_check','Only alphabets and spaces are allowed in {field} field');
            return FALSE;
        }
    } //End function
    
    
    function send_emo_post(){
        $title   = $this->post('title');
        $name    = $this->post('name');
        $dataInsert = array('title'=>$title,'name'=>$name);
        $last_id = $this->common_model->insert_data('emoj', $dataInsert);
        //$this->db->insert('emoj',$dataInsert);
        //$last_id = $this->db->insert_id();
        if(!$last_id)
            $this->response(array('status'=>Fail)); 
        
        $emo_detail = $this->common_model->getsingle('emoj', array('emoId'=>$last_id));
        $this->response(array('status'=>SUCCESS,'message'=>'Done','emoDetail'=>$emo_detail)); 
        
    }
    

} //End class


