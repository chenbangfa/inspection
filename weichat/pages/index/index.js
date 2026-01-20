//index.js
//获取应用实例
var app = getApp();
Page({
  data: {
    // 此页面 页面内容距最顶部的距离
    height: app.globalData.height, 
  },

  scanCode: function () {
    var that = this;
    wx.scanCode({ //扫描API
      success(res) { //扫描成功
        
        var scanres=res.result;
        wx.navigateTo({url: '../blueinspect/blueinspect?id='+scanres})
      },
      fail: (res) => 
      {
        //接口调用失败的回调函数
      },
    })
  },
  onLoad: function (options) 
  {
    var that=this;
    if (app.globalData.employ && app.globalData.employ!= '') 
    {
      that.loadIndex(that);
    }else
    {
      app.employCallback = employ => {
        if (employ!= '')
        {
          that.loadIndex(that);
        }
      }
    }    
  },
  loadIndex:function(e)
  {
    var hyid=app.globalData.hyid;
    var tid=app.globalData.tid;
    if(hyid==0||tid==0)
    {
      wx.redirectTo({
        url: '../welcome/welcome',
      })
    }else{
      wx.request({
        url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
        data: {
          tag: "getindex",
          tid:tid,
          hyid:hyid,
        },
        header: {
          'content-type': 'application/x-www-form-urlencoded'
        },
        success: function (res)
        {
          wx.hideLoading();
          e.setData({
            rcxj:res.data.rcxj,
            zqxj:res.data.zqxj,
            ycnum:res.data.ycnum,
            jrxj:res.data.jrxj,
            bzxj:res.data.bzxj,
            byxj:res.data.byxj,
            bjxj:res.data.bjxj,
            bnxj:res.data.bnxj,
          });
        }
      })
    }
  },
  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function ()
  {
    wx.showShareMenu({
      withShareTicket: true,
      menus: ['shareAppMessage', 'shareTimeline']
    })
    return {
      title: "安全生产隐患巡检平台",
      path: 'pages/index/index',
      imageUrl: ""
    }      
  },  
  onShareTimeline: function ()
  {
    return{
      title: '安全生产隐患巡检平台',
      query:
      {
        key: 'value' 
      },
      imageUrl: '' //默认logo
    }    
  },
  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function ()
  {
    if(app.globalData.refresh==1)
    {
      app.globalData.refresh=0;
      this.loadIndex(this);
    }
  },
  
  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {
   
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
    
  },
})
