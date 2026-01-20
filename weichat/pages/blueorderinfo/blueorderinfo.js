// pages/shopadd/shopadd.js
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {    
    navbarData: {
      showCapsule: 1, //是否显示左上角图标   1表示显示    0表示不显示
      title: '巡检记录', //导航栏 中间的标题
    },
    height: app.globalData.height, 
    imgs: [],
    count: 9,
    clickFlag:true, //防重复点击 
    hyaddress:'',
    ycshow:false,
    proid:0,
    xjmsg:'',
    odInfo:'',
    hyname:'',
    odtogether:'',
    bluePro:[],
    id:0,
    dropPhoto:'image/nopic.png',
    addTime:'',
    odState:0,    
    showcode:false,
  },
  
  showcode:function(e)
  {
    var imgurl=e.currentTarget.dataset.imgurl;
    this.setData({
      tCode:imgurl,
      showcode:true
    })
  },
  hidecode: function ()
  {
    this.setData({
      showcode: false,
    });
  },
  onLoad(options)
  {    
    wx.showLoading({title: '数据加载中...',mask: true });
    var id=options.id;
    this.setData({
      id:id
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
  wx.request({
    url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
    data: {
      tag: "getblueorderinfo",
      id:id
    },
    header: {
      'content-type': 'application/x-www-form-urlencoded'
    },
    success: function (res)
    {      
      wx.hideLoading();
      that.setData({
        dropNo:res.data.odlist[0].dropNo,
        dropName:res.data.odlist[0].dropName,
        addTime:res.data.odlist[0].addTime,
        odInfo:res.data.odlist[0].odInfo,
        hyname:res.data.odlist[0].hyname,
        odtogether:res.data.odlist[0].odtogether,
        odState:res.data.odlist[0].odState,
        bluePro:res.data.prolist,
        imgs:res.data.imgs,
        issfz:res.data.odlist[0].issfz,
        sfzzmimg:res.data.odlist[0].sfzzmimg,
        sfzfmimg:res.data.odlist[0].sfzfmimg,
        yyzzimg:res.data.odlist[0].yyzzimg,
      })
    }
  })
  },
  onReady() {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow() {

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