<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Club_model extends CI_Model {

	public function nearByClubsList($data){

		if(!isset($data['offset']) || empty($data['limit'])){
            $data['offset'] = 0; $data['limit']= 10; 
        }
        $where = array('c.status' => '1','cc.status' => '1','u.status'=>'1','c.user_id !='=>$data['userId']);
		if(!empty($data['clubCategoryId'])){
    		$where['c.club_category_id'] = $data['clubCategoryId']; 
    	}

        if(!empty($data['city'])){
            $where['c.club_city'] = $data['city']; 
        }

        if($data['clubType'] == '1'){
            $where['c.club_type'] = '1';//public club
        }elseif($data['clubType'] == '2'){
            $where['c.club_type'] = '2';//private club
        }

        $clubImageUrl = base_url().CLUB_MAIN_IMAGE;
        $defaultClubImage = base_url().DEFAULT_CLUB_IMAGE;

        $clubIconUrl = base_url().CLUB_ICON;
        $defaultClubIcon = base_url().DEFAULT_CLUB_ICON;

        $defaultUserImg = base_url().USER_DEFAULT_IMAGE;
        $userImg = base_url().USER_MAIN_IMAGE;

		if(!empty($data['latitude']) && !empty($data['longitude'])){
	        $radius = 300;//kilometers
        	$this->db->select('c.*,( 6371 * acos ( cos ( radians('.$data['latitude'].') ) * cos( radians( club_latitude ) ) * cos( radians( club_longitude ) - radians('.$data['longitude'].') ) + sin ( radians('.$data['latitude'].') ) * sin( radians( club_latitude ) ) ) ) AS distance,cc.club_category_name,u.full_name,COALESCE(cu.club_user_status,"") as club_user_status,COALESCE(cu.is_allow_feeds,"") as is_allow_feeds,IF(c.club_image IS NULL or c.club_image ="","'.$defaultClubImage.'",concat("'.$clubImageUrl.'",c.club_image)) as club_image,IF(c.club_icon IS NULL or c.club_icon ="","'.$defaultClubIcon.'",concat("'.$clubIconUrl.'",c.club_icon)) as club_icon,(case 
                when (u.profile_image = "") 
                    THEN "'.$defaultUserImg.'"
                when (u.profile_image != "" && u.is_profile_url = 1) 
                    THEN u.profile_image
                ELSE
                    concat("'.$userImg.'",u.profile_image) 
            END) as profile_image');
        }else{
        	$this->db->select('c.*,cc.club_category_name,u.full_name,COALESCE(cu.club_user_status,"") as club_user_status,COALESCE(cu.is_allow_feeds,"") as is_allow_feeds,IF(c.club_image IS NULL or c.club_image ="","'.$defaultClubImage.'",concat("'.$clubImageUrl.'",c.club_image)) as club_image,IF(c.club_icon IS NULL or c.club_icon ="","'.$defaultClubIcon.'",concat("'.$clubIconUrl.'",c.club_icon)) as club_icon,"" as distance,(case 
                when (u.profile_image = "") 
                    THEN "'.$defaultUserImg.'"
                when (u.profile_image != "" && u.is_profile_url = 1) 
                    THEN u.profile_image
                ELSE
                    concat("'.$userImg.'",u.profile_image) 
            END) as profile_image');
        } 	
    	$this->db->from(CLUBS.' as c');
		$this->db->join(CLUB_CATEGORY.' as cc','c.club_category_id = cc.clubCategoryId'); 
		$this->db->join(USERS.' as u','u.userId = c.user_id'); 
        $this->db->join(CLUB_USER_MAPPING.' as cu','cu.club_id = c.clubId AND cu.user_id = "'.$data['userId'].'" ','left');

		$this->db->where($where);
        $this->db->where('(cu.club_user_status IS NULL or cu.club_user_status = "0")');
		if(!empty($data['searchText'])){
			$this->db->like('c.club_name',$data['searchText']);
		}
		if(!empty($data['latitude']) && !empty($data['longitude'])){
			$this->db->having('distance <=',$radius);
		}
		$this->db->limit($data['limit'],$data['offset']);
		if(!empty($data['latitude']) && !empty($data['longitude'])){
			$this->db->order_by('distance');
		}
		$query = $this->db->get();
		if($query->num_rows() >0){
        	
            $clubData = $query->result();
            return $clubData;
    	}

    }//End function

    //for searching all club including my c;lub and near by
    public function searchClub($data,$userId){

        if(!isset($data['offset']) || empty($data['limit'])){
            $data['offset'] = 0; $data['limit']= 10; 
        }
        $where = array('c.status' => '1','cc.status' => '1','u.status'=>'1');
        if(!empty($data['city'])){
            $where['c.club_city'] = $data['city']; 
        }
        if($data['clubType'] == '1'){
            $where['c.club_type'] = '1';//public club
        }elseif($data['clubType'] == '2'){
            $where['c.club_type'] = '2';//private club
        }
        $clubImageUrl = base_url().CLUB_MAIN_IMAGE;
        $defaultClubImage = base_url().DEFAULT_CLUB_IMAGE;

        $clubIconUrl = base_url().CLUB_ICON;
        $defaultClubIcon = base_url().DEFAULT_CLUB_ICON;

        $defaultUserImg = base_url().USER_DEFAULT_IMAGE;
        $userImg = base_url().USER_MAIN_IMAGE;
        $radius = 300;//kilometers
        if((!empty($data['latitude']) && !empty($data['longitude']))){

            $this->db->select('c.*,( 6371 * acos ( cos ( radians('.$data['latitude'].') ) * cos( radians( club_latitude ) ) * cos( radians( club_longitude ) - radians('.$data['longitude'].') ) + sin ( radians('.$data['latitude'].') ) * sin( radians( club_latitude ) ) ) ) AS distance,cc.club_category_name,u.full_name,COALESCE(cu.club_user_status,"") as club_user_status,COALESCE(cu.is_allow_feeds,"") as is_allow_feeds,IF(c.club_image IS NULL or c.club_image ="","'.$defaultClubImage.'",concat("'.$clubImageUrl.'",c.club_image)) as club_image,IF(c.club_icon IS NULL or c.club_icon ="","'.$defaultClubIcon.'",concat("'.$clubIconUrl.'",c.club_icon)) as club_icon,(case 
                when (u.profile_image = "") 
                    THEN "'.$defaultUserImg.'"
                when (u.profile_image != "" && u.is_profile_url = 1) 
                    THEN u.profile_image
                ELSE
                    concat("'.$userImg.'",u.profile_image) 
            END) as profile_image');   
        }else{
            $this->db->select('c.*,cc.club_category_name,u.full_name,COALESCE(cu.club_user_status,"") as club_user_status,COALESCE(cu.is_allow_feeds,"") as is_allow_feeds,IF(c.club_image IS NULL or c.club_image ="","'.$defaultClubImage.'",concat("'.$clubImageUrl.'",c.club_image)) as club_image,IF(c.club_icon IS NULL or c.club_icon ="","'.$defaultClubIcon.'",concat("'.$clubIconUrl.'",c.club_icon)) as club_icon,"" as distance,(case 
                when (u.profile_image = "") 
                    THEN "'.$defaultUserImg.'"
                when (u.profile_image != "" && u.is_profile_url = 1) 
                    THEN u.profile_image
                ELSE
                    concat("'.$userImg.'",u.profile_image) 
            END) as profile_image');
        }
        $this->db->from(CLUBS.' as c');
        $this->db->join(CLUB_CATEGORY.' as cc','c.club_category_id = cc.clubCategoryId'); 
        $this->db->join(USERS.' as u','u.userId = c.user_id'); 
        $this->db->join(CLUB_USER_MAPPING.' as cu','cu.club_id = c.clubId AND cu.user_id = "'.$userId.'" ','left');
        $this->db->where($where);
        $this->db->like('c.club_name',$data['searchText']);
        if(!empty($data['latitude']) && !empty($data['longitude'])){
            $this->db->having('distance <=',$radius);
        }
        $this->db->limit($data['limit'],$data['offset']);
        if(!empty($data['latitude']) && !empty($data['longitude'])){
            $this->db->order_by('distance');
        }

        $query = $this->db->get();
        if($query->num_rows() >0){
            
            $clubData = $query->result();
            return $clubData;
        }

    }//End function


    public function getClubNames($data){

        if($data['isMyClub'] == '1'){
            $res = $this->myClubsName($data);
        }elseif($data['isMyClub'] == '0'){
            $res = $this->nearByClubsName($data);
        }else{
            $res = $this->allClubsName($data);
        }
        return $res;
    }


    //for searching all club including my c;lub and near by
    public function allClubsName($data){

        if(!isset($data['offset']) || empty($data['limit'])){
            $data['offset'] = 0; $data['limit']= 10; 
        }
        $where = array('c.status' => '1','cc.status' => '1','u.status'=>'1');
        if(!empty($data['city'])){
            $where['c.club_city'] = $data['city']; 
        }
        if(isset($data['clubType']) && $data['clubType'] == '1'){
            $where['c.club_type'] = '1';//public club
        }elseif(isset($data['clubType']) && $data['clubType'] == '2'){
            $where['c.club_type'] = '2';//private club
        }
       
        $radius = 300;//kilometers
        if((!empty($data['latitude']) && !empty($data['longitude']))){

            $this->db->select('c.club_name,c.club_type,( 6371 * acos ( cos ( radians('.$data['latitude'].') ) * cos( radians( club_latitude ) ) * cos( radians( club_longitude ) - radians('.$data['longitude'].') ) + sin ( radians('.$data['latitude'].') ) * sin( radians( club_latitude ) ) ) ) AS distance');   
        }else{
            $this->db->select('c.club_name,c.club_type,"" as distance');
        }
        $this->db->from(CLUBS.' as c');
        $this->db->join(CLUB_CATEGORY.' as cc','c.club_category_id = cc.clubCategoryId'); 
        $this->db->join(USERS.' as u','u.userId = c.user_id'); 
        $this->db->join(CLUB_USER_MAPPING.' as cu','cu.club_id = c.clubId AND cu.user_id = "'.$data['userId'].'" ','left');
        $this->db->where($where);
        !empty($data['searchText']) && isset($data['searchText']) ? $this->db->like('c.club_name',$data['searchText']) : '';
        if(!empty($data['latitude']) && !empty($data['longitude'])){
            $this->db->having('distance <=',$radius);
        }
        $this->db->limit($data['limit'],$data['offset']);
        if(!empty($data['latitude']) && !empty($data['longitude'])){
            $this->db->order_by('distance');
        }
        $query = $this->db->get();
        if($query->num_rows() >0){
            
            $clubData = $query->result();
            return $clubData;
        }

    }//End function


    //To get names of near by clubs from the user
    public function nearByClubsName($data){

        $where = array('c.status' => '1','cc.status' => '1','u.status'=>'1','c.user_id !='=>$data['userId']);
        if(!empty($data['city'])){
            $where['c.club_city'] = $data['city'];
        }

        if(isset($data['clubType']) && $data['clubType'] == '1'){
            $where['c.club_type'] = '1';//public club
        }elseif(isset($data['clubType']) && $data['clubType'] == '2'){
            $where['c.club_type'] = '2';//private club
        }
        
        if(!empty($data['latitude']) && !empty($data['longitude'])){
            $radius = 300;//kilometers
            $this->db->select('club_name,c.club_type,( 6371 * acos ( cos ( radians('.$data['latitude'].') ) * cos( radians( club_latitude ) ) * cos( radians( club_longitude ) - radians('.$data['longitude'].') ) + sin ( radians('.$data['latitude'].') ) * sin( radians( club_latitude ) ) ) ) AS distance');
        }else{
            $this->db->select('club_name,c.club_type,"" as distance');
        }   
        $this->db->from(CLUBS.' as c');
        $this->db->join(CLUB_CATEGORY.' as cc','c.club_category_id = cc.clubCategoryId'); 
        $this->db->join(USERS.' as u','u.userId = c.user_id'); 
        $this->db->join(CLUB_USER_MAPPING.' as cu','cu.club_id = c.clubId AND cu.user_id = "'.$data['userId'].'" ','left');
        $this->db->where($where);
        $this->db->where('(cu.club_user_status IS NULL or cu.club_user_status = "0")');
        !empty($data['searchText']) && isset($data['searchText']) ? $this->db->like('c.club_name',$data['searchText']) : '';

        $this->db->group_by('c.club_name');
        if(!empty($data['latitude']) && !empty($data['longitude'])){
            $this->db->having('distance <=',$radius);
        }
        if(!empty($data['latitude']) && !empty($data['longitude'])){
            $this->db->order_by('distance');
        }
        $query = $this->db->get();
        if($query->num_rows() >0){
            
            $clubData = $query->result();
            return $clubData;
        }

    }//End function


    //To get names of user created clubs
    public function myClubsNameOld($data){

        $where = array('c.user_id'=>$data['userId'],'c.status' => '1','cc.status' => '1');
        $this->db->select('c.club_name,c.club_type,"" as distance');
        $this->db->from(CLUBS.' as c'); 
        $this->db->join(CLUB_CATEGORY.' as cc','c.club_category_id = cc.clubCategoryId'); 
        $this->db->where($where);
        $this->db->group_by('c.clubId');
        $this->db->order_by('c.clubId','desc');
        $query = $this->db->get();
        if($query->num_rows() >0){
            
            $clubData = $query->result();
            return $clubData;
        }

    }//End function

    public function myClubsName($data){
         
        if(isset($data['clubType']) && $data['clubType'] == '1'){
           $clubType = "1";
        }elseif(isset($data['clubType']) && $data['clubType'] == '2'){
            $clubType = '2';//private club
        } 
        $where = "";
        $search = "";
        if(isset($clubType)){
            $where = "AND c.club_type = '".$clubType."'" ;
        }
        if(!empty($data['searchText'])){
            $search = "AND c.club_name LIKE '%".$data['searchText']."%'";
        }
        
        $q = 'SELECT cl.club_name,cl.club_type,"" as distance FROM 
            (SELECT c.*
        FROM '.CLUBS.' as c
        LEFT JOIN '.CLUB_USER_MAPPING.' as cum ON c.clubId = cum.club_id
        JOIN '.USERS.' as u ON c.user_id = u.userId
       
        WHERE  (
        c.user_id = "'.$data['userId'].'"
        OR (cum.user_id = "'.$data['userId'].'" AND cum.club_user_status = "1" AND cum.member_status = "1")
        )
        AND c.status = "1"
        AND u.status = "1" '.$where.' '.$search.'
        GROUP BY c.clubId
        ) as cl
        LEFT JOIN '.CLUB_USER_MAPPING.' as cum1 ON cl.clubId = cum1.club_id
        JOIN '.CLUB_CATEGORY.' as cc ON cl.club_category_id = cc.clubCategoryId
        LEFT JOIN '.USERS.' as u1 ON cum1.user_id = u1.userId AND u1.status = "1"
        GROUP BY cl.clubId
        ORDER BY cl.clubId DESC';
        $result = $this->common_model->custom_query($q);
        return $result;
    }


    public function clubDetail($clubId){

        $clubImageUrl = base_url().CLUB_MAIN_IMAGE;
        $defaultClubImage = base_url().DEFAULT_CLUB_IMAGE;

        $defaultUserImg = base_url().USER_DEFAULT_IMAGE;
        $userImg = base_url().USER_MAIN_IMAGE;

        $clubIconUrl = base_url().CLUB_ICON;
        $defaultClubIcon = base_url().DEFAULT_CLUB_ICON;

        $where = array('c.clubId'=>$clubId);
        $this->db->select('c.*,cc.club_category_name,count(u1.userId) as members,IF(c.club_image IS NULL or c.club_image ="","'.$defaultClubImage.'",concat("'.$clubImageUrl.'",c.club_image)) as club_image,IF(c.club_icon IS NULL or c.club_icon ="","'.$defaultClubIcon.'",concat("'.$clubIconUrl.'",c.club_icon)) as club_icon,u.full_name,(case 

                when (u.profile_image = "") 
                    THEN "'.$defaultUserImg.'"
                when (u.profile_image != "" && u.is_profile_url = 1) 
                    THEN u.profile_image
                ELSE
                    concat("'.$userImg.'",u.profile_image) 
            END) as profile_image');
        $this->db->from(CLUBS.' as c');
        $this->db->join(CLUB_CATEGORY.' as cc','c.club_category_id = cc.clubCategoryId'); 
        $this->db->join(CLUB_USER_MAPPING.' as cum','c.clubId = cum.club_id AND cum.club_user_status="1"','left'); 
        $this->db->join(USERS.' as u','c.user_id = u.userId AND u.status="1"','left');
        $this->db->join(USERS.' as u1','cum.user_id = u1.userId AND u1.status="1"','left'); 
        $this->db->where($where);
        $query = $this->db->get();
        if($query->num_rows()){

            $res = $query->row();
            return $res;
        }
    }//End function


    public function myClubsOld($userId,$data,$searchText){

        if(!isset($data['offset']) || empty($data['limit'])){
            $data['offset'] = 0; $data['limit']= 10; 
        }
        $clubImageUrl = base_url().CLUB_MAIN_IMAGE;
        $defaultClubImage = base_url().DEFAULT_CLUB_IMAGE;

        $clubIconUrl = base_url().CLUB_ICON;
        $defaultClubIcon = base_url().DEFAULT_CLUB_ICON;

        $where = array('c.status' => '1','cc.status' => '1');
        $this->db->select('c.*,cc.club_category_name,count(u.userId) as members,IF(c.club_image IS NULL or c.club_image ="","'.$defaultClubImage.'",concat("'.$clubImageUrl.'",c.club_image)) as club_image,IF(c.club_icon IS NULL or c.club_icon ="","'.$defaultClubIcon.'",concat("'.$clubIconUrl.'",c.club_icon)) as club_icon');
        $this->db->from(CLUBS.' as c');
        $this->db->join(CLUB_CATEGORY.' as cc','c.club_category_id = cc.clubCategoryId'); 
        $this->db->join(CLUB_USER_MAPPING.' as cum','c.clubId = cum.club_id AND cum.club_user_status="1"','left'); 
        $this->db->join(USERS.' as u','cum.user_id = u.userId AND u.status ="1"','left'); 
        $this->db->where('c.user_id = "'.$userId.'" or (cum.club_user_status = "1" and cum.user_id= "'.$userId.'")');
        $this->db->where('(case when (cum.user_id = "'.$userId.'") THEN cum.member_status = "1" END)');
        $this->db->where($where);
        if(!empty($searchText)) {
            $this->db->like('c.club_name', $searchText,'after');
        } 
        $this->db->group_by('c.clubId');
        $this->db->order_by('c.clubId','desc');
        empty($searchText) ? $this->db->limit($data['limit'],$data['offset']) : '';
        $query = $this->db->get();
        if($query->num_rows() >0){
            
            $clubData = $query->result();
            return $clubData;
        }

    }//End function


    public function myClubs($userId,$data,$searchText){

        if(!isset($data['offset']) || empty($data['limit'])){
            $data['offset'] = 0; $data['limit']= 10; 
        }
        $clubType = "";
        if(!empty($data['clubType']) && $data['clubType'] == '1'){
            $clubType = 'AND (c.club_type = "1" or c.club_type = "3")';
        }elseif(!empty($data['clubType']) && $data['clubType'] == '2'){
            $clubType = 'AND c.club_type = "2"';
        }
        
        $clubImageUrl = base_url().CLUB_MAIN_IMAGE;
        $defaultClubImage = base_url().DEFAULT_CLUB_IMAGE;

        $clubIconUrl = base_url().CLUB_ICON;
        $defaultClubIcon = base_url().DEFAULT_CLUB_ICON;

        $defaultUserImg = base_url().USER_DEFAULT_IMAGE;
        $userImg = base_url().USER_MAIN_IMAGE;

        $q = 'SELECT cl.*,(case 
                when (cl.profile_image = "") 
                    THEN "'.$defaultUserImg.'"
                when (cl.profile_image != "" && cl.is_profile_url = 1) 
                    THEN cl.profile_image
                ELSE
                    concat("'.$userImg.'",cl.profile_image) 
            END) as profile_image,COALESCE(cum1.club_user_status,"") as club_user_status,cum1.clubUserId,cum1.is_allow_feeds,cc.club_category_name,IF(cl.club_image IS NULL or cl.club_image ="","'.$defaultClubImage.'",concat("'.$clubImageUrl.'",cl.club_image)) as club_image,IF(cl.club_icon IS NULL or cl.club_icon ="","'.$defaultClubIcon.'",concat("'.$clubIconUrl.'",cl.club_icon)) as club_icon,count(u1.userId) as members FROM 
            (SELECT c.*,u.full_name,u.profile_image,u.is_profile_url
        FROM '.CLUBS.' as c
        LEFT JOIN '.CLUB_USER_MAPPING.' as cum ON c.clubId = cum.club_id
        JOIN '.USERS.' as u ON c.user_id = u.userId
       
        WHERE  (
        c.user_id = "'.$userId.'"
        OR (cum.user_id = "'.$userId.'" AND cum.club_user_status = "1" AND cum.member_status = "1")
         )
        AND c.status = "1"
        AND u.status = "1" '.$clubType.'
        GROUP BY c.clubId
        ) as cl
        LEFT JOIN '.CLUB_USER_MAPPING.' as cum1 ON cl.clubId = cum1.club_id
        JOIN '.CLUB_CATEGORY.' as cc ON cl.club_category_id = cc.clubCategoryId
        LEFT JOIN '.USERS.' as u1 ON cum1.user_id = u1.userId AND u1.status = "1"
        GROUP BY cl.clubId
        ORDER BY cl.clubId DESC
        LIMIT '.$data['offset'].' , '.$data['limit'].' ';
        $result = $this->common_model->custom_query($q);
        return $result;
    }


    public function getClubMembers($userId,$data){

        if(!isset($data['offset']) || empty($data['limit'])){
            $data['offset'] = 0; $data['limit']= 10; 
        }
        $defaultUserImg = base_url().USER_DEFAULT_IMAGE;
        $userImg = base_url().USER_MAIN_IMAGE;
        $where = array('cum.club_id'=>$data['clubId'],'cum.club_user_status' => '1','u.status' => '1');
        $this->db->select('COALESCE(GROUP_CONCAT(ut.tag_name),"") as tag_name,cum.clubUserId,u.userId,u.full_name,cum.member_status,cum.user_nickname,(case 

                when (u.profile_image = "") 
                    THEN "'.$defaultUserImg.'"
                when (u.profile_image != "" && u.is_profile_url = 1) 
                    THEN u.profile_image
                ELSE
                    concat("'.$userImg.'",u.profile_image) 
            END) as profile_image');
        $this->db->from(CLUB_USER_MAPPING.' as cum');
        $this->db->join(USERS.' as u','cum.user_id = u.userId'); 
        $this->db->join(USER_TAGS.' as ut','cum.clubUserId = ut.club_user_id','left'); 
        $this->db->where($where);
        $this->db->group_by('cum.clubUserId');
        $this->db->limit($data['limit'],$data['offset']);
        $this->db->order_by('u.full_name','asc');
        $query = $this->db->get();
        if($query->num_rows() >0){
            
            $userData = $query->result();
            return $userData;
        }

    }//End function


    public function newsFeedBookmarkList($userId,$data){

        $clubImageUrl = base_url().CLUB_MAIN_IMAGE;
        $defaultClubImage = base_url().DEFAULT_CLUB_IMAGE;

        $feedAttachUrl = base_url().NEWS_FEEDS_ATTACHMENT;

        $clubIconUrl = base_url().CLUB_ICON;
        $defaultClubIcon = base_url().DEFAULT_CLUB_ICON;

        if(!isset($data['offset']) || empty($data['limit'])){
            $data['offset'] = 0; $data['limit']= 10; 
        }
        $where = array('nfb.user_id'=>$userId,'nf.status'=>'1','c.status'=>'1');
        $this->db->select('nf.newsFeedId,nf.news_feed_title,nf.news_feed_description,nf.crd as datetime,c.club_name,count(nfl.newsFeedsLikeId) as likes,c.comment_count as comments,IF(nfll.news_feed_id IS NULL or nfll.news_feed_id = "", 0, 1) as isLiked,IF(c.club_image IS NULL or c.club_image ="","'.$defaultClubImage.'",concat("'.$clubImageUrl.'",c.club_image)) as club_image,IF(nf.news_feed_attachment IS NULL or nf.news_feed_attachment ="","",concat("'.$feedAttachUrl.'",nf.news_feed_attachment)) as news_feed_attachment,IF(c.club_icon IS NULL or c.club_icon ="","'.$defaultClubIcon.'",concat("'.$clubIconUrl.'",c.club_icon)) as club_icon,nf.crd,now() as currentDateTime');
        $this->db->from(NEWS_FEEDS_BOOKMARKS.' as nfb');
        $this->db->join(NEWS_FEEDS.' as nf','nfb.news_feed_id = nf.newsFeedId'); 
        $this->db->join(CLUBS.' as c','nf.club_id = c.clubId');
        $this->db->join(NEWS_FEEDS_LIKES.' as nfl','nf.newsFeedId = nfl.news_feed_id','left');
        $this->db->join(NEWS_FEEDS_LIKES.' as nfll','nf.newsFeedId = nfll.news_feed_id AND nfll.user_id="'.$this->authData->userId.'"','left');
        $this->db->where($where);
        $this->db->limit($data['limit'],$data['offset']);
        $this->db->group_by('nfb.news_feed_id');
        $this->db->order_by('nfb.newsFeedsBookmarkId','desc');
        $query = $this->db->get();
        if($query->num_rows() >0){

            $bookmarkData = $query->result();
            return $bookmarkData;
        }

    }//End function


    public function newsFeedsList($bookmarks,$likes,$comments,$clubs,$data,$isMyFeed = ''){

        if(isset($isMyFeed) && $isMyFeed == '1'){

            $list = $this->myNewsFeedsList($bookmarks,$likes,$comments,$clubs,$data);
        }else{
            $list = $this->OtherNewsFeedsList($bookmarks,$likes,$comments,$clubs,$data);
        }
        return $list;
    }


    //For getting my feeds
    public function myNewsFeedsList($bookmarks,$likes,$comments,$clubs,$data){

        if(!isset($data['offset']) || empty($data['limit'])){
            $data['offset'] = 0; $data['limit']= 10; 
        }
        
        $clubImageUrl = base_url().CLUB_MAIN_IMAGE;
        $defaultClubImage = base_url().DEFAULT_CLUB_IMAGE;

        $feedAttachUrl = base_url().NEWS_FEEDS_ATTACHMENT;

        $clubIconUrl = base_url().CLUB_ICON;
        $defaultClubIcon = base_url().DEFAULT_CLUB_ICON;

        $defaultUserImg = base_url().USER_DEFAULT_IMAGE;
        $userImg = base_url().USER_MAIN_IMAGE;

        $userId = $this->authData->userId;
        $where = array('nf.status'=>'1','c.status'=>'1','c.user_id'=>$userId);
        $this->db->select('nf.newsFeedId,nf.is_comment_allow,nf.news_feed_title,nf.news_feed_description,nf.crd as datetime,c.club_name,count(distinct nfl.newsFeedsLikeId) as likes,nf.comment_count as comments,count(distinct nfb.newsFeedsBookmarkId) as bookmarks,IF(nfll.news_feed_id IS NULL or nfll.news_feed_id = "", 0, 1) as isLiked,IF(nfbb.news_feed_id IS NULL or nfbb.news_feed_id = "", 0, 1) as isBookmarked,IF(nf.news_feed_attachment IS NULL or nf.news_feed_attachment ="","",concat("'.$feedAttachUrl.'",nf.news_feed_attachment)) as news_feed_attachment,IF(c.club_image IS NULL or c.club_image ="","'.$defaultClubImage.'",concat("'.$clubImageUrl.'",c.club_image)) as club_image,IF(c.club_icon IS NULL or c.club_icon ="","'.$defaultClubIcon.'",concat("'.$clubIconUrl.'",c.club_icon)) as club_icon,nf.crd,now() as currentDateTime,u.userId,COALESCE(GROUP_CONCAT(nft.feed_filter_tag_name),"") as tagName,u.full_name,c.clubId,(case 

                when (u.profile_image = "") 
                    THEN "'.$defaultUserImg.'"
                when (u.profile_image != "" && u.is_profile_url = 1) 
                    THEN u.profile_image
                ELSE
                    concat("'.$userImg.'",u.profile_image) 
            END) as profile_image');

        $this->db->from(NEWS_FEEDS.' as nf');
        $this->db->join(CLUBS.' as c','nf.club_id = c.clubId');
        $this->db->join(USERS.' as u','c.user_id = u.userId');
        $this->db->join(NEWS_FEEDS_LIKES.' as nfl','nf.newsFeedId = nfl.news_feed_id','left');
        $this->db->join(NEWS_FEEDS_LIKES.' as nfll','nf.newsFeedId = nfll.news_feed_id AND nfll.user_id="'.$userId.'"','left');
        $this->db->join(NEWS_FEEDS_BOOKMARKS.' as nfb','nf.newsFeedId = nfb.news_feed_id','left');
        $this->db->join(NEWS_FEEDS_BOOKMARKS.' as nfbb','nf.newsFeedId = nfbb.news_feed_id AND nfbb.user_id="'.$userId.'"','left');
        $this->db->join(NEWS_FEED_FILTER_TAGS_MAPPING.' as nftm','nf.newsFeedId = nftm.news_feed_id','left');
        $this->db->join(NEWS_FEED_FILTER_TAGS.' as nft','nftm.feed_filter_tag_id = nft.feedFilterTagId','left');
        $this->db->where($where);
        $this->db->limit($data['limit'],$data['offset']);
        $this->db->group_by('nf.newsFeedId');
        !empty($bookmarks) ? $this->db->order_by('bookmarks','desc') : '';
        !empty($likes) ? $this->db->order_by('likes','desc') : '';
        !empty($comments) ? $this->db->order_by('comments','desc') : '';
        !empty($clubs) ? $this->db->order_by('c.club_name','asc') : '';
        if(empty($bookmarks) && empty($likes) && empty($comments) && empty($clubs)){
            $this->db->order_by('nf.newsFeedId','desc');
        }
        $query = $this->db->get();
        if($query->num_rows() >0){

            $newsData = $query->result();
            return $newsData;
        }

    }//End function


    //For getting news feeds list of joined clubs
    public function otherNewsFeedsList($bookmarks,$likes,$comments,$clubs,$data){

        if(!isset($data['offset']) || empty($data['limit'])){
            $data['offset'] = 0; $data['limit']= 10; 
        }
        
        $clubImageUrl = base_url().CLUB_MAIN_IMAGE;
        $defaultClubImage = base_url().DEFAULT_CLUB_IMAGE;

        $feedAttachUrl = base_url().NEWS_FEEDS_ATTACHMENT;

        $clubIconUrl = base_url().CLUB_ICON;
        $defaultClubIcon = base_url().DEFAULT_CLUB_ICON;

        $defaultUserImg = base_url().USER_DEFAULT_IMAGE;
        $userImg = base_url().USER_MAIN_IMAGE;

        $userId = $this->authData->userId;
        $where = array('cum.user_id'=>$userId,'cum.member_status'=>'1','cum.is_allow_feeds'=>'1','nf.status'=>'1','c.status'=>'1','cum.club_user_status'=>'1');
        $this->db->select('nf.newsFeedId,nf.is_comment_allow,nf.news_feed_title,nf.news_feed_description,nf.crd as datetime,c.club_name,count(distinct nfl.newsFeedsLikeId) as likes,nf.comment_count as comments,count(distinct nfb.newsFeedsBookmarkId) as bookmarks,IF(nfll.news_feed_id IS NULL or nfll.news_feed_id = "", 0, 1) as isLiked,IF(nfbb.news_feed_id IS NULL or nfbb.news_feed_id = "", 0, 1) as isBookmarked,IF(nf.news_feed_attachment IS NULL or nf.news_feed_attachment ="","",concat("'.$feedAttachUrl.'",nf.news_feed_attachment)) as news_feed_attachment,IF(c.club_image IS NULL or c.club_image ="","'.$defaultClubImage.'",concat("'.$clubImageUrl.'",c.club_image)) as club_image,IF(c.club_icon IS NULL or c.club_icon ="","'.$defaultClubIcon.'",concat("'.$clubIconUrl.'",c.club_icon)) as club_icon,nf.crd,now() as currentDateTime,u.userId,u.full_name,COALESCE(GROUP_CONCAT(nft.feed_filter_tag_name),"") as tagName,c.clubId,(case 

                when (u.profile_image = "") 
                    THEN "'.$defaultUserImg.'"
                when (u.profile_image != "" && u.is_profile_url = 1) 
                    THEN u.profile_image
                ELSE
                    concat("'.$userImg.'",u.profile_image) 
            END) as profile_image');

        $this->db->from(CLUB_USER_MAPPING.' as cum');
        $this->db->join(CLUBS.' as c','cum.club_id = c.clubId');
        $this->db->join(USERS.' as u','c.user_id = u.userId');
        $this->db->join(NEWS_FEEDS.' as nf','nf.club_id = c.clubId');
        $this->db->join(NEWS_FEEDS_LIKES.' as nfl','nf.newsFeedId = nfl.news_feed_id','left');
        $this->db->join(NEWS_FEEDS_LIKES.' as nfll','nf.newsFeedId = nfll.news_feed_id AND nfll.user_id="'.$userId.'"','left');
        $this->db->join(NEWS_FEEDS_BOOKMARKS.' as nfb','nf.newsFeedId = nfb.news_feed_id','left');
        $this->db->join(NEWS_FEEDS_BOOKMARKS.' as nfbb','nf.newsFeedId = nfbb.news_feed_id AND nfbb.user_id="'.$userId.'"','left');
        $this->db->join(NEWS_FEED_FILTER_TAGS_MAPPING.' as nftm','nf.newsFeedId = nftm.news_feed_id','left');
        $this->db->join(NEWS_FEED_FILTER_TAGS.' as nft','nftm.feed_filter_tag_id = nft.feedFilterTagId','left');

        $this->db->where($where);
        $this->db->limit($data['limit'],$data['offset']);
        $this->db->group_by('nf.newsFeedId');
        !empty($bookmarks) ? $this->db->order_by('bookmarks','desc') : '';
        !empty($likes) ? $this->db->order_by('likes','desc') : '';
        !empty($comments) ? $this->db->order_by('comments','desc') : '';
        !empty($clubs) ? $this->db->order_by('c.club_name','asc') : '';
        if(empty($bookmarks) && empty($likes) && empty($comments) && empty($clubs)){
            $this->db->order_by('nf.newsFeedId','desc');
        }
        $query = $this->db->get();
        if($query->num_rows() >0){

            $newsData = $query->result();
            return $newsData;
        }

    }//End function


    //get all applicants for the club
    public function getClubApplicants($clubId,$data){

        if(!isset($data['offset']) || empty($data['limit'])){
            $data['offset'] = 0; $data['limit']= 10; 
        }
        $defaultUserImg = base_url().USER_DEFAULT_IMAGE;
        $userImg = base_url().USER_MAIN_IMAGE;
        $where = array('cum.club_id'=>$clubId,'cum.club_user_status' => '0','u.status' => '1');
        $this->db->select('( 6371 * acos ( cos ( radians('.$data['clubLatitude'].') ) * cos( radians( latitude ) ) * cos( radians(longitude ) - radians('.$data['clubLongitude'].') ) + sin ( radians('.$data['clubLatitude'].') ) * sin( radians(latitude ) ) ) ) AS distance,u.userId,u.full_name,cum.crd as requestDateTime,(case 

                when (u.profile_image = "") 
                    THEN "'.$defaultUserImg.'"
                when (u.profile_image != "" && u.is_profile_url = 1) 
                    THEN u.profile_image
                ELSE
                    concat("'.$userImg.'",u.profile_image) 
            END) as profile_image');

        $this->db->from(CLUB_USER_MAPPING.' as cum');
        $this->db->join(USERS.' as u','cum.user_id = u.userId'); 
        $this->db->where($where);
        $this->db->limit($data['limit'],$data['offset']);
        $this->db->order_by('u.full_name','asc');
        $query = $this->db->get();
        if($query->num_rows() >0){
            
            $userData = $query->result();
            return $userData;
        }
    }//End function

    //For get joined clubs
    public function joinedClubs($userId,$data){

        if(!isset($data['offset']) || empty($data['limit'])){
            $data['offset'] = 0; $data['limit']= 10; 
        }
        $clubImageUrl = base_url().CLUB_MAIN_IMAGE;
        $defaultClubImage = base_url().DEFAULT_CLUB_IMAGE;

        $clubIconUrl = base_url().CLUB_ICON;
        $defaultClubIcon = base_url().DEFAULT_CLUB_ICON;

        $where = array('cum1.user_id'=>$userId,'cum1.club_user_status'=>'1','cum1.member_status'=>'1','c.status' => '1','cc.status' => '1');
        $this->db->select('c.*,u.full_name,cc.club_category_name,count(u1.userId) as members,IF(c.club_image ="","'.$defaultClubImage.'",concat("'.$clubImageUrl.'",c.club_image)) as club_image,IF(c.club_icon ="","'.$defaultClubIcon.'",concat("'.$clubIconUrl.'",c.club_icon)) as club_icon');
        $this->db->from(CLUB_USER_MAPPING.' as cum1');
        $this->db->join(CLUBS.' as c','cum1.club_id = c.clubId'); 
        $this->db->join(CLUB_USER_MAPPING.' as cum2','c.clubId = cum2.club_id AND cum2.club_user_status="1"','left');
        $this->db->join(USERS.' as u','c.user_id = u.userId'); 
        $this->db->join(CLUB_CATEGORY.' as cc','c.club_category_id = cc.clubCategoryId'); 
        $this->db->join(USERS.' as u1','cum2.user_id = u1.userId AND u1.status="1"','left');
        $this->db->where($where);
        $this->db->limit($data['limit'],$data['offset']);
        $this->db->group_by('c.clubId');
        $this->db->order_by('cum1.club_id','desc');
        $query = $this->db->get();
        if($query->num_rows() >0){
            
            $clubData = $query->result();
            return $clubData;
        }

    }//End function


    //For get news feed detail
    public function newsFeedDetail($newsFeedId){

        $feedAttachUrl = base_url().NEWS_FEEDS_ATTACHMENT;
        $where = array('nf.newsFeedId'=>$newsFeedId);
        $this->db->select('IF(nf.news_feed_attachment = "" || nf.news_feed_attachment IS NULL,"",concat("'.$feedAttachUrl.'",nf.news_feed_attachment)) as news_feed_attachment,nf.news_feed_title,nf.news_feed_description,nf.is_comment_allow,c.club_name,nf.crd,count(nfl.news_feed_id) as likes,nf.comment_count as comments');
        $this->db->from(NEWS_FEEDS.' as nf');
        $this->db->join(CLUBS.' as c','nf.club_id = c.clubId'); 
        $this->db->join(NEWS_FEEDS_LIKES.' as nfl','nf.newsFeedId = nfl.news_feed_id','left');
        $this->db->where($where);
        $query = $this->db->get();
        if($query->num_rows()){
            $res = $query->row();
            return $res;
        }

    }//End function

    //For get my created clubs name list
    public function myCreatedClubsName($userId){

        $where = array('c.user_id'=>$userId,'c.status' => '1','cc.status' => '1');
        $this->db->select('c.clubId,c.club_name');
        $this->db->from(CLUBS.' as c');
        $this->db->join(CLUB_CATEGORY.' as cc','c.club_category_id = cc.clubCategoryId'); 
        $this->db->where($where);
        $this->db->order_by('c.clubId','desc');
        $query = $this->db->get();
        if($query->num_rows() >0){
            
            $clubData = $query->result();
            return $clubData;
        }
    }//End function


    //For get feed tags
    public function allNewsFilterTags($searchText){

        $where = array('nft.status' => '1');
        $this->db->select('nft.feedFilterTagId,nft.feed_filter_tag_name');
        $this->db->from(NEWS_FEED_FILTER_TAGS.' as nft');
        $this->db->where($where);
        $this->db->like('nft.feed_filter_tag_name',$searchText,'after');
        $this->db->order_by('nft.feedFilterTagId','desc');
        $this->db->limit(20,0);
        $query = $this->db->get();
        if($query->num_rows() >0){
            
            $data = $query->result();
            return $data;
        }
    }//End function


    //For add news feed tags
    public function addNewsFilterTags($feedId,$tagName){

        $array = explode(",", $tagName);
        $new = array();
        foreach ($array as $key => $value) {
            
            $data['news_feed_id'] = $feedId;
            $tagName = ucwords($value);
         
            $where = array('feed_filter_tag_name'=>$tagName);
            $mainData['feed_filter_tag_name'] = $tagName;
            $mainData['crd'] = $mainData['upd'] = date('Y-m-d H:i:s');
            $isTagExist = $this->common_model->is_data_exists(NEWS_FEED_FILTER_TAGS,$where);
            if($isTagExist){
                $tagData = $this->common_model->getsingle(NEWS_FEED_FILTER_TAGS,$where);
                $tagId = $tagData->feedFilterTagId;
            }else{
                $tagId = $this->common_model->insert_data(NEWS_FEED_FILTER_TAGS,$mainData);
            }
            $data['feed_filter_tag_id'] = $tagId;
            $isMappingExist = $this->common_model->is_data_exists(NEWS_FEED_FILTER_TAGS_MAPPING,$data);
            if(!$isMappingExist){
                 $data['crd'] = $data['upd'] = date('Y-m-d H:i:s');
                $ins = $this->common_model->insert_data(NEWS_FEED_FILTER_TAGS_MAPPING,$data);
            } 
        }

    }//End function



}
