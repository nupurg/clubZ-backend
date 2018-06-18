<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//load rest library
require APPPATH . '/libraries/REST_Controller.php';
class CommonService extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        //load language
        $lang_arr = array('en'=>'english', 'es'=>'spanish');
        $header = $this->input->request_headers();
        $this->appLang = 'english'; //default langauge
        $lang_key = '';
        if(array_key_exists ( 'language' , $header )){
            $lang_key = 'language';
        } elseif(array_key_exists ( 'Language' , $header )){
            $lang_key = 'Language';
        }
        
        if(!empty($lang_key)){
            $lang_val = $header[$lang_key];
            if(array_key_exists($lang_val , $lang_arr )){
                $this->appLang = $lang_arr[$lang_val];
            }
        }
        
        $this->lang->load('response_messages_lang', $this->appLang);  //load response lang file
        $this->load->model('service_model'); //load service model
    }
    
     //check auth token of request
    public function check_service_auth(){
        /*Authtoken*/
        $this->authData = '';
        $header = $this->input->request_headers();
        //check if key exist as different server may have different types of key (case sensitive) 
        if(array_key_exists ( 'authToken' , $header )){
            $key = 'authToken';
        }
        elseif(array_key_exists ( 'Authtoken' , $header )){
            $key = 'Authtoken';
        }
        elseif(array_key_exists ( 'AuthToken' , $header )){
            $key = 'AuthToken';
        }
        else{
            return false;
        }
        $authToken = isset($header[$key]) ? $header[$key] : '';
        $userAuthData =  !empty($authToken) ? $this->service_model->isValidToken($authToken) : '';

        if(!empty($userAuthData)){
            $this->authData = $userAuthData;
            return true;    
        } 
        else {
            return false;     
        }
    }
    
    //show auth token error message
    public function token_error_msg(){
        
        return array( 'message'=>ResponseMessages::getStatusCodeMessage(101),'authToken'=>'','responseCode'=>300);
    }

    //check language
    public function check_lang(){

        
    }
}//End Class 

