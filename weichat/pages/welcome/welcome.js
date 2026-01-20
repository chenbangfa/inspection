// pages/hycenter/hycenter.js
var app = getApp()
Page({
  /**
   * 页面的初始数据
   */
  data: {
    navbarData: {
      showCapsule:1, //是否显示左上角图标   1表示显示    0表示不显示
      title: "工作台", //导航栏 中间的标题
    },
    // 此页面 页面内容距最顶部的距离
    height: app.globalData.height, 
    teamlist:[],
    hyid:0
  },
  
  changeteam:function(e)
  {
    var hyid=e.currentTarget.dataset.hyid;
    wx.request({
      url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
      data: {
        tag: "changeteam",
        hyid:hyid,
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res)
      {
        app.globalData.hyid = res.data.id;
        app.globalData.tid = res.data.tId;
        app.globalData.openid = res.data.weid;
        app.globalData.hytel = res.data.hytel;
        app.globalData.hyIdentity=res.data.hyIdentity;
        app.globalData.hyname=res.data.hyname;
        app.globalData.refresh=1;
        
        wx.showToast({
          title: '登录成功',
          duration:2000, 
          icon: 'success',
          success: function ()
          {
            setTimeout(function ()
            {
              wx.switchTab({url: '../hycenter/hycenter',})
            },2000)
          }
        })      
      }
    })
 
  },
  tuichu:function(e)
  {
    wx.exitMiniProgram({success: (res) => {}})
  },
loadcenter:function(e)
{
  var hytel=app.globalData.hytel;
  wx.request({
    url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
    data: {
      tag: "welcome",
      hytel:hytel,
    },
    header: {
      'content-type': 'application/x-www-form-urlencoded'
    },
    success: function (res)
    {
      wx.hideLoading();
      e.setData({
        teamlist:res.data.teamlist,
        hyid:app.globalData.hyid
      });
    }
  })
},
  /**
   * 生命周期函数--监听页面加载
   */

onLoad: function (options)
{
  wx.showLoading({title: '数据加载中...',mask: true });
  var that=this;
  if (app.globalData.employ && app.globalData.employ!= '') 
  {
    that.loadcenter(that);
  }else
  {
    app.employCallback = employ => {
      if (employ!= '')
      {
        that.loadcenter(that);
      }
    }
  }
},

 
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    
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

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function ()
  {
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

})