//Init hl
hl = function(params){
    params = $.extend(params);
    hl.baseUrl = params.baseUrl;
    hl.templatePath = params.templatePath;
}
hl.root = function(){
    return hl.baseUrl;
}
//Build template
hl.template = function(template, data){
    return new EJS({
        url: hl.baseUrl + hl.templatePath + template
    }).render(data);
}

//Ajax call
hl.ajax = function(params){
    params = $.extend({
        method:'get',
        loading: true
    }, params);
    if(params.loading)
        loading.show();
    $.ajax({
        url: hl.baseUrl + params.service,
        type: params.method,
        data: params.data,
        dataType: 'json',
        async: params.async,
        success:function(result){
            if(params.loading)
                loading.hide();
                params.success(result);
        },
        error:function(){
            if(params.loading)
                loading.hide();
            popup.msg('Có lỗi xảy ra trong quá trình truyền dữ liệu, xin hãy kiểm tra lại kết nối mạng!');
        }
    });
}

//Ajax submit form
hl.submit = function(params){
    para = {
        data:$('#'+params.id).serialize(), 
        success:function(result){
            if(result.status){
                params.success(result);
            }
            else{
                $('#'+params.id+' input, select, textarea').each(function(){
                    $(this).parent().parent().removeClass('error');
                    $(this).next('.help-block').remove();
                    if($(this).attr('name') && result.data[$(this).attr('name').replace(/.*\[/,'').replace(/\].*/,'')]){
                        $(this).parent().parent().addClass('error');
                        $(this).after('<span class="help-block">'+result.data[$(this).attr('name').replace(/.*\[/,'').replace(/\].*/,'')]+'</span>');
                    }
                });
                if(result.message){
                    popup.msg(result.message);
                }
                popup.resetPos();
            }
        }, 
        service: params.service, 
        method:'post'
    };
    hl.ajax(para);
}

//Ajax submit form
hl.submitWithFile = function(params){
    var action = hl.baseUrl + params.service;
    if (!$('#upload-iframe-submit').length)
        $('body').append('<iframe id="upload-iframe-submit" name="upload-iframe-submit" style="display:none" />');
    $('#'+params.id).attr('target', 'upload-iframe-submit');
    $('#'+params.id).attr('action', action);
    $('#'+params.id).attr('method', 'post');
    $('#'+params.id).attr('enctype', 'multipart/form-data');
    $('#'+params.id).submit();
    $('#upload-iframe-submit').load(function (){
        try{
            var result = $('#upload-iframe-submit').contents().find('body');
            result = $.parseJSON(result.text());
            if(params.loading)
                loading.hide();
            if(result.status){
                params.success(result);
            }else{
                $('#'+params.id+' input, select, textarea').each(function(){
                    $(this).parent().parent().removeClass('error');
                    $(this).next('.help-block').remove();
                    if($(this).attr('name') && result.data[$(this).attr('name').replace(/.*\[/,'').replace(/\].*/,'')]){
                        $(this).parent().parent().addClass('error');
                        $(this).after('<span class="help-block">'+result.data[$(this).attr('name').replace(/.*\[/,'').replace(/\].*/,'')]+'</span>');
                    }
                });
                if(result.message){
                    popup.msg(result.message);
                }
                popup.resetPos();
            }
        }
        catch(err){
            if(params.loading)
                loading.hide();
            popup.msg('Có lỗi xảy ra trong quá trình truyền dữ liệu, xin hãy kiểm tra lại kết nối mạng! '+err);
        }
    });
}

hl.parseTime = function(obj){
    var dd = new Date(parseInt(obj*1000));
    return dd.getDate() + "/" + (dd.getMonth() + 1) + "/" + dd.getFullYear() + " " + dd.getHours() + ":" + dd.getMinutes();
}

Number.prototype.toMoney = function(decimals, decimal_sep, thousands_sep){
    var n = this,
        c = isNaN(decimals) ? 2 : Math.abs(decimals), //if decimal is zero we must take it, it means user does not want to show any decimal
        d = decimal_sep || '.', //if no decimal separator is passed we use the dot as default decimal separator (we MUST use a decimal separator)

        t = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep, //if you don't want to use a thousands separator you can pass empty string as thousands_sep value

        sign = (n < 0) ? '-' : '',

    //extracting the absolute value of the integer part of the number and converting to string
        i = parseInt(n = Math.abs(n).toFixed(c)) + '',

        j = ((j = i.length) > 3) ? j % 3 : 0;
    return sign + (j ? i.substr(0, j) + t : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : '');
}