<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Common Model
 * Consist common DB methods which will be commonly used throughout the project
 */
class Common_model extends CI_Model {
    
    /* Check user login and set session */
    function isLogin($data, $table){

        $email = $data["email"];
        $where = array('email'=>$email);
     
        $sql = $this->db->select('*')->where($where)->get($table);
        if($sql->num_rows()){
          
            $user = $sql->row();
            //verify password- It is good to use php's password hashing functions so we are using password_verify fn here
            if($data['password'] == $user->password){
               
                $session_data['id']     = $user->id ;
                $session_data['fullName']   =     $user->fullName ;
                $session_data['email']		= $user->email ;
                $session_data['isLogin'] 	= TRUE ;
                $this->session->set_userdata($session_data);
                return TRUE;
            }
            else{
               
               return FALSE; 
            }
        }else{
           
        }
       return FALSE;
    }
        
    /* <!--INSERT RECORD FROM SINGLE TABLE--> */
    function insert_data($table, $dataInsert) {
        $this->db->insert($table, $dataInsert);
        return $this->db->insert_id();
    }

    /* <!--UPDATE RECORD FROM SINGLE TABLE--> */
    function updateFields($table, $data, $where){
        $this->db->update($table, $data, $where);
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
    }


  public function update_data($table,$whereCondition,$updateData){
    
      $this->db->update($table, $updateData, $whereCondition);
      $row = $this->db->affected_rows() ;
      return $row;
  } 


