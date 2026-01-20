/*
* 必须要定义的变量
* shareTitle // 分享标题
* shareDesc //分享描述
* shareImgUrl // 分享图标
*
* 非必须定义的变量
* shareUrl  // 分享链接
* shareType  // 分享类型,music、video或link，不填默认为link
* shareDataUrl  // 如果type是music或video，则要提供数据链接，默认为空
* */
var myURL = window.location.href.split('#')[0];
if(typeof shareUrl == 'undefined') {
    var shareUrl = window.location.href.split('?')[0];
}
if(typeof shareDataUrl == 'undefined'){
    var shareType='link',shareDataUrl='';
}
/*
$(document).ready(function(){
    var $windowWidth = $(window).width();
    setTimeout(function(){
        $windowWidth = $(window).width();
        if($windowWidth > 640){
            $windowWidth = 640;
        }
        $("html").css("font-size",(100/320) * $windowWidth + "px");
    },100);


    $(window).resize(function(){
        $windowWidth = $(window).width();
        if($windowWidth > 640){
            $windowWidth = 640;
        }
        $("html").css("font-size",(100/320) * $windowWidth + "px");
    });
});
*/
//微信config用
var enURL = encodeURIComponent(myURL);
//微信检查并开启微信config

if(isWeixin()){
    // alert("在用微信 "+ver);
    wxConfigToken (enURL);
}else if(isApp(myURL)){
    btnChange();
    // alert("不是微信 "+ver);
    // wxConfigToken (enURL);
}

//微信config ok后启用
wx.ready(function(){

    // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
	
	
    wx.onMenuShareTimeline({
        title: shareTitle, // 分享标题
        link: shareUrl, // 分享链接
        imgUrl: shareImgUrl, // 分享图标
        success: function () {
            // 用户确认分享后执行的回调函数
            // alert("success");
            console.log("success");
        },
        cancel: function () {
            // 用户取消分享后执行的回调函数
            // alert("cancel");
        }
    });
    wx.onMenuShareQQ({
        title: shareTitle, // 分享标题
        desc: shareDesc, //分享描述
        link: shareUrl, // 分享链接
        imgUrl: shareImgUrl, // 分享图标
        success: function () {
            // 用户确认分享后执行的回调函数
            console.log("success");
        },
        cancel: function () {
            // 用户取消分享后执行的回调函数
        }
    });
    wx.onMenuShareAppMessage({
        title: shareTitle, // 分享标题
        desc: shareDesc, //分享描述
        link: shareUrl, // 分享链接
        imgUrl: shareImgUrl, // 分享图标
        type: shareType, // 分享类型,music、video或link，不填默认为link
        dataUrl: shareDataUrl, // 如果type是music或video，则要提供数据链接，默认为空
        success: function () {
            // 用户确认分享后执行的回调函数
            // alert("success");
            console.log("success");
        },
        cancel: function () {
            // 用户取消分享后执行的回调函数
            // alert("cancel");
        }
    });

});
// 微信config
function wxConfigToken (url){
    var _url =url;
	$.getJSON("https://www.bnng.net/anquan/data/ajax.php",{tag:"wxconfig",url:_url},function(dat){
		wx.config({
                debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                appId: dat.appId, // 必填，公众号的唯一标识
                timestamp: dat.timestamp - 0 , // 必填，生成签名的时间戳
                nonceStr: dat.nonceStr, // 必填，生成签名的随机串
                signature: dat.signature,// 必填，签名，见附录1
                jsApiList: [
                    'onMenuShareTimeline',
                    'onMenuShareAppMessage',
                    'onMenuShareQQ',
                    'onMenuShareQQ',
					'chooseImage',
					'previewImage',
					'uploadImage',
					'downloadImage',
					'getLocalImgData',
					'getLocation',
					'openLocation',
					'scanQRCode'
                ], // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
				openTagList: ['wx-open-launch-weapp']
           });
		   
	});
	
}
//微信判别
function isWeixin (){
    if(window.navigator.userAgent.indexOf("MicroMessenger") === -1 ){
        return false;
    }else{return true}
}
//IPhone判别
function isIPhone (){
	var ua =window.navigator.userAgent.toLowerCase();
    var s = ua.match(/IPhone/i);
    if(s == null){
        return false;
    }else{
        return true;
    }
}
//app判别
function isApp (url){
    if(url.indexOf("gosportapp") === -1 ){
        return false;
    }else{return true}
}

function btnChange(){
    console.log("change");
    $("#btnHai").attr({"href":"gosport://business_detail?business_id=1928&category_id=1"});
    $("#btnYu").attr({"href":"gosport://business_detail?business_id=16755&category_id=1"});
    $("#btnLong").attr({"href":"gosport://business_detail?business_id=1927&category_id=1"});
    // $(".Downbuttom").attr({"href":"gosport://business_detail?business_id=16681&category_id=1"});
    $(".Downbuttom").css({"display":"none"});
}
