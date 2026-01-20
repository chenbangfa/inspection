 // JavaScript Document
var flag = false;
var ishttps = 'https:' == document.location.protocol ? true: false;
var _url = "http://"+window.location.host+"/anquan/";
if(ishttps)
	_url = "https://"+window.location.host+"/anquan/";

var tgg;
var _tel = /^13[0-9]{9}$|^14[0-9]{9}$|^15[0-9]{9}$|^17[0-9]{9}$|^18[0-9]{9}|^19[0-9]{9}$/;
var _number = /^(0|[\-|1-9][0-9]{0,9})?$/;
var _price = /^(0|[\-|1-9][0-9]{0,9})(\.[0-9]{1,2})?$/;
var _eml;

function showNotic(msg,url)
{	
	if($(".bg").length<1){
		var _msg = '<div style="margin:0 auto; width:80%; max-width:600px; position:relative"><div class="bg" style=" position:fixed; top:35%; width:75%;  padding:5%; background:rgba(0,0,0,0.7); color:#FFF; z-index:9000; border-radius:5px; max-width:600px; max-height:200px;">';
	_msg +='<div style=" padding:3% 5%; text-align:center;" class="msg">'+msg+'</div></div></div>';
	
		$("body").append(_msg);
	}
	if(msg){
		$(".msg").html(msg);
		$(".bg").show();
		setTimeout(function(){$(".bg").slideUp();},2000);
		if(url)
			setTimeout(function(){location.href=url},2300);
	}else{$(".bg").hide();};
	
	if(!msg&&url)location.href=url;
	
}


