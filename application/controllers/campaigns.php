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
    }

    public function show(){
        $data['view'] = 'campaigns/show_view';
        $this->load->view('index_view',$data);
    }
}
?>