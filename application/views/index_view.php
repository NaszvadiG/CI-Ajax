<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php if(isset($title)) echo $title;?></title>
        <base href="<?=base_url()?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <link rel="Shortcut Icon" href="http://appota.com/images/appota.ico" type="image/x-icon">
        <link type="text/css" href="<?=base_url()?>public/css/bootstrap.css" rel="stylesheet"/>
        <link type="text/css" href="<?=base_url()?>public/css/bootstrap-responsive.css" rel="stylesheet"/>
        <link type="text/css" href="<?=base_url()?>public/css/index.css" rel="stylesheet"/>
        <link type="text/css" href="<?=base_url()?>public/css/datepicker.css" rel="stylesheet"/>

        <!--Loading Javascript-->
        <script type="text/javascript" src="<?=base_url()?>public/js/jquery.js"></script>
        <script type="text/javascript" src="<?=base_url()?>public/js/bootstrap-datepicker.js"></script>
        <script type="text/javascript" src="<?=base_url()?>public/js/lib/hl.js"></script>
        <script type="text/javascript" src="<?=base_url()?>public/js/lib/ejs.js"></script>
        <script type="text/javascript" src="<?=base_url()?>public/js/common.js"></script>
        <script type="text/javascript" src="<?=base_url()?>public/js/ajaxfileupload.js"></script>
        <!--end load js-->
    </head>
    <header>
        <div class="container">
            <a class="logo" href="<?=base_url('backend/home')?>"><img src="<?=base_url()?>public/img/logo.png" alt="APPOTA" /></a>
            <div class="menu pull-right">
                <a href="<?=base_url()?>backend/home">Trang chủ</a>
                <?php if($this->session->userdata('userAdminName')!=''){
                    echo  '| <a href="'.base_url().'backend/login/logout">Đăng xuất</a>';
                    echo '(<span style="color:#d9251d;">'.$this->session->userdata('userAdminName').'</span>)';
                }?>
            </div>
        </div><!--container-->
    </header>
    <body>
        <div id="container">
            <?=$this->load->view($view);?>
        </div>
        <hr/>
        <footer>
            <p align="center">&copy; APPOTA <?php echo date("Y");?></p>
        </footer>
    </body>
    <script>
        hl({
            baseUrl:'<?=base_url();?>',
            templatePath: 'assets/template'
            //templatePath: 'application/views'
        });
        $('body').keypress(function(e){
            //Sự kiện ESC
            if(e.keyCode == 27)
                $('.close').click();
        });
    </script>
    <script type="text/javascript" src="<?=base_url()?>public/js/bootstrap.js"></script>
</html>
