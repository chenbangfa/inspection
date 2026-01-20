// pages/login/login.js
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    navbarData: {
      showCapsule: 1, //是否显示左上角图标   1表示显示    0表示不显示
      title: '流动摊位登记', //导航栏 中间的标题
    },
    // 此页面 页面内容距最顶部的距离
    height: app.globalData.height,
    tid:0,
    stallid:0,
    tName:'',
    hyName:'',
    hyTel:'',
    stalljyhy:'',
    tanwei:0,
    stallgj:'image/addpic.png',    
    yyzzimg:'image/yyzz.png',
    sfzzmimg:'image/sfzzm.png',
    sfzfmimg:'image/sfzfm.png',
    bmsfz:0,
    bmjygj:0,
  },
  /**
   * 生命周期函数--监听页面加载
   */    
  onLoad: function (options) 
  {
    if(!options.scene)
    {
      wx.showToast({
        title: '没找到摊位!',
        duration:2000, 
        icon: 'error',
        success: function ()
        {
          setTimeout(function ()
          {
            wx.reLaunch({
              url: '../index/index',
            })
          },2000)
        }
      })  
      return;
    }
    this.setData({
      stallid:decodeURIComponent(options.scene),
    })
    if (app.globalData.employ && app.globalData.employ!= '') 
    {
      this.getstall();
    }else
    {
      app.employCallback = employ => {
        if (employ!= '')
        {
          this.getstall();
        }
      }
    }
  },     
  openditu:function(e)
  {
    var add=e.currentTarget.dataset.add;
    var name=e.currentTarget.dataset.names;
    var latitude=e.currentTarget.dataset.lat;
    var longitude=e.currentTarget.dataset.lon;
    wx.openLocation({
      latitude: parseFloat(latitude),  //经度
      longitude:parseFloat(longitude), //维度
      name: name,  // 位置名
      address:add,  // 要去的地址详情说明
      scale: 12,   // 地图缩放级别,整形值,范围从1~28。默认为最大
      success: function (data) {
        console.log(data)
      },
      fail(res) {
        console.log(res) // getLocation:fail the api need to be declared in the requiredPrivateInfos field in app.json
      },
      complete(){
        wx.hideLoading()
      }
    })

  },
  getstall:function ()
  {
    var that=this;
    const stallid = this.data.stallid;//decodeURIComponent(options.scene);
    wx.request({
      url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
      data: {
        tag: "gettallid",
        stallid:stallid,
        hyid:app.globalData.hyid,
        wxid:app.globalData.openid
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res)
      {
        app.globalData.tid=res.data.tid;
        app.globalData.hyIdentity=res.data.hyIdentity;
        app.globalData.hyid=res.data.hyid;
        if(res.data.blueinfo.length>0)
        {
        that.setData({
          stallinfo:res.data.blueinfo,
          tanwei:res.data.tanwei,
          stallid:stallid,
          bmsfz:res.data.blueinfo[0].bmsfz,
          bmjygj:res.data.blueinfo[0].bmjygj,
        });
      }else{
        wx.showToast({
          title: '没找到摊位!',
          duration:2000, 
          icon: 'error',
          success: function ()
          {
            setTimeout(function ()
            {
              wx.reLaunch({
                url: '../index/index',
              })
            },2000)
          }
        })  
      }
      }
    })  
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
  bindUpload: function (e) 
  {
    var str=e.currentTarget.dataset.sfzinfo;
    var that = this
    wx.chooseMedia({
      count: 1, // 最多可以选择的文件个数
			mediaType: ['image'], // 文件类型
			sizeType: ['compressed'], // 是否压缩所选文件  compressed 压缩  original 原始
      sourceType:['camera','album'],   
      success: function (res) 
      {        
        var tempFiles = res.tempFiles
        for (var i = 0; i < tempFiles.length; i++)
        {
          var tempFilePath=tempFiles[i].tempFilePath;
          wx.uploadFile({
            url: 'https://xj.tajian.cc/servers/upimg.php',
            filePath: tempFilePath,
            name: "file",
            formData: {
              'tag': 'upimg'
            },
            success: function (res)
            {
              var reimg=JSON.parse(res.data)
              if (reimg.st == 1) {
                wx.showToast({
                  title: "上传成功",
                  icon: "none",
                  duration: 1500
                })  
                that.setData({
                  [str]: reimg.url
                })
              }
            },
            fail: function (err) {
              wx.showToast({
                title: "上传失败",
                icon: "none",
                duration: 2000
              })
            },
            complete: function (result) {
              console.log(result.errMsg)
            }
          })
        }
      }
    })
  },
  saveadd:function(e)
  {
    var bmsfz=this.data.bmsfz;
    var bmjygj=this.data.bmjygj;
    var stallid=this.data.stallid;
    var tid=app.globalData.tid;
    var hyid=app.globalData.hyid;
    var wxid=app.globalData.openid;
    var hyName=this.data.hyName;
    var hyTel=this.data.hyTel;
    var stalljyhy=this.data.stalljyhy;
    var stallgj=this.data.stallgj;    
    var yyzzimg=this.data.yyzzimg;
    var sfzzmimg=this.data.sfzzmimg;
    var sfzfmimg=this.data.sfzfmimg;
    const regu=/^1\d{10}$/;
    if(hyName=='')
    {
      wx.showToast({
          title: '请输入您的姓名!',
          icon: 'none',
          duration: 1500
          })
          return false;
    }else if(hyTel=="")
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
    }if(bmsfz=='1'&&sfzzmimg=='image/sfzzm.png')
    {
      wx.showToast({
          title: '请上传身份证正面!',
          icon: 'none',
          duration: 1500
          })
          return false;
    }else if(bmsfz=='1'&&sfzfmimg=='image/sfzfm.png')
    {
      wx.showToast({
          title: '请上传身份证反面!',
          icon: 'none',
          duration: 1500
          })
          return false;
    } else if(bmsfz=='2'&&yyzzimg=='image/yyzz.png')
    {
      wx.showToast({
          title: '请上传营业执照!',
          icon: 'none',
          duration: 1500
          })
          return false;
    }else if(bmjygj=='1'&&yyzzimg=='image/addpic.png')
    {
      wx.showToast({
          title: '请上传营业执照!',
          icon: 'none',
          duration: 1500
          })
          return false;
    } else
    {     
        wx.request({
          url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
          data: {
            tag: "stallreg",
            stallid:stallid,
            hyid:hyid,
            tid:tid,
            wxid:wxid,
            hyName:hyName,
            hyTel:hyTel,
            stalljyhy:stalljyhy,
            stallgj:stallgj,    
            yyzzimg:yyzzimg,
            sfzzmimg:sfzzmimg,
            sfzfmimg:sfzfmimg,
            bmsfz:bmsfz
          },
          header: {'content-type': 'application/x-www-form-urlencoded'},
          success: function (res)
          {
            app.globalData.refresh=1;
              wx.showToast({
                title: res.data.st,
                icon: 'none',
                duration: 3000,
                success: function ()
                {
                  setTimeout(function ()
                  {
                    wx.switchTab({
                      url: '../index/index.js',
                    })
                  },3000)
                }
                })         
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

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})