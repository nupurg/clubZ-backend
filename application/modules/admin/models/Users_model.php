<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users_model extends CI_Model {

    //var $table , $column_order, $column_search , $order =  '';
    var $table = USERS;
    var $column_order = array('userId','profile_image','full_name','email','social_type','status'); //set column field database for datatable orderable
    var $column_search = array('full_name'); //set column field database for datatable searchable
    var $order = array('userId' => 'DESC');  // default order
    var $where = '';
    
    public function __construct(){
        parent::__construct();
    }
    
    public function set_data($where=''){
        $this->where = $where;
    }
   
    //prepare post list query
    private function posts_get_query(){

        $sel_fields = array_filter($this->column_order);
        $this->db->select($sel_fields);
        $this->db->from($this->table);
        
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

    function get_list()
    {
        $this->posts_get_query();
		if(isset($_POST['length']) && $_POST['length'] < 1) {
			$_POST['length']= '10';
		} else
		$_POST['length']= $_POST['length'];
		
		if(isset($_POST['start']) && $_POST['start'] > 1) {
			$_POST['start']= $_POST['start'];
		}
        $this->db->limit($_POST['length'], $_POST['start']);
		//print_r($_POST);die;
        $query = $this->db->get(); 
        return $query->result();
    }

    function count_filtered()
    {
        $this->posts_get_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    function getUserDetail($id){

        $userImg = base_url().USER_IMAGE;
        $defaultUserImg = base_url().USER_DEFAULT_IMAGE;

        $this->db->select('u.*,COALESCE(GROUP_CONCAT(DISTINCT ua.affiliate_name),"") as affiliates,COALESCE(GROUP_CONCAT(DISTINCT i.interest_name),"") as interests,COALESCE(GROUP_CONCAT(DISTINCT s.skill_name),"") as skills,concat(u.country_code,"-",u.contact_no) as contact_no,
           (case 
                when (u.profile_image = "") 
                    THEN "'.$defaultUserImg.'"
                when (u.profile_image != "" && u.is_profile_url = 1) 
                    THEN u.profile_image
                ELSE
                    concat("'.$userImg.'",u.profile_image) 
            END) as profile_image');

        $this->db->from(USERS .' as u');
        $this->db->join(USER_INTERESTS.' as ui',"ui.user_id = u.userId","left");
        $this->db->join(INTERESTS.' as i',"ui.interest_id = i.interestId","left");
        $this->db->join(USER_SKILLS.' as us',"u.userId = us.user_id","left");
        $this->db->join(SKILLS.' as s',"us.skill_id = s.skillId","left");
        $this->db->join(USER_AFFILIATES.' as ua',"u.userId = ua.user_id","left");
        $this->db->where(array('u.userId'=>$id));
        $result = $this->db->get();
        $row = $result->row();
        $row->affiliates = !empty($row->affiliates) ? explode(',',$row->affiliates) : '';
        $row->interests = !empty($row->interests) ? explode(',',$row->interests) : '';
        $row->skills = !empty($row->skills) ? explode(',',$row->skills) : '';
        return $row;
    }

}