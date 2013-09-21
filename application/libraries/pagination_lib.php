<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pagination_lib
{    

//id - для чего навигация, name - имя для подстановки к base_url (только для категорий),всего, ограничение)
public function get_settings($id,$name,$total,$limit)
{
    $config = array();
    $config['total_rows'] = $total;
    $config['per_page'] = $limit;    
    $config['first_link'] = 'First';
    $config['last_link'] = 'Last';
    $config['next_link'] = 'Next';
    $config['prev_link'] = 'Previous';
    
    // открывющий тэг перед навигацией
    $config['full_tag_open'] = '<section class="page">';
    
    // закрывающий тэг после навигации
    $config['full_tag_close'] = '</section>';
    
    // первая страница открывающий тэг
    $config['first_tag_open'] = '';
    
    // первая страница закрывающий тэг 
    $config['first_tag_close'] = '';
    
    // последняя страница открывающий тэг
    $config['last_tag_open'] = '';
    
    // последняя страница закрывающий тэг
    $config['last_tag_close'] = '';
    
    // предыдущая страница открывающий тэг
    $config['prev_tag_open'] = '';
    
    // предыдущая страница закрывающий тэг 
    $config['prev_tag_close'] = '';
    
    // текущая страница открывающий тэг
    $config['cur_tag_open'] = '<a class = "active">';
    
    // текущая страница закрывающий тэг
    $config['cur_tag_close'] = '</a>';
        
    $config['num_tag_open'] = ''; // цифровая ссылка открывающий тэг
    $config['num_tag_close'] = ''; // цифровая ссылка закрывающий тэг
    
    // следующая страница открывающий тэг
    $config['next_tag_open'] = '';
    
    // следующая страница закрывающий тэг
    $config['next_tag_close'] = '';
    
    switch($id)
    {
        // Если навигация для категорий
        case 'campaign':
            
            $config['base_url'] = base_url().'campaign/show/'.$name;
            $config['uri_segment'] = 4;
            
            //количество "цифровых" ссылок по бокам от текущей
            $config['num_links'] = 2;
            return $config;            
            break; 

        case 'search':

            $config['base_url'] = base_url().'search/';
            $config['uri_segment'] = 2;
            $config['num_links'] = 2;

            return $config;
            break;    
        case 'actives':
            $config['base_url'] = base_url().'account/info/'.$name;
            $config['uri_segment'] = 4;
            $config['num_links'] = 2;

            return $config;
            break;
        case 'users':
            $config['base_url'] = base_url().'backend/users/'.$name;
            $config['uri_segment'] = 4;
            $config['num_links'] = 2;

            return $config;
            break;
        case 'campaignsbackend':
            $config['base_url'] = 'javascript:void(0);';
            $config['uri_segment'] = 1;
            $config['num_links'] = 2;

            return $config;
            break;
        case 'search_adv':
            $config['base_url'] = base_url().'backend/users/'.$name;
            $config['uri_segment'] = 4;
            $config['num_links'] = 2;

            return $config;
            break;
        case 'campaign_search_backend':
            $config['base_url'] = base_url().'backend/campaigns/'.$name;
            $config['uri_segment'] = 4;
            $config['num_links'] = 2;

            return $config;
            break;
        case 'search_adv_user':
            $config['base_url'] = base_url().'backend/admins/'.$name;
            $config['uri_segment'] = 4;
            $config['num_links'] = 2;

            return $config;
            break;
        case 'adv_user':
            $config['base_url'] = base_url().'backend/admins/'.$name;
            $config['uri_segment'] = 3;
            $config['num_links'] = 2;

            return $config;
            break;

    }
}
   
}
?>
