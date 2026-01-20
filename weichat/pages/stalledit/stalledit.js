// pages/shopadd/shopadd.js
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {    
    navbarData: {
      showCapsule: 1, //是否显示左上角图标   1表示显示    0表示不显示
      title: '编辑流动摊位区域', //导航栏 中间的标题
    },
    height: app.globalData.height, 
    imgs: [],
    count:3,
    clickFlag:true, //防重复点击 
    zhouqi:["每天","每周","每月","每季","每年"],
    zqindex:0,
    zqvalue:'每天',
    zqdata:'天',
    xjnum:1,
    xjjgtime:0,
    zddanwei:'时',
    addshow:false,
    editshow:false,
    proid:0,
    dropNo:'',
    dropName:'',
    dropInfo:'',
    newAppoint:'全部',
    newAppointId:'全部',
    starttime:'00:00',
    endtime:'23:59',
    exittime:'05:00',

    
    exitzhouqi:["每天","每周","每月"],
    exitindex:0,
    exitzqvalue:'每天',
    exitdate:'',
    exitdatelist:[],
    exitdateindex:0,
    stallnum:0,
    bmsfz:0,
    bmjyhy:0,
    bmjygj:0,
    bmtel:0,
    addname:'',
    blueinfo:[]

  }, 
  starttimeChange:function(e)
  {
    this.setData({
      starttime:e.detail.value,
    })
  },
  endtimeChange:function(e)
  {
    this.setData({
      endtime:e.detail.value,
    })
  },
  exittimeChange:function(e)
  {
    this.setData({
      exittime:e.detail.value,
    })
  },
  exitChange:function(e)
  {
   var zqindex=e.detail.value;
   var exitdate='';
   var exitdatelist=[];
    switch(zqindex)
    {
      case '0':
        exitdatelist=[]
        exitdate='';
      break;      
      case '1':
        exitdatelist=['周一','周二','周三','周四','周五','周六','周日']
        exitdate='请选择释放日期';
      break;      
     default:
        exitdatelist=[0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31];
        exitdate='请选择释放日期';
      break;    
    }
    this.setData({      
      exitindex:zqindex,
      exitdatelist:exitdatelist,
      exitdate:exitdate,
      exitzqvalue:this.data.exitzhouqi[zqindex]
    })
  },
  exitdateChange:function(e)
  {
    var zqindex=e.detail.value;
    this.setData({      
      exitdateindex:zqindex,
      exitdate:this.data.exitdatelist[zqindex],
    })
  },
  zqChange:function(e)
  {
   var zqdata="天";
   var zddanwei="时";
   var zqindex=e.detail.value;
    switch(zqindex)
    {
      case '0':
        zqdata="天";
        zddanwei="时";
      break;
      case '1':
        zqdata="周";
        zddanwei="天";
      break;      
      case '2':
        zqdata="月";
        zddanwei="天";
      break;             
      case '3':
        zqdata="季";
        zddanwei="天";
      break;         
      case '4':
        zqdata="年";
        zddanwei="天";
      break;
    }
    this.setData({
      zqindex: e.detail.value,
      zqvalue:this.data.zhouqi[e.detail.value],
      zqdata:zqdata,
      zddanwei:zddanwei
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
    var id=this.data.id;
    var that=this;

    var dropPhoto=this.data.imgs;
    var dropName=this.data.dropName;
    var starttime=this.data.starttime;
    var endtime=this.data.endtime;
    var stallnum=this.data.stallnum;
    var addname=this.data.addname;
    var dropInfo=this.data.dropInfo;
    
    var exitzhouqi=this.data.exitzqvalue;
    var exitdate=this.data.exitdate;
    var exittime=this.data.exittime;


    if(dropName=="")
    {
      wx.showToast({
        title: '请输入区域名称!',
        icon: 'none',
        duration: 1500
     })
     return false;
    }else if(stallnum==0)
    {
      wx.showToast({
        title: '请输入摊位数量!',
        icon: 'none',
        duration: 1500
     })
     return false;
    }else if(addname=='')
    {
      wx.showToast({
        title: '请选择流动摊位位置!',
        icon: 'none',
        duration: 1500
     })
     return false;
    }else if(dropPhoto.length<1)
    {
      wx.showToast({
        title: '请上传规划图或场地图!',
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
          tag: "stallinspectsave",
          id:id,
          dropName:dropName,
          starttime:starttime,
          endtime:endtime,
          stallnum:stallnum,
          addname:addname,
          latitude:that.data.latitude,
          longitude:that.data.longitude,
          dropPhoto:dropPhoto,
          dropInfo:dropInfo,

          exitzhouqi:exitzhouqi,
          exitdate:exitdate,
          exittime:exittime,


          bmsfz:that.data.bmsfz,
          bmjyhy:that.data.bmjyhy,
          bmjygj:that.data.bmjygj,
          bmtel:that.data.bmtel,

        },
        header: {
          'content-type': 'application/x-www-form-urlencoded'
        },
        success: function (res)
        {
          console.log(res.data)
          app.globalData.refresh=1;
          wx.showToast({
            title: '保存成功',
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
  getadd:function(e)
  {
    var that=this;
    wx.chooseLocation({
      success: function (res) {  
      //  console.log(res)  
          that.setData({  
            address: res.address,
            addname:res.name,
            latitude:res.latitude,
            longitude:res.longitude      //调用成功直接设置地址
          })                
      },
      fail:function(){
          wx.getSetting({
              success: function (res) {
                  var statu = res.authSetting;
                  if (!statu['scope.userLocation']) {
                      wx.showModal({
                          title: '是否授权当前位置',
                          content: '需要获取您的地理位置，请确认授权，否则地图功能将无法使用',
                          success: function (tip) {
                              if (tip.confirm) {
                                  wx.openSetting({
                                      success: function (data) {
                                          if (data.authSetting["scope.userLocation"] === true) {
                                              wx.showToast({
                                                  title: '授权成功',
                                                  icon: 'success',
                                                  duration: 1000
                                              })
                                              //授权成功之后，再调用chooseLocation选择地方
                                              wx.chooseLocation({
                                                  success: function(res) {
                                                      that.setData({                                                      
                                                          address: res.address,
                                                          addname:res.name,
                                                          latitude:res.latitude,
                                                          longitude:res.longitude
                                                      })
                                                  },
                                              })
                                          } else {
                                              wx.showToast({
                                                  title: '授权失败',
                                                  icon: 'success',
                                                  duration: 1000
                                              })
                                              that.setData({
                                                address: "定位失败",
                                                addrname:"定位失败"
                                            })
                                          }
                                      }
                                  })
                              }
                          }
                      })
                  }
              },
              fail: function (res) {
                  wx.showToast({
                      title: '调用授权失败',
                      icon: 'success',
                      duration: 1000
                  })
                  that.setData({
                    address: "定位失败",
                    addrname:"定位失败"
                })
              }
          })
      }
    })   
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad(options)
  {
    var that=this;
    var id=options.id;
    if (app.globalData.employ && app.globalData.employ!= '') 
    {
      that.getstallinfo(id);
    }else
    {
      app.employCallback = employ => {
        if (employ!= '')
        {
          that.getstallinfo(id);
        }
      }
    }
  }, 
  getstallinfo:function(id) 
{
  var that=this;
  wx.request({
    url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
    data: {
      tag: "getstalledit",
      id:id
    },
    header: {
      'content-type': 'application/x-www-form-urlencoded'
    },
    success: function (res)
    {
      console.log(res.data);
      that.setData({
        id:id,
        dropName:res.data.blueinfo[0].dropName,
        starttime:res.data.blueinfo[0].starttime,
        endtime:res.data.blueinfo[0].endtime,
        stallnum:res.data.blueinfo[0].stallnum,
        addname:res.data.blueinfo[0].addname,
        latitude:res.data.blueinfo[0].latitude,
        longitude:res.data.blueinfo[0].longitude,
        imgs:res.data.blueinfo[0].dropPhoto,
        dropInfo:res.data.blueinfo[0].dropInfo,
        exitzhouqi:res.data.blueinfo[0].exitzhouqi,
        exitdate:res.data.blueinfo[0].exitdate,
        exittime:res.data.blueinfo[0].exittime,
        bmsfz:res.data.blueinfo[0].bmsfz,
        bmjygj:res.data.blueinfo[0].bmjygj,
      });   
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