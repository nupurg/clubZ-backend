<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Common_Controller {

    public $data = "";

    function __construct() {
        parent::__construct();
        if(!$this->session->userdata('id')) {
            redirect('admin'); 
        }
        
    }

    public function allUsers(){

        $data = '';
        $this->load->admin_render('usersList', $data, '');
    }

    //For user listing via ajax
    public function getUsersList() {

        $this->load->model('users_model');
        $list = $this->users_model->get_list(); 

        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            // print_r($data);die;
            $action ='';
            $no++;
            $row = array();
            if(empty($get->profile_image)){
                $imgPath = base_url().USER_DEFAULT_IMAGE;
            }elseif(!empty($get->profile_image) && $get->social_type != ''){
                $imgPath = $get->profile_image;
            }else{
                $imgPath = base_url().USER_IMAGE.$get->profile_image;
            }
            $row[] = $no;
            $row[] = '<img src="'.$imgPath.'" class="ListImage">';
            $row[] = display_placeholder_text($get->full_name); 
            $row[] = display_placeholder_text($get->email); 
            if(empty($get->social_type)){
                $socialType = '<span class="normalBtn">Normal</span>';
            }elseif($get->social_type == 'facebook'){
                $socialType = '<a class="btn btn-social-icon btn-facebook customBtn"><i class="fa fa-facebook"></i></a>';
            }elseif($get->social_type == 'gmail' || $get->social_type == 'google'){
                $socialType = '<a class="btn btn-social-icon btn-google customBtn"><i class="fa fa-google-plus"></i></a>';
            }else{
                $socialType = '<span class="normalBtn">NA</span>';
            }
            $row[] = $socialType;
            $encoded = encoding($get->userId);
            $viewUrl = base_url().'admin/users/userDetail/'.$encoded;
            $dltUrl = base_url()."admin/users/deleteUser";
            $clkDelete = "deleteFn('".USERS."','userId','".$encoded."','user','".$dltUrl."')" ;
            if($get->status){

                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Active'.'</span>';
                 $row[] = $status;
                 $title = 'Inactive';
                 $clkStatus = "statusFn('".USERS."','userId','".$encoded."','$get->status','user')" ;
                 $class = 'fa fa-times';

            }else{
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Inactive'.'</span>';
                 $row[] = $status;
                 $title = 'Active';
                 $clkStatus = "statusFn('".USERS."','userId','".$encoded."','$get->status','user')" ;
                 $class = 'fa fa-check';
            }
           
           $action .= '<a href="javascript:void(0)" onclick="'.$clkStatus.'" title="'.$title.'" class="on-default edit-row table_action" >'.'<i class="'.$class.'" aria-hidden="true"></i>'.'</a>';

           $action .= '<a href="javascript:void(0)" title="Delete" onclick="'.$clkDelete.'"  class="on-default edit-row table_action">'.'<i class="fa fa-trash" aria-hidden="true"></i>'.'</a>';
          
           $action .= '<a href="'.$viewUrl.'" title="View" class="on-default edit-row table_action" >'.'<i class="fa fa-eye" aria-hidden="true"></i>'.'</a>';

           $row[] = $action;
             $data[] = $row;
            $_POST['draw']='';
        }

        $output = array(
                "draw" => $_POST['draw'], 
                "recordsTotal" => $this->users_model->count_all(),
                "recordsFiltered" => $this->users_model->count_filtered(),
                "data" => $data
        );

        //output to json format
       echo json_encode($output);

    }//End function


    //For user active inactive option
    public function userStatus(){
       
        $id = $this->uri->segment(4);
        $where = array('userId'=>$id);
        $res = $this->common_model->changeStatus(USERS,$where);
        if($res==1){
            $this->session->set_flashdata('success', 'User inactivated successfully');
        } elseif($res==2){
            $this->session->set_flashdata('success', 'User activated successfully');
        }else{
            $this->session->set_flashdata('warning', 'Please try again');
        }
        redirect('admin/users/allUsers');
       
    }//End function


    //For user Detail
    public function userDetail(){

        $id = decoding($this->uri->segment('4'));
        $this->load->model('users_model');
        $data['detail'] = $this->users_model->getUserDetail($id);
        $this->load->admin_render('userDetail',$data);

    }//End function


    //For user created club listing via ajax
    public function myClubsList() {

        $this->load->model('MyClubs_list_model'); 
        $userId = $this->input->post('userId');
        $this->MyClubs_list_model->set_userId($userId);
        $list = $this->MyClubs_list_model->get_list(); 
        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
           
            $action ='';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<img src="'.$get->club_image_url.'" class="ListImage">';
            $row[] = display_placeholder_text($get->club_name);
            $encoded = encoding($get->clubId);
            $clkView = base_url('admin/club/clubDetail/'.$get->clubId);
    
            $action .= '<a href="'.$clkView.'" title="View" class="on-default edit-row table_action">'.'<i class="fa fa-eye" aria-hidden="true"></i>'.'</a>';

                $row[] = $action;
                $data[] = $row;
                $_POST['draw']='';
        }

        $output = array(
                "draw" => $_POST['draw'], 
                "recordsTotal" => $this->MyClubs_list_model->count_all(),
                "recordsFiltered" => $this->MyClubs_list_model->count_filtered(),
                "data" => $data
        );

        //output to json format
       echo json_encode($output);

    }//End function


    //For user joined club listing via ajax
    public function joinedClubsList() {

        $this->load->model('JoinedClubs_list_model'); 
        $userId = $this->input->post('userId');
        $this->JoinedClubs_list_model->set_userId($userId);
        $list = $this->JoinedClubs_list_model->get_list(); 
        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
           
            $action ='';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<img src="'.$get->club_image_url.'" class="ListImage">';
            $row[] = display_placeholder_text($get->club_name);
            $encoded = encoding($get->clubId);
            $clkView = base_url('admin/club/clubDetail/'.$get->clubId);
    
            $action .= '<a href="'.$clkView.'" title="View" class="on-default edit-row table_action">'.'<i class="fa fa-eye" aria-hidden="true"></i>'.'</a>';

                $row[] = $action;
                $data[] = $row;
                $_POST['draw']='';
        }

        $output = array(
                "draw" => $_POST['draw'], 
                "recordsTotal" => $this->JoinedClubs_list_model->count_all(),
                "recordsFiltered" => $this->JoinedClubs_list_model->count_filtered(),
                "data" => $data
        );

        //output to json format
       echo json_encode($output);

    }//End function


    //For deleting a user
    public function deleteUser(){

        $id = decoding($this->input->post('id'));
        $where = array('userId'=>$id);
        $uData = $this->common_model->getsingle(USERS,$where);
        $profileUrl = $uData->is_profile_url;
        $image = $uData->profile_image;
        $res = $this->common_model->deleteData(USERS,$where);
        if($res){
            if($profileUrl == 0 && !empty($image)){
                $this->load->model('Image_model');
                $this->Image_model->unlinkFile(USER_MAIN_IMAGE,$image);
            }
            $response = 200;  
        }else{
            
            $response = 400;
        }
        echo $response;

    }//End function
  
}