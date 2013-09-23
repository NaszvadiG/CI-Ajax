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
            <a class="btn btn-primary" onclick="campaigns.create();">Thêm campaign mới</a>
            <a class="btn" href="<?=base_url()?>backend">Quay về trang chính</a>
        </div><!--control-group-->
        <div>
            <form id="searchbox">
                <div class="row-fluid">
                    <div class="control-group span4">
                        <label class="control-label" for="name">Name</label>
                        <div class="controls">
                            <input type="text" name="name" id="name">
                        </div>
                    </div>
                    <div class="control-group span4">
                        <label class="control-label" for="name">Status</label>
                        <div class="controls">
                            <select name="status" id="status">
                                <option value="">All</option>
                                <option value="new">New</option>
                                <option value="started">Started</option>
                                <option value="paused">Paused</option>
                                <option value="running">Running</option>
                                <option value="banned">Banned</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row-fluid">
                    <div class="control-group span4">
                        <label class="control-label" for="datefrom">Start date</label>
                        <div class="controls">
                            <input type="text" id="fromdate" name="fromdate" class="datepicker">
                        </div>
                    </div>
                    <div class="control-group span4">
                        <label class="control-label" for="enddate">End date</label>
                        <div class="controls">
                            <input type="text" id="enddate" name="enddate" class="datepicker">
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="control-group span4">
                        <label class="control-label" for="type">Type</label>
                        <div class="controls">
                            <select name="ads_type" id="ads_type">
                                <option value="">All</option>
                                <option value="cpim">Cost Per Impression</option>
                                <option value="cpv">Cost Per View</option>
                                <option value="cpc">Cost Per Click</option>
                                <option value="cpd">Cost Per Download</option>
                                <option value="cpi">Cost Per Install</option>
                                <option value="cpa">Cost Per Active</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group span4">
                        <label class="control-label"></label>
                        <div class="controls">
                            <input type="button" class="btn btn-inverse" value="Tìm kiếm" onclick="campaigns.search();">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <hr />
        <div id="list-profiles">

        </div>
    </div><!--form-horizontal-->
</div><!--folder-content-->
<script type="text/javascript" src="<?=base_url()?>public/js/class/campaigns.js"></script>
<script>
    campaigns.list();
</script>
<!--<script>
    //sau do se custom theo class javascript
    $(document).ready(function(){
        $.ajax({
           type:'POST',
           cache:false,
           url:'<?/*=base_url()*/?>campaigns/list_campaign',
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
                url:'<?/*=base_url()*/?>campaigns/list_campaign',
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
                url:'<?/*=base_url()*/?>campaigns/list_campaign',
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
                url:'<?/*=base_url()*/?>campaigns/changeStatus',
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
</script>-->