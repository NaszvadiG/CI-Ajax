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
            //init datepicker
            $(".datepicker").datepicker({
                showOn: "button",
                buttonImage: hl.baseUrl + "public/backend/img/icon-calendar.png",
                buttonImageOnly: true,
                dateFormat: 'dd/MM/yyyy',
                selectMultiple:true
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

//create new campaign function
campaigns.create = function(){
    console.log(hl.baseUrl+hl.templatePath);
    popup.open('popup-campaign-form','Tạo campaign',hl.template('/campaigns/create_form_view.php',{
        data:null
    }),
    [
        {
            title: 'Thêm',
            fn:function(){
                hl.submit({
                    id:'campaign-form',
                    service: 'campaigns/add',
                    success: function(rs){
                        console.log(rs);
                        if(!rs.status){
                            popup.msg(rs.message);
                        }else{
                            popup.msg(rs.message);
                            campaigns.list();
                            popup.close('popup-campaign-form');
                        }
                    }
                });
            }
        },
        {
            title: 'Hủy bỏ',
            fn:function(){
                popup.close('popup-campaign-form');
            }
        }
    ]);
}

//search function
campaigns.search = function(){
    campaigns.start_from = 0;
    campaigns.name = $('input#name').attr('value');
    campaigns.status = $('select#status').val();
    campaigns.fromtime = $('#fromdate').val();
    campaigns.totime = $('#enddate').val();
    campaigns.type = $('#ads_type').val();
    campaigns.list();
}

//function upload
campaigns.upload = function(url,id) {
    console.log(url+id);
    $(document).ready(function () {
        $.ajaxFileUpload({
//            url: "<?=base_url()?>campaign/upload_file",
            url: url,
            secureuri: false,
            fileElementId: id,
//            fileElementId: "userfile",
            data: {"size": "200x200"},
            dataType: "json",
            beforeSend:function(){
                alert('ok');
            },
            success: function (data) {
                console.log(data);
                if (data.err == 1) {
                    alert(data.content);
                }
                else {
                    $('.data').val(data.content);
                    popup.msg('upload successful');
                    $('#show_image').html("<img src='"+hl.baseUrl+"public/upload/"+data.content+"'>").parent().show();
                }
            }
        });
        return false;
    }); //ket thuc upload file
}
