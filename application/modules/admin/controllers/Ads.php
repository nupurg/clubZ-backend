<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ads extends Common_Controller {

    public $data = "";

    function __construct() {
        parent::__construct();
        if(!$this->session->userdata('id')) {
            redirect('admin'); 
        }
        
    }

    public function addAdCategory() {

        $this->form_validation->set_rules('adCategoryName','Ad category Name','required');
        if($this->form_validation->run() == FALSE){

            $data['error'] = validation_errors();
            $response = array('status' => 0, 'message' => $data['error']); //error msg

        }else{

            $date = date('Y-m-d H:i:s');
            $dataVal = array(
                'ad_category_name' =>$this->input->post('adCategoryName'),
                'crd'=>$date,
                'upd'=>$date
                );
            $where = array('ad_category_name'=>$dataVal['ad_category_name']);
            $isExist = $this->common_model->is_data_exists(AD_CATEGORY,$where);
            if($isExist === true){
            
                $response = array('status' => 0, 'message' =>'Category already exist'); //error msg 
                
            }else{
               
                $insertId = $this->common_model->insert_data(AD_CATEGORY,$dataVal);
                if($insertId){
                   
                    $response = array('status' => 1, 'message' => 'Successfully added', 'url' => base_url('admin/ads/allAdCategory')); //success msg

                }else{
                     $response = array('status' => 0, 'message' =>'Something going wrong'); //error msg 
                }
            }
          
        }
        echo json_encode($response); die;
    }

    public function allAdCategory(){

         $data = '';
         $this->load->admin_render('adCategoryList', $data, '');
    }

        //For ad listing via ajax
    public function getAdCategoryList() {

        $this->load->model('ad_category_list_model'); 
        $list = $this->ad_category_list_model->get_list(); 

        $data = array();
        $no = !empty($_POST['start']) ? $_POST['start'] : 0;
         foreach ($list as $get) { 
            // print_r($data);die;
            $action ='';
            $no++;
            $row = array();
            $row[] = $no;
            
            $row[] = display_placeholder_text($get->ad_category_name);
            $encoded = encoding($get->adCategoryId);
            $clkDelete = "deleteFn('".AD_CATEGORY."','adCategoryId','".$encoded."','ad category')" ;
            
            if($get->status){
                
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Active'.'</span>';
                 $row[] = $status;
                 $title = 'Inactive';
                 $clkStatus = "statusFn('".AD_CATEGORY."','adCategoryId','".$encoded."','$get->status','ad category')" ;
                 $class = 'fa fa-times';

            }else{
                 $req = status_color($get->status); 
                 $status = '<span style="color:'.$req.'">'.'Inactive'.'</span>';
                 $row[] = $status;
                 $title = 'Active';
                 $clkStatus = "statusFn('".AD_CATEGORY."','adCategoryId','".$encoded."','$get->status','ad category')" ;
                 $class = 'fa fa-check';
            }
           
         $action .= '<a href="javascript:void(0)" onclick="'.$clkStatus.'" title="'.$title.'" class="on-default edit-row table_action" >'.'<i class="'.$class.'" aria-hidden="true"></i>'.'</a>';

         $action .= '<a href="javascript:void(0)" title="Delete" onclick="'.$clkDelete.'"  class="on-default edit-row table_action">'.'<i class="fa fa-trash" aria-hidden="true"></i>'.'</a>';

        $action .= '<a href="javascript:void(0)" title="Edit" id="editAdCategory" data-cid="'.$get->adCategoryId.'" data-cname="'.$get->ad_category_name.'" class="on-default edit-row table_action">'.'<i class="fa fa-pencil" aria-hidden="true"></i>'.'</a>';


           $row[] = $action;
            $data[] = $row;
            $_POST['draw']='';
        }

        $output = array(
                "draw" => $_POST['draw'], 
                "recordsTotal" => $this->ad_category_list_model->count_all(),
                "recordsFiltered" => $this->ad_category_list_model->count_filtered(),
                "data" => $data
        );

        //output to json format
       echo json_encode($output);

    }//End function

     //For ad active inactive option
    public function adCategoryStatus(){
       
        $id = $this->uri->segment(4);
        $where = array('adCategoryId'=>$id);
        $res = $this->common_model->changeStatus(AD_CATEGORY,$where);
        if($res==1){
            $this->session->set_flashdata('success', 'Ad category inactivated successfully');
        } elseif($res==2){
            $this->session->set_flashdata('success', 'Ad category activated successfully');
        }else{
            $this->session->set_flashdata('warning', 'Please try again');
        }

       
        redirect('admin/ads/allAdCategory');
        

    }//End function

    public function deleteAdCategory(){

        $id = $this->uri->segment(4);
        $where = array('adCategoryId'=>$id);
        $res = $this->common_model->deleteData(AD_CATEGORY,$where);
        if($res){

           $this->session->set_flashdata('success', 'Ad category deleted successfully');
           redirect('admin/ads/allAdCategory');
        }else{
            $this->session->set_flashdata('error', 'Something going wrong,Please try again.');
            redirect('admin/ads/allAdCategory');

        }

    }//End function

    public function updateAdCategory(){

        $this->form_validation->set_rules('adCategoryName','Ad category Name','required');
        if($this->form_validation->run() == FALSE){

            $data['error'] = validation_errors();
            $response = array('status' => 0, 'message' => $data['error']); //error msg
        }else{

            $date = date('Y-m-d H:i:s');
            $dataVal = array(
                'ad_category_name' =>$this->input->post('adCategoryName'),
                'upd'=>$date
                );
            $adCategoryId = $this->input->post('adCategoryId');
            $where = array('ad_category_name'=>$dataVal['ad_category_name'],'adCategoryId !='=>$adCategoryId);
            $isExist = $this->common_model->is_data_exists(AD_CATEGORY,$where);
            if($isExist === true){
                  $response = array('status' => 0, 'message' => 'Category already exist'); //error msg
                
            }else{
                $w = array('adCategoryId'=>$adCategoryId);
                $isUpdate = $this->common_model->updateFields(AD_CATEGORY,$dataVal,$w);
                if($isUpdate){
                    $response = array('status' => 1, 'message' => 'Successfully updated', 'url' => base_url('admin/ads/allAdCategory')); //success msg
                }else{
                     $response = array('status' => 0, 'message' => 'Something going wrong'); //error msg
                }
            }
        }

        echo json_encode($response); die;
    }//End function


           


    
}