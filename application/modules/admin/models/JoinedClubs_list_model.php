<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JoinedClubs_list_model extends CI_Model {

    //var $table , $column_order, $column_search , $order =  '';
    var $column_search = array(); //set column field database for datatable searchable
    var $order = array('cum1.club_id' => 'DESC');  // default order
    var $where = '';
    
    public function __construct(){
        parent::__construct();
    }
    
    public function set_userId($userId=''){
        $this->userId = $userId;
    }

    function prepare_query(){
       
        $clubImageUrl = base_url().CLUB_MAIN_IMAGE;
        $defaultClubImage = base_url().DEFAULT_CLUB_IMAGE;

        $clubIconUrl = base_url().CLUB_ICON;
        $defaultClubIcon = base_url().DEFAULT_CLUB_ICON;

        $where = array('cum1.user_id'=>$this->userId,'cum1.club_user_status'=>'1','cum1.member_status'=>'1','c.status' => '1','cc.status' => '1');
        $this->db->select('c.*,u.full_name,cc.club_category_name,count(cum2.clubUserId) as members,IF(c.club_image ="","'.$defaultClubImage.'",concat("'.$clubImageUrl.'",c.club_image)) as club_image_url,IF(c.club_icon ="","'.$defaultClubIcon.'",concat("'.$clubIconUrl.'",c.club_icon)) as club_icon_url');
        $this->db->from(CLUB_USER_MAPPING.' as cum1');
        $this->db->join(CLUBS.' as c','cum1.club_id = c.clubId'); 
        $this->db->join(CLUB_USER_MAPPING.' as cum2','c.clubId = cum2.club_id AND cum2.club_user_status="1"','left');
        $this->db->join(USERS.' as u','c.user_id = u.userId'); 
        $this->db->join(CLUB_CATEGORY.' as cc','c.club_category_id = cc.clubCategoryId'); 
        $this->db->where($where);
        $this->db->group_by('c.clubId');    
    } 
   
    //prepare post list query
    private function posts_get_query(){

        $this->prepare_query();
        $i = 0;
        foreach ($this->column_search as $emp) // loop column 
        {
            if(isset($_POST['search']['value']) && !empty($_POST['search']['value'])){
                $_POST['search']['value'] = $_POST['search']['value'];
            } else
                $_POST['search']['value'] = '';

            if($_POST['search']['value']) // if datatable send POST for search
            {
                if($i===0) // first loop
                {
                    $this->db->group_start();
                    $this->db->like(($emp), $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like(($emp), $_POST['search']['value']);
                }

                if(count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
            }
            if(!empty($this->where))
                $this->db->where($this->where); 

            $count_val = count($_POST['columns']);
            for($i=1;$i<=$count_val;$i++){ 

                if(!empty($_POST['columns'][$i]['search']['value'])){ 
                    $this->db->where(array($this->table_col[$i]=>$_POST['columns'][$i]['search']['value'])); 
                }else if(!empty($_POST['columns'][$i]['search']['value'])){ 
                    $this->db->where(array($this->table_col[$i]=>$_POST['columns'][$i]['search']['value'])); 
                } 
            }



            if(isset($_POST['order'])) // here order processing
            {
                $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
            } 
            else if(isset($this->order))
            {
                $order = $this->order;
                $this->db->order_by(key($order), $order[key($order)]);
            }
    }

    function get_list(){

        $this->posts_get_query();
        if(isset($_POST['length']) && $_POST['length'] < 1) {
            $_POST['length']= '10';
        } else
        $_POST['length']= $_POST['length'];
        
        if(isset($_POST['start']) && $_POST['start'] > 1) {
            $_POST['start']= $_POST['start'];
        }
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get(); 
        return $query->result();
    }

    function count_filtered(){

        $this->posts_get_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all(){

        $this->prepare_query();
        return $this->db->count_all_results();
    }

}