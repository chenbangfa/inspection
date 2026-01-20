// pages/shopadd/shopadd.js
var app = getApp();
var QRCode = require('../../utils/weapp-qrcode.js')
Page({
  /**
   * 页面的初始数据
   */
  data: {    
    navbarData: {
      showCapsule: 1, //是否显示左上角图标   1表示显示    0表示不显示
      title: '巡检点', //导航栏 中间的标题
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
    odtogether:'',
    bluePro:[],
    id:0,
    dropPhoto:'image/nopic.png',
    editshow:false,
    people:[],
    hyAppoint:'',
    hyAppointName:'',
    newAppoint:'',
    newAppointId:'',
    hyIdentity:0
  },
  peoplechange:function(e)
  {
    var people=this.data.people;
    let checked =e.detail.value; 
    var newAppoint='';
    var newAppointId='';
    var i=0;
    checked.forEach(element => {
      if(i==0)
      {
        newAppoint=people[element].hyName;
        newAppointId=people[element].id;
      }
      else
      {
        newAppoint=newAppoint+","+people[element].hyName;
        newAppointId=newAppointId+","+people[element].id;
      }
      i++;
   });
   this.setData({
    newAppoint:newAppoint,
    newAppointId:newAppointId
   })
  },
  peoplesave:function(e)
  {
    var that=this;
    var newAppoint=this.data.newAppoint;
    var newAppointId=this.data.newAppointId;
    if(newAppoint=='')
    {
      wx.showToast({
        title: '请选择巡检人!',
        icon: 'none',
        duration: 1500
     })
     return false;
    }else
    {
    var id=this.data.id;
    wx.request({
      url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
      data: {
        tag: "sethyAppoint",
        id:id,
        hyAppoint:newAppoint,
        newAppointId:newAppointId
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res)
      {      
        that.setData({
          hyAppoint:res.data.hyAppoint,
          hyAppointName:res.data.hyAppointName,
          newAppoint:res.data.hyAppoint,
          editshow:false
        })
        wx.showToast({
          title: '保存成功!',
          icon: 'success',
          duration: 1500
       })
      }
    })
  }
  },
  delstall:function(e)
  {
    var that=this;    
    var id=this.data.id;
    wx.showModal({
      title: "提示",
      content: "是否删除",
      success: function (res)
      {
        if (res.confirm)
        {
          wx.request({
            url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
            data: {
              tag: "del",  
              i:id,
              t:'blueInspect',
            },
            header: {
              'content-type': 'application/x-www-form-urlencoded'
            },
            success: function (res)
            {
              app.globalData.refresh=1;
              wx.showToast({
                title: '删除成功',
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
            }
          })
        }else
        {
          console.log("取消")
        }
      }
    })
  },
  closeycshow:function(e)
  {
    this.setData({
      ycshow: false,
    })    
  },
  peoplebind:function(e)
  {
    if(this.data.hyIdentity>0)
    {
    this.setData({
      editshow:true
    })
    }
  },
  hideeditshow:function(e)
  {    
    this.setData({
      editshow:false
    })
  },
  getcode()
  {
    wx.showLoading({title: '二维码生成中...',mask: true });
    this.setData({
      ycshow: true,
    })    
    var that=this;    
    var qrcode = new QRCode('canvas', {
      text: that.data.id,
      width: 320,
      height: 320,
      padding: 12,
      colorDark: "#000000",
      colorLight: "#ffffff",
      correctLevel: QRCode.CorrectLevel.H,
      callback: (res) =>
      {
        //保存图片
        console.log(res)
        wx.hideLoading();
      },
    });
  },  
  handleImgSave() {
    wx.canvasToTempFilePath({
      canvasId: 'canvas',
      success(res) {
        wx.saveImageToPhotosAlbum({
          filePath: res.tempFilePath,
          success(res)
          {
            wx.showToast({
              icon:'success',
              title: '保存成功！',
            })
          },
          fail(err) {
            wx.showToast({
              icon:'error',
              title: '保存失败！',
            })
          }
        })
      }
    })
  },
  onLoad(options)
  {    
    wx.showLoading({title: '数据加载中...',mask: true });    
    var hyIdentity=app.globalData.hyIdentity;
    var id=options.id;
    this.setData({
      id:id,
      hyIdentity:hyIdentity
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
  var tid=app.globalData.tid;
  wx.request({
    url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
    data: {
      tag: "getblueinspectinfo",
      id:id,
      tid:tid
    },
    header: {
      'content-type': 'application/x-www-form-urlencoded'
    },
    success: function (res)
    {      
      that.setData({
        deviceId:res.data.blueinfo[0].deviceId,
        hyAppointName:res.data.blueinfo[0].hyAppointName,
        hyAppoint:res.data.blueinfo[0].hyAppoint,
        newAppoint:res.data.blueinfo[0].hyAppoint,
        dropNo:res.data.blueinfo[0].dropNo,
        dropName:res.data.blueinfo[0].dropName,
        groupid:res.data.blueinfo[0].gId,
        gName:res.data.blueinfo[0].dropClass,
        dropInfo:res.data.blueinfo[0].dropInfo,
        bluePro:res.data.blueinfo[0].prolist,
        zqvalue:res.data.blueinfo[0].patrolCycle,
        xjnum:res.data.blueinfo[0].patrolNum,
        xjjgtime:res.data.blueinfo[0].patrolDiff,
        dropPhoto:res.data.blueinfo[0].dropPhoto,
        addTime:res.data.blueinfo[0].addTime,
        hyName:res.data.blueinfo[0].hyName,
        xjcount:res.data.blueinfo[0].xjcount,
        people:res.data.blueinfo[0].people,
        timelist:res.data.timelist
      })
      wx.hideLoading();
    }
  })
  },
  onReady() {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow()
  {
    if(app.globalData.refresh==1)
    {
      app.globalData.refresh=0;
      this.getblueinfo();
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