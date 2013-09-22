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
    //sau do se custom theo class javascript
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
        $('.btn-primary1').live('click',function(){
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
                },
                error:function(){
                    loading.hide();
                    popup.msg('Có lỗi xảy ra, vui lòng kiểm tra lại!');
                }
            });
        });

        //change status
        /*$('ul.change-status li a').live('click',function(){
            var now_status =$(this).closest('ul').attr('status');
            var change_status = $(this).attr('for');
            var id= $(this).closest('ul').attr('for'); console.log(id);
            if(now_status==change_status){
                return false;
            }
            popup.confirm('Bạn có muốn thay đổi trạng thái không?',function(){

            });
        });*/
    });
    function changeStatus(id,now_status,change_status){
        console.log(id);
        if(now_status==change_status){
            return false;
        }
        popup.confirm('Bạn có muốn thay đổi trạng thái không?',function(){
            $.ajax({
                type:'POST',
                cache:false,
                url:'<?=base_url()?>campaigns/changeStatus',
                dataType:'JSON',
                data:'id='+id+'&status='+change_status,
                beforeSend:function(){
                    loading.show();
                },
                success:function(result){
                    loading.hide();
                    popup.msg(result.msg);
                },
                error:function(){
                    loading.hide();
                    popup.msg('Có lỗi xảy ra vui lòng thử lại!');
                }
            });
        });
    }
</script>