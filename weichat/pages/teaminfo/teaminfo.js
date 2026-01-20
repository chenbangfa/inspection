// pages/hycenter/hycenter.js
var app = getApp()
Page({
  /**
   * 页面的初始数据
   */
  data: {
    navbarData: {
      showCapsule:1, //是否显示左上角图标   1表示显示    0表示不显示
      title: "工作台", //导航栏 中间的标题
    },
    // 此页面 页面内容距最顶部的距离
    height: app.globalData.height, 
    tId:'',
    tName:'',
    tCode:'',
    showcode:false,

    hylist: [],
    page:0,
    isdata:false,
    count:0,

    editshow:false,
    showname:'',
    hyentity:0,

    userid:0,
    grouplist:[],
    groupindex:0,
    groupid:'',
    groupname:''
  },
  hideuser:function(e)
  {
    this.setData({
      editshow:false
    })
  },
  showuser:function(e)
  {
    var id=e.currentTarget.dataset.id;
    var showname=e.currentTarget.dataset.showname;
    var gId=e.currentTarget.dataset.showgid;
    var gName=e.currentTarget.dataset.showgname;
    var hyentity=e.currentTarget.dataset.hyentity;
    var grouplist=this.data.grouplist;
    let a = {groupid:gId};
    let groupindex= grouplist.findIndex(item => item.groupid === a.groupid);
    groupindex=groupindex==-1?0:groupindex;
    this.setData({
      userid:id,
      editshow:true,
      showname:showname,
      hyentity:hyentity,
      groupid:gId,
      groupname:gName,
      groupindex:groupindex
    })
  },
  bindPickerChange:function(e)
  {
    var index=e.detail.value;
    var groupid=this.data.grouplist[index].groupid;
    var groupname=this.data.grouplist[index].groupname;
   this.setData({
    groupid:groupid,
    groupname:groupname,
    groupindex:index
   })
  },
  entitychange:function(e)
  {
    this.setData({
      hyentity:e.detail.value
     })
  },
  saveuser:function(e)
  {
    var that=this;
    var id=this.data.userid;
    var gId=this.data.groupid;
    var gName=this.data.groupname;
    var hyentity=this.data.hyentity;
    wx.request({
      url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
      data: {
        tag: "setuser",  
        id:id,
        gId:gId,
        gName:gName,
        hyentity:hyentity,
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res)
      {
        wx.showToast({
          title: '保存成功,下拉刷新！',
          icon:'none'
        })
        that.setData({
          editshow:false
        })
      }
    })
  },
  deluser:function(e)
  {    
    var id=this.data.userid;
    var that=this;
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
              t:'hyUser',
            },
            header: {
              'content-type': 'application/x-www-form-urlencoded'
            },
            success: function (res)
            {
              wx.showToast({
                title: '删除成功,下拉刷新！',
                icon:'none'
              })
              that.setData({
                editshow:false
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
loadcenter:function(e,page)
{
  var tid=app.globalData.tid;
  wx.request({
    url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
    data: {
      tag: "teaminfo",      
      p:page+1,
      tid:tid,
    },
    header: {
      'content-type': 'application/x-www-form-urlencoded'
    },
    success: function (res)
    {
      console.log(res.data)
      wx.hideLoading();
      if(res.data.hylist.length>0)
      {
            e.setData({
              ["hylist["+page+"]"]:res.data.hylist,
              page:page,
              isdata:false,
              count:res.data.hylist[0].count,
              grouplist:res.data.grouplist
            });
      }else{
            e.setData({
              ["hylist["+page+"]"]:[],
              page:page-1,              
              isdata:true,
            });
       }
    }
  })
},

hidecode: function ()
{
  this.setData({
    showcode: false,
  });
},
getcode:function()
{
  var that=this;
  var tid=app.globalData.tid;
  console.log(tid)
  wx.showToast({title: '二维码生成中',icon: 'loading',duration: 1000})
  wx.request({
    // 获取token
    url: 'https://xj.tajian.cc/servers/mycode.php',
    data: {
      tag: 'getQRCode', 
      tid:tid,
    },
    header: {
      'content-type': 'application/x-www-form-urlencoded'
    }, 
    success: function (res)
    {
      that.setData({
        showcode:true,
        tCode:res.data.path
      });
    }
  })
},
showcode:function()
{
  var that=this;
  var tCode=this.data.tCode;
  if(tCode==""||tCode==null)
  {
    
  console.log("///"+tCode)
   that.getcode();
  }else{
    that.setData({
      showcode:true,
      tCode:tCode
    });
  }
},

onLoad: function (options)
{
  var that=this;
  that.setData({
    tName:options.tname
  })  
  var page=that.data.page;
  wx.showLoading({title: '数据加载中...',mask: true }); 
  if (app.globalData.employ && app.globalData.employ!= '') 
  {
    that.loadcenter(that,page);
  }else
  {
    app.employCallback = employ => {
    if (employ!= '')
    {
      that.loadcenter(that,page);
    }
    }
  }
},

  
 // 长按保存图片
 saveImage(e){
  let url = e.currentTarget.dataset.imgurl;
  //用户需要授权
  wx.getSetting({
   success: (res) => {
    if (!res.authSetting['scope.writePhotosAlbum']) {
     wx.authorize({
      scope: 'scope.writePhotosAlbum',
      success:()=> {
       // 同意授权
       this.saveImg1(url);
      },
      fail: (res) =>{
       console.log(res);
      }
     })
    }else{
     // 已经授权了
     this.saveImg1(url);
    }
   },
   fail: (res) =>{
    console.log(res);
   }
  })  
 },
 
 saveImg1(url){
  wx.getImageInfo({
   src: url,
   success:(res)=> {
    let path = res.path;
    wx.saveImageToPhotosAlbum({
     filePath:path,
     success:(res)=> { 
      console.log(res);
     },
     fail:(res)=>{
      console.log(res);
     }
    })
   },
   fail:(res)=> {
    console.log(res);
   }
  })
 },
  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    
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

  onPullDownRefresh: function ()
  {
    this.setData({
      hylist: []
    })
    this.loadcenter(this,0);
    wx.stopPullDownRefresh();//停止刷新操作
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom() 
  {
    if(!this.data.isdata)
    {
      wx.showLoading({title: '数据加载中...',mask: true });
      var page=this.data.page;
      page=page+1;
      this.loadcenter(this,page);
    }
  },

})