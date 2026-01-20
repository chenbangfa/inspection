
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    navbarData: {
      showCapsule: 1, //是否显示左上角图标   1表示显示    0表示不显示
      title: '巡检详情', //导航栏 中间的标题
    },
    // 此页面 页面内容距最顶部的距离
    height: app.globalData.height, 
    dtinfo: [],
    id:0,
    zgAsk:'',
    zgTime:'',    
    showcode:false,
    tCode:''
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options)
  {
    this.setData({
      id:options.id
    })
    this.loaddongtai(this);
  },
  inputWacth(e)
  {
    let newValue = e.target.dataset.input;
    this.setData(
      {
        [newValue]: e.detail.value
      }
    )
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
  save:function(e)
  {
    var that=this;
    var zgAsk= that.data.zgAsk;
    var zgTime= that.data.zgTime;
    if(zgAsk=='')
    {
      wx.showToast({
        title: '请输入整治要求!',
        icon: 'none',
        duration: 1500
     })
     return false;
    }else if(zgTime=='')
    {
      wx.showToast({
        title: '请输入整治时限!',
        icon: 'none',
        duration: 1500
     })
     return false;
    }{
    wx.request({
      url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
      data: {
        tag: "inspectsave",
        id:that.data.id,
        zgAsk:zgAsk,
        zgTime:zgTime
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res)
      {
        wx.showToast({
          title: '保存成功',
          duration:1500, 
          icon: 'success',
          success: function ()
          {
            setTimeout(function ()
            {
              that.loaddongtai(that);
            },1500)
          
          }
        })  
      }
    })
  }
  },

  loaddongtai:function(e)
  {
    wx.showLoading({title: '加载中',mask: true });
    wx.request({
      url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
      data: {
        tag: "inspectinfo",
        id:e.data.id
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res)
      {
        console.log(res)
        wx.hideLoading();
        e.setData({
          dtinfo:res.data.dtinfo,
          zgAsk:res.data.dtinfo[0].zgAsk,
          zgTime:res.data.dtinfo[0].zgTime
        });
     
      }
    })
  },
   /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function ()
  {
  },
  onShareTimeline: function ()
  {
  },
})