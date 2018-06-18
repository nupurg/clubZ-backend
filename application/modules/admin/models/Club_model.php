<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');

class Club_model extends CI_Model {

	public function ViewClub($id){

        $limit = 8;$offset = 0;
		$where = array('clubId'=>$id);
		$this->db->select('c.*,cc.club_category_name,u.full_name as ownerName,u.userId as ownerId');
    	$this->db->from(CLUBS.' as c');
		$this->db->join(CLUB_CATEGORY.' as cc','c.club_category_id = cc.clubCategoryId','left'); 
		$this->db->join(USERS.' as u','u.userId = c.user_id','left'); 
		$this->db->where($where);
		$query = $this->db->get();
		if($query->num_rows() >0){
        	
            $clubData = $query->row();
            if(!empty($clubData->club_image)){
                $clubData->club_image = base_url().CLUB_RESIZE_IMAGE.$clubData->club_image;
            }else{
                $clubData->club_image = base_url().DEFAULT_CLUB_IMAGE;
            }
            $w = array('club_id'=>$id,'club_user_status'=>'1');//joined users
            $w1 = array('club_id'=>$id,'club_user_status'=>'0');//pending users
            $clubData->joined_users = $this->common_model->get_total_count(CLUB_USER_MAPPING,$w);
            $clubData->pending_users = $this->common_model->get_total_count(CLUB_USER_MAPPING,$w1);
            $clubData->feeds = $this->getFeedsList($id,$limit,$offset);
            $totalFeeds = $this->countFeeds($id);
            $nextOffset = $limit + $offset;
            $isNext = 0;
            if($totalFeeds > $nextOffset){
                $isNext = 1;
            }
            $clubData->nextOffset = $nextOffset;
            $clubData->isNext = $isNext;
            return $clubData;
    	}
	}

    function prepareFeedsList($clubId){

        $clubImageUrl = base_url().CLUB_MAIN_IMAGE;
        $defaultClubImage = base_url().DEFAULT_CLUB_IMAGE;

        $feedAttachUrl = base_url().NEWS_FEEDS_ATTACHMENT;
        $defaultNewsFeed = base_url().DEFAULT_FEED_ATTACH;

        $clubIconUrl = base_url().CLUB_ICON;
        $defaultClubIcon = base_url().DEFAULT_CLUB_ICON;

        $where = array('nf.club_id'=>$clubId);
        $this->db->select('nf.newsFeedId,nf.news_feed_title,nf.news_feed_description,nf.crd as datetime,c.club_name,count(distinct nfl.newsFeedsLikeId) as likes,nf.comment_count as comments,IF(nf.news_feed_attachment IS NULL or nf.news_feed_attachment ="","'.$defaultNewsFeed.'",concat("'.$feedAttachUrl.'",nf.news_feed_attachment)) as news_feed_attachment,IF(c.club_image IS NULL or c.club_image ="","'.$defaultClubImage.'",concat("'.$clubImageUrl.'",c.club_image)) as club_image,IF(c.club_icon IS NULL or c.club_icon ="","'.$defaultClubIcon.'",concat("'.$clubIconUrl.'",c.club_icon)) as club_icon,nf.crd');
        $this->db->from(NEWS_FEEDS.' as nf');
        $this->db->join(CLUBS.' as c','nf.club_id = c.clubId');
        $this->db->join(NEWS_FEEDS_LIKES.' as nfl','nf.newsFeedId = nfl.news_feed_id','left');
        $this->db->where($where);
        $this->db->group_by('nf.newsFeedId');  
    }


    function getFeedsList($clubId,$limit,$offset){

        $this->prepareFeedsList($clubId);
        $this->db->limit($limit,$offset);
        $this->db->order_by('nf.newsFeedId','desc');
        $query = $this->db->get();
        if($query->num_rows() >0){
            $newsData = $query->result();
            return $newsData;
        }
    }

    function countFeeds($clubId){

        $this->prepareFeedsList($clubId);
        $count = $this->db->count_all_results();
        return $count;
    }

    function feedDetail($feedId){

        $feedAttachUrl = base_url().NEWS_FEEDS_ATTACHMENT;
        $defaultNewsFeed = base_url().DEFAULT_FEED_ATTACH;

        $clubIconUrl = base_url().CLUB_ICON;
        $defaultClubIcon = base_url().DEFAULT_CLUB_ICON;

        $where = array('nf.newsFeedId'=>$feedId);
        $this->db->select('IF(nf.news_feed_attachment = "" || nf.news_feed_attachment IS NULL,"",concat("'.$feedAttachUrl.'",nf.news_feed_attachment)) as news_feed_attachment,nf.news_feed_title,nf.news_feed_description,nf.is_comment_allow,c.club_name,nf.crd,count(nfl.news_feed_id) as likes,nf.comment_count as comments,IF(c.club_icon IS NULL or c.club_icon ="","'.$defaultClubIcon.'",concat("'.$clubIconUrl.'",c.club_icon)) as club_icon');
        $this->db->from(NEWS_FEEDS.' as nf');
        $this->db->join(CLUBS.' as c','nf.club_id = c.clubId'); 
        $this->db->join(NEWS_FEEDS_LIKES.' as nfl','nf.newsFeedId = nfl.news_feed_id','left');
        $this->db->where($where);
        $query = $this->db->get();
        if($query->num_rows()){
            $res = $query->row();
            $res->crd = date('M d, Y',strtotime($res->crd));
            return $res;
        }
    }


}

