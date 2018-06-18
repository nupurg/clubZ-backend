<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ClubMembers_list_model extends CI_Model {

    //var $table , $column_order, $column_search , $order =  '';
    var $column_search = array(); //set column field database for datatable searchable
    var $order = array('u.full_name' => 'ASC');  // default order
    var $where = '';
    
    public function __construct(){
        parent::__construct();
    }
    
    public function set_id($clubId=''){
        $this->clubId = $clubId;
    }

    function prepare_query(){
       
        $defaultUserImg = base_url().USER_DEFAULT_IMAGE;
        $userImg = base_url().USER_MAIN_IMAGE;
        $where = array('cum.club_id'=>$this->clubId,'cum.club_user_status' => '1');
        $this->db->select('COALESCE(GROUP_CONCAT(ut.tag_name),"") as tag_name,u.userId,u.full_name,cum.member_status,(case 
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