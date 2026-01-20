// pages/shopadd/shopadd.js
var app = getApp();
var QRCode = require('../../utils/weapp-qrcode.js')
Page({
  /**
   * 页面的初始数据
   */
  data: {    
    navbarData: {
      showCapsule: 1, //是否显示左上角图标   1表示显示    0表示不显示
      title: '流动摊位详情', //导航栏 中间的标题
    },
    height: app.globalData.height, 
    ycshow:false,
    id:0,
    hyIdentity:0
  },
  del:function(e)
  {
    wx.showToast({
      title: '即将开通!',
      duration:2000, 
      icon: 'none',
    })  
  },
  closeycshow:function(e)
  {
    this.setData({
      ycshow: false,
    })    
  },
  delstall:function(e)
  {
    var that=this;    
    var id=this.data.id;
    wx.showModal({
      title: "提示",
      content: "是否删除",
      success: function (res)
      {
        if (res.confirm)
        {
          wx.request({
            url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
            data: {
              tag: "del",  
              i:id,
              t:'stallInspect',
            },
            header: {
              'content-type': 'application/x-www-form-urlencoded'
            },
            success: function (res)
            {
              app.globalData.refresh=1;
              wx.showToast({
                title: '删除成功',
                duration:2000, 
                icon: 'success',
                success: function ()
                {
                  setTimeout(function ()
                  {
                    wx.navigateBack(0)
                  },2000)
                }
              })      
            }
          })
        }else
        {
          console.log("取消")
        }
      }
    })
  },
  getcode:function()
  {
    var that=this;
    var tid=app.globalData.tid;
    var id=that.data.id;
    wx.showToast({title: '二维码生成中',icon: 'loading',duration: 1000})
    wx.request({
      // 获取token
      url: 'https://xj.tajian.cc/servers/mycode.php',
      data: {
        tag: 'gettallCode', 
        tid:tid,
        stallid:id,
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      }, 
      success: function (res)
      {
        console.log(res.data);
        that.setData({
          ycshow:true,
          tCode:res.data.path
        });
      }
    })
  },

  onLoad(options)
  {    
    wx.showLoading({title: '数据加载中...',mask: true });    
    var hyIdentity=app.globalData.hyIdentity;
    if(options.scene)
      var id = decodeURIComponent(options.scene);
    else
      var id=options.id;
    this.setData({
      id:id,
      hyIdentity:hyIdentity
    })
    var that=this;
    if (app.globalData.employ && app.globalData.employ!= '') 
    {
      that.getblueinfo();
    }else
    {
      app.employCallback = employ => {
        if (employ!= '')
        {
          that.getblueinfo();
        }
      }
    }
  }, 
  getblueinfo:function() 
{
  var that=this;
  var id=that.data.id;
  var tid=app.globalData.tid;
  wx.request({
    url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
    data: {
      tag: "getstallinfo",
      id:id,
      tid:tid
    },
    header: {
      'content-type': 'application/x-www-form-urlencoded'
    },
    success: function (res)
    {      
      that.setData({
        stallinfo:res.data.blueinfo,
        timelist:res.data.timelist
      })
      wx.hideLoading();
    }
  })
  },
  
 // 长按保存图片
 saveImage(e){
  let url = e.currentTarget.dataset.imgurl;
  //用户需要授权
  wx.getSetting({
   success: (res) => {
    if (!res.authSetting['scope.writePhotosAlbum']) {
     wx.authorize({
      scope: 'scope.writePhotosAlbum',
      success:()=> {
       // 同意授权
       this.saveImg1(url);
      },
      fail: (res) =>{
       console.log(res);
      }
     })
    }else{
     // 已经授权了
     this.saveImg1(url);
    }
   },
   fail: (res) =>{
    console.log(res);
   }
  })  
 },
 
 saveImg1(url){
  wx.getImageInfo({
   src: url,
   success:(res)=> {
    let path = res.path;
    wx.saveImageToPhotosAlbum({
     filePath:path,
     success:(res)=> { 
      console.log(res);
     },
     fail:(res)=>{
      console.log(res);
     }
    })
   },
   fail:(res)=> {
    console.log(res);
   }
  })
 },

  onReady() {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow()
  {
    if(app.globalData.refresh==1)
    {
      app.globalData.refresh=0;
      this.getblueinfo();
    }
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide() {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload() {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh() {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom() {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage() {

  }
})