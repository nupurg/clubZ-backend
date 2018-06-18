<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ads extends CommonService {

    public function __construct(){

        parent::__construct();
        $this->check_lang();
        if(!$this->check_service_auth()){ //check for auth 
            $this->response($this->token_error_msg(), SERVER_ERROR); //authentication failed 
        }
        $this->load->model('ads_model');
    }
    
    //For create new ads
    public function createAd_post(){

    	$this->form_validation->set_rules('title','title','trim|required');
        $this->form_validation->set_rules('clubId','club id','trim|required');
        $this->form_validation->set_rules('userRole','user role','trim|required');

        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $data['title'] = $this->post('title');
        $data['club_id'] = $this->post('clubId');
        $data['user_id'] = $this->authData->userId;
        $data['fee'] = $this->post('fee');
        $data['is_renew'] = $this->post('isRenew') == 1 ? 1 : 0;
        $data['description'] = $this->post('description');
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
            $folder     = 'ad_image';
            $hieght = $width = 600;
            $image = $this->image_model->updateMedia('image',$folder,$hieght,$width,FALSE);
            if(is_array($image) && !empty($image['error'])){

                $response = array('status' => FAIL, 'message' =>strip_tags($image['error']));
                $this->response($response);
            }
        }
        $data['image'] = isset($image) && is_string($image) ? $image : '';
        $res = $this->common_model->insert_data('ads',$data);
        if($res){
            $response = array('status'=>SUCCESS,'message'=>'Ad successfully created');
        }else{
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
        }
        $this->response($response); 
        
    }//End function
    
} //End class


