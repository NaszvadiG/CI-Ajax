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
        $this->session->set_userdata(array('advertiser_id'=>new MongoId('51b8335d49a672255d000000'),'currency'=>'usd'));
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
        if($status=='delete'){
            if($this->mongo_model->delete('campaigns',$query)){
                echo json_encode(array('status'=>true,'msg'=>'Delete campaign Successfull','data'=>$query));
            }else{
                echo json_encode(array('status'=>false,'msg'=>'Delete campaign Failed','data'=>$query));
            }
        }else{
            if($this->mongo_model->update('campaigns',array('$set'=>array('status'=>$status)),$query)){
                echo json_encode(array('status'=>true,'msg'=>'Update Successfull','data'=>$query));
            }else{
                echo json_encode(array('status'=>false,'msg'=>'Update Failed','data'=>$query));
            }
        }
    }

    public function add(){
        if($this->input->is_ajax_request()){
            $data['name'] = $this->input->post('name');
            $data['ads_type'] = $this->input->post('ads_type');
            $data['startdate'] = $this->input->post('start');
            $data['enddate'] = $this->input->post('end');
            $data['daily_budget'] = $this->input->post('daily-budget-txt');
            $data['budget'] = $this->input->post('budget-txt');
            $data['min_cost'] = $this->input->post('min-cost-txt');
            $data['max_cost'] = $this->input->post('max-cost-txt');
            $data['ads_content']['logo'] = $this->input->post('logo_link');
            $data['ads_content']['url'] = $this->input->post('link-txt');
            $data['advertiser_id']= $this->input->post('advertiser_id_hidden');;
            $data['status'] = 'new';
            $data['currency'] = $this->session->userdata('currency');
            $data['profiles'] = array();
            $min = $this->input->post('min');
            $max = $this->input->post('max');

            //validate data
            $validate = array();
            if($data['advertiser_id']==''){
                $validate['ad_id_input']= 'Bạn cần chọn advertiser id cho campaign';
            }
            if($data['name'] == ''){
                $validate['name'] = 'Tên không được bỏ trống';
            }
            if($data['startdate'] == ''){
                $validate['start'] = 'Thời gian bắt đầu không được bỏ trống';
            }else{
                $data['startdate'] = strtotime($data['startdate']);
                if($data['startdate']<strtotime(date('m/d/Y'))){
                    $validate['start'] = 'Thời gian bắt đầu phải lớn hơn thời gian hiện tại '.date('m/d/Y');
                }
            }
            if($data['enddate']==''){
                $validate['end'] = 'Thời gian kết thúc không được bỏ trống';

            }else{
                $data['enddate'] = strtotime($data['enddate']);
                if($data['enddate']< $data['startdate']){
                    $validate['end'] = 'Thời gian kết thúc phải lớn hơn hoặc bằng thời gian bắt đầu ';
                }
            }
            if($data['daily_budget']==''){
                $validate['daily-budget-txt'] = 'Daily budget không được bỏ trống';
            }else{
                if(!preg_match('/^[\d\.]+$/',$data['daily_budget'])){
                    $validate['daily-budget-txt'] = 'Daily budget phải ở định dạng số';
                }else if($data['daily_budget']<$min){
                    $validate['daily-budget-txt'] = 'Daily budget phải lớn hơn min cost ('.$min.')';
                }
            }
            if($data['budget']==''){
                $validate['budget-txt'] = 'Budget không được bỏ trống';
            }else{
                if(!preg_match('/^[\d\.]+$/',$data['budget'])){
                    $validate['budget-txt'] = 'Budget phải ở định dạng số';
                }else if($data['budget']<$data['daily_budget']){
                    $validate['budget-txt'] = 'Budget phải lớn hơn daily budget';
                }
            }
            if($data['min_cost']==''||!preg_match('/^[\d\.]+$/',$data['min_cost'])){
                $validate['min-cost-txt'] = 'Min cost không được bỏ trống và ở dạng số';
            }else{
                if($data['min_cost']<$min){
                    $validate['min-cost-txt'] = 'Min cost phải bằng hoặc lớn hơn '.$min;
                }
            }
            if($data['max_cost']==''){
                $validate['max-cost-txt'] = 'Max cost không được bỏ trống';
            }else{
                if($data['max_cost']<$max){
                    $validate['min-cost-txt'] = 'Min cost phải bằng hoặc lớn hơn '.$min;
                }
            }
            if(count($validate) > 0){
                echo json_encode(new Result(false, null, $validate));
                die;
            }else{
                if($this->mongo_model->insert('campaigns',$data)){
                    echo json_encode(array('status'=>true,'message'=>'Tạo campaign thành công!'));
                    die;
                }
            }
        }

    }
    public function edit(){
        if($this->input->is_ajax_request()){
            $data['ads_type'] = $this->input->post('ads_type');
            $data['startdate'] = $this->input->post('start');
            $data['enddate'] = $this->input->post('end');
            $data['daily_budget'] = $this->input->post('daily-budget-txt');
            $data['budget'] = $this->input->post('budget-txt');
            $data['min_cost'] = $this->input->post('min-cost-txt');
            $data['max_cost'] = $this->input->post('max-cost-txt');
            $data['ads_content']['logo'] = $this->input->post('logo_link');
            $data['ads_content']['url'] = $this->input->post('link-txt');
            $campaign_id= $this->input->post('campaign_id');
            $data['profiles'] = array();
            $min = $this->input->post('min');
            $max = $this->input->post('max');

            //validate data
            $validate = array();
            if(!$campaign_id){
                echo json_encode(array('status'=>true,'message'=>'Cập nhật thất bại, không thấy campaign_id'));
                die;
            }
            $data['startdate'] = strtotime($data['startdate']);
            if($data['enddate']==''){
                $validate['end'] = 'Thời gian kết thúc không được bỏ trống';

            }else{
                $data['enddate'] = strtotime($data['enddate']);
                if($data['enddate']< $data['startdate']){
                    $validate['end'] = 'Thời gian kết thúc phải lớn hơn hoặc bằng thời gian bắt đầu ';
                }
                if($data['enddate']<strtotime(date('m/d/Y'))){
                    $validate['end'] = 'Thời gian kết thúc phải lớn hơn hoặc bằng thời gian hiện tại ';
                }
            }
            if($data['daily_budget']==''){
                $validate['daily-budget-txt'] = 'Daily budget không được bỏ trống';
            }else{
                if(!preg_match('/^[\d\.]+$/',$data['daily_budget'])){
                    $validate['daily-budget-txt'] = 'Daily budget phải ở định dạng số';
                }else if($data['daily_budget']<$min){
                    $validate['daily-budget-txt'] = 'Daily budget phải lớn hơn min cost ('.$min.')';
                }
            }
            if($data['budget']==''){
                $validate['budget-txt'] = 'Budget không được bỏ trống';
            }else{
                if(!preg_match('/^[\d\.]+$/',$data['budget'])){
                    $validate['budget-txt'] = 'Budget phải ở định dạng số';
                }else if($data['budget']<$data['daily_budget']){
                    $validate['budget-txt'] = 'Budget phải lớn hơn daily budget';
                }
            }
            if($data['min_cost']==''||!preg_match('/^[\d\.]+$/',$data['min_cost'])){
                $validate['min-cost-txt'] = 'Min cost không được bỏ trống và ở dạng số';
            }else{
                if((float)$data['min_cost']<(float)$min){
                    $validate['min-cost-txt'] = 'Min cost phải bằng hoặc lớn hơn '.$min;
                }
            }
            if($data['max_cost']==''){
                $validate['max-cost-txt'] = 'Max cost không được bỏ trống';
            }else{
                if($data['max_cost']<$max){
                    $validate['max-cost-txt'] = 'Max cost phải bằng hoặc lớn hơn '.$max;
                }
                if($data['max_cost']<$data['min_cost']){
                    $validate['max-cost-txt'] = 'Max cost phải bằng hoặc lớn hơn Min cost ('.$data['min_cost'].')';
                }
            }
            if(count($validate) > 0){
                echo json_encode(new Result(false, null, $validate));
                die;
            }else{
                if($this->mongo_model->update('campaigns',array('$set'=>$data),array('_id'=>new MongoId($campaign_id)))){
                    echo json_encode(array('status'=>true,'message'=>'Cập nhật campaign thành công!'));
                    die;
                }
            }
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

    public function get(){
        if($this->input->is_ajax_request()){
            $id = $this->input->get('id');
            $data = $this->mongo_model->get('campaigns',array('_id'=>new MongoId($id)));
            echo json_encode(array('data'=>$data[0]));
        }
    }

    /**
     * get min max cost
     */
    public function min_max_cost(){
        $currency = $this->session->userdata('currency');; //echo $currency;
        $type = $this->input->post('type'); //echo $type;
        $data = unserialize(file_get_contents('./log/cost/'.$currency.'_'.$type));
        $data['currency'] = $currency;
        echo json_encode($data);
    }

    public function getAdvertiser(){
        if($this->input->is_ajax_request()){
            $id = $this->input->get('id');
            if($id==null){
                echo json_encode(array('status'=>false,'msg'=>'Bạn cần nhập advertiser id'));
                die;
            }
            $info = $this->mongo_model->get_select('advertisers','info.name',array('_id'=>new MongoId($id)));
            if(count($info)){
                echo json_encode(array('status'=>true,'msg'=>$info[0]->info['name']));
                die;
            }else{
                echo json_encode(array('status'=>false,'msg'=>'Không tìm thấy advertiser id tương ứng'));
                die;
            }
        }
    }
}
?>