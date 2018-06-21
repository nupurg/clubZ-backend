<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {

	function autoSearch($data){

		$limit = 15;
		if($data['searchType'] == 1){ // interests
			$q = $this->db->select('interestId,interest_name')->where('status','1')->like('interest_name',$data['searchText'],'after')->limit($limit)->get(INTERESTS);
		}elseif($data['searchType'] == 2){//skills
			$q = $this->db->select('skillId,skill_name')->where('status','1')->like('skill_name',$data['searchText'],'after')->limit($limit)->get(SKILLS);
		}else{
			return 'IT';//invalid type
		}
		if($q->num_rows()){
			$result = $q->result_array();
			return $result;
		}else{
			return 'NF';//not found
		}
	}//End function


	function updateUserMeta($data){

		$where = array('user_id'=>$data['userId']);
		if(!empty($data['interests'])){
			$type = '1';
			$intData = explode(",",$data['interests']);
			$this->updateCommon(INTERESTS,USER_INTERESTS,'interest_name',$type,$intData,$where);

		}if(!empty($data['skills'])){
			$type = '2';
			$skData = explode(",",$data['skills']);
			$this->updateCommon(SKILLS,USER_SKILLS,'skill_name',$type,$skData,$where);

		}if(!empty($data['affiliates'])){
			$type = '3';
			$afData = explode(",",$data['affiliates']);
			$this->updateCommon('',USER_AFFILIATES,'affiliate_name',$type,$afData,$where);
		}
		return true;

	}//End function
    

    //common function for updating skills,interests and affiliates
	function updateCommon($maintbl,$usertbl,$columnName,$type,$allData,$where){
		
		$date= date('Y-m-d H:i:s');
		$isExist = $this->common_model->is_data_exists($usertbl,$where);
		if($isExist){
			$this->db->where($where);
			$this->db->delete($usertbl);
		}
		foreach ($allData as $k => $val) {

			if($type == '1' || $type == '2'){
				$where = array($columnName=>$val);
				$isInterestExist = $this->common_model->check_data($maintbl,$where);
				if($isInterestExist){
					if($type == '1'){
						$insId = $isInterestExist->interestId;
					}else{
						$insId = $isInterestExist->skillId;
					}
				}else{
					$dataIns[$columnName] = $val;
					$dataIns['crd'] = $dataIns['upd'] = $date;
					$insId = $this->common_model->insert_data($maintbl,$dataIns);
				}
				if($type == '1'){
					$userAllData['interest_id'] = $insId;
				}else{
					$userAllData['skill_id'] = $insId;
				}
				
			}else{
				$userAllData['affiliate_name'] = $val;
			}
			$userAllData['user_id'] = $this->authData->userId;
			$userAllData['crd'] = $userAllData['upd'] = $date;
			$this->common_model->insert_data($usertbl,$userAllData);	
		}
		
	}//End function


	//For my profile
	function myProfile($userId){

		$defaultUserImg = base_url().USER_DEFAULT_IMAGE;
        $userImg = base_url().USER_MAIN_IMAGE;
		$where = array('u.userId'=>$userId);
        $this->db->select('u.userId,u.full_name,u.email,u.contact_no,u.country_code,u.about_me,u.dob,(case 
            when (u.profile_image = "") 
            THEN "'.$defaultUserImg.'"
            when (u.profile_image != "" && u.is_profile_url = 1) 
            THEN u.profile_image
            ELSE
            concat("'.$userImg.'",u.profile_image) 
            END) as profile_image,COALESCE(GROUP_CONCAT(DISTINCT s.skill_name),"") as skills,COALESCE(GROUP_CONCAT(DISTINCT i.interest_name),"") as interests,COALESCE(GROUP_CONCAT(DISTINCT uf.affiliate_name),"") as affiliates');
        $this->db->from(USERS.' as u');
        $this->db->join(USER_AFFILIATES.' as uf','u.userId = uf.user_id','left'); 
        $this->db->join(USER_INTERESTS.' as ui','u.userId = ui.user_id','left'); 
        $this->db->join(INTERESTS.' as i','ui.interest_id = i.interestId','left');
        $this->db->join(USER_SKILLS.' as us','u.userId = us.user_id','left');
        $this->db->join(SKILLS.' as s','us.skill_id = s.skillId','left');
        $this->db->where($where);
        $query = $this->db->get();
		if($query->num_rows() >0){
            
            $userData = $query->row();
            return $userData;
        }
      
	}//End function


	//for getting other user profile
	function otherProfile($userId,$currentUserId){

		$defaultUserImg = base_url().USER_DEFAULT_IMAGE;
        $userImg = base_url().USER_MAIN_IMAGE;
		$where = array('u.userId'=>$userId);
        $this->db->select('u.userId,u.full_name,u.email,u.contact_no,u.country_code,u.about_me,u.dob,about_me_visibility,dob_visibility,contact_no_visibility,email_visibility,affiliates_visibility,skills_visibility,interest_visibility,(case 
            when (u.profile_image = "") 
            THEN "'.$defaultUserImg.'"
            when (u.profile_image != "" && u.is_profile_url = 1) 
            THEN u.profile_image
            ELSE
            concat("'.$userImg.'",u.profile_image) 
            END) as profile_image,COALESCE(GROUP_CONCAT(DISTINCT s.skill_name),"") as skills,COALESCE(GROUP_CONCAT(DISTINCT i.interest_name),"") as interests,COALESCE(GROUP_CONCAT(DISTINCT uf.affiliate_name),"") as affiliates');
        $this->db->from(USERS.' as u');
        $this->db->join(USER_AFFILIATES.' as uf','u.userId = uf.user_id','left'); 
        $this->db->join(USER_INTERESTS.' as ui','u.userId = ui.user_id','left'); 
        $this->db->join(INTERESTS.' as i','ui.interest_id = i.interestId','left');
        $this->db->join(USER_SKILLS.' as us','u.userId = us.user_id','left');
        $this->db->join(SKILLS.' as s','us.skill_id = s.skillId','left');
        
        $this->db->where($where);
        $query = $this->db->get();
		if($query->num_rows() >0){
            
            $userData = $query->row();
            $contact = $this->checkContact($userId,$currentUserId);
            $isClubMember = 1;
            if($contact['isContact'] == 0){
            	$data = $this->checkClubMember($userId,$currentUserId);
            	$isClubMember = $data['isClubMember'];
            }
            $userData->isContact = $contact['isContact'];
            $userData->isClubMember = $isClubMember;
            return $userData;
        }
	}


	function checkClubMember($userId,$currentUserId){

		$data['isClubMember'] = 0;
		$where = array('c.user_id'=>$userId,'cum.user_id'=>$currentUserId,'cum.club_user_status'=>'1','cum.member_status'=>'1');
        $this->db->select('cum.is_favorite');
        $this->db->from(CLUBS.' as c');
        $this->db->join(CLUB_USER_MAPPING.' as cum','c.clubId = cum.club_id');
        $this->db->where($where);
        $query = $this->db->get();
		if($query->num_rows() >0){
            $data['isClubMember'] = 1;
        }
        return $data;
	}


	function checkContact($userId,$currentUserId){

		$data['isContact'] = 0;
		$where = array('c.user_id'=>$userId,'cum.user_id'=>$currentUserId,'cum.club_user_status'=>'1','cum.member_status'=>'1','cum.is_favorite'=>1);
        $this->db->select('cum.is_favorite');
        $this->db->from(CLUBS.' as c');
        $this->db->join(CLUB_USER_MAPPING.' as cum','c.clubId = cum.club_id');
        $this->db->where($where);
        $query = $this->db->get();
		if($query->num_rows() >0){
            $data['isContact'] = 1;
        }
        return $data;
	}

	//For getting list of silenced users by current user
	function silencedUsers($userId,$data){

		if(!isset($data['offset']) || empty($data['limit'])){
            $data['offset'] = 0; $data['limit']= 10; 
        }
		$where = array('c.user_id'=>$userId,'c.status' => '1','cum.member_status'=>'0');
        $this->db->select('c.clubId,cum.clubUserId,u.userId,(case 
                when (cum.user_nickname != "") 
                    THEN cum.user_nickname
                when (ut.tag_name IS NOT NULL || ut.tag_name != "") 
                    THEN ut.tag_name
                ELSE
                    u.full_name
            END) as name');
        $this->db->from(CLUBS.' as c'); 
        $this->db->join(CLUB_USER_MAPPING.' as cum','c.clubId = cum.club_id','left');  
        $this->db->join(USERS.' as u','cum.user_id = u.userId');
        $this->db->join(USER_TAGS.' as ut','cum.clubUserId = ut.club_user_id','left'); 
        $this->db->where($where);
        $this->db->order_by('cum.clubUserId','desc');
        $this->db->limit($data['limit'],$data['offset']);
        $query = $this->db->get();
        if($query->num_rows() >0){
            
            $clubData = $query->result();
            return $clubData;
        }

	}//End function


	//function for get contact list
	function contactList($userId,$data){

		if(!isset($data['offset']) || empty($data['limit'])){
            $data['offset'] = 0; $data['limit']= 10; 
        }
        $defaultUserImg = base_url().USER_DEFAULT_IMAGE;
        $userImg = base_url().USER_MAIN_IMAGE;
		$where = array('c.user_id'=>$userId,'c.status' => '1','cum.is_favorite'=>1,'cum.member_status'=>'1');
        $this->db->select('c.clubId,cum.clubUserId,cum.is_favorite,c.club_name,u.userId,(case 

        	    when (cum.user_nickname != "") 
                    THEN cum.user_nickname
                when (ut.tag_name IS NOT NULL || ut.tag_name != "") 
                    THEN ut.tag_name
                ELSE
                    u.full_name
            END) as name,(case 
                when (u.profile_image = "") 
                    THEN "'.$defaultUserImg.'"
                when (u.profile_image != "" && u.is_profile_url = 1) 
                    THEN u.profile_image
                ELSE
                    concat("'.$userImg.'",u.profile_image) 
            END) as profile_image');

        $this->db->from(CLUBS.' as c'); 
        $this->db->join(CLUB_USER_MAPPING.' as cum','c.clubId = cum.club_id');  
        $this->db->join(USERS.' as u','cum.user_id = u.userId');
        $this->db->join(USER_TAGS.' as ut','cum.clubUserId = ut.club_user_id','left'); 
        $this->db->where($where);
        $this->db->order_by('cum.clubUserId','desc');
        $this->db->limit($data['limit'],$data['offset']);
        $query = $this->db->get();
        if($query->num_rows() >0){
            
            $clubData = $query->result();
            return $clubData;
        }

	}//End function


}
