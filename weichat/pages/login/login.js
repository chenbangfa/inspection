// pages/login/login.js
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    navbarData: {
      showCapsule:0, //是否显示左上角图标   1表示显示    0表示不显示
      title: '安全生产隐患巡检平台', //导航栏 中间的标题
    },
    // 此页面 页面内容距最顶部的距离
    height: app.globalData.height,
    hyTel:'',
    hyPwd:'',
  },
  /**
   * 生命周期函数--监听页面加载
   */    
  onLoad: function (options) 
  {
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
  getPhoneNumber (e)
{
  var that = this;
  //通过绑定手机号登录
  var code=e.detail.code;
　　//用code传给服务器调换session_key
  wx.request({
   url: 'https://xj.tajian.cc/servers/mycode.php', //接口地址
   data: {
    tag: "gettel",
    code: code,
   },
   success: function (res) 
   {
     console.log(res.data)
     var errcode=res.data.errcode;
     if(errcode==0)
     {
      var phoen = res.data.phone_info.phoneNumber;
      that.setData({
      hyTel: phoen,
      });
     }
   }
  })
  },
  saveadd:function(e)
  {
    var hyTel=this.data.hyTel;
    var hyPwd=this.data.hyPwd;
    const regu=/^1\d{10}$/;
    if(hyTel=="")
    {
          wx.showToast({
          title: '请输入联系电话!',
          icon: 'none',
          duration: 1500
          })
          return false;
    }else if(!regu.test(hyTel))
    {
          wx.showToast({
          title: '请正确输入手机号!',
          icon: 'none',
          duration: 1500
          })
          return false;
    }else if(hyPwd.length<6)
    {
        wx.showToast({
        title: '密码长度至少6位!',
        icon: 'none',
        duration: 1500
        })
        return false;
    }else
    {     
        wx.request({
          url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
          data: {
            tag: "login",
            hyTel:hyTel,
            hyPwd:hyPwd,
          },
          header: {'content-type': 'application/x-www-form-urlencoded'},
          success: function (res)
          {
            if(res.data.st==1)
            {
            app.globalData.hyid = res.data.hyid;
            app.globalData.tid = res.data.tid;
            app.globalData.refresh=1;

            wx.showToast({
              title: '登陆成功',
              duration:1000, 
              icon: 'success',
              success: function ()
              {
                setTimeout(function ()
                {
                  wx.switchTab({
                    url: '../hycenter/hycenter',
                  })
                },1000)
              }
            
            })    
          }else
          {
            wx.showToast({
              title: res.data.msg,
              duration:3000,
              icon:'error'
            })
          }
          }
     })
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
  onShow: function ()
  {
   //this.loadbusiness(this);
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
    wx.showLoading({title: '刷新中',mask: true });
    this.loadlogin(this);    
    wx.stopPullDownRefresh();//停止刷新操作
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})