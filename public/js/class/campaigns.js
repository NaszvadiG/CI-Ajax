/**
 * Created with JetBrains PhpStorm.
 * User: tienn2t
 * Date: 9/22/13
 * Time: 9:03 AM
 * To change this template use File | Settings | File Templates.
 */
//init campaigns
campaigns = {};
campaigns.limit = 100;
campaigns.start_from = 0;
campaigns.name='';
campaigns.status='';
campaigns.type = '';
campaigns.fromtime = '';
campaigns.totime = '';

//declare method of campaigns
campaigns.list = function(){
    $.ajax({
        type:'POST',
        cache:false,
        url:'/campaigns/list_campaign',
        data:'name='+campaigns.name+'&status='+campaigns.status+'&start_from='+campaigns.start_from
            +'&limit='+campaigns.limit+'&type='+campaigns.type+'&fromtime='+campaigns.fromtime
            +'&totime='+campaigns.totime,
        beforeSend:function(){
            loading.show();
        },
        success:function(result){
            loading.hide();
            $('#list-profiles').html(result);
            $('div#paginator_id a').click(function(){
                var start = $(this).attr('num');
                campaigns.start_from = start;
                campaigns.list();
            });
        },
        error:function(){
            loading.hide();
            popup.msg('Có lỗi xảy ra, vui lòng kiểm tra lại!');
        }
    });
}

//change status
campaigns.changeStatus = function(id,now_status,change_status){
    if(now_status==change_status){
        return false;
    }
    popup.confirm('Bạn có muốn thay đổi trạng thái không?',function(){
        $.ajax({
            type:'POST',
            cache:false,
            url:'/campaigns/changeStatus',
            dataType:'JSON',
            data:'id='+id+'&status='+change_status,
            beforeSend:function(){
                loading.show();
            },
            success:function(result){
                loading.hide();
                popup.msg(result.msg);
                campaigns.list();
            },
            error:function(){
                loading.hide();
                popup.msg('Có lỗi xảy ra vui lòng thử lại!');
            }
        });
    });
}
