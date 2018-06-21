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


    //For add to favourite the ad
    public function favoriteAd_post(){

        $this->form_validation->set_rules('adId','ad id','trim|required|numeric');
        if($this->form_validation->run() == FALSE){
            $response = array('status' => FAIL, 'message' => preg_replace("/[\\n\\r]+/", " ",strip_tags(validation_errors())));
            $this->response($response);die;
        }
        $adId = $this->post('adId');
        $w = array('adId'=>$adId);
        $isAdExist = $this->common_model->is_data_exists(ADS,$w);
        if(!$isAdExist){
            $response = array('status'=>FAIL,'message'=>ResponseMessages::getStatusCodeMessage(118));
            $this->response($response);
        }
        $userId = $this->authData->userId;
        $data = array('ad_id'=>$adId,'user_id'=>$userId);
        $isFav = $this->common_model->is_data_exists(FAVORITE_ADS,$data);
        if($isFav){
            $this->common_model->deleteData(FAVORITE_ADS,$data);
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(507),'isFav'=>0);//unfav
            $this->response($response); 
        }else{
            $data['crd'] = date('Y-m-d H:i:s');
            $this->common_model->insert_data(FAVORITE_ADS,$data);
            $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(507),'isFav'=>1);//fav
            $this->response($response); 
        }

    }//End function


    //For getting the ads list for those clubs in which i have joined
    public function adsList_get(){

        $this->load->model('ads_model');
        $userId = $this->authData->userId;
        $data['listType'] = $this->get('listType');
        $data['offset'] = $this->get('offset');
        $data['limit'] = $this->get('limit');
        
        $res = $this->ads_model->adsList($userId,$data);
        $response = array('status'=>SUCCESS,'message'=>ResponseMessages::getStatusCodeMessage(507),'data'=>$res);
        $this->response($response);

    }//End function
    
} //End class


