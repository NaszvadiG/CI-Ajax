<?php
/**
 * User: hungdp
 * Date: 3/12/13
 * Time: 10:20 AM
 */
class Paginate{

    var $pagination;
    function get_html($current_page,$num_record,$num_display)
    {
        $this->pagination=new CI_Pagination();
        /*$current_page : trang hien tai
        $num_record : tong so ban ghi
        $num_display : so ban ghi tren 1 trang
        */
        $config['uri_segment'] = 4;
        $config['base_url'] = $current_page;
        $config['total_rows'] = $num_record;
        $config['per_page'] = $num_display;
        //tag of current link
        $config['cur_tag_open'] = '<a class="active"> ';
        $config['cur_tag_close'] = '</a>';
        //first link
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<a>';
        $config['first_tag_close'] = '</a>';
        //Ten cua last link
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<a>';
        $config['last_tag_close'] = '</a>';

        //next
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<a>';
        $config['next_tag_close'] = '</a>';
        //prev
        //next
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<a>';
        $config['prev_tag_close'] = '</a>';
        $this->pagination->initialize($config);
        return $this->pagination->create_links();
    }
}