    function deleteData($table,$where){
        $this->db->where($where);
        $this->db->delete($table); 
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }   
    }
    
    /* ---GET SINGLE RECORD--- */
    function getsingle($table, $where = '', $fld = NULL, $order_by = '', $order = '') {
        if ($fld != NULL) {
            $this->db->select($fld);
        }
        $this->db->limit(1);

        if ($order_by != '') {
            $this->db->order_by($order_by, $order);
        }
        if ($where != '') {
            $this->db->where($where);
        }

        $q = $this->db->get($table);
        //$num = $q->num_rows();
        return $q->row();
    }
    
     /* ---GET MULTIPLE RECORD--- */
    function getAll($table, $order_fld = '', $order_type = '', $select = 'all', $limit = '', $offset = '',$group_by='') {
        $data = array();
        if ($select == 'all') {
            $this->db->select('*');
        } else {
            $this->db->select($select);
        }
        if($group_by !=''){
            $this->db->group_by($group_by);
        }
        $this->db->from($table);

        $clone_db = clone $this->db;
        $total_count = (int) $clone_db->get()->num_rows();

        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }
        if ($order_fld != '' && $order_type != '') {
            $this->db->order_by($order_fld, $order_type);
        }
        $q = $this->db->get();
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
        }
        $total = array('total_count' => $total_count,'result' => $data);
        return $data;
    }

     /* ---GET MULTIPLE RECORD WITH WHERE--- */
    function getAllWhr($table, $order_fld = '', $order_type = '', $select = 'all', $limit = '', $offset = '',$group_by='',$where='') {
        $data = array();
        if ($select == 'all') {
            $this->db->select('*');
        } else {
            $this->db->select($select);
        }
        if($group_by !=''){
            $this->db->group_by($group_by);
        }
        $this->db->from($table);
        $this->db->where($where);

        $clone_db = clone $this->db;
        $total_count = (int) $clone_db->get()->num_rows();

        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }
        if ($order_fld != '' && $order_type != '') {
            $this->db->order_by($order_fld, $order_type);
        }
        $q = $this->db->get();
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
        }
        $total = array('total_count' => $total_count,'result' => $data);
        return $data;
    }
    
    //get single record using join
    function GetSingleJoinRecord($table, $field_first, $tablejointo, $field_second,$field_val='',$where="") {
        $data = array();
        if(!empty($field_val)){
            $this->db->select("$field_val");
        }else{
            $this->db->select("*");
        }
        $this->db->from("$table");
        $this->db->join("$tablejointo", "$tablejointo.$field_second = $table.$field_first","inner");
        if(!empty($where)){
            $this->db->where($where);
        }
        $q = $this->db->get();
        return $q->row();  //return only single record
    }

    /* Get mutiple records using join */ 
    function GetJoinRecord($table, $field_first, $tablejointo, $field_second,$field_val='',$where="",$group_by='',$order_fld='',$order_type='', $limit = '', $offset = '') {
        $data = array();
        if(!empty($field_val)){
            $this->db->select("$field_val");
        }else{
            $this->db->select("*");
        }
        $this->db->from("$table");
        $this->db->join("$tablejointo", "$tablejointo.$field_second = $table.$field_first","inner");
        if(!empty($where)){
            $this->db->where($where);
        }
        if(!empty($group_by)){
            $this->db->group_by($group_by);
        }

        $clone_db = clone $this->db;
        $total_count = (int) $clone_db->get()->num_rows();

        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }
        if(!empty($order_fld) && !empty($order_type)){
            $this->db->order_by($order_fld, $order_type);
        }
        $q = $this->db->get();
        return $q->result();
    }
    
    /*Get records joining 3 tables*/
    function GetJoinRecordThree($table, $field_first, $tablejointo, $field_second,$tablejointhree,$field_three,$table_four,$field_four,$field_val='',$where="" ,$group_by="",$order_fld='',$order_type='', $limit = '', $offset = '') {
        $data = array();
        if(!empty($field_val)){
            $this->db->select("$field_val");
        }else{
            $this->db->select("*");
        }
        $this->db->from("$table");
        $this->db->join("$tablejointo", "$tablejointo.$field_second = $table.$field_first",'inner');
        $this->db->join("$tablejointhree", "$tablejointhree.$field_three = $table_four.$field_four",'inner');
        if(!empty($where)){
            $this->db->where($where);
        }

        if(!empty($group_by)){
            $this->db->group_by($group_by);
        }
        $clone_db = clone $this->db;
        $total_count = (int) $clone_db->get()->num_rows();

        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }
        
        if(!empty($order_fld) && !empty($order_type)){
            $this->db->order_by($order_fld, $order_type);
        }
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
        }
        return array('total_count' => $total_count,'result' => $data);
    }

    function getAllwhereIn($table,$where = '',$column ='',$wherein = '', $order_fld = '', $order_type = '', $select = 'all', $limit = '', $offset = '',$group_by='') {
        $data = array();
        if ($order_fld != '' && $order_type != '') {
            $this->db->order_by($order_fld, $order_type);
        }
        if ($select == 'all') {
            $this->db->select('*');
        } else {
            $this->db->select($select);
        }
        $this->db->from($table);
        if ($where != '') {
            $this->db->where($where);
        }
        if ($wherein != '') {
            $this->db->where_in($column,$wherein);
        }
        if($group_by !=''){
            $this->db->group_by($group_by);
        }

        $clone_db = clone $this->db;
        $total_count = (int) $clone_db->get()->num_rows();

        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }

        $q = $this->db->get();
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
        }
        return array('total_count' => $total_count,'result' => $data);
    }
    
    /* Exceute a custom build query- Useful when we are not able to build queries using CI DB methods*/
    public function custom_query($myquery){
        $query = $this->db->query($myquery);
        return $query->result_array();
    }

    /*check if any value exists in and return row if found*/
    public function is_id_exist($table,$key,$value){
        $this->db->select("*");
        $this->db->from($table);
        $this->db->where($key,$value);
        $ret = $this->db->get()->row();
        if(!empty($ret)){
            return $ret;
        }
        else
            return FALSE;
    }
    
    /*get single value based on table key*/
    public function get_field_value($table, $where, $key){ 
        $this->db->select($key);
        $this->db->from($table);
        $this->db->where($where);
        $ret = $this->db->get()->row();
        if(!empty($ret)){
            return $ret->$key;
        }
        else
            return FALSE;
    }
    
    //get total records of any table
    function get_total_count($table, $where=''){
        $this->db->from($table);
        if(!empty($where))
            $this->db->where($where);
        
        $query = $this->db->get();
        $count = $query->num_rows();
        return $count;
    }
    
    /* delete attachment file from folder and table */
    function delete_attachment($ref_id, $ref_table, $att_name=''){
        $del_where = array('reference_id'=>$ref_id, 'reference_table'=>$ref_table); $file_folder = '';
        switch ($ref_table){
            case USERS:
            $file_folder = USER_AVATAR_PATH;
            break;
            case CATEGORIES:
            $file_folder = CATEGORY_IMAGE_PATH;
            break;
            case ALBUMS:
            $file_folder = ALBUM_IMAGE_PATH;
            break;
        }
        
        if(empty($file_folder))
            return;
        
        $att_data = $this->getAllwhere(ATTACHMENTS, $del_where);  //get all attachments of reference
        if(!empty($att_data['result'])){
            foreach($att_data['result'] as $row){
                delete_file($file_folder.$row->attachment_name, FCPATH);  //delete attachment from server
            }
        }
        $this->deleteData(ATTACHMENTS,$del_where);  //delete attachment entries from table
    }
    
    /* check if given data exists in table - Very useful fn */
    function is_data_exists($table, $where){
        $this->db->from($table);
        $this->db->where($where);
        $query = $this->db->get();
        $rowcount = $query->num_rows();
        if($rowcount==0){
            return false; //record not found
        }
        else {
            return true; //returns true if record found
        }
    } 


     public function get_records_by_id($table,$single,$where,$select,$order_by_field,$order_by_value ){

        if(!empty($select)){
            $this->db->select($select);
        }
        
        if(!empty($where)){
            $this->db->where($where);
        }
        
        if(!empty($order_by_field) && !empty($order_by_value)){
            $this->db->order_by($order_by_field, $order_by_value);
        }
        
            $query = $this->db->get($table);
            $result = $query->result_array();
            if(!empty($result)){
            if($single){
                $result = $result[0];
            }else{
                $result = $result;
            }  
        } else{
           $result = 0; 
        }
        return $result; 
    }

    function check_data($table, $where){

        $this->db->from($table);
        $this->db->where($where);
        $query = $this->db->get();
        $rowcount = $query->num_rows();
        if($rowcount==0){
            return false; //record not found
        }
        else {
            return $query->row(); //returns true if record found
        }
    }
    
   function changeStatus($table,$where){ //For active inactive

        $query = $this->db->select('*')->from($table)->where($where)->get();
        if($query->num_rows()>0){

            $result = $query->row();
            if($result->status == '1'){   
                $updStatus = '0';
            }else{
                $updStatus = '1';
            }

            $array = array('status'=>$updStatus);
            $this->db->where($where);
            $this->db->update($table,$array);
            if($updStatus == '0'){
                return 1; //inactive
            }else{
                return 2;//active
            }   
        }
    }

     
} //end of class
/* Do not close php tags */