$(function(){
	$("body").on('click','.save',function (e) { 
		if(flag)
			return false;
		var _req = $(".req");
		//不为空
		var jg = true;
		_req.each(function(ind, ele) {
			if(!$(ele).val()){
				var _pal = $(ele).attr("placeholder")
				if(_pal) showNotic(_pal);	
				$(ele).focus();
				_eml = ele;
				jg = false;
				return jg;
			}
		});
		if(jg===true){
			nums = $(".number");
			nums.each(function(index, element) { 
				num = $(element).val();
				if(_number.test(num)==false)
				{
					showNotic("此项只能是整数");
				_eml = element;
					$(element).focus();
					jg = false;
				}
				if(!jg) return false;
			});
		}
		if(jg===true){
			nums = $(".price");
			nums.each(function(index, element) { 
				num = $(element).val();
				if(num!=""&&_price.test(num)==false)
				{
					showNotic("此项只能是数字");
				_eml = element;
					$(element).focus();
					jg = false;
				}
				if(!jg) return false;
			});
		}
		if(jg===true){
			nums = $(".tel");
			nums.each(function(index, element) { 
				num = $(element).val();
				if(num!=""&&_tel.test(num)==false)
				{
					showNotic("输入的手机号不正确!");
				_eml = element;
					$(element).focus();
					jg = false;
				}
				if(!jg) return false;
			});
		}
		if(jg===true){
			nums = $(".min");
			nums.each(function(index, element) { 
				var num =$(element).val();
				var _num = $(element).attr("min");
				var _ut = $(element).attr("ut");
				if(_ut==undefined)_ut = "";
				if(isNaN(num)==false && isNaN(_num)==false)
				{
					var a=parseFloat(num);
					var b=parseFloat(_num);
					
					if(a<b){
						showNotic("输入的值不能小于 "+_num+_ut);
				_eml = element;
						$(element).focus();
						jg = false;	
					}
				}
				if(!jg) return false;
			});
		}
		if(jg===true){
			nums = $(".max");
			nums.each(function(index, element) { 
				var num =$(element).val();
				var _num = $(element).attr("max");
				if(isNaN(num)==false && isNaN(_num)==false)
				{
					var a=parseFloat(num);
					var b=parseFloat(_num);
					if(a>b){
						var _m = $(this).attr("_max");
						if(_m==undefined || _m=="")
							_m = b;
						showNotic("输入的值不能大于 "+_m);
						$(element).focus();
				_eml = element;
						jg = false;	
					}
				}
				if(!jg) return false;
			});
		}
		if(jg===true){
			var re = $(".re");
			re.each(function(index, element) { 
				num = $(element).val();
				re = $(element).attr("re");
				renum = $("."+re).val();
				if(num!=renum)
				{
					t =  $(element).attr("placeholder");
					showNotic(t.replace("请输入","")+"不正确！");
				_eml = element;
					$(element).focus();
					jg = false;
				}
				if(!jg) return false;
			});
		}
		//
		if(jg===true){
			$(this).attr("disabled",true);	
			var _btn = $(this);
			flag = 1;
			$.ajax({
				url:_url+"data/ajax.php",
				type:"POST",
				data:$(".form1").serialize(),
				dataType:"JSON",
				cache:false,
				success:function(suc)
				{
					if(suc.st==1)
					{	
						_btn.attr("disabled",false);	
						flag = false;;
						showNotic(suc.msg,suc.url);
						//if(suc.url) location.href=suc.url;
					}else
					{
						_btn.attr("disabled",true);	
						flag = true;;
						showNotic(suc.msg,suc.url);
					}
				},
				error:function(err){
					$(this).attr("disabled",false);	
					flag = false;;
					_btn.attr("disabled",false);	
					showNotic(err.responseText);
				}
			});
		}
		return false;
	});
	
	
	$(document).on('click','.guanzhu',function (e) {
		var wxid=$(this).attr("wxid");
		var sjwxid=$(this).attr("sjwxid");
		var than=this;
		$.ajax({
			url:_url+"data/ajax.php",
			type:"POST",
			data:{tag:"guanzhu",wxid:wxid,sjwxid:sjwxid},
			dataType:"JSON",
			cache:false,
			success:function(suc){
				$(than).html(suc.msg);
			},
			error:function(err){
				showNotic(err.responseText);
			}
		});
	});
	
	$(document).on('click','.gzquanzi',function (e) {
		var wxid=$(this).attr("wxid");
		var qzid=$(this).attr("qzid");
		var hyfs=parseInt($("#qzhynum").html());
		var than=this;
		$.ajax({
			url:_url+"data/ajax.php",
			type:"POST",
			data:{tag:"gzquanzi",wxid:wxid,qzid:qzid},
			dataType:"JSON",
			cache:false,
			success:function(suc){
				$(than).html(suc.msg);
				if(suc.msg=="关注")
				$("#qzhynum").html(hyfs-1);
				else
				$("#qzhynum").html(hyfs+1);
			},
			error:function(err){
				showNotic(err.responseText);
			}
		});
	});
	
	$(document).on('click','.getkq_use',function (e) {
		var _u=$(this).attr("u");
		var _w=$(this).attr("w");
		var _s=$(this).attr("s");
		var _hysj=$(this).attr("hysj");
		var than=this;
		$.ajax({
			url:_url+"data/ajax.php",
			type:"POST",
			data:{tag:"getkq",u:_u,w:_w,hysj:_hysj},
			dataType:"JSON",
			cache:false,
			success:function(suc){
				if(suc.st=="1")
				{
					$(than).html("使用");
					$(than).removeClass("getkq_use");
					$(than).addClass("usekq");
					$(than).attr("w","mykqinfo");
					$(than).attr("s","i="+suc.id);
					$(than).siblings(".kqinfo").children(".sykq").html(_s-1);			
					
				}
				showNotic(suc.msg,suc.url);
			},
			error:function(err){
				showNotic(err.responseText);
			}
		});
	});
	
	$(document).on('click','.usekq',function (e) {
		var _u=$(this).attr("w");
		var _p=$(this).attr("s");
		var _url = _u+".php";
		if(_p&&_p!="")
			_url = _url+"?"+_p
		location.href=_url;	
	});
	
	
	$(".qrdel").click(function(){
		var _i = $(this).attr("i");
		$(".del").attr("i",_i);
	});	
	
	$(document).on('click','.joinqz',function (e) {
		var than=this;
		var _v = "您确认加入此圈子？";
		if(confirm(_v)==false)
		{
			return false;	
		}
		if(flag)
			return false;
		var qzid = $(this).attr("qzid");
		var qzwxid = $(this).attr("qzwxid");
		var wxid = $(this).attr("wxid");
		var qzname = $(this).attr("qzname");
		flag = 1;
		$.ajax({
			url:_url+"data/ajax.php",
			type:"POST",
			data:{tag:"joinqz",qzwxid:qzwxid,qzid:qzid,wxid:wxid,qzname:qzname},
			dataType:"JSON",
			cache:false,
			success:function(suc){
				flag = true;
				$(than).html("等待审核");
				$(than).removeClass("joinqz");
				showNotic(suc.msg,suc.url);
			},
			error:function(err){
				flag = false;;
				showNotic(err.responseText);
			}
		});
	});
	
	$(document).on('click','.joinis',function (e) {
		var than=this;
		var id = $(this).attr("id");
		var zt = $(this).attr("zt");		
		var _v = "您确认"+zt+"？";
		if(confirm(_v)==false)
		{
			return false;	
		}
		if(flag)
			return false;
		
		flag = 1;
		$.ajax({
			url:_url+"data/ajax.php",
			type:"POST",
			data:{tag:"joinis",id:id,zt:zt},
			dataType:"JSON",
			cache:false,
			success:function(suc){
				flag = true;
				$(than).parent().html(suc.msg);
				//showNotic(suc.msg,suc.url);
			},
			error:function(err){
				flag = false;;
				showNotic(err.responseText);
			}
		});
	});
	
	$(document).on('click','.qrok',function (e) {
		e.preventDefault();
		var _v = "您确认确认收货吗？";
		if(confirm(_v)==false){
			return false;	
		}
		if(flag)
			return false;
		var _w = $(this).attr("w");
		var _i = $(this).attr("i");
		var _m = $(this).attr("m"); 
		flag = 1;
		$.ajax({
			url:_url+"data/ajax.php",
			type:"POST",
			data:{tag:"qrok",w:_w,i:_i,m:_m},
			dataType:"JSON",
			cache:false,
			success:function(suc){
				flag = true;;
				 showNotic(suc.msg,suc.url);
				//if(suc.url) location.href=suc.url;
			},
			error:function(err){
				flag = false;;
				showNotic(err.responseText);
			}
		});
	});
	
	$(document).on('click','.del',function (e) {
		e.preventDefault();
		var _v = "您确认删除此项？";
		var _u = $(this).attr("u");
		if(_u) _v = _u;
		if(confirm(_v)==false){
			return false;	
		}
		if(flag)
			return false;
		var _t = $(this).attr("t");
		var _i = $(this).attr("i");
		var _m = $(this).attr("m"); 
		flag = 1;
		$.ajax({
			url:_url+"data/ajax.php",
			type:"POST",
			data:{tag:"del",t:_t,i:_i,m:_m},
			dataType:"JSON",
			cache:false,
			success:function(suc){
				flag = true;;
				 showNotic(suc.msg,suc.url);
				//if(suc.url) location.href=suc.url;
			},
			error:function(err){
				flag = false;;
				showNotic(err.responseText);
			}
		});
	});
	
	$(document).on('click','.delqzsj',function (e) {
		e.preventDefault();
		var than=this;
		var _v = "您确认移除该商家吗？";
		if(confirm(_v)==false){
			return false;	
		}
		if(flag)
			return false;
		var _w = $(this).attr("w");
		var _q = $(this).attr("q");
		flag = 1;
		$.ajax({
			url:_url+"data/ajax.php",
			type:"POST",
			data:{tag:"delqzsj",w:_w,q:_q},
			dataType:"JSON",
			cache:false,
			success:function(suc){
				flag = true;
				$(than).parent().html("已移除圈子");
				showNotic(suc.msg,suc.url);
				//if(suc.url) location.href=suc.url;
			},
			error:function(err){
				flag = false;;
				showNotic(err.responseText);
			}
		});
	});
		
	$(document).on('click','.delshop',function () {		
		var than=this;
		var _t = $(this).attr("t");
		var _v = "您确认"+_t+"该产品吗？";
		
		if(confirm(_v)==false){
			return false;	
		}
		var _w = $(this).attr("w");
		var _id = $(this).attr("id");
		
		$.ajax({
			url:_url+"data/ajax.php",
			type:"POST",
			data:{tag:"delshop",w:_w,t:_t,id:_id},
			dataType:"JSON",
			cache:false,
			success:function(suc){
				flag = true;
				if(_t=="上架")
				{
					$(than).attr("t","下架");
					$(than).html("下架");
				}
				else if(_t=="下架")
				{
					$(than).attr("t","上架");
					$(than).html("上架");
				}
				showNotic(suc.msg,suc.url);
				//if(suc.url) location.href=suc.url;
			},
			error:function(err){
				flag = false;
				showNotic(err.responseText);
			}
		});
	});
	
	$(document).on('click','.delkq',function () {		
		var than=this;
		var _t = $(this).attr("t");
		var _v = "您确认"+_t+"该卡券吗？";
		
		if(confirm(_v)==false){
			return false;	
		}
		var _w = $(this).attr("w");
		var _id = $(this).attr("id");
		var onefan = $(this).attr("onefan");
		var kqnum = $(this).attr("kqnum");
		
		$.ajax({
			url:_url+"data/ajax.php",
			type:"POST",
			data:{tag:"delkq",w:_w,t:_t,id:_id,onefan:onefan,kqnum:kqnum},
			dataType:"JSON",
			cache:false,
			success:function(suc)
			{
				if(suc.st=="1")
				{
					flag = true;				
					if(_t=="上架")
					{
						$(than).attr("t","下架");
						$(than).html("下架");
					}
					else if(_t=="下架")
					{
						$(than).attr("t","上架");
						$(than).html("上架");
					}
				}
				showNotic(suc.msg,suc.url);
				//if(suc.url) location.href=suc.url;
			},
			error:function(err){
				flag = false;
				showNotic(err.responseText);
			}
		});
	});
	
	$(document).on('click','.url',function (e) {
		var _u=$(this).attr("u");
		var _p=$(this).attr("p");
		var _url = _u+".php";
		if(_p&&_p!="")
			_url = _url+"?"+_p
		location.href=_url;	
	});
	
	$(".click").click(function(){
		var _u=$(this).attr("u");
		var _p=$(this).attr("p");
		var _c=$(this).attr("c");
		if(_c!=undefined&&_c!=""){
			if(!confirm(_c))return false;	
		}
		if(flag)
			return false;
		flag = 1;
		$.ajax({
			url:_url+"data/ajax.php",
			type:"POST",
			data:_p,
			dataType:"JSON",
			cache:false,
			success:function(suc){
				flag = true;
				showNotic(suc.msg,suc.url);
				
				//if(suc.url) location.href=suc.url;
			},
			error:function(err){
				flag = false;;
				showNotic(err.responseText);
			}
		});
	});
	
	$("img").error(function(){
		$(this).attr("src","../image/nopic.png");
	});
	$("a").click(function(e){
		var _href = $(this).attr("href");
		if(_href ==""||_href == "../")
			e.preventDefault();
	});
		
	
});


