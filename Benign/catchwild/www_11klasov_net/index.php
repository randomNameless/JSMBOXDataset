$(document).ready(function(){
	 
	$("body").addClass("js");
	
	$('body').on('click','#pagi-load a',function(){
		var $urlNext = $(this).attr('href');
		var $scrollNext = $(this).offset().top - 60;
        if ($urlNext !== undefined) {
			$.ajax({
				url: $urlNext,
				beforeSend: function() {
					ShowLoading('');
				},			 
                success: function(data) {
                    $('#bottom-nav').remove();
                    $('#dle-content').append($('#dle-content', data).html());
                    // $('#dle-content').after($('#bottom-nav'));
					window.history.pushState("", "", $urlNext);
					$('html, body').animate({scrollTop:$scrollNext}, 800);	
					HideLoading('');
					$('[data-src]').lazyLoadXT ();
                },
				  error: function() {				
					HideLoading('');
					alert('что-то пошло не так');
				  }
			});
		};
		return false;
	});
	
	$('body').append('<div class="close-overlay" id="close-overlay"></div><div class="side-panel" id="side-panel"></div><div class="btn-close"><span class="fa fa-times"></span></div>');
	$('.to-mob').each(function() {
		$(this).clone().appendTo('#side-panel');
	});		
	$(".btn-menu").click(function(){
		$('#side-panel, .btn-close').addClass('active');
		$("#close-overlay").fadeIn(200);
		$('body').addClass('opened-menu');
	});
	$(".close-overlay, .btn-close").click(function(){
		$('#side-panel, .btn-close').removeClass('active');
		$('#close-overlay').fadeOut(200);
		$('body').removeClass('opened-menu');
	}); 
	
	$("#login-box").dialog({
		autoOpen: false,
		modal: true,
		show: 'fade',
		hide: 'fade',
		width: 320
	});

	$('.js-login').click(function(){
		$('#login-box').dialog('open');
		return false;
	});
	
	$('.full-text table').each(function(){
		$(this).wrap("<div class='table-resp' />");
	});
	
	$('#ac-av').html($('#lb-ava').html());
	
	$(".add-comm-btn").click(function(){
		$("#add-comm-form").slideToggle(200);
	});
	$(".reply, .qqu").click(function(){
		$("#add-comm-form").slideDown(200);
	});
	$('body').on('click','.ac-textarea textarea, .fr-wrapper',function(){
		$('.add-comm-form').addClass('active').find('.ac-protect').slideDown(400);
	});
	$('.lb-soc a, .ac-soc a').on('click',function(){
	   var href = $(this).attr('href');
       var width  = 820;
       var height = 420;
       var left   = (screen.width  - width)/2;
       var top   = (screen.height - height)/2-100;   

       auth_window = window.open(href, 'auth_window', "width="+width+",height="+height+",top="+top+",left="+left+"menubar=no,resizable=no,scrollbars=no,status=no,toolbar=no");
       return false;
	});
    $('#dle-content > #dle-ajax-comments').appendTo($('#full-comms')); 

	$('body').append('<div id="gotop"><span class="fa fa-chevron-up"></span></div>');
	var $gotop=$('#gotop'); 
	$(window).scroll (function () {
		if ($(this).scrollTop () > 300) {$gotop.fadeIn(200);
		} else {$gotop.fadeOut(200);}
	});	
	$gotop.click(function(){
		$('html, body').animate({ scrollTop : 0 }, 'slow');
	});
	
});

	

$(document).ready(function(){
	if($.cookie('grid-view') == 'grid-list' ) {
		$('#grid').removeClass('grid-thumb').addClass('grid-list');
		$('#grid-select div:first-child').addClass('current').siblings('div').removeClass('current');
	};
	if($.cookie('grid-view') == 'grid-thumb' ) {
		$('#grid').removeClass('grid-list').addClass('grid-thumb');
		$('#grid-select div:last-child').addClass('current').siblings('div').removeClass('current');
	}; 
		$('#grid-select').on('click', 'div:not(.current)', function() {
			var viewType = $(this).attr('data-type'),
				gr = $('#grid');
			$(this).addClass('current').siblings('div').removeClass('current');
			gr.stop().fadeOut(100, function(){
				gr.toggleClass('grid-list grid-thumb');
				$(this).fadeIn().addClass(viewType);
			});
			$.cookie('grid-view', viewType, { path: '/', expires : 7});
		});
	
});

