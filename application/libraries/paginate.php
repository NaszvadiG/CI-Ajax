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

    public function pagenav($total,$limit,$start){
        $total_pages = ceil($total/$limit);
        $page = $start/$limit+1;
        $page_nav = "<div align='center' id='paginator_id'>";

        // Tạo liên kết đến trang trước trang đang xem
        if($page > 1){
            $prev = ($page - 1);
            $page_nav.= "<a num='".(($prev-1)*$limit)."' class='btn'><<</a>&nbsp;";
        }
        if($total_pages<13){
            for($i=1;$i<=$total_pages;$i++){
                if($i==$page){
                    $page_nav.="<span class='btn btn-danger'>$i</span>&nbsp;";
                }else{
                    $page_nav.="<a num='".(($i-1)*$limit)."' class='btn'>$i</a>&nbsp;";
                }
            }
        }else if($page<9){
            for($i=1;$i<=10;$i++){
                if($i==$page){
                    $page_nav.="<span class='btn btn-danger'>$i</span>&nbsp;";
                }else{
                    $page_nav.="<a num='".(($i-1)*$limit)."' class='btn'>$i</a>&nbsp;";
                }
            }
            $page_nav.="&hellip;";
            $page_nav.="<a num='".(($total_pages-1)*$limit)."' class='btn'>".($total_pages-1)."</a>&nbsp;";
            $page_nav.="<a num='".($total_pages*$limit)."' class='btn'>".$total_pages."</a>&nbsp;";
        }else if($page>$total_pages-8){
            $page_nav.="<a num='0' class='btn'>1</a>&nbsp;";
            $page_nav.="<a num='".$limit."' class='btn'>2</a>&nbsp;";
            $page_nav.="&hellip;";
            for($i=$total_pages-9;$i<=$total_pages;$i++){
                if($i==$page){
                    $page_nav.="<span class='btn btn-danger'>$i</span>&nbsp;";
                }else{
                    $page_nav.="<a num='".(($i-1)*$limit)."' class='btn'>$i</a>&nbsp;";
                }
            }
        }else{
            $page_nav.="<a num='0' class='btn'>1</a>&nbsp;";
            $page_nav.="<a num='".$limit."' class='btn'>2</a>&nbsp;";
            $page_nav.="&hellip;";
            for($i=$page-5;$i<$page+5;$i++){
                if($i==$page){
                    $page_nav.="<span class='btn btn-danger'>$i</span>&nbsp;";
                }else{
                    $page_nav.="<a num='".(($i-1)*$limit)."' class='btn'>$i</a>&nbsp;";
                }
            }
            $page_nav.="&hellip;";
            $page_nav.="<a num='".(($total_pages-1)*$limit)."' class='btn'>".($total_pages-1)."</a>&nbsp;";
            $page_nav.="<a num='".($total_pages*$limit)."' class='btn'>".$total_pages."</a>&nbsp;";
        }

        if($page < $total_pages){
            $next = ($page + 1);
            $page_nav.= "<a num='".(($next-1)*$limit)."' class='btn'>>></a>";
        }
        $page_nav.= "</div>";
        return $page_nav;
    }
}
