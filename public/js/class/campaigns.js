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
campaigns.list = function(load){
    $.ajax({
        type:'POST',
        cache:false,
        url:'/campaigns/list_campaign',
        data:'name='+campaigns.name+'&status='+campaigns.status+'&start_from='+campaigns.start_from
            +'&limit='+campaigns.limit+'&type='+campaigns.type+'&fromtime='+campaigns.fromtime
            +'&totime='+campaigns.totime,
        beforeSend:function(){
            if(!load){
                loading.show();
            }
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
            $(".datepicker").datepicker();
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
    if(change_status=='delete'&&now_status!='new'){
        popup.msg('Campaign đã được chạy, bạn không thể xóa được');
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
                campaigns.list(true);
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
    $('.datepicker').datepicker();
    $('#popCmd_popup-campaign-form_0').addClass('btn-primary');
    //function get advertiser
    $('#search_adv').click(function(){
        hl.ajax({
            'service':'campaigns/getAdvertiser',
            data:{
                id:$('#advertiser').val()
            },
            success:function(result){
                if(result.status){
                    $('.advertiser-name').attr('value',result.msg);
                    $('#advertiser_id_hidden').attr('value',$('#advertiser').val());
                }else{
                    popup.msg(result.msg);
                }
            }
        });
    });
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
                    popup.msg(data.content);
                }
                else {
                    $('.data').val(data.content);
                    $('#logo_input').val(hl.baseUrl+data.content);
                    $('#show_image').html("<img src='"+data.content+"'>").parent().show();
                }
            }
        });
        return false;
    }); //ket thuc upload file
}

//edit campaign
campaigns.edit = function(id){
    hl.ajax({
        service:'campaigns/get',
        data:{
            id:id
        },
        success:function(result){
            console.log(result);

            popup.open('popup-edit-campaign','Cập nhật campaign',hl.template('/campaigns/create_form_view.php',{
                data:result.data
            }),
                [
                    {
                        title: 'Thêm',
                        fn:function(){
                            hl.submit({
                                id:'campaign-form',
                                service: 'campaigns/edit',
                                success: function(rs){
                                    console.log(rs);
                                    if(!rs.status){
                                        popup.msg(rs.message);
                                    }else{
                                        popup.msg(rs.message);
                                        campaigns.list();
                                        popup.close('popup-edit-campaign');
                                    }
                                }
                            });
                        }
                    },
                    {
                        title: 'Đóng',
                        fn:function(){
                            popup.close('popup-edit-campaign');
                        }
                    }
                ]
            );
            $('#slt_type').val(result.data.ads_type);
            popup.resetPos();
            $('#startdate').removeClass('datepicker');//ko cho sua ngay bat dau
            $('input.datepicker').datepicker();
            if(result.data&&result.data.status=='banned'){
                $('a#popCmd_popup-edit-campaign_0').hide();
                popup.msg('Campaign đã bị banned nên không cập nhật được');
            }

        }
    });
}

//function get min cost and max cost from file
campaigns.readCost = function(type){
    var data = 'type='+type;
    $.ajax({
        url:'campaigns/min_max_cost',
        type:'POST',
        cache:false,
        data:data,
        success:function(string){
            var result = JSON.parse(string);
            $('span#span-min-cost').text('(Min cost must be greater or equal '+result.min+' '+result.currency+')').attr('name',result.min);
            $('span#span-max-cost').text('(Max cost must be greater or equal '+result.max+' '+result.currency+')').attr('name',result.max);
            $('input#min').attr('value',result.min);
            $('input#max').attr('value',result.max);
        }
    });
    if(type=='cpi'||type=='cpa'){
        $('#choose-logo').show();
    }else{
        $('#choose-logo').hide();
    }
}
