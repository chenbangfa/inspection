// pages/dtlist/dtlist.js
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    navbarData: {
      showCapsule: 1, //是否显示左上角图标   1表示显示    0表示不显示
      title: '流动摊位区域管理', //导航栏 中间的标题
    },
    height: app.globalData.height, 
    odlist: [],
    page:0,
    isdata:false,
    group:[],
    count:0,
    hyIdentity:-1,
  }, 
 
  onLoad(options)
  {
   
    var that=this;
    var page=that.data.page;
    if (app.globalData.employ && app.globalData.employ!= '') 
    {
      if(options.scene)
      {
        app.globalData.tid=decodeURIComponent(options.scene);
      }
      that.loadmydongtai(that,page);
    }
    else
    {
      app.employCallback = employ => {
      if (employ!= '')
      {
        if(options.scene)
        {
          app.globalData.tid=decodeURIComponent(options.scene);
        }
        that.loadmydongtai(that,page);
      }
      }
    }
  },
  closeycshow:function(e)
  {
    this.setData({
      ycshow: false,
    })    
  },
  getcode:function()
  {
    var that=this;
    var tid=app.globalData.tid;
    wx.showToast({title: '二维码生成中',icon: 'loading',duration: 1000})
    wx.request({
      // 获取token
      url: 'https://xj.tajian.cc/servers/mycode.php',
      data: {
        tag: 'gettalllist', 
        tid:tid,
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
  loadmydongtai:function(e,page)
  {
    wx.showLoading({title: '数据加载中...',mask: true });
      var tid=app.globalData.tid;
      var wxid=app.globalData.openid;
      var hyid=app.globalData.hyid;
      var hyIdentity=app.globalData.hyIdentity;
      wx.request({
        url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
        data: {
          tag: "getstalllist",
          p:page+1,
          tid:tid,
          hyid:hyid,
          wxid:wxid,
          hyIdentity:hyIdentity
        },
        header: {
          'content-type': 'application/x-www-form-urlencoded'
        },
        success: function (res)
        {
          wx.hideLoading();
          app.globalData.hyid=res.data.hyid;
          app.globalData.hyIdentity=res.data.hyIdentity;
          if(res.data.odlist.length>0)
          {
            e.setData({
              ["odlist["+page+"]"]:res.data.odlist,
              page:page,
              isdata:false,
              count:res.data.count,
              hyIdentity:res.data.hyIdentity
            });
          }else{
            e.setData({
              ["odlist["+page+"]"]:[],
              page:page-1, 
              isdata:true,
              hyIdentity:res.data.hyIdentity
            });
          }
        }
      })
  },  
  onPullDownRefresh: function ()
  {
    this.setData({
      odlist: []
    })
    this.loadmydongtai(this,0);
    wx.stopPullDownRefresh();//停止刷新操作
  },
  onReady() {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow() {
    if(app.globalData.refresh==1)
    {
      app.globalData.refresh=0;
      this.loadmydongtai(this,0);
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
   * 页面上拉触底事件的处理函数
   */
  onReachBottom() 
  {
    if(!this.data.isdata)
    {
    wx.showLoading({title: '数据加载中...',mask: true });
    var that=this;
    var page=that.data.page;
    page=page+1;
    that.loadmydongtai(that,page);
    }
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage() {

  }
})