<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tienn2t
 * Date: 9/20/13
 * Time: 10:58 AM
 * To change this template use File | Settings | File Templates.
 */
class Campaigns extends CI_Controller{
    public function __construct(){
        parent::__construct();
        $this->load->model('mongo_model');
        $this->load->library('paginate');
    }

    public function show(){
        $data['title'] = 'Quản trị campaign';
        $data['view'] = 'campaigns/show_view';
        $this->load->view('index_view',$data);
    }

    public function list_campaign(){
        $name = $this->input->post('name');
        $status = $this->input->post('status');
        $limit = $this->input->post('limit');
        $start_from = ($this->input->post('start_from')!='')?$this->input->post('start_from'):0;
        $from = $this->input->post('fromtime');
        $end = $this->input->post('totime');
        $type = $this->input->post('type');
        $query='';
        if($name){
            $query['name']= new MongoRegex('/'.trim($name).'/i');
        }
        if($status){
            $query['status'] = $status;
        }
        if($from){
            $from = strtotime($from);
            $query['startdate'] = array('$gte'=>$from);
        }
        if($end){
            $end = strtotime(trim($end).' 23:59:59');
            $query['enddate'] = array('$lte'=>$end);
        }
        if($type){
            $query['ads_type'] = $type;
        }
        //end update
        $data['title'] = 'Quản trị Campaign';
        $total = $this->mongo_model->get_campaign('total','campaigns',$query);
        $data['total'] = $total;
        //$settings = $this->pagination_lib->get_settings('campaignsbackend','show',$total,$limit);
        //$this->pagination->initialize($settings);
        //$data['page_nav'] = $this->pagination->create_links();
        $data['info'] = $this->mongo_model->get_campaign('','campaigns',$query,$limit,$start_from);
        $data['start_from'] = $start_from;
        $data['limit'] = $limit;
        $data['page_nav'] = $this->paginate->pagenav($total,$limit,$start_from);
        //get profile name with campaign
        if(count($data['info'])>0){
            foreach ($data['info'] as $rs) {
                $profiles = $rs->profiles;
                if(count($profiles)>0){
                    $data['profile'][] = $this->mongo_model->get_select('profiles',array('name'=>1),array('_id'=>array('$in'=>$profiles)));
                }else{
                    $data['profile'][] = array();
                }

            }

        }

        //$data['content'] = 'backend/campaigns/list_campaign_view';
        //$data['userName'] = $this->session->userdata('sessionUserAdmin');
        //$this->load->view('backend/master_view',$data);
        //$data['info'] = $this->mongo_model->get('campaigns',$query);
        $this->load->view('campaigns/list_view',$data);
    }

    public function changeStatus(){
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $query =array('_id'=>new MongoId($id));
        if($this->mongo_model->update('campaigns',array('$set'=>array('status'=>$status)),$query)){
            echo json_encode(array('status'=>true,'msg'=>'Update Successfull','data'=>$query));
        }else{
            echo json_encode(array('status'=>false,'msg'=>'Update Failed','data'=>$query));
        }
    }

    public function add(){
        if($this->input->is_ajax_request()){
            $name = $this->input->post('name');
            $type = $this->input->post('ads_type');
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            $daily_budget = $this->input->post('daily-budget-txt');
            $budget = $this->input->post('budget-txt');
            $min = $this->input->post('min-cost-txt');
            $max = $this->input->post('max-cost-txt');
            $link = $this->input->post('min-cost-txt');

            echo json_encode(array('status'=>false,'message'=>'ok nhoe'));
            die;
        }

    }

    public function upload(){
        $this->load->library('base');
        $size = $this->input->post("size");
        $file = $this->base->do_upload("userfile", 'image',$size);
        if (isset($file["upload_data"])) {
            echo json_encode(array('err' => 0, 'content' => $file["upload_data"]["path"].'/'.$file["upload_data"]["file_name"],'info'=>$file));
            die;
        } else {
            echo json_encode(array('err' => 1, 'content' => 'Upload Failed!','error'=>$file));
            die;
        }
    }
}
?>