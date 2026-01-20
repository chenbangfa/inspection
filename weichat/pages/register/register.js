// pages/login/login.js
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    navbarData: {
      showCapsule: 1, //是否显示左上角图标   1表示显示    0表示不显示
      title: '加入巡检团队', //导航栏 中间的标题
    },
    // 此页面 页面内容距最顶部的距离
    height: app.globalData.height,
    tid:0,
    types:0,
    tName:'',
    hyName:'',
    hyTel:'',
    hyPwd:'',
    rePwd:'',
    imgurl:'image/addpic.png'
  },
  /**
   * 生命周期函数--监听页面加载
   */    
  onLoad: function (options) 
  {
    var that=this;
    const tid = decodeURIComponent(options.scene);
    wx.request({
      url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
      data: {
        tag: "getteam",
        tid:tid,
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res)
      {
        if(res.data.st==1)
        {
        that.setData({
          tName:res.data.tName,
          tid:tid,
        });
      }else{
        wx.showToast({
          title: '没找到团队!',
          duration:2000, 
          icon: 'error',
          success: function ()
          {
            setTimeout(function ()
            {
              wx.reLaunch({
                url: '../welcome/welcome',
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
  typechange:function(e)
  {
    this.setData({
      types:e.detail.value
    })
  },
  
  bindUpload:function(e)
  {
    var that=this;
    wx.chooseMedia({
    count: 1, // 最多可以选择的文件个数
    mediaType: ['image'], // 文件类型
    sizeType: ['compressed'], // 是否压缩所选文件  compressed 压缩  original 原始
    sourceType: ['camera','album'],       
    success: async function(res) 
    {
        wx.showLoading({title: '上传中...',mask: true });
        try {
          var tempFiles = res.tempFiles[0].tempFilePath;
          const invokeRes = await wx.serviceMarket.invokeService({
            service: 'wx79ac3de8be320b71',
            api: 'OcrAllInOne',
            data: {
              // 用 CDN 方法标记要上传并转换成 HTTP URL 的文件
              img_url: new wx.serviceMarket.CDN({
                type: 'filePath',
                filePath: tempFiles,
              }),
              data_type: 3,
              ocr_type: 7
            },
          })
          let daima ="";//社会代码
          let faren ="";//公司名称
          let gsname ="";
          let address ="";//注册地址
          let gstypes ="";//公司类型
          let business ="";//经营范围
          let zhucetime ="";//成立日期
          if(invokeRes.data.biz_license_res.reg_num)
          {
            daima =invokeRes.data.biz_license_res.reg_num.text;//社会代码
            faren =invokeRes.data.biz_license_res.legal_representative.text;//公司名称
            gsname =invokeRes.data.biz_license_res.enterprise_name.text;//法人
            address =invokeRes.data.biz_license_res.address.text;//注册地址
            gstypes =invokeRes.data.biz_license_res.type_of_enterprise.text;//公司类型
            business =invokeRes.data.biz_license_res.business_scope.text;//经营范围
            zhucetime =invokeRes.data.biz_license_res.registered_date.text;//成立日期
          }
          wx.uploadFile({
            url: 'https://xj.tajian.cc/servers/upimg.php',
            filePath:tempFiles ,
            name: "file",
            formData: {
              'tag': 'upimg'
            },
            success: function (res)
            {
              var reimg=JSON.parse(res.data)
              that.setData({
                imgurl: reimg.url,
                daima:daima,
                faren:faren,
                gsname:gsname,
                address:address,
                gstypes:gstypes,
                business:business,
                zhucetime:zhucetime,
              })
            },
            fail: function (err) {
              wx.showToast({
                title: "上传失败",
                icon: "none",
                duration: 2000
              })
            },
            complete: function (result) {
             // console.log(result.errMsg)
            }
          })
          wx.hideLoading();
        } catch (err)
        {
          console.error('invokeService fail', err)
          wx.showModal({
            title: 'fail',
            content: err,
          })
          wx.hideLoading();
        }
      },
    })
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
  saveadd:function(e)
  {
    var that=this;
    var tid=this.data.tid;
    var wxid=app.globalData.openid;
    var tName=this.data.tName;
    var hyName=this.data.hyName;
    var hyTel=this.data.hyTel;
    var hyPwd=this.data.hyPwd;
    var rePwd=this.data.rePwd;    
    var types=this.data.types;
    const regu=/^1\d{10}$/;
    if(types=='1'&&imgurl=='image/addpic.png')
    {
      wx.showToast({
          title: '请上传营业执照!',
          icon: 'none',
          duration: 1500
          })
          return false;
    }else if(hyName=='')
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
    }else if(hyPwd.length<6)
    {
        wx.showToast({
        title: '密码长度至少6位!',
        icon: 'none',
        duration: 1500
        })
        return false;
    }else if(hyPwd!=rePwd)
    {
          wx.showToast({
          title: '两次密码输入不一致!',
          icon: 'none',
          duration: 1500
          })
          return false;
    }else
    {     
        wx.request({
          url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
          data: {
            tag: "jointeam",
            tid:tid,
            weId:wxid,
            tName:tName,
            hyName:hyName,
            hyTel:hyTel,
            hyPwd:hyPwd,
            daima:daima,
            types:that.data.types,
            imgurl:that.data.imgurl,
            faren:that.data.faren,
            gsname:that.data.gsname,
            address:that.data.address,
            gstypes:that.data.gstypes,
            business:that.data.business,
            zhucetime:that.data.zhucetime,
          },
          header: {'content-type': 'application/x-www-form-urlencoded'},
          success: function (res)
          {
            app.globalData.tid = res.data.tId;
            app.globalData.hyid = res.data.hyid;
            app.globalData.refresh=1;
            app.globalData.hyState=1;
              wx.showToast({
                title: res.data.st,
                icon: 'none',
                duration: 3000,
                success: function ()
                {
                  setTimeout(function ()
                  {
                    wx.switchTab({
                      url: '../hycenter/hycenter',
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