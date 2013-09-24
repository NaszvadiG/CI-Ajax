<?php
class Base extends CI_Controller
{

    /**
     * Function upload
     * @param $userfile
     * @param $size
     * @return array
     */
    public function do_upload($userfile, $size)
    {
        $CI = & get_instance();
        $size = explode('x', $size);
        $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . '/public/upload/';
        $config['allowed_types'] = 'jpg|png|mp4|ipa|apk|jar|cod|jad|sis|sisx';
        $config['max_size'] = '1000000';
        $config['encrypt_name'] = TRUE;
        $CI->load->library('upload', $config);
        $CI->upload->initialize($config);
        if (!$CI->upload->do_upload()) {
            $data = array('error' == $CI->upload->display_errors(),'config'=>$config);
        } else {
            $data = array('upload_data' => $CI->upload->data());
        }
        #BEGIN RESIZE IMAGE
        if (isset($data["upload_data"])) {
            $img_array = array();
            $img_array['image_library'] = 'gd2';
            $img_array['maintain_ratio'] = TRUE;
            $img_array['source_image'] = $data["upload_data"]["full_path"];
            $img_array['width'] = $size[0];
            $img_array['height'] = $size[1];

            $CI->load->library('image_lib', $img_array);
            $CI->image_lib->resize();

        }
        #END RESIZE IMAGE
        return $data;
    }

    public function auto_login(&$language)
    {
        //INIT LIBRARY AND HELPER
        $this->load->library('encrypt');
        $this->load->helper('cookie');
        //update get language
        $language = get_cookie('lang');
        if(!$language){
            $language = 'en';
        }
        //END INIT LIBRARY AND HELPER
        $auto_login = get_cookie('adv_lg');
        if ($auto_login != "") {
            $login_info = $this->encrypt->decode($auto_login);
            $login_info = json_decode($login_info);
            if ($login_info->session_key != '') {
                $this->session->set_userdata($login_info->session_key);
            }
        }
    }

