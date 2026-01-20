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
      title: '开始巡检', //导航栏 中间的标题
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
    odtogether:'可选择随检人员',
    bluePro:[],
    id:0,
    dropPhoto:'image/nopic.png',
    gName:'',
    states:0,
    imgurl:'image/yyzz.png',
    sfzzm:'image/sfzzm.png',
    sfzfm:'image/sfzfm.png',
    hyName:''
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
  closeycshow:function(e)
  {
    this.setData({
      ycshow: false,
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
              console.log(result.errMsg)
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
  savedongtai()
  {
    var that=this;
    var wxid=app.globalData.openid;
    var tid=app.globalData.tid;
    var hyid=app.globalData.hyid;
    var hyname=app.globalData.hyname;
    
    var id=this.data.id;
    var bluePro=this.data.bluePro;
    var odInfo=this.data.odInfo;
    var odtogether=this.data.odtogether;
    var imgs=this.data.imgs;
    var hyaddress=this.data.hyaddress;

    var dropName=this.data.dropName;
    var dropNo=this.data.dropNo;
    var gName=this.data.gName;
    var groupid=this.datagroupid;
    if(hyaddress==""&&that.data.isaddress==1)
    {
      wx.showToast({
        title: '请先授权地理位置!',
        icon: 'none',
        duration: 1500
     })
     return false;
    }else if(imgs.length<1&&that.data.isphoto==1)
    {
      wx.showToast({
        title: '请拍摄巡检照片!',
        icon: 'none',
        duration: 1500
     })
     return false;
    }else
    {      
     that.setData({clickFlag: false});
      wx.request({
        url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
        data: {
          tag: "blueOrderadd",
          wxid:wxid,
          hyid:hyid,
          id:id,
          tid:tid,
          hyname:hyname,
          dropNo:dropNo,
          dropName:dropName,
          bluePro:bluePro,
          odInfo:odInfo,
          hyaddress:hyaddress,
          odtogether:odtogether,
          imgs:imgs,
          gName:gName,
          gId:groupid,

          issfz:that.data.issfz,
          imgurl:that.data.imgurl,
          daima:that.data.daima,
          faren:that.data.faren,
          gsname:that.data.gsname,
          gstypes:that.data.gstypes,
          business:that.data.business,
          zhucetime:that.data.zhucetime,

          sfzname:that.data.sfzname,
          sfznum:that.data.sfznum,
          address:that.data.address,
          gender:that.data.gender,
          sfzzm:that.data.sfzzm,
          sfzfm:that.data.sfzfm
        },
        header: {
          'content-type': 'application/x-www-form-urlencoded'
        },
        success: function (res)
        {
          console.log(res.data)
          if(res.data.st==1)
          {
            app.globalData.refresh=1;
            wx.showToast({
              title: '巡检成功！',
              duration:2000, 
              icon: 'success',
              success: function ()
              {
                setTimeout(function ()
                {
                  wx.navigateBack();
                },2000)
              }
            }) 
          }else
          {
            wx.showToast({
              title: res.data.msg,
              icon: 'none',
              duration: 1500
           })
            that.setData({clickFlag: true})
          }
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
  yyzzUpload:function(e)
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
  sfzUpload:function(e)
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
              ocr_type: 1
            },
          })
          let sfzname ="";//社会代码
          let sfznum ="";//公司名称
          let address ="";//注册地址
          let gender ="";//注册地址
          var sfzimg="sfzfm"
          //console.log(JSON.stringify(invokeRes));
          if(invokeRes.data.idcard_res.type==0)
          {
            sfzimg="sfzzm";
            sfzname =invokeRes.data.idcard_res.name.text;//姓名
            sfznum =invokeRes.data.idcard_res.id.text;//身份证号
            address =invokeRes.data.idcard_res.address.text;//地址
            gender =invokeRes.data.idcard_res.gender.text;//性别
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
                [sfzimg]: reimg.url,
                sfzname:sfzname,
                sfznum:sfznum,
                address:address,
                gender:gender,
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
  ycbind:function(e)
  {
    var proid=e.currentTarget.dataset.id;    
    let abnormal = "bluePro[" + proid + "].abnormal"
    let normal = "bluePro[" + proid + "].normal"
    let state = "bluePro[" + proid + "].state"

    this.setData({
      [abnormal]: "bzcspan",
      [normal]: "cshspan",
      [state]: 1,
      ycshow:true,
      proid:proid
    })
    
  },  
  zcbind:function(e)
  {
    var proid=e.currentTarget.dataset.id;    
    let abnormal = "bluePro[" + proid + "].abnormal"
    let normal = "bluePro[" + proid + "].normal"    
    let state = "bluePro[" + proid + "].state"
    this.setData({
      [abnormal]: "cshspan",
      [normal]: "zcspan",
      [state]: 0,
      proid:proid
    })
  },  
  probind:function(e)
  {
    var proid=e.currentTarget.dataset.id;    
    let msg = this.data.bluePro[proid].msg;
    this.setData({
      xjmsg:msg,
      proid:proid,      
      ycshow:true,
    })
  },
  savemsg:function(e)
{
  var proid=this.data.proid;
  var msg=this.data.xjmsg;
  var xjmsg="bluePro[" + proid + "].msg";  
  this.setData({
    [xjmsg]: msg,
    ycshow:false,
    xjmsg:'',
  })
  },  
  hidepepple:function(e)
  {
    this.setData({
      peopleshow: false,
    })    
  },  
  getpeople:function(e)
  {
    var that=this;
    var tid=app.globalData.tid;
    wx.request({
      url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
      data: {
        tag: "gethylist",
        tid:tid,
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res)
      {
        wx.hideLoading();
        that.setData({
            people:res.data.hylist,
            peopleshow:true,
          });
      }
    })
  },
  peoplechange:function(e)
  {
    var people=this.data.people;
    let checked =e.detail.value; 
    var odtogether='';
    var i=0;
    checked.forEach(element => {
      if(i==0)
        odtogether=people[element].hyName;
      else
      {
        odtogether=odtogether+","+people[element].hyName;
      }
      i++;
   });
   this.setData({
    odtogether:odtogether,
   })
  },
  getblueinfo:function() 
{
  var that=this;
  var id=that.data.id;
  var tid=app.globalData.tid;
  wx.request({
    url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
    data: {
      tag: "getblueinspect",
      id:id
    },
    header: {
      'content-type': 'application/x-www-form-urlencoded'
    },
    success: function (res)
    {      
      if(res.data.blueinfo.length>0)
      {        
        var isaddress=res.data.blueinfo[0].isaddress;
        if(isaddress==1)
          that.getuseradds();
        that.setData({
          states:res.data.blueinfo[0].state,
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
          issfz:res.data.blueinfo[0].issfz,
          isxjfs:res.data.blueinfo[0].isxjfs,
          isaddress:res.data.blueinfo[0].isaddress,
          isphoto:res.data.blueinfo[0].isphoto,
          xjName:app.globalData.hyname
        })
      }else
      {
        wx.showToast({
          title: '没有数据！',
          duration:2000, 
          icon: 'error',
          success: function ()
          {
            setTimeout(function ()
            {
              wx.navigateBack(0)
            },2000)
          }
        })     
      }
      wx.hideLoading();
    }
  })
  },
  getuseradds:function()
  {  
   // wx.showLoading({title: '定位中...',mask: true });
    var that=this;
    wx.getLocation({
      type: 'wgs84',
      isHighAccuracy:true,
      success: function (res) {
       // console.log(res)
        var latitude = res.latitude;
        var longitude = res.longitude;
        qqmapsdk.reverseGeocoder({
          location: {
            latitude: latitude,
            longitude: longitude
          },
          coord_type:1,
          get_poi:1,
          poi_options: 'policy=2;radius=100;page_size=20;page_index=1', 
          success: function (res) {
          // console.log(JSON.stringify(res));
           //console.log(res.result.address_reference.town.title)
           let district ='';
           if (res.result.formatted_addresses)
           district = res.result.formatted_addresses.recommend;
            else
            district = res.result.address;
           //console.log("地址："+district);        
            that.setData({
              hyaddress:district,
              dtlat:latitude,
              dtlon:longitude
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
                                        } else {
                                            wx.showToast({
                                                title: '您未开启定位权限，将无法为您定位',
                                                icon: 'none',
                                                duration: 3000
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
                              hyaddress:"没有开启定位，点我授权"
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