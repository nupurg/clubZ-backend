<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity_model extends CI_Model {

	//for getting leader list for the activity creation
    function activityLeaderList($userId,$data){

        $where = array('cum.club_id'=>$data['clubId'],'cum.club_user_status'=>'1','cum.member_status'=>'1');
        $this->db->select('u.userId,(case 
                when (ut.tag_name IS NOT NULL || ut.tag_name != "") 
                    THEN ut.tag_name
                when (cum.user_nickname != "") 
                    THEN cum.user_nickname
                ELSE
                    u.full_name
            END) as name');
        $this->db->from(CLUB_USER_MAPPING.' as cum');
        $this->db->join(USERS.' as u','cum.user_id = u.userId');
        $this->db->join(USER_TAGS.' as ut','cum.clubUserId = ut.club_user_id','left'); 
        $this->db->where($where);
        $this->db->order_by('cum.clubUserId','desc');
        $query = $this->db->get();
        if($query->num_rows() >0){
            
            $activityData = $query->result();
            return $activityData;
        }

    }//End function

    //For getting activity list created by me
    function activityList($userId,$data){

        $activity = array(); 
        $activity['hasAffiliates'] = 0;
        $aff = $this->common_model->is_data_exists(USER_AFFILIATES,array('user_id'=>$userId));
        if($aff){
            $activity['hasAffiliates'] = 1;
        }
        switch($data['listType']){ //For get activity list
            case 'today' : 
            $activity['today'] = $this->todayActivityList($userId,$data);
            break;

            case 'tomorrow' : 
            $activity['tomorrow'] = $this->tomorrowActivityList($userId,$data);
            break;

            case 'soon' :
            $activity['soon'] = $this->soonActivityList($userId,$data);
            break;

            case 'others' :
            $activity['others'] = $this->othersActivityList($userId,$data);
            break;

            default:
            $activity['today'] = $this->todayActivityList($userId,$data);
            $activity['tomorrow'] = $this->tomorrowActivityList($userId,$data);
            $activity['soon'] = $this->soonActivityList($userId,$data);  
            $activity['others'] = $this->othersActivityList($userId,$data);  
        }//End of activity list

        //Now we will set events for each activities
        if(!empty($activity['today']) && isset($activity['today'])){
            foreach ($activity['today'] as $key => $value) {
                $activityId = $value->activityId;
                $activity['today'][$key]->events = $this->getActivityEvents($userId,$activityId);
            }
        }
        if(!empty($activity['tomorrow']) && isset($activity['tomorrow'])){
            foreach ($activity['tomorrow'] as $key => $value) {
                $activityId = $value->activityId;
                $activity['tomorrow'][$key]->events = $this->getActivityEvents($userId,$activityId);
            }
        }
        if(!empty($activity['soon']) && isset($activity['soon'])){ 
            foreach ($activity['soon'] as $key => $value) {
                $activityId = $value->activityId;
                $activity['soon'][$key]->events = $this->getActivityEvents($userId,$activityId);
            }
        }//End of setting events
        return $activity;

    }//End function


    //For getting events
    function getActivityEvents($userId,$activityId){

        $activityData = array();
        $activityImg = base_url().ACTIVITY_IMAGE;
        $defaultActivityImg = base_url().DEFAULT_ACTIVITY_IMAGE;
        $data['offset'] = 0; $data['limit']= 10; 
        $where1 = array('ae.status'=>'1','a.status'=>'1','ae.activity_id'=>$activityId);
        $this->db->select('ae.activityEventId,ae.event_title,ae.event_date,ae.event_time,ae.description,ae.location,ae.latitude,ae.longitude,a.fee,a.fee_type,a.max_users,count(DISTINCT aj.activityJoinId) as total_users,count(DISTINCT ac.activityConfirmId) as joined_users,if(ac_me.activityConfirmId IS NULL,0,1) as is_confirm,if(aj1.activityJoinId IS NULL,0,1) as hasJoined,if(aj2.activityJoinId IS NULL,0,1) as hasAffiliatesJoined');
        $this->db->from(ACTIVITY_EVENTS.' as ae');
        $this->db->join(ACTIVITIES.' as a','ae.activity_id = a.activityId'); 
        $this->db->join(ACTIVITY_JOIN.' as aj','a.activityId = aj.activity_id','left');
        $this->db->join(ACTIVITY_CONFIRM.' as ac','ae.activityEventId = ac.activity_event_id','left');
        $this->db->join(ACTIVITY_CONFIRM.' as ac_me','ae.activityEventId = ac_me.activity_event_id  AND ac_me.user_id = "'.$userId.'"','left');
        $this->db->join(ACTIVITY_JOIN.' as aj1','a.activityId = aj1.activity_id AND aj1.user_id = "'.$userId.'"','left');
        $this->db->join(ACTIVITY_JOIN.' as aj2','a.activityId = aj2.activity_id AND aj2.user_id = "'.$userId.'" AND aj2.affiliate_id != 0','left');
        $this->db->where($where1);
        $this->db->group_by('ae.activityEventId');
        $this->db->order_by("ae.event_date asc,ae.event_time asc");
        $this->db->limit($data['limit'],$data['offset']);
        $query = $this->db->get();
        if($query->num_rows() >0){
            $activityData = $query->result();
        }
        return $activityData;

    }//End function
    

    //For today activity list
    function todayActivityList($userId,$data){

        $activityData = array();
        $activityImg = base_url().ACTIVITY_IMAGE;
        $defaultActivityImg = base_url().DEFAULT_ACTIVITY_IMAGE;
        if(empty($data['limit']) && empty($data['offset'])){
            $data['offset'] = 0; $data['limit']= 5; 
        }
        $where1 = array('ae.status'=>'1','c.status'=>'1','cc.status'=>'1','a.status'=>'1','a.creator_id !='=>$userId,'a.is_hide'=>0,'cum.club_user_status'=>'1','cum.member_status'=>'1','cum.user_id'=>$userId);
        $where2 = "ae.event_date = CAST(CURRENT_TIMESTAMP AS DATE)";
        $this->db->select('IF(a.image IS NULL or a.image = "","'.$defaultActivityImg.'",concat("'.$activityImg.'",a.image)) as image,a.name as activityName,a.activityId,c.club_name,IF(aj.activityJoinId IS NULL,0,1) as is_like');
        $this->db->from(CLUB_USER_MAPPING.' as cum');
        $this->db->join(CLUBS.' as c','cum.club_id = c.clubId');
        $this->db->join(CLUB_CATEGORY.' as cc','c.club_category_id = cc.clubCategoryId'); 
        $this->db->join(ACTIVITIES.' as a','cum.club_id = a.club_id'); 
        $this->db->join(ACTIVITY_EVENTS.' as ae','a.activityId = ae.activity_id');
        $this->db->join(ACTIVITY_JOIN.' as aj','a.activityId = aj.activity_id AND aj.user_id = "'.$userId.'"','left');
        $this->db->where($where1);
        $this->db->where($where2);
        $this->db->group_by('a.activityId');
        $this->db->order_by("ae.event_time asc,a.activityId desc");
        $this->db->limit($data['limit'],$data['offset']);
        $query = $this->db->get();
        if($query->num_rows() >0){
            $activityData = $query->result();
        }
        return $activityData;

    }//End function
    
    //For tomorrow activity list
    function tomorrowActivityList($userId,$data){

        $activityData = array();
        $activityImg = base_url().ACTIVITY_IMAGE;
        $defaultActivityImg = base_url().DEFAULT_ACTIVITY_IMAGE;
        if(empty($data['limit']) && empty($data['offset'])){
            $data['offset'] = 0; $data['limit']= 5; 
        }
        $where1 = array('ae.status'=>'1','c.status'=>'1','cc.status'=>'1','a.status'=>'1','a.creator_id !='=>$userId,'a.is_hide'=>0,'cum.club_user_status'=>'1','cum.member_status'=>'1','cum.user_id'=>$userId);
        $where2 = "ae.event_date = DATE_ADD(CURDATE(), INTERVAL 1 DAY)"; 
        $this->db->select('IF(a.image IS NULL or a.image = "","'.$defaultActivityImg.'",concat("'.$activityImg.'",a.image)) as image,a.name as activityName,a.activityId,c.club_name,IF(aj.activityJoinId IS NULL,0,1) as is_like');
        $this->db->from(CLUB_USER_MAPPING.' as cum');
        $this->db->join(CLUBS.' as c','cum.club_id = c.clubId');
        $this->db->join(CLUB_CATEGORY.' as cc','c.club_category_id = cc.clubCategoryId'); 
        $this->db->join(ACTIVITIES.' as a','cum.club_id = a.club_id'); 
        $this->db->join(ACTIVITY_EVENTS.' as ae','a.activityId = ae.activity_id');
        $this->db->join(ACTIVITY_JOIN.' as aj','a.activityId = aj.activity_id AND aj.user_id = "'.$userId.'"','left');
        $this->db->where($where1);
        $this->db->where($where2);
        $this->db->group_by('a.activityId');
        $this->db->order_by("ae.event_time asc,a.activityId desc");
        $this->db->limit($data['limit'],$data['offset']);
        $query = $this->db->get();
        if($query->num_rows() >0){
            $activityData = $query->result();
        }
        return $activityData;
    }//End function


    //For soon activity list
    function soonActivityList($userId,$data){

        $activityData = array();
        $activityImg = base_url().ACTIVITY_IMAGE;
        $defaultActivityImg = base_url().DEFAULT_ACTIVITY_IMAGE;
        if(empty($data['limit']) && empty($data['offset'])){
            $data['offset'] = 0; $data['limit']= 5; 
        }
        $where1 = array('ae.status'=>'1','c.status'=>'1','cc.status'=>'1','a.status'=>'1','a.creator_id !='=>$userId,'a.is_hide'=>0,'cum.club_user_status'=>'1','cum.member_status'=>'1','cum.user_id'=>$userId);
        $where2 = "ae.event_date > DATE_ADD(CURDATE(), INTERVAL 1 DAY)"; 
        $this->db->select('IF(a.image IS NULL or a.image = "","'.$defaultActivityImg.'",concat("'.$activityImg.'",a.image)) as image,a.name as activityName,a.activityId,c.club_name,IF(aj.activityJoinId IS NULL,0,1) as is_like');
        $this->db->from(CLUB_USER_MAPPING.' as cum');
        $this->db->join(CLUBS.' as c','cum.club_id = c.clubId');
        $this->db->join(CLUB_CATEGORY.' as cc','c.club_category_id = cc.clubCategoryId'); 
        $this->db->join(ACTIVITIES.' as a','cum.club_id = a.club_id'); 
        $this->db->join(ACTIVITY_EVENTS.' as ae','a.activityId = ae.activity_id');
        $this->db->join(ACTIVITY_JOIN.' as aj','a.activityId = aj.activity_id AND aj.user_id = "'.$userId.'"','left');
        $this->db->where($where1);
        $this->db->where($where2);
        $this->db->group_by('a.activityId');
        $this->db->order_by("ae.event_date asc,ae.event_time asc");
        $this->db->limit($data['limit'],$data['offset']);
        $query = $this->db->get();
        if($query->num_rows() >0){
            $activityData = $query->result();
        }
        return $activityData;

    }//End function


    //For other activity list
    function othersActivityList($userId,$data){

        $activityData = array();
        $activityImg = base_url().ACTIVITY_IMAGE;
        $defaultActivityImg = base_url().DEFAULT_ACTIVITY_IMAGE;
        if(empty($data['limit']) && empty($data['offset'])){
            $data['offset'] = 0; $data['limit']= 5; 
        }
        $arrr = '[]';
        $where = array('c.status'=>'1','cc.status'=>'1','a.status'=>'1','a.creator_id !='=>$userId,'a.is_hide'=>0,'cum.club_user_status'=>'1','cum.member_status'=>'1','cum.user_id'=>$userId,'ae.activityEventId'=>NULL);
        
        $this->db->select('IF(a.image IS NULL or a.image = "","'.$defaultActivityImg.'",concat("'.$activityImg.'",a.image)) as image,a.name as activityName,a.activityId,c.club_name,IF(aj.activityJoinId IS NULL,0,1) as is_like');
        $this->db->from(CLUB_USER_MAPPING.' as cum');
        $this->db->join(CLUBS.' as c','cum.club_id = c.clubId');
        $this->db->join(CLUB_CATEGORY.' as cc','c.club_category_id = cc.clubCategoryId'); 
        $this->db->join(ACTIVITIES.' as a','cum.club_id = a.club_id'); 
        $this->db->join(ACTIVITY_EVENTS.' as ae','a.activityId = ae.activity_id','left');
        $this->db->join(ACTIVITY_JOIN.' as aj','a.activityId = aj.activity_id AND aj.user_id = "'.$userId.'"','left');
        $this->db->where($where);
        $this->db->group_by('a.activityId');
        $this->db->order_by("ae.event_date asc,ae.event_time asc");
        $this->db->limit($data['limit'],$data['offset']);
        $query = $this->db->get();
        if($query->num_rows() >0){
            $activityData = $query->result();
        }
        return $activityData;

    }//End function

    //For getting my activity list
    function myActivityList($userId,$data){

        $activityData = array();
        $activityImg = base_url().ACTIVITY_IMAGE;
        $defaultActivityImg = base_url().DEFAULT_ACTIVITY_IMAGE;
        if(empty($data['limit']) && empty($data['offset'])){
            $data['offset'] = 0; $data['limit']= 5; 
        }
        $where = array('c.status'=>'1','cc.status'=>'1','a.status'=>'1','a.creator_id'=>$userId);
        
        $this->db->select('IF(a.image IS NULL or a.image = "","'.$defaultActivityImg.'",concat("'.$activityImg.'",a.image)) as image,a.name as activityName,a.is_hide,a.activityId,c.club_name');
        $this->db->from(ACTIVITIES.' as a'); 
        $this->db->join(CLUBS.' as c','a.club_id = c.clubId');
        $this->db->join(CLUB_CATEGORY.' as cc','c.club_category_id = cc.clubCategoryId'); 
        $this->db->join(ACTIVITY_EVENTS.' as ae','a.activityId = ae.activity_id AND ae.status = "1"','left');
        
        $this->db->where($where);
        $this->db->group_by('a.activityId');
        $this->db->order_by("a.activityId desc");
        $this->db->limit($data['limit'],$data['offset']);
        $query = $this->db->get();
        if($query->num_rows() >0){
            $activityData = $query->result();
            foreach ($activityData as $key => $value) {
                $activityId = $value->activityId;
                $activityData[$key]->events = $this->getActivityEvents($userId,$activityId);
            }
        }
        return $activityData;
    }//End function


    //for getting user detail for activity join
    function getJoinedUser($userId,$activityId){
        
       $where = array('userId'=>$userId);
       $defaultUserImg = base_url().USER_DEFAULT_IMAGE;
       $userImg = base_url().USER_MAIN_IMAGE;
       $this->db->select('u.full_name,u.userId,(case 
                when (u.profile_image = "") 
                    THEN "'.$defaultUserImg.'"
                when (u.profile_image != "" && u.is_profile_url = 1) 
                    THEN u.profile_image
                ELSE
                    concat("'.$userImg.'",u.profile_image) 
            END) as profile_image,if(aj.activityJoinId IS NULL,0,1) as isJoined');
       $this->db->from(USERS.' as u');
       $this->db->join(ACTIVITY_JOIN.' as aj','u.userId = aj.user_id AND aj.activity_id = "'.$activityId.'" AND aj.affiliate_id = 0','left');
       $this->db->where($where);
       $q = $this->db->get();
       if($q->num_rows()){
            $row = $q->row();
            $row->affiliates = $this->getJoinUserAffiliates($userId,$activityId);
            return $row;
       }

    }//End function


    //For getting user affiliates for activity join
    function getJoinUserAffiliates($userId,$activityId){

       $data = array();
       $where = array('ua.user_id'=>$userId);
       $this->db->select('userAffiliateId,affiliate_name,if(aj.activityJoinId IS NULL,0,1) as isJoined');
       $this->db->from(USER_AFFILIATES.' as ua');
       $this->db->join(ACTIVITY_JOIN.' as aj','ua.userAffiliateId = aj.affiliate_id AND aj.activity_id = "'.$activityId.'" AND aj.user_id = "'.$userId.'"','left');
       $this->db->where($where);
       $q = $this->db->get();
       if($q->num_rows()){
            $data = $q->result();
       }
       return $data;

    }//End function


    //for getting user detail for activity join
    function getConfirmUser($userId,$activityEventId,$activityId){

        $row = new stdclass;
        $where = array('aj.user_id'=>$userId,'aj.activity_id' => $activityId,'aj.affiliate_id'=> 0);
        $defaultUserImg = base_url().USER_DEFAULT_IMAGE;
        $userImg = base_url().USER_MAIN_IMAGE;
        $this->db->select('u.full_name,u.userId,(case 
                when (u.profile_image = "") 
                    THEN "'.$defaultUserImg.'"
                when (u.profile_image != "" && u.is_profile_url = 1) 
                    THEN u.profile_image
                ELSE
                    concat("'.$userImg.'",u.profile_image) 
            END) as profile_image,if(ac.activityConfirmId IS NULL,0,1) as isConfirmed');
        $this->db->from(ACTIVITY_JOIN.' as aj');
        $this->db->join(USERS.' as u','aj.user_id = u.userId');
        $this->db->join(ACTIVITY_CONFIRM.' as ac','u.userId = ac.user_id AND ac.activity_event_id = "'.$activityEventId.'" AND ac.affiliate_id = 0','left');

        $this->db->where($where);
        $q = $this->db->get();
        if($q->num_rows()){
            $row = $q->row();
        }
        $row->affiliates = $this->getConfirmUserAffiliates($userId,$activityEventId,$activityId);
        return $row;

    }//End function


    //For getting user affiliates for activity confirm
    function getConfirmUserAffiliates($userId,$activityEventId,$activityId){

       $data = array();
       $where = array('aj.user_id'=>$userId,'aj.activity_id' => $activityId,'aj.affiliate_id !=' =>0);
       $this->db->select('userAffiliateId,affiliate_name,if(ac.activityConfirmId IS NULL,0,1) as isConfirmed');
       $this->db->from(ACTIVITY_JOIN.' as aj');
       $this->db->join(USER_AFFILIATES.' as ua','aj.affiliate_id = ua.userAffiliateId');
       $this->db->join(ACTIVITY_CONFIRM.' as ac','ua.userAffiliateId = ac.affiliate_id AND ac.activity_event_id = "'.$activityEventId.'" AND ac.user_id = "'.$userId.'"','left');
       $this->db->where($where);
       $q = $this->db->get();
       if($q->num_rows()){
            $data = $q->result();
       }
       return $data;

    }//End function


    //for fetching activity detail
    function activityDetail($userId,$activityId){

        $where = array('activityId'=>$activityId);
        $activityImg = base_url().ACTIVITY_IMAGE;
        $defaultActivityImg = base_url().DEFAULT_ACTIVITY_IMAGE;
        $defaultUserImg = base_url().USER_DEFAULT_IMAGE;
        $userImg = base_url().USER_MAIN_IMAGE;
        $this->db->select('a.activityId,a.name,a.location,a.latitude,a.longitude,a.fee_type,a.fee,a.min_users,a.max_users,a.user_role,a.description,a.terms_conditions,IF(a.image IS NULL || a.image = "","'.$defaultActivityImg.'",concat("'.$activityImg.'",a.image)) as image,IF(aj.activityJoinId IS NULL,0,1) as is_like,COALESCE(ut.tag_name,"")as leader_name,COALESCE(u1.full_name,"")as creator_name,(case 
                when (u1.profile_image = "") 
                    THEN "'.$defaultUserImg.'"
                when (u1.profile_image != "" && u1.is_profile_url = 1) 
                    THEN u1.profile_image
                ELSE
                    concat("'.$userImg.'",u1.profile_image) 
            END) as creator_profile_image');
        $this->db->from(ACTIVITIES.' as a');
        $this->db->join(CLUBS.' as c','a.club_id = c.clubId');
        $this->db->join(CLUB_CATEGORY.' as cc','c.club_category_id = cc.clubCategoryId AND cc.status = "1"'); 
        $this->db->join(USERS.' as u1','a.creator_id = u1.userId'); 
        $this->db->join(USER_TAGS.' as ut','a.leader_id = ut.userTagId','left');
        $this->db->join(ACTIVITY_JOIN.' as aj','a.activityId = aj.activity_id AND aj.user_id = "'.$userId.'"','left');
        $this->db->where($where);
        $q = $this->db->get();
        if($q->num_rows()){
            $row = $q->row();
            $row->next_event = $this->getNextEvent($activityId);
            return $row;
        }

    }//End function


    //function to get next event of activity
    function getNextEvent($activityId){

        $activityData = array();
        $where1 = array('ae.status'=>'1','ae.activity_id'=>$activityId);
        $where2 = "concat(ae.event_date,' ',ae.event_time) > now()"; 

        $this->db->select('ae.activityEventId,ae.event_title,ae.event_date,ae.event_time,ae.description,ae.location,ae.latitude,ae.longitude,a.fee,a.fee_type');
        $this->db->from(ACTIVITY_EVENTS.' as ae');
        $this->db->join(ACTIVITIES.' as a','ae.activity_id = a.activityId'); 
        $this->db->where($where1);
        $this->db->where($where2);
        $this->db->order_by("ae.event_date asc,ae.event_time asc");
        $this->db->limit('1');
        $query = $this->db->get();
        if($query->num_rows() >0){
           
            $activityData = $query->row();
        }
        return $activityData;

    }//End function


    //function to get activity members list
    function activityMembersList($data){

        $where = array('activity_id'=>$data['activityId'],'affiliate_id'=>0);
        if(empty($data['limit']) && empty($data['offset'])){
            $data['offset'] = 0; $data['limit']= 5; 
        }
        $defaultUserImg = base_url().USER_DEFAULT_IMAGE;
        $userImg = base_url().USER_MAIN_IMAGE;

        $this->db->select('u.full_name,u.userId,(case 
            when (u.profile_image = "") 
            THEN "'.$defaultUserImg.'"
            when (u.profile_image != "" && u.is_profile_url = 1) 
            THEN u.profile_image
            ELSE
            concat("'.$userImg.'",u.profile_image) 
            END) as profile_image');

        $this->db->from(ACTIVITY_JOIN.' as aj');
        $this->db->join(USERS.' as u','aj.user_id = u.userId'); 
        $this->db->where($where);
        $this->db->limit($data['limit'],$data['offset']);
        $q = $this->db->get();
        if($q->num_rows()){
            $result = $q->result();
            foreach ($result as $key => $value) {
                $userId = $value->userId;
                $result[$key]->affiliates = $this->activityAffiliteMembers($data['activityId'],$userId);
            }
            return $result;
        }

    }//End function

    function activityAffiliteMembers($activityId,$userId){

        $result = array();
        $where = array('activity_id'=>$activityId,'aj.user_id'=>$userId);
        $this->db->select('uf.userAffiliateId,uf.affiliate_name');
        $this->db->from(ACTIVITY_JOIN.' as aj');
        $this->db->join(USER_AFFILIATES.' as uf','aj.affiliate_id = uf.userAffiliateId'); 
        $this->db->where($where);
        $q = $this->db->get();
        if($q->num_rows()){
            $result = $q->result();
        }
        return $result;

    }//End function

}
