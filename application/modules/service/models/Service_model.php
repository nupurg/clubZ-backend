<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service_model extends CI_Model {

	/**
	* Generate token for users
	*/
	function generate_token(){

		$this->load->helper('security');
		$res = do_hash(time().mt_rand());
		$new_key = substr($res,0,config_item('rest_key_length'));
		return $new_key;

	}
	/**
	* Update users deviceid and auth token while login
	*/
	function checkDeviceToken(){

		$sql = $this->db->select('userId')->where('deviceToken', $deviceToken)->get(USERS);
		if($sql->num_rows()){

			$id = array();
			foreach($sql->result() as $result){
				$id[] = $result->userId;
			}
			$this->db->where_in('userId', $id);
			$this->db->update(USERS,array('deviceToken'=>''));
			if($this->db->affected_rows() > 0){
				return true;
			}else{
				return false;
			}
		}
		return true;
	} //Function for check Device Token
	
	/*
	Function for check provided token is resultid or not
	*/
	function isValidToken($authToken){

		$this->db->select('*');
		$this->db->where('auth_token',$authToken);
		if($sql = $this->db->get(USERS)){
			if($sql->num_rows() > 0){
				return $sql->row();
			}
		}
		return false;
	}


    //for updating device token and device type
    function updateDeviceIdToken($id,$deviceToken,$authToken,$deviceType=''){

        $req = $this->db->select('userId')->where('userId',$id)->get(USERS);
        if($req->num_rows()){
            $this->db->update(USERS,array('device_token'=>''),array('userId !='=>$id,'device_token'=>$deviceToken));
            $this->db->update(USERS,array('device_token'=>$deviceToken,'auth_token'=>$authToken,'device_type'=>$deviceType),array('userId'=>$id));
            return TRUE;
        }
        return FALSE;

    }//End Function


    //for get user information
    function userInfo($userId){
        
        $this->db->select('userId,full_name,social_id,social_type,email,country_code,contact_no,profile_image,auth_token,device_type,device_token')->where($userId)->from(USERS);

        $resultQuery = $this->db->get();
        if($resultQuery->num_rows()){

                $userData = $resultQuery->row();
                if(!empty($userData->profile_image)){
                        if (!filter_var($userData->profile_image, FILTER_VALIDATE_URL) === false) {
             $userData->profile_image;
                        }else {

                                $userData->profile_image = base_url().'uploads/profile/'.$userData->profile_image;
                        }
                }
        }
        return $userData;

    } //End function


    //user registration
    function registration($data){	

        if(!empty($data['social_id']) && !empty($data['social_type'])){  
            // social registration or social login
            $query = $this->db->select('*')->where(array('social_id'=>$data['social_id'],'social_type'=>$data['social_type']))->get(USERS);
            if($query->num_rows()==1){

                $result = $query->row();
                $status_check = $result->status ;
                
                if($status_check != 1){
                    return array('regType'=>'NA'); //User not active
                }
                
                $id = $result->userId;
                //updating user's device ID and auth token
                $updateToken = $this->updateDeviceIdToken($id,$data['device_token'],$data['auth_token'],$data['device_type']);
                
                //social login
                return array('regType'=>'SL','returnData'=>$this->userInfo(array('userId' => $id)));
                
            }else{	
                //social registration
                $this->db->insert(USERS,$data);
                $insertId = $this->db->insert_id();
                if(!empty($insertId)){
                	$clubData = $this->common_model->getsingle(CLUBS,array('club_type'=>'3'));
					if(!empty($clubData->clubId)){
						
						$uData['user_id'] = $insertId;
						$uData['club_id'] = $clubData->clubId;
						$uData['club_user_status'] = '1';
						$uData['is_allow_feeds'] = '1';
						$uData['upd'] = $uData['crd'] = date('Y-m-d H:i:s');  
						$this->db->insert(CLUB_USER_MAPPING,$uData);
					}
                    return array('regType'=>'SR','returnData'=>$this->userInfo(array('userId' => $insertId)));
                }else{
                    return false;
                }
            }
        }else{  
            //Normal registration 
            if(!empty($data['email'])){
                $res = $this->db->select('email')->where(array('email'=>$data['email']))->get(USERS);
                if($res->num_rows()){
                    return array('regType'=>'AE'); //email already exist
                }
            }
            $this->db->insert(USERS,$data);
            $last_id = $this->db->insert_id();
            $clubData = $this->common_model->getsingle(CLUBS,array('club_type'=>'3'));
			if(!empty($clubData->clubId)){
				
				$uData['user_id'] = $last_id;
				$uData['club_id'] = $clubData->clubId;
				$uData['club_user_status'] = '1';
				$uData['is_allow_feeds'] = '1';
				$uData['upd'] = $uData['crd'] = date('Y-m-d H:i:s');  
				$this->db->insert(CLUB_USER_MAPPING,$uData);
			}
            return array('regType'=>'NR','returnData'=>$this->userInfo(array('userId' => $last_id)));  
        }

    } //End Function 


    //For reset password
    function resetPassword($email){
		
		$query = $this->db->select('userId,first_name,email,password')->from(USERS)->where(array('email'=>$email['email']))->get();
		if($query->num_rows()){
	
			$data = $query->row();
			$this->load->library('encrypt');
			$email = $data->email;
			$res['first_name'] = ucwords($data->first_name);
			$res['PWD'] = $this->encrypt->decode($data->password);
			$password = $this->load->view('email',$res,true);
			$subject = "ClubZ Password";
			
			return $this->emailSent($email,$password,$subject);
			
		}else{
			return 1;
		}

	} //End Function 


	//for sending email
	function emailSent($email,$password,$subject){

		$this->load->library('email');
		$config=array(
		'charset'=>'utf-8',
		'wordwrap'=> TRUE,
		'mailtype' => 'html'
		);
		$this->email->initialize($config);
		$this->email->from('arvind.mindiii@gmail.com', 'ClubZ');
		$this->email->to($email);
		$this->email->subject($subject);
		$this->email->message($password);
		$mailStatus = $this->email->send();
		if ($mailStatus) {
			return  array('emailType'=>'ES','email'=>'The email has successfully been sent!!' ); //ES emailSend
		}else{
			return  array('emailType'=>'NS','email'=> "something went wrong.") ; //NS NOSend
		}

	} //End Function 


}// End class