//js保留两位小数，自动补充零
function returnFloat(value){
 var value=Math.round(parseFloat(value)*100)/100;
 var xsd=value.toString().split(".");
 if(xsd.length==1){
 value=value.toString()+".00";
 return value;
 }
 if(xsd.length>1){
 if(xsd[1].length<2){
 value=value.toString()+"0";
 }
 return value;
 }
}


//写cookies 
function setCookie(name,value) 
{ 
    var Days = 30; 
    var exp = new Date(); 
    exp.setTime(exp.getTime() + Days*24*60*60*1000); 
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString(); 
} 

//读取cookies 
function getCookie(name) 
{ 
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
    if(arr=document.cookie.match(reg))
        return unescape(arr[2]); 
    else 
        return null; 
} 

//删除cookies 
function delCookie(name) 
{ 
    var exp = new Date(); 
    exp.setTime(exp.getTime() - 1); 
    var cval=getCookie(name); 
    if(cval!=null) 
        document.cookie= name + "="+cval+";expires="+exp.toGMTString(); 
} 

function showImg(url){
	if($("#outerdiv").length<1){
		var _sty = '<style type="text/css">.close{ width:30px; height:30px; background:url(http://www.rrzmt.com/gglm/images/close.png) no-repeat 0 0; position:fixed; top:5px; right:30px; _position:absolute; _top:expression(documentElement.scrollTop+5+"px"); cursor:pointer;}.close:hover{ background:url(http://www.rrzmt.com/gglm/images/close.png) no-repeat 0px -30px;opacity:1;}</style>';
		var _con ='<div id="outerdiv" style="position:fixed;top:0;left:0;background:rgba(0,0,0,0.7);z-index:999;width:100%;height:100%;display:none;">    <a class="close" onclick="$(\'#outerdiv\').fadeOut(\'fast\');"></a><div id="innerdiv" style="position:absolute;"><img id="bigimg" style="border:5px solid #fff;" src="" /></div></div> ';
		
		$("body").append(_sty+_con);
	}
	var src = url;
	$("#bigimg").attr("src", src);
	$("<img/>").attr("src", src).load(function(){
		var windowW = $(window).width();
		var windowH = $(window).height();
		var realWidth = this.width;
		var realHeight = this.height;
		var imgWidth, imgHeight;
		var scale = 0.95;
		
		if(realHeight>windowH*scale) {
			imgHeight = windowH*scale;
			imgWidth = imgHeight/realHeight*realWidth;
			if(imgWidth>windowW*scale) {
				imgWidth = windowW*scale;
			}
		} else if(realWidth>windowW*scale) {
			imgWidth = windowW*scale;
			imgHeight = imgWidth/realWidth*realHeight;
		} else {
			imgWidth = realWidth;
			imgHeight = realHeight;
		}
		$("#bigimg").css("width",imgWidth);
		$("#bigimg").wrap("<a href='"+src+"' target='_blank'></a>").attr("title","点击查看原图");
		var w = (windowW-imgWidth)/2;
		var h = (windowH-imgHeight)/2;
		$("#innerdiv").css({"top":h, "left":w});
		$("#outerdiv").fadeIn("fast");
	});
	$("#outerdiv").click(function(){
		$(this).fadeOut("fast");
	});
}


