// pages/dtlist/dtlist.js
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    navbarData: {
      showCapsule: 1, //是否显示左上角图标   1表示显示    0表示不显示
      title: '我的摆摊记录', //导航栏 中间的标题
    },
    height: app.globalData.height, 
    odlist: [],
    page:0,
    isdata:false,
    group:[],
    count:0,


  }, 
 
  onLoad(options)
  {
    var that=this;
    var page=that.data.page;
    if (app.globalData.employ && app.globalData.employ!= '') 
      that.loadmydongtai(that,page);
    else
    {
      app.employCallback = employ => {
      if (employ!= '')
        that.loadmydongtai(that,page);
      }
    }
  },
  loadmydongtai:function(e,page)
  {
    wx.showLoading({title: '数据加载中...',mask: true });
      var tid=app.globalData.tid;
      var hyid=app.globalData.hyid;
      wx.request({
        url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
        data: {
          tag: "getstallrecord",
          p:page+1,
          tid:tid,
          hyid:hyid,
        },
        header: {
          'content-type': 'application/x-www-form-urlencoded'
        },
        success: function (res)
        {
          wx.hideLoading();
          if(res.data.odlist.length>0)
          {
            e.setData({
              ["odlist["+page+"]"]:res.data.odlist,
              page:page,
              isdata:false,
              count:res.data.count,
            });
          }else{
            e.setData({
              ["odlist["+page+"]"]:[],
              page:page-1, 
              isdata:true,
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