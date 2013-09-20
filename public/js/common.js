function Popup(){
    this.open = function(id, title, content, cmd){
        if ($('#' + id).length != 0){
            $('#' + id).remove();
        }
        
        $('body:first').append('<div id="'+id+'" style="display:none" class="modal">\
                <div style="cursor:move" class="modal-header">\
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>\
                    <h3>'+title+'</h3>\
                </div>\
                <div class="modal-body">\
                    '+content+'\
                </div>\
                <div class="modal-footer">\
                </div>\
            </div>');
        $('#' + id + ' .close').click(function(){
            popup.close(id);
        });
        for(var i=0; i<cmd.length; i++){
            $('#' + id + ' .modal-footer').append('<a class="btn '+cmd[i].style+'" id="popCmd_'+id+'_'+i+'">'+cmd[i].title+'</a>');
            $('#'+'popCmd_'+id+'_'+i).click(cmd[i].fn);
        }
        
        popup.resetPos();
        
        $('#' + id).show('drop');
        shadow.show();
        
        $('#' + id).resize(function(){
            popup.resetPos();
        });
        $(window).resize(function(){
            popup.resetPos();
        });
    }
    
    this.close = function(id){
        $('#'+id).remove();
        $('#'+id).hide('drop');
        shadow.hide();
    }
    
    this.resetPos = function(){
        $('.modal').each(function(){
            $(this).find('.modal-body').css('max-height',$(window).height() - 200);
            $(this).find('.modal-body').css('max-width',$(window).width() - 200);
            $(this).css('margin-top', -($(this).height()/2));
            $(this).css('margin-left', -($(this).width()/2));
        });
    }
    
    this.msg = function(msg){
        this.open('popup-msg', 'Thông báo', msg, [{
            title:"Đồng ý",
            style:"btn-primary",
            fn:function(){
                popup.close('popup-msg');
            }
        }]);
    }
    
    this.confirm = function(msg, fn){
        this.open('popup-confirm', 'Thông báo', msg, [{
            title:"Đồng ý",
            style:"btn-primary",
            fn:function(){
                fn();
                popup.close('popup-confirm');
            }
        },{
            title:'Từ chối',
            fn:function(){
                popup.close('popup-confirm');
            }
        }]);
    }
}

function Loading(){
    this.show = function(){
        if ($('#loading').length == 0)
            $('body:first').append('<div id="loading" class="modal" style="width: 230px; display: none; margin-left:-115px; z-index:1100">\
                <div class="modal-body">\
                        <div class="loading">\
                        <div class="loading-img"></div><p>Vui lòng chờ trong giây lát!</p></div>\
                </div>\
            </div>');
        popup.resetPos();
        $('#loading').show('fade');
        shadow.show();
    }
    this.hide = function(){
        $('#loading').hide('fade');
        shadow.hide();
    }
}

function Shadow(){
    this.show = function(){
        if ($('#shadow').length == 0)
            $('body:first').append('<div id="shadow" class="modal-backdrop"></div>');
        $('#shadow').show();
    }
    this.hide = function(){
        $('#shadow').hide();
    }
}

function Text(){
    this.subString = function(length, source){
        if(source.length>length)
            return source.substring(0, length)+'...';
        else
            return source;
    }
}

var loading = new Loading();
var shadow = new Shadow();
var popup = new Popup();
var text = new Text();