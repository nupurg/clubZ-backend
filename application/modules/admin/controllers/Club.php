<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Club extends Common_Controller {

    public $data = "";

    function __construct() {
        parent::__construct();
        if(!$this->session->userdata('id')) {
            redirect('admin'); 
        }
        
    }

    //For add club category
    public function addClubCategory() {

        $this->form_validation->set_rules('clubCategoryName','Club Category Name','required');
        if($this->form_validation->run() == FALSE){

            $data['error'] = validation_errors();
            $response = array('status' => 0, 'message' => $data['error']); //error msg

        }else{

            $date = date('Y-m-d H:i:s');
            $dataVal = array(
                'club_category_name' =>ucwords($this->input->post('clubCategoryName')),
                'crd'=>$date,
                'upd'=>$date
                );
            $where = array('club_category_name'=>$dataVal['club_category_name']);
            $isExist = $this->common_model->is_data_exists(CLUB_CATEGORY,$where);
            if($isExist === true){
                $response = array('status' => 0, 'message' =>'Category already exist'); //error msg 
            }else{
               
                $insertId = $this->common_model->insert_data(CLUB_CATEGORY,$dataVal);
                if($insertId){
                    $response = array('status' => 1, 'message' => 'Successfully added', 'url' => base_url('admin/club/allClubCategory')); //success msg

                }else{
                     $response = array('status' => 0, 'message' =>'Something going wrong'); //error msg 
                }
            }
          
        }
        echo json_encode($response); die;

    }//End function


    //For list all club category
    public function allClubCategory(){

         $data = '';
         $this->load->admin_render('clubCategoryList', $data, '');
    }//End function


   //For club category listing via ajax
    public function getClubCategoryList() {

        $this->load->model('Club_category_list_model'); 
        $list = $this->Club_category_list_model->get_list(); 

        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
            // print_r($data);die;
            $action ='';
            $no++;
            $row = array();
            $row[] = $no;
            
            $row[] = display_placeholder_text($get->club_category_name);
            $encoded = encoding($get->clubCategoryId);
            $clkDelete = "deleteFn('".CLUB_CATEGORY."','clubCategoryId','".$encoded."','club category')" ;
            
            if($get->status){
                
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Active'.'</span>';
                 $row[] = $status;
                 $title = 'Inactive';
                 $clkStatus = "statusFn('".CLUB_CATEGORY."','clubCategoryId','".$encoded."','$get->status','club category')" ;
                 $class = 'fa fa-times';

            }else{
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Inactive'.'</span>';
                 $row[] = $status;
                 $title = 'Active';
                 $clkStatus = "statusFn('".CLUB_CATEGORY."','clubCategoryId','".$encoded."','$get->status','club category')" ;
                 $class = 'fa fa-check';
            }
     
        $action .= '<a href="javascript:void(0)" title="Delete" onclick="'.$clkDelete.'"  class="on-default edit-row table_action">'.'<i class="fa fa-trash" aria-hidden="true"></i>'.'</a>';

        $action .= '<a href="javascript:void(0)" title="Edit" id="editProfile" data-cid="'.$get->clubCategoryId.'" data-cname="'.$get->club_category_name.'" class="on-default edit-row table_action">'.'<i class="fa fa-pencil" aria-hidden="true"></i>'.'</a>';


           $row[] = $action;
            $data[] = $row;
            $_POST['draw']='';
        }

        $output = array(
                "draw" => $_POST['draw'], 
                "recordsTotal" => $this->Club_category_list_model->count_all(),
                "recordsFiltered" => $this->Club_category_list_model->count_filtered(),
                "data" => $data
        );

        //output to json format
       echo json_encode($output);

    }//End function

     //For user active inactive option
    public function clubCategoryStatus(){
       
        $id = $this->uri->segment(4);
        $where = array('clubCategoryId'=>$id);
        $res = $this->common_model->changeStatus(CLUB_CATEGORY,$where);
        if($res==1){
            $this->session->set_flashdata('success', 'Club category inactivated successfully');
        } elseif($res==2){
            $this->session->set_flashdata('success', 'Club category activated successfully');
        }else{
            $this->session->set_flashdata('warning', 'Please try again');
        }

       
        redirect('admin/club/allClubCategory');
        

    }//End function


    //For deleting a club category
    public function deleteClubCategory(){

        $id = $this->uri->segment(4);
        $where = array('clubCategoryId'=>$id);
        $res = $this->common_model->deleteData(CLUB_CATEGORY,$where);
        if($res){

           $this->session->set_flashdata('success', 'Club Category deleted successfully');
           redirect('admin/club/allClubCategory');
        }else{
            $this->session->set_flashdata('error', 'Something going wrong,Please try again.');
            redirect('admin/club/allClubCategory');

        }

    }//End function

    //For updating a club category
    public function updateClubCategory(){

        $this->form_validation->set_rules('clubCategoryName','Club Category Name','required');
        if($this->form_validation->run() == FALSE){

            $data['error'] = validation_errors();
            $response = array('status' => 0, 'message' => $data['error']); //error msg
        }else{

            $date = date('Y-m-d H:i:s');
            $dataVal = array(
                'club_category_name' =>ucwords($this->input->post('clubCategoryName')),
                'upd'=>$date
                );
            $clubCategoryId = $this->input->post('clubCategoryId');
            $where = array('club_category_name'=>$dataVal['club_category_name'],'clubCategoryId !='=>$clubCategoryId);
            $isExist = $this->common_model->is_data_exists(CLUB_CATEGORY,$where);
            if($isExist === true){
        
                $response = array('status' => 0, 'message' => 'Category already exist'); //error msg
            }else{

                $w = array('clubCategoryId'=>$clubCategoryId);
                $isUpdate = $this->common_model->updateFields(CLUB_CATEGORY,$dataVal,$w);
                if($isUpdate){
                   
                    $response = array('status' => 1, 'message' => 'Successfully updated', 'url' => base_url('admin/club/allClubCategory')); //success msg
                }else{
                    
                    $response = array('status' => 0, 'message' => 'Something going wrong'); //error msg
                }
            }
        }

        echo json_encode($response); die;

    }//End function


    //For listing all club
    public function allClub(){

        $data = '';
        $this->load->admin_render('clubList', $data, '');

    }//End function

    //For club listing via ajax
    public function getClubList() {

        $this->load->model('Club_list_model'); 
        $list = $this->Club_list_model->get_list(); 
        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 
           
            $action ='';
            $no++;
            $row = array();
            $row[] = $no;
            if($get->club_image){
                $imgPath = base_url().CLUB_IMAGE.$get->club_image;
            }else{
                $imgPath = base_url().DEFAULT_CLUB_IMAGE;
            }
            $row[] = '<img src="'.$imgPath.'" class="ListImage">';
            $row[] = display_placeholder_text($get->club_name);
            $row[] = display_placeholder_text($get->club_email);
            $row[] = display_placeholder_text($get->club_category_name);
            $encoded = encoding($get->clubId);
            $clkDelete = "deleteFn('".CLUBS."','clubId','".$encoded."','club')" ;
            $clkView = base_url('admin/club/clubDetail/'.$get->clubId);
            if($get->status){
                $req = status_color($get->status); 
                $status = '<span style="color:'.$req.'">'.'Active'.'</span>';
                $row[] = $status;
                $title = 'Inactive';
                $clkStatus = "statusFn('".CLUBS."','clubId','".$encoded."','$get->status','club')" ;
                $class = 'fa fa-times';
            }else{
                $req = status_color($get->status); 
                $status = '<span style="color:'.$req.'">'.'Inactive'.'</span>';
                $row[] = $status;
                $title = 'Active';
                $clkStatus = "statusFn('".CLUBS."','clubId','".$encoded."','$get->status','club')" ;
                $class = 'fa fa-check';
            }

            $action .= '<a href="javascript:void(0)" title="Delete" onclick="'.$clkDelete.'"  class="on-default edit-row table_action">'.'<i class="fa fa-trash" aria-hidden="true"></i>'.'</a>';


            $action .= '<a href="'.$clkView.'" title="View" class="on-default edit-row table_action">'.'<i class="fa fa-eye" aria-hidden="true"></i>'.'</a>';

                $row[] = $action;
                $data[] = $row;
                $_POST['draw']='';
        }

        $output = array(
                "draw" => $_POST['draw'], 
                "recordsTotal" => $this->Club_list_model->count_all(),
                "recordsFiltered" => $this->Club_list_model->count_filtered(),
                "data" => $data
        );

        //output to json format
       echo json_encode($output);

    }//End function


    //For getting club detail
    public function clubDetail(){

       $data = array();
       $clubId =  $this->uri->segment('4');
       $this->load->model('club_model');
       $data['club'] = $this->club_model->viewClub($clubId);
       $this->load->admin_render('clubDetail',$data,'');

    }//End function


    // For getting more news feed list on click of load more
    public function newsFeedList(){

        $this->load->model('club_model');
        $offset = $this->input->post('offset');
        $clubId = $this->input->post('clubId');
        $limit = 8;

        $totalFeeds = $this->club_model->countFeeds($clubId);
        $nextOffset = $limit + $offset;
        $isNext = 0;
        $button_html = '';
        if($totalFeeds > $nextOffset){
            $isNext = 1;
            $button_html ='<button class="btn themeBtn load loadMore" data-offset = "'.$nextOffset.'" data-clubid ="'.$clubId.'">Load More</button><div class="loaderUrl"></div>';
        }
        $feeds = $this->club_model->getFeedsList($clubId,$limit,$offset);
        if(!$feeds){
            $response = array('status' => 0, 'message' => 'Something went wrong!!');
            echo json_encode($response);
        }
        $club['feeds'] = $feeds; 
        $html = $this->load->view('feedList',$club, true);
        $response = array('status'=>'1','message'=>'success','html'=>$html,'isNext'=>$isNext,'btn_html'=>$button_html);
        echo json_encode($response);

    }//End function


    //For getting club members via ajax
    public function getClubMembersList() {

        $this->load->model('ClubMembers_list_model');
        $clubId = $this->input->post('clubId');
        $this->ClubMembers_list_model->set_id($clubId); 
        $list = $this->ClubMembers_list_model->get_list(); 

        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
        foreach ($list as $get) { 

            $action ='';
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<img src="'.$get->profile_image.'" class="ListImage">';
            $row[] = display_placeholder_text($get->full_name); 
            $encoded = encoding($get->userId);
            $viewUrl = base_url().'admin/users/userDetail/'.$encoded;
            $action .= '<a href="'.$viewUrl.'" title="View" class="on-default edit-row table_action" >'.'<i class="fa fa-eye" aria-hidden="true"></i>'.'</a>';

            $row[] = $action;
            $data[] = $row;
            $_POST['draw']='';
        }
        $output = array(
                "draw" => $_POST['draw'], 
                "recordsTotal" => $this->ClubMembers_list_model->count_all(),
                "recordsFiltered" => $this->ClubMembers_list_model->count_filtered(),
                "data" => $data
        );
        echo json_encode($output);

    }//End function


    //For getting news feed detail
    public function newsFeedDetail(){

       $data = array();
       $feedId =  decoding($this->uri->segment('4'));
       $this->load->model('club_model');
       $data['detail'] = $this->club_model->feedDetail($feedId);
       $this->load->admin_render('newsFeedDetail',$data,'');

    }//End function

        
}