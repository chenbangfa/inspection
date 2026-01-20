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
      title: '日常巡检', //导航栏 中间的标题
    },
    height: app.globalData.height, 
    imgs: [],
    count: 3,
    clickFlag:true, //防重复点击 
    posAdd:'定位中...',
    gsName:'',
    yhAdd:'',
    yhPosition:'',
    yhContent:'',
    together:[], 
    editshow:false,
    people:[],
    tid:0,
    zyshow:false,
    zylist:[],    
    yhSpeciality:'综合',
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
  hideeditshow:function(e)
  {
    this.setData({
      editshow: false,
    })    
  },
  getpeople:function(e)
  {
    this.setData({
      editshow: true,
    })  
  },
  peoplechange:function(e)
  {
    let checked =e.detail.value; 
    var together='';
    checked.forEach(element => {
      together=together+" "+element;
   });
      this.setData({
        together:together
      })
  }, 
  zychange:function(e)
  {
    let yhSpeciality =e.detail.value; 
   this.setData({
    yhSpeciality:yhSpeciality
   })
  },
  getzylist:function(e)
  {
    var that=this;
    var tid=app.globalData.tid;
    wx.request({
      url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
      data: {
        tag: "getzylist",
        tid:tid,
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res)
      {
        wx.hideLoading();
        that.setData({
            zylist:res.data.zylist,
            zyshow:true,
          });
      }
    })
  },
  hidezyshow:function(e)
  {
    this.setData({
      zyshow:false
    })
  },  
  hideteam:function(e)
  {
    this.setData({
      teamshow:false
    })
  },
  showteam:function()
  {
    this.setData({
      teamshow:true
    })
  },
  teamchange:function(e)
  {
    var tid=e.detail.value;
    var carArray1 = this.data.teamlist.filter(item => item.tId== tid);
    this.setData({
      tid:e.detail.value,
      tName:carArray1[0].tName,
      teamshow:false,
    })
  },
  getteamlist:function(e)
  {
    var that=this;
    var hytel=app.globalData.hytel;
    var tid=app.globalData.tid;
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
        var carArray1 =res.data.teamlist.filter(item =>item.tId== tid);
       var tName=carArray1[0].tName;
        that.setData({
          teamlist:res.data.teamlist,          
          tName:tName,
          tid:tid
        });
      }
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
      sourceType: ['camera'],       
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
    var wxid=app.globalData.openid;
    var hyid=app.globalData.hyid;
    var hyname=app.globalData.hyname;
    var tid=this.data.tid;
    var posAdd=this.data.posAdd;    
    var gsName=this.data.gsName;
    var yhAdd=this.data.yhAdd;
    var yhPosition=this.data.yhPosition;
    var yhContent=this.data.yhContent;
    var imgs=this.data.imgs;
    var yhSpeciality=this.data.yhSpeciality;
    var together=this.data.together;
    if(posAdd=="定位中...")
    {
      wx.showToast({
        title: '请先授权地理位置!',
        icon: 'none',
        duration: 1500
     })
     return false;
    }else if(gsName=="")
    {
      wx.showToast({
        title: '请输入公司名称!',
        icon: 'none',
        duration: 1500
     })
     return false;
    }else if(yhAdd=="")
    {
      wx.showToast({
        title: '请输入隐患位置!',
        icon: 'none',
        duration: 1500
     })
     return false;
    }else if(yhPosition=="")
    {
      wx.showToast({
        title: '请输入隐患部位!',
        icon: 'none',
        duration: 1500
     })
     return false;
    }else if(yhContent=="")
    {
      wx.showToast({
        title: '请输入隐患内容!',
        icon: 'none',
        duration: 1500
     })
     return false;
    }else if(yhSpeciality=="")
    {
      wx.showToast({
        title: '请输入隐患分类!',
        icon: 'none',
        duration: 1500
     })
     return false;
    }else if(imgs.length<1)
    {
      wx.showToast({
        title: '请上传隐患照片!',
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
          tag: "inspectadd",
          tid:tid,
          wxid:wxid,
          hyid:hyid,
          hyname:hyname,
          posAdd:posAdd,
          gsName:gsName,
          yhAdd:yhAdd,
          yhPosition:yhPosition,
          yhContent:yhContent,
          imgs:imgs,
          yhSpeciality:yhSpeciality,
          together:together,
        },
        header: {
          'content-type': 'application/x-www-form-urlencoded'
        },
        success: function (res)
        {
          app.globalData.refresh=1;
          app.globalData.tid=tid;
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
  onLoad(options)
  {
    var that=this;
    if (app.globalData.employ && app.globalData.employ!= '') 
    {
      that.getinspect();
      that.getuseradds();
      that.getteamlist();
    }else
    {
      app.employCallback = employ => {
        if (employ!= '')
        {
          that.getinspect();
          that.getuseradds();
          that.getteamlist();
        }
      }
    }
  },   
getinspect:function(e) 
{
  var that=this;
  var tid=app.globalData.tid;
  var hyid=app.globalData.hyid;
  wx.request({
    url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
    data: {
      tag: "getinspect",
      tid:tid,
      hyid:hyid
    },
    header: {
      'content-type': 'application/x-www-form-urlencoded'
    },
    success: function (res)
    {
      if(res.data.odlist.length>0)
      {
        that.setData({
          gsName:res.data.odlist[0].gsName,
          yhAdd:res.data.odlist[0].yhAdd,
          yhPosition:res.data.odlist[0].yhPosition,
          together:res.data.odlist[0].together,
        })
      }
      that.setData({
        zylist:res.data.zylist,
        people:res.data.people,
      });   
    }
  })
},
  getuseradds:function()
  {  
    var that=this;
    that.setData({
      posAdd:"定位中...",
    })
    wx.getLocation({
      type: 'wgs84',
      isHighAccuracy:true,
      success: function (res) {
        var latitude = res.latitude;
        var longitude = res.longitude;
        qqmapsdk.reverseGeocoder({
          location: {
            latitude: latitude,
            longitude: longitude
          },
          coord_type:1,
          get_poi:0,
          poi_options: 'policy=2;radius=100;page_size=20;page_index=1', 
          success: function (res) {
          //console.log(JSON.stringify(res));
           //console.log(res.result.address_reference.town.title)
           let district ='';
           if (res.result.formatted_addresses)
           district = res.result.formatted_addresses.recommend;
            else
            district = res.result.address;
           //console.log("地址："+district);        
            that.setData({
              posAdd:district,
            })
          },
          fail: function (res) { console.log(res); },
          complete: function (res) { }
        });
      },
      fail:function(){
        wx.getSetting({
            success: function (res) {
                var statu = res.authSetting;
                if (!statu['scope.userLocation']) {
                    wx.showModal({
                        title: '是否授权当前位置',
                        content: '需要获取您的位置，请确认授权，否则无法为您定位',
                        success: function (tip) {
                            if (tip.confirm) {
                                wx.openSetting({
                                    success: function (data) {
                                        if (data.authSetting["scope.userLocation"] === true) {
                                            wx.showToast({
                                                title: '您已开启定位，点击重新定位',
                                                icon: 'none',
                                                duration: 3000
                                            })
                                            that.setData({
                                              posAdd:"您已开启定位，点我定位"
                                            })
                                        } else {
                                            wx.showToast({
                                                title: '您未开启定位权限，将无法为您定位',
                                                icon: 'none',
                                                duration: 3000
                                            })
                                            that.setData({
                                              posAdd:"没有开启定位，点我授权"
                                            })
                                        }
                                    },fail: function (res) {
                                      console.log(res);
                                    }
                                })
                            }else
                            {
                              wx.showToast({
                                title: '您未开启定位权限，将无法为您定位',
                                icon: 'none',
                                duration: 3000
                            })
                            that.setData({
                              posAdd:"没有开启定位，点我授权"
                            })
                            }
                        },fail: function (res) {
                          console.log(res);
                        }
                        
                    });
                }
            },
            fail: function (res) {
              console.log(res);     
            }
        })
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