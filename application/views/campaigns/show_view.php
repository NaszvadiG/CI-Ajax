<div class="folder-header">
    <h3>Quản trị campaigns</h3>
</div><!--folder-header-->
<div class="folder-tab">
    <ul class="nav nav-tabs">
        <li class="active"><a href="<?=base_url()?>backend/profiles">Danh sách campaigns</a></li>
    </ul>
</div><!--folder-tab-->
<div class="folder-content">
    <div class="form-horizontal">
        <div class="control-group">
            <a class="btn btn-primary" onclick="">Thêm profiles mới</a>
            <a class="btn" href="<?=base_url()?>backend">Quay về trang chính</a>
        </div><!--control-group-->
        <hr />
        <div id="list-profiles">

        </div>
    </div><!--form-horizontal-->
</div><!--folder-content-->
<script>
    $(document).ready(function(){
        $.ajax({
           type:'POST',
           cache:false,
           url:'<?=base_url()?>campaigns/list_campaign',
           data:'name=&status=&start_from=',
            beforeSend:function(){
                loading.show();
            },
            success:function(result){
                loading.hide();
                $('#list-profiles').html(result);
            },
            error:function(){
                loading.hide();
                popup.msg('Có lỗi xảy ra, vui lòng kiểm tra lại!');
            }
        });
        //click btn-primary
        $('.btn-primary').live('click',function(){
            $.ajax({
                type:'POST',
                cache:false,
                url:'<?=base_url()?>campaigns/list_campaign',
                data:'name=tien&status=&start_from=',
                beforeSend:function(){
                    loading.show();
                },
                success:function(result){
                    loading.hide();
                    $('#list-profiles').html(result);
                },
                error:function(){
                    loading.hide();
                    popup.msg('Có lỗi xảy ra, vui lòng kiểm tra lại!');
                }
            });
        });

        //paginator
        $('div#paginator_id a').live('click',function(){
            var start = $(this).attr('num');
            if(!start){
                return false;
            }
            $.ajax({
                type:'POST',
                cache:false,
                url:'<?=base_url()?>campaigns/list_campaign',
                data:'name=&status=&start_from='+start,
                beforeSend:function(){
                    loading.show();
                },
                success:function(result){
                    loading.hide();
                    $('#list-profiles').html(result);
                    $('section.page a').removeClass('active');
                    var i=0;
                    $('section.page a').each(function(){
                        console.log(parseInt($(this).text())*5+'//'+page[1]);
                        if(parseInt($(this).text())*5==page[1]){
                            $(this).addClass('active');
                        }
                    });
                },
                error:function(){
                    loading.hide();
                    popup.msg('Có lỗi xảy ra, vui lòng kiểm tra lại!');
                }
            });
        });
    });
</script>