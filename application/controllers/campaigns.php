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
        $this->load->library('pagination_lib');
    }

    public function show(){
        $data['title'] = 'Quản trị campaign';
        $data['view'] = 'campaigns/show_view';
        $this->load->view('index_view',$data);
    }

    public function list_campaign(){
        $name = $this->input->post('name');
        $status = $this->input->post('status');
        $start_from = ($this->input->post('start_from')!='')?$this->input->post('start_from'):0;
        $query='';
        if($name){
            $query['name']=$name;
        }
        if($status){
            $query['status'] = $status;
        }
        //end update
        $limit = 100;
        $data['title'] = 'Quản trị Campaign';
        $total = $this->mongo_model->get_campaign('total','campaigns',$query);
        $data['total'] = $total;
        //$settings = $this->pagination_lib->get_settings('campaignsbackend','show',$total,$limit);
        //$this->pagination->initialize($settings);
        //$data['page_nav'] = $this->pagination->create_links();
        $data['info'] = $this->mongo_model->get_campaign('','campaigns',$query,$limit,$start_from);
        $data['start_from'] = $start_from;
        $data['limit'] = $limit;
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

}
?>