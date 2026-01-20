// pages/hycenter/hycenter.js
var app = getApp()
Page({
  /**
   * 页面的初始数据
   */
  data: {
    navbarData: {
      showCapsule:0, //是否显示左上角图标   1表示显示    0表示不显示
      title: "工作台", //导航栏 中间的标题
    },
    // 此页面 页面内容距最顶部的距离
    height: app.globalData.height, 
    hyid:0,
    hyName:'',
    tName:'',
    bluecount:0,
    bluegroup:0,
    hyIdentity:0,
  },

loadcenter:function(e)
{
  wx.showLoading({title: '数据加载中...',mask: true });
  var hyid=app.globalData.hyid;
  var hyIdentity=app.globalData.hyIdentity;
  wx.request({
    url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
    data: {
      tag: "mycenter",
      hyid:hyid,
    },
    header: {
      'content-type': 'application/x-www-form-urlencoded'
    },
    success: function (res)
    {
      console.log(res);
      wx.hideLoading();
      e.setData({
        hyid:hyid,
        hyIdentity:hyIdentity,
        hyName:res.data.memarr[0].hyName,
        tName:res.data.memarr[0].tName,
        bluecount:res.data.memarr[0].bluecount,
        bluegroup:res.data.memarr[0].bluegroup,
      });      
    }
  })
},
onLoad: function (options)
{
  var that=this;
  var hyIdentity=app.globalData.hyIdentity;
  this.setData({
    hyIdentity:hyIdentity,
  })
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
  
  callphoto:function(e)
  {
    wx.makePhoneCall({
      phoneNumber:'18508518768', 
    })
  },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () 
  {    
    if(app.globalData.refresh==1)
    {
      app.globalData.refresh=0;
      this.loadcenter(this)
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

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function ()
  {
    this.loadcenter(this)    
    wx.stopPullDownRefresh();//停止刷新操作
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

})