    public function uploadApp($userfile)
    {
        $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . '/public/upload/';
        $config['allowed_types'] = 'ipa|apk';
        $config['max_size'] = '0';
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload()) {
            $data = array('error' => $this->upload->display_errors());
        } else {
            $data = array('upload_data' => $this->upload->data());
        }
        return $data;
    }

    public function uploadVideo($userfile)
    {
        $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . '/public/upload/';
        $config['allowed_types'] = 'mp4';
        $config['max_size'] = '1000000';
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload()) {
            $data = array('error' => $this->upload->display_errors());
        } else {
            $data = array('upload_data' => $this->upload->data());
        }
        return $data;
    }

    public function upload($userfile)
    {
        $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . '/public/upload/';
        $config['allowed_types'] = 'jpg|png|mp4|ipa|apk|jar|cod|jad|sis|sisx';
        $config['max_size'] = '1000000';
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload()) {
            $data = array('error' => $this->upload->display_errors());
        } else {
            $data = array('upload_data' => $this->upload->data());
        }
        return $data;
    }

    /**
     * count requests, impressions, clicks, views, downloads, installs
     * @param $profiles
     */
    public function report($profiles,$ads_type=FALSE){
        $CI = & get_instance();
        $CI->load->model('mongo_model');
        $info = $CI->mongo_model->get_from_reports($profiles,$ads_type);
        $result = $this->calculate_report($info);
        return $result;
    }

    private function calculate_report($info){
            $data = array(
                'requests'=>0,
                'impressions'=>0,
                'clicks'=>0,
                'views'=>0,
                'downloads'=>0,
                'installs'=>0,
                'actives'=>0);
            //count du lieu
            foreach($info as $rs){
                //count impressions
                $requests = $rs->requests;
                if(count($requests)>0){
                    foreach($requests as $key=>$value){
                        $data['requests'] = $data['requests']+(int)$value;
                    }
                }
                //count impressions
                $impressions = $rs->impressions;
                if(count($impressions)>0){
                    foreach($impressions as $key=>$value){
                        $data['impressions'] = $data['impressions']+(int)$value;
                    }
                }
                //count clicks
                if(isset($rs->clicks)){
                    $clicks = $rs->clicks;
                    if(count($clicks)>0){
                        foreach($clicks as $key=>$value){
                            $data['clicks'] = $data['clicks']+(int)$value;
                        }
                    }
                }
                //count view
                if(isset($rs->views)){
                    $views = $rs->views;
                    if(count($views)>0){
                        foreach($views as $key=>$value){
                            $data['views'] = $data['views']+(int)$value;
                        }
                    }
                }
                //count downloads
                if(isset($rs->downloads)){
                    $downs = $rs->downloads;
                    if(count($downs)>0){
                        foreach($downs as $key=>$value){
                            $data['downloads'] = $data['downloads']+(int)$value;
                        }
                    }
                }
                //count install
                if(isset($rs->installs)){
                    $installs = $rs->installs;
                    if(count($installs)>0){
                        foreach($installs as $key=>$value){
                            $data['installs'] = $data['installs']+(int)$value;
                        }
                    }
                }
                //count activies
                if(isset($rs->actives)){
                    $actives = $rs->actives;
                    if(count($actives)>0){
                        foreach($actives as $key=>$value){
                            $data['actives'] = $data['actives']+(int)$value;
                        }
                    }
                }
            }
        return $data;
    }

    /**
     * check role function
     */
    public function check_role($id_array=FALSE){
        $CI = & get_instance();
        $url = $CI->uri->uri_string();
        $action = $CI->uri->segment(2).'/'.$CI->uri->segment(3);
        //echo $action;
        switch ($action){
            case 'users/edit':
                $adv_id = array(new MongoId($CI->uri->segment(4)));
                $check = $this->check_exist('advertisers',$adv_id);
                break;
            case 'users/update':
                $check = $this->check_exist('advertisers',$id_array);
                return $check;
                break;
            case 'users/add':
                //$check = $this->check_exist('advertisers',$id_array);
                break;
            case 'users/action':
                foreach ($id_array as $rs) {
                    $check = $this->check_exist('advertisers',array(new MongoId($rs)));
                    if($check==false){
                        return $check;
                        break;
                    }
                }
                return true;
                break;
            case 'campaigns/add':
                $check = $this->check_exist('advertisers',array(new MongoId($this->uri->segment(4))));
                break;
            case 'campaigns/update':
                $check = $this->check_exist('campaigns',$id_array);
                return $check;
                break;
            case 'campaigns/delete':
                foreach ($id_array as $rs) {
                    $check = $this->check_exist('campaigns',array(new MongoId($rs)));
                    if($check==false){
                        return $check;
                        break;
                    }
                }
                return true;
                break;
            case 'campaigns/action':
                foreach ($id_array as $rs) {
                    $check = $this->check_exist('campaigns',array($rs));
                    if($check==false){
                        return $check;
                        break;
                    }
                }
                return true;
                break;
        }

        if(!$check){
            redirect(base_url('backend/home/nopermission'));
        }

    }

    /**
     * check ton tai trong phan quyen
     */
    public function check_exist($type,$data){
        $data[] = 'all';
        $CI=  & get_instance();
        $CI->load->model('mongo_model');
        switch ($type){
            case 'advertisers':
                $adv = $CI->mongo_model->get_select('admin',array('_id'=>1),array('permission.advertisers'=>array('$in'=>$data)));
                break;
            case 'campaigns':
                $adv = $CI->mongo_model->get_select('admin',array('_id'=>1),array('permission.campaigns'=>array('$in'=>$data)));
                break;
            case 'profiles':
                $adv = $CI->mongo_model->get_select('admin',array('_id'=>1),array('permission.profiles'=>array('$in'=>$data)));
                break;
            case 'banners':
                $adv = $CI->mongo_model->get_select('admin',array('_id'=>1),array('permission.banners'=>array('$in'=>$data)));
                break;
        }
        if(count($adv)>0){
            return true;
        }
        return false;
    }

    public function check_active(){
        if($this->session->userdata('sessionIdAdmin')==''){
            redirect(base_url().'backend/login');
        }
        if($this->session->userdata('sessionStatus')!='active'){
            redirect(base_url('backend/home/nonactive'));
        }
    }

}
