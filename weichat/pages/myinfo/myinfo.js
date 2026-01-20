// pages/shopadd/shopadd.js
var app = getApp();
var QQMapWX = require('../../libs/qqmap-wx-jssdk.js');
var qqmapsdk = new QQMapWX({key: 'QZTBZ-7OAK6-Q7FSZ-MR7YJ-NPYLS-3IBCZ'});
Page({
  /**
   * 页面的初始数据
   */
  data: {    
    navbarData: {
      showCapsule: 1, //是否显示左上角图标   1表示显示    0表示不显示
      title: '我的档案', //导航栏 中间的标题
    },
    height: app.globalData.height, 
    imgs: [],
    count:1,
    clickFlag:true, //防重复点击 
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

  shibie:function(e)
  {
  wx.chooseImage({
  count: 1,
  success: async function(res) {
    try {
      const invokeRes = await wx.serviceMarket.invokeService({
        service: 'wx79ac3de8be320b71',
        api: 'OcrAllInOne',
        data: {
          // 用 CDN 方法标记要上传并转换成 HTTP URL 的文件
          img_url: new wx.serviceMarket.CDN({
            type: 'filePath',
            filePath: res.tempFilePaths[0],
          }),
          data_type: 3,
          ocr_type: 7
        },
      })
      
      let daima =invokeRes.data.biz_license_res.reg_num.text;//社会代码
      let faren =invokeRes.data.biz_license_res.legal_representative.text;//公司名称
      let name =invokeRes.data.biz_license_res.enterprise_name.text;//法人
      let address =invokeRes.data.biz_license_res.address.text;//法人
      let types =invokeRes.data.biz_license_res.type_of_enterprise.text;//公司类型
      let business =invokeRes.data.biz_license_res.business_scope.text;//经营范围
      let registered_date =invokeRes.data.biz_license_res.registered_date.text;//成立日期
      let registered_capital =invokeRes.data.biz_license_res.registered_capital.text;//注册资本

      console.log(daima);
      console.log(faren);
      console.log(name);
      console.log(address);
      console.log(types);
      console.log(business);
      console.log(registered_date);

    } catch (err) {
      console.error('invokeService fail', err)
      wx.showModal({
        title: 'fail',
        content: err,
      })
    }
  },
  fail: function(res) {},
  complete: function(res) {},
    })
  },
  bindUpload: function (e) 
  {
    switch (this.data.imgs.length) 
    {
      case 0:
        this.data.count = 3
        break
      case 1:
        this.data.count = 2
        break
      case 2:
        this.data.count = 1
        break
    }
    var that = this
    wx.chooseMedia({
      count: that.data.count, // 最多可以选择的文件个数
			mediaType: ['image'], // 文件类型
			sizeType: ['compressed'], // 是否压缩所选文件  compressed 压缩  original 原始
      sourceType: ['camera','album'],       
      success: function (res) {
        var tempFiles = res.tempFiles
        for (var i = 0; i < tempFiles.length; i++)
        {
          var tempFilePath=tempFiles[i].tempFilePath;
          wx.uploadFile({
            url: 'https://xj.tajian.cc/servers/upimg.php',
            filePath:tempFilePath ,
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
                that.data.imgs.push(reimg.url)  
                that.setData({
                  imgs: that.data.imgs
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
             // console.log(result.errMsg)
            }
          })
        }
      }
    })
  },
  // 删除图片
  deleteImg: function (e) {
    var that = this
    wx.showModal({
      title: "提示",
      content: "是否删除",
      success: function (res) {
        if (res.confirm) {
          for (var i = 0; i < that.data.imgs.length; i++) {
            if (i == e.currentTarget.dataset.index) that.data.imgs.splice(i, 1)
          }
          that.setData({
            imgs: that.data.imgs
          })
        } else if (res.cancel) {
          console.log("用户点击取消")
        }
      }
    })
  },
  //保存数据库
  save()
  {
    //调用服务器保存视频信息接口    
    var that=this;
    var hyid=app.globalData.hyid;
    var hyname=app.globalData.hyname;
    var tid=app.globalData.tid;
    var hyIdentity=app.globalData.hyIdentity;

    var id=this.data.id;    
    var dropNo=this.data.dropNo;    
    var dropPhoto=this.data.imgs;
    var dropName=this.data.dropName;
    var gName=this.data.gName;
    var groupid=this.data.groupid;
    var dropInfo=this.data.dropInfo;
    var patrolCycle=this.data.zqvalue;
    var patrolNum=this.data.xjnum;
    var patrolDiff=this.data.xjjgtime;
    var bluePro=this.data.bluePro;

    if(dropNo=="")
    {
      wx.showToast({
        title: '请输入巡检编号!',
        icon: 'none',
        duration: 1500
     })
     return false;
    }else if(dropName=="")
    {
      wx.showToast({
        title: '请输入巡检点名称!',
        icon: 'none',
        duration: 1500
     })
     return false;
    }else if(gName=="请选择分类")
    {
      wx.showToast({
        title: '请选择/新建分类!',
        icon: 'none',
        duration: 1500
     })
     return false;
    }else if(bluePro.length==0)
    {
      wx.showToast({
        title: '请添加巡检项目!',
        icon: 'none',
        duration: 1500
     })
     return false;
    }else
    {      
     this.setData({clickFlag: false});
      wx.request({
        url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
        data: {
          tag: "inspectset",
          id:id,
          tid:tid,
          hyid:hyid,
          dropNo:dropNo,
          dropPhoto:dropPhoto,
          dropName:dropName,
          groupid:groupid,
          gName:gName,
          dropInfo:dropInfo,
          patrolCycle:patrolCycle,
          patrolNum:patrolNum,
          patrolDiff:patrolDiff,
          bluePro:bluePro,
          hyIdentity:hyIdentity,
          hyname:hyname
        },
        header: {
          'content-type': 'application/x-www-form-urlencoded'
        },
        success: function (res)
        {
          app.globalData.refresh=1;
          wx.showToast({
            title: '提交成功',
            duration:2000, 
            icon: 'success',
            success: function ()
            {
              setTimeout(function ()
              {
                wx.navigateBack(0)
              },2000)
            }
          })       
        },fail:function()
        {
          wx.showToast({
            title: '服务器出现故障，请稍后再试!',
            icon: 'none',
            duration: 1500
         })
          that.setData({clickFlag: true})
        }
      })
    }
  },  
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad(options)
  {
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
  return;
  wx.showLoading({title: '模板加载中...',mask: true });
  wx.request({
    url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
    data: {
      tag: "getblueinfo",
      id:id
    },
    header: {
      'content-type': 'application/x-www-form-urlencoded'
    },
    success: function (res)
    {
      wx.hideLoading();
    }
  })
},
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
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