/*!
 * jQuery Cookie Plugin v1.3
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2011, Klaus Hartl
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.opensource.org/licenses/GPL-2.0
 */
 (function(e,h,j){function k(b){return b}function l(b){return decodeURIComponent(b.replace(m," "))}var m=/\+/g,d=e.cookie=function(b,c,a){if(c!==j){a=e.extend({},d.defaults,a);null===c&&(a.expires=-1);if("number"===typeof a.expires){var f=a.expires,g=a.expires=new Date;g.setDate(g.getDate()+f)}c=d.json?JSON.stringify(c):String(c);return h.cookie=[encodeURIComponent(b),"=",d.raw?c:encodeURIComponent(c),a.expires?"; expires="+a.expires.toUTCString():"",a.path?"; path="+a.path:"",a.domain?"; domain="+
a.domain:"",a.secure?"; secure":""].join("")}c=d.raw?k:l;a=h.cookie.split("; ");f=0;for(g=a.length;f<g;f++){var i=a[f].split("=");if(c(i.shift())===b)return b=c(i.join("=")),d.json?JSON.parse(b):b}return null};d.defaults={};e.removeCookie=function(b,c){return null!==e.cookie(b)?(e.cookie(b,null,c),!0):!1}})(jQuery,document);



/* end */

function showAlert(text){
	var err = $('<div>'+text+'</div>');
	err.prependTo(".show-alerts");
	err.slideDown(300);
	setTimeout(function(){err.fadeOut(500,function(){$(this).remove()})},3000);
}
function showLoad(show){
	$(".showLoad").remove();
	if(show) $(".show-alerts").append('<div class="showLoad"></div>');
}
$(function(){
	$('body').append('<div class="show-alerts"></div>');
})

