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
      title: '创建巡检模板', //导航栏 中间的标题
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
    bluePro:[],
    proName:'',
    proSort:'',
    addshow:false,
    editshow:false,
    proid:0,
    group:[],
    groupid:0,
    gName:'请新建/选择分类',
    groupindex:0,
    groupshow:false,
    dropNo:'',
    dropName:'',
    dropInfo:'',
    id:0,
    newAppoint:'全部',
    newAppointId:'全部',
    issfz:0,
    isxjfs:0,
    isaddress:0,
    isphoto:0,
  },  
  setGroup:function(e)
  {
    this.setData({
      groupshow:true
    })
  },
  saveGroup:function(e)
  {
    var gName=this.data.gName;
    if(gName=='请选择分类')
    {
      wx.showToast({
        title: '请选择/新建分类!',
        duration:2000, 
        icon: 'none',
      })  
      return;
    }else{
    this.setData({
      groupshow:false
    })
  }
  },
  groupChange:function(e)
  {
    var groupindex=e.detail.value;
    this.setData({
      groupindex:groupindex,
      groupid:this.data.group[groupindex].id,
      gName:this.data.group[groupindex].gName
    })
  },
  hidegroupshow:function(e)
  {
    this.setData({
      groupshow: false,
    })    
  },
  closeaddshow:function(e)
  {
    this.setData({
      addshow: false,
    })    
  },
  hideeditshow:function(e)
  {
    this.setData({
      editshow: false,
    })    
  },
  addpro:function(e)
  {
    var proName=this.data.proName;
    var proSort=this.data.proSort;
    var obj = { proName: proName, proSort: proSort};
    var carArray1 = this.data.bluePro.filter(item => item.proName != proName);
    carArray1.push(obj);
    this.setData({
      bluePro: carArray1,      
      addshow:false
    })
  },
  savepro:function(e)
  {
    var proid=this.data.proid;    
    var newproName=this.data.proName;
    var newproSort=this.data.proSort;

    let proName = "bluePro[" + proid + "].proName"
    let proSort = "bluePro[" + proid + "].proSort"
    this.setData({
      [proName]: newproName,
      [proSort]: newproSort,
      editshow:false
    })
  },
  editpro:function(e)
  {
    var id=e.currentTarget.dataset.id;
    var bluePro = this.data.bluePro;
    this.setData({
      proName:bluePro[id].proName,
      proSort:bluePro[id].proSort,
      proid:id,
      editshow:true
    })
  },
  delpro:function(e)
  {
    var proid=this.data.proid;
    this.data.bluePro.splice(proid, 1);
    this.setData({
      bluePro:this.data.bluePro,
      editshow:false
    })
  },
  showaddpro:function(e)
  {
    this.setData({
      proName:'',
      proSort:'',
      addshow:true
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
  gNamechange:function(e) 
  {
   var gName=e.detail.value;
   if(gName!='')
   {
     this.setData({
      groupid:0,
       gName:gName
     })
   }
  },
  inputWacth(e)
  {
    let newValue = e.target.dataset.input;
    this.setData(
      {
        [newValue]: e.detail.value
      }
    )
    console.log(this.data.isxjfs)
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
  typechange:function(e)
  {
    if(e.detail.value==1)
    {
      this.getpeople();
    }else
    {
      this.setData({
        newAppoint:'全部',
        newAppointId:'全部'
      })
    }
  },
  hidepepple:function(e)
  {
    var newAppoint=this.data.newAppoint;
    this.setData({
      peopleshow: false,
      newAppoint:newAppoint
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
          hyname:hyname,
          newAppoint:that.data.newAppoint,
          newAppointId:that.data.newAppointId,
          issfz:that.data.issfz,
          isxjfs:that.data.isxjfs,
          isaddress:that.data.isaddress,
          isphoto:that.data.isphoto,
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
    var id=options.id;
    if (app.globalData.employ && app.globalData.employ!= '') 
    {
      that.getblueinfo(id,that);
    }else
    {
      app.employCallback = employ => {
        if (employ!= '')
        {
          that.getblueinfo(id,that);
        }
      }
    }
  }, 
getblueinfo:function(id,that) 
{
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
      var zqindex=0;
      for (var i = 0; i < that.data.zhouqi.length; i++)
      {
          if(that.data.zhouqi[i]==res.data.blueinfo[0].patrolCycle)
          {
            zqindex=i;
            break;
          }
      }
    var zqdata="天";
    var zddanwei="时";
    switch(zqindex)
    {
      case 0:
        zqdata="天";
        zddanwei="时";
      break;
      case 1:
        zqdata="周";
        zddanwei="天";
      break;      
      case 2:
        zqdata="月";
        zddanwei="天";
      break;             
      case 2:
        zqdata="季";
        zddanwei="天";
      break;         
      case 4:
        zqdata="年";
        zddanwei="天";
      break;
    }

      that.setData({
        id:id,
        dropNo:res.data.blueinfo[0].dropNo,
        dropName:res.data.blueinfo[0].dropName,
        groupid:res.data.blueinfo[0].gId,
        group:res.data.blueinfo[0].group,
        imgs:res.data.blueinfo[0].dropPhoto,
        dropInfo:res.data.blueinfo[0].dropInfo,
        bluePro:res.data.blueinfo[0].prolist,
        zqvalue:res.data.blueinfo[0].patrolCycle,
        xjnum:res.data.blueinfo[0].patrolNum,
        xjjgtime:res.data.blueinfo[0].patrolDiff,
        zqindex:zqindex,
        zqdata:zqdata,
        zddanwei:zddanwei,
        hyIdentity:app.globalData.hyIdentity
      })
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