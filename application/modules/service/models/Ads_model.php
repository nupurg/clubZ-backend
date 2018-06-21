<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ads_model extends CI_Model {

	function adsList($userId,$data){

        $ads = array(); 
        switch($data['listType']){ //For get ads list
            case 'fav' : 
            $ads['fav'] = $this->favAdsList($userId,$data);
            break;

            case 'recent' : 
            $ads['recent'] = $this->recentAdsList($userId,$data);
            break;

            case 'all' :
            $ads['all'] = $this->allAdsList($userId,$data);
            break;

            default:
            $ads['fav'] = $this->favAdsList($userId,$data);
            $ads['recent'] = $this->recentAdsList($userId,$data);
            $ads['all'] = $this->allAdsList($userId,$data);  

        }//End of ads list

        return $ads;
	}


	//For getting favorite ads list
    function favAdsList($userId,$data){

        $adData = array();
        $adImg = base_url().AD_IMAGE;
        if(empty($data['limit']) && empty($data['offset'])){
            $data['offset'] = 0; $data['limit']= 5; 
        }
        $where1 = array('c.status'=>'1','cc.status'=>'1','a.status'=>1,'fa.user_id'=>$userId);
        $where2 = "(a.is_renew = 1 or (CURDATE() <= DATE_ADD(a.crd, INTERVAL 30 DAY)))"; 
        $this->db->select('a.adId,a.title,a.fee,a.is_renew,a.description,a.club_id,a.user_id,a.user_role,a.crd,IF(a.image IS NULL or a.image = "","",concat("'.$adImg.'",a.image)) as image,c.club_name,u.full_name,NOW() as currentDatetime');
        $this->db->from(FAVORITE_ADS.' as fa');
        $this->db->join(ADS.' as a','a.adId = fa.ad_id');
        $this->db->join(USERS.' as u','a.user_id = u.userId');
        $this->db->join(CLUBS.' as c','c.clubId = a.club_id');
        $this->db->join(CLUB_CATEGORY.' as cc','c.club_category_id = cc.clubCategoryId'); 
        $this->db->where($where1);
        $this->db->where($where2);
        $this->db->order_by("fa.favoriteAdId",'DESC');
        $this->db->limit($data['limit'],$data['offset']);
        $query = $this->db->get();
        if($query->num_rows() >0){
            $adData = $query->result();
        }
        return $adData;

    }//End function
    
    //For recent ads list
    function recentAdsList($userId,$data){

        $adData = array();
        $adImg = base_url().AD_IMAGE;
        if(empty($data['limit']) && empty($data['offset'])){
            $data['offset'] = 0; $data['limit']= 5; 
        }
        $where1 = array('c.status'=>'1','cc.status'=>'1','a.status'=>1,'a.user_id !='=>$userId,'cum.club_user_status'=>'1','cum.member_status'=>'1','cum.user_id'=>$userId);
        $where2 = "NOT(fa.favoriteAdId IS NOT NULL) AND (a.is_renew = 1 or (CURDATE() <= DATE_ADD(a.crd, INTERVAL 30 DAY)))";
        $this->db->select('a.adId,a.title,a.fee,a.is_renew,a.description,a.club_id,a.user_id,a.user_role,a.crd,IF(a.image IS NULL or a.image = "","",concat("'.$adImg.'",a.image)) as image,c.club_name,u.full_name,NOW() as currentDatetime');
        $this->db->from(CLUB_USER_MAPPING.' as cum');
        $this->db->join(CLUBS.' as c','cum.club_id = c.clubId');
        $this->db->join(CLUB_CATEGORY.' as cc','c.club_category_id = cc.clubCategoryId'); 
        $this->db->join(ADS.' as a','cum.club_id = a.club_id'); 
        $this->db->join(USERS.' as u','a.user_id = u.userId');
        $this->db->join(FAVORITE_ADS.' as fa','a.adId = fa.ad_id AND fa.user_id = "'.$userId.'"','left');
        $this->db->where($where1);
        $this->db->where($where2);
        $this->db->order_by("a.adId",'DESC');
        $this->db->limit($data['limit'],$data['offset']);
        $query = $this->db->get();
        if($query->num_rows() >0){
            $adData = $query->result();
        }
        return $adData;

    }//End function


    //For all ads list
    function allAdsList($userId,$data){

        $adData = array();
        $adImg = base_url().AD_IMAGE;
        if(empty($data['limit']) && empty($data['offset'])){
            $data['offset'] = 0; $data['limit']= 5; 
        }
        $where1 = array('c.status'=>'1','cc.status'=>'1','a.status'=>1,'a.user_id !='=>$userId,'cum.club_user_status'=>'1','cum.member_status'=>'1','cum.user_id'=>$userId);
        $where2 = "(a.is_renew = 1 or (CURDATE() <= DATE_ADD(a.crd, INTERVAL 30 DAY)))";
        $this->db->select('a.adId,a.title,a.fee,a.is_renew,a.description,a.club_id,a.user_id,a.user_role,a.crd,IF(a.image IS NULL or a.image = "","",concat("'.$adImg.'",a.image)) as image,c.club_name,u.full_name,IF(fa.favoriteAdId IS NULL,0,1) as isFav,NOW() as currentDatetime');
        $this->db->from(CLUB_USER_MAPPING.' as cum');
        $this->db->join(CLUBS.' as c','cum.club_id = c.clubId');
        $this->db->join(CLUB_CATEGORY.' as cc','c.club_category_id = cc.clubCategoryId'); 
        $this->db->join(ADS.' as a','cum.club_id = a.club_id'); 
        $this->db->join(USERS.' as u','a.user_id = u.userId');
        $this->db->join(FAVORITE_ADS.' as fa','a.adId = fa.ad_id AND fa.user_id = "'.$userId.'"','left');
        $this->db->where($where1);
        $this->db->where($where2);
        $this->db->order_by("a.adId",'DESC');
        $this->db->limit($data['limit'],$data['offset']);
        $query = $this->db->get();
        if($query->num_rows() >0){
            $adData = $query->result();
        }
        return $adData;

    }//End function


}