var od_delay=null;
$(document)
.on('click','.orderdesc-add',function(e){
	var $this = $(this);
	if($this.hasClass('current')){
		$this.removeClass('current');
		$(".orderdesc-add-area").slideUp(300);
		return false;
	}
	showLoad(1);
	$.post(dle_root+"engine/mods/orderdesc/ajax.php",{action:'form'},function(d){
		showLoad(0);
		var cd = d.split("::");
		if(cd[0]=='.') showAlert(cd[1]);
		else{
			$(".orderdesc-add-area").html(d).slideDown(300);
			$this.addClass('current');
		}
	})
	e.preventDefault();
})
.on('click','.orderdesc-cancel',function(e){
	$(".orderdesc-add-area").slideUp(300,function(){$(this).html('')});
	$(".orderdesc-add").removeClass('current');
	e.preventDefault();
})
.on('keyup','#orderdescTitle',function(e){
	var title = $("#orderdescTitle").val();
	clearTimeout(od_delay);
	if(title.length>2){
		od_delay = setTimeout(function(){
			showLoad(1);
			$.post(dle_root+"engine/mods/orderdesc/ajax.php",{title:title,action:'related'},function(d){
				showLoad(0);
				var cd = d.split("::");
				if(cd[0]=='.') showAlert(cd[1]);
				else $(".orderdesc-related").html(d).slideDown(300);
			})
		},400)
	}else $(".orderdesc-related").slideUp(300,function(){$(this).html('')});
})
.on('click','.orderdesc-doadd',function(e){
	var title = $("#orderdescTitle").val();
	if(title.length<3){
		showAlert('Слишком короткий заголовок');
		return false;
	}
	showLoad(1);
	var subscribe = $("#orderdescV1").prop('checked')?1:0;
	var anonim = $("#orderdescV2").prop('checked')?1:0;
	$.post(dle_root+"engine/mods/orderdesc/ajax.php",{title:title,action:'add',subscribe:subscribe,anonim:anonim,hash:$(".orderdesc-hash").val()},function(d){
		showLoad(0);
		var cd = d.split("::");
		if(cd[0]=='.') showAlert(cd[1]);
		else{
			showAlert('Ваш заказ успешно добавлен');
			$(".orderdesc-add").removeClass('current');
			$(".orderdesc-table").prepend(d);
			$(".orderdesc-add-area").slideUp(300,function(){$(this).html('')});
		}
	})
	e.preventDefault();
})
.on('click','.orderdesc-mass-sel',function(){
	if($(this).prop('checked')) $(".orderdesc-mass").prop('checked',true);
	else $(".orderdesc-mass").prop('checked',false);
})
.on('click','.orderdesc-rating',function(){
	var $this = $(this);
	showLoad(1);
	$.post(dle_root+"engine/mods/orderdesc/ajax.php",{action:'rating',id:$this.data('id')},function(d){
		showLoad(0);
		var cd = d.split("::");
		if(cd[0]=='.') showAlert(cd[1]);
		else{
			$this.html(parseInt($this.text())+1);
			$this.addClass('orderdesc-rating-green');
		}
	})
})
.on('click','.orderdesc-edit',function(e){
	var id = $(this).data('id');
	$("#orderdesc-edit").remove();
	$("body").append('<div id="orderdesc-edit" title="Редактирование заказа #'+id+'"/>')
	showLoad(1);
	$.post(dle_root+"engine/mods/orderdesc/ajax.php",{action:'edit',id:id},function(d){
		showLoad(0);
		var cd = d.split("::");
		if(cd[0]=='.') showAlert(cd[1]);
		else{
			$("#orderdesc-edit").html(d).dialog({
				width: '720px',
				buttons: {
					"Удалить?":function(e){
						var b = $(e.currentTarget);
						if(!b.hasClass('confirm')){
							b.addClass('confirm').find('span').html('Точно удалить');
							return false;
						}
						showLoad(1);
						$.post(dle_root+"engine/mods/orderdesc/ajax.php",{action:'delete',id:id},function(d){
							showLoad(0);
							var cd = d.split("::");
							if(cd[0]=='.') showAlert(cd[1]);
							else{
								$("#odrow-"+id).remove();
								$("#orderdesc-edit").dialog('close');
							}
						})
					},"Отмена":function(){
						$(this).dialog('close');
					},"Сохранить":function(){
						var subscribe = $("#od-V1").prop('checked')?1:0;
						var anonim = $("#od-V2").prop('checked')?1:0;
						var sendemail = $("#od-V3").prop('checked')?1:0;
						var status = $(".od-status:checked").val();
						var link = $("#od-link").val();
						var reason = $("#od-reason").val();
						if(status==1 && link.length<1){
							showAlert('Не добавлена ссылка на комикс');
							return false;
						}
						if(status==2 && reason.length<1){
							showAlert('Не указана причина отказа');
							return false;
						}
						showLoad(1);
						$.post(dle_root+"engine/mods/orderdesc/ajax.php",{action:'doedit',id:id,title:$("#od-title").val(),date:$("#od-date").val(),author:$("#od-author").val(),status:status,link:link,reason:reason,subscribe:subscribe,anonim:anonim,sendemail:sendemail},function(d){
							showLoad(0);
							var cd = d.split("::");
							if(cd[0]=='.') showAlert(cd[1]);
							else{
								showAlert("Изменения успешно сохранены");
								$("#odrow-"+id).html($(d,"tr").html());
								$("#orderdesc-edit").dialog('close');
							}
						})
					}
					
				}
			})
		}
	})
	e.preventDefault();
})

$('body').on('click', '#show_online', function() {
	var src = $(this).data('src'), width = $(this).data('width'), height = $(this).data('height');

	$('body').append('<div class="show_online"><div class="show_online_close">X</div><iframe src="' + src + '" frameborder="0" allowfullscreen></iframe></div>').find('.show_online iframe').css({margin: (width/2) + 'px 0 0 ' + (height/2) + 'px', width: width + 'px', height: height + 'px'});
	$('body').css('overflow', 'hidden');
}).on('click', '.show_online_close', function() {
	$('.show_online').remove();
	$('body').css('overflow', 'auto');
});

function show_iframe(title, src) {
	$('body').css('overflow', 'hidden').append('<div id="show_iframe" style="position: fixed;top: 60px;left: 0px;bottom: 0px;right: 0px;z-index:100001;"><div style="padding: 0px 25px;line-height: 60px;background: #97ce68;color: #fff;font-size: 20px;font-weight: bold;margin-top: -60px">' + title + '<span onclick="$(\'body\').css(\'overflow\', \'auto\');$(this).closest(\'#show_iframe\').remove();" style="float: right;font-size: 14px;cursor: pointer">Закрыть</span></div><iframe src="' + src + '" width="100%" height="100%" allowfullscreen frameborder="0"></iframe></div>');
}