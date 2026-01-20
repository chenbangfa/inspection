// pages/hycenter/hycenter.js
var app = getApp()
Page({
  /**
   * 页面的初始数据
   */
  data: {
    navbarData: {
      showCapsule:1, //是否显示左上角图标   1表示显示    0表示不显示
      title: "巡检分类管理", //导航栏 中间的标题
    },
    // 此页面 页面内容距最顶部的距离
    height: app.globalData.height, 
    tId:'',
    glist: [],
    count:0,

    editshow:false,
    showname:'',
    showsort:0,
    groupindex:0,
    addshow:false,

    addname:'',    
    addsort:0,
  },
  showadd:function(e)
  {
    this.setData({
      addshow:true,
      addname:''
    })
  },
  hideadd:function(e)
  {
    this.setData({
      addshow:false
    })
  },
  hideuser:function(e)
  {
    this.setData({
      editshow:false
    })
  },
  showuser:function(e)
  {
    var showid=e.currentTarget.dataset.showid;
    var showname=e.currentTarget.dataset.showname;
    var showsort=e.currentTarget.dataset.showsort;
    var glist=this.data.glist;
    let a = {showid:showid};
    let groupindex= glist.findIndex(item => item.id === a.showid);
    groupindex=groupindex==-1?0:groupindex;
    this.setData({
      showid:showid,
      editshow:true,
      showname:showname,
      showsort:showsort,
      groupindex:groupindex
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
  savegroup:function(e)
  {
    var that=this;
    var showid=this.data.showid;
    var showname=this.data.showname;
    var showsort=this.data.showsort;
    var groupindex=this.data.groupindex;
    wx.request({
      url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
      data: {
        tag: "savebluegroup",  
        id:showid,
        showname:showname,
        showsort:showsort,
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res)
      {
        wx.showToast({
          title: '保存成功！',
          icon:'success'
        })

      let gName = "glist[" + groupindex + "].gName"
      let gSort = "glist[" + groupindex + "].gSort"
      that.setData({
      [gName]: showname,
      [gSort]: showsort,
      editshow:false
      })

      }
    })
  },  
  addgroup:function(e)
  {
    var that=this;
    var tid=app.globalData.tid;
    var addname=this.data.addname;
    var addsort=this.data.addsort;
    if(addname=='')
    {
      wx.showToast({
        title: '请输入分类名称！',
        icon:'none'
      })
      return;
    }else{
      wx.request({
        url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
        data: {
          tag: "addbluegroup",  
          tid:tid,
          addname:addname,
          addsort:addsort,
        },
        header: {
          'content-type': 'application/x-www-form-urlencoded'
        },
        success: function (res)
        {
          if(res.data.st=="1")
          {
            wx.showToast({
              title: '保存成功!',
              duration:1000, 
              icon: 'success',
              success: function ()
              {
                setTimeout(function ()
                {
                  that.setData({
                    addshow:false
                  })
                  that.loadcenter(that);
                },1000)
              }
            })       

          }else
          {
            wx.showToast({
              title: '分类名称重复！',
              icon:'success'
            })
          }
        }
      })
    }
  },
  delgroup:function(e)
  {    
    var that=this;    
    var groupindex=this.data.groupindex;
    wx.showModal({
      title: "提示",
      content: "是否删除",
      success: function (res)
      {
        if (res.confirm)
        {
          var id=that.data.showid;
          wx.request({
            url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
            data: {
              tag: "del",  
              i:id,
              t:'blueGroup',
            },
            header: {
              'content-type': 'application/x-www-form-urlencoded'
            },
            success: function (res)
            {
              wx.showToast({
                title: '删除成功！',
                icon:'none'
              })
              var count=that.data.count-1;
              that.data.glist.splice(groupindex, 1);
              that.setData({
                glist:that.data.glist,
                editshow:false,
                count:count
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
  loadcenter:function(e)
  {    
    wx.showLoading({title: '数据加载中...',mask: true }); 
    var tid=app.globalData.tid;
    wx.request({
      url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
      data: {
        tag: "getGroup",      
        tid:tid,
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res)
      {
        wx.hideLoading();
        e.setData({
          glist:res.data.group,
          count:res.data.count
        });
      }
    })
  },
  onLoad: function (options)
  {
    var that=this;
    if (app.globalData.employ && app.globalData.employ!= '') 
    {
      that.loadcenter(that);
    }else
    {
      app.employCallback = employ => {
      if (employ!= '')
      {
        that.loadcenter(that);
      }
      }
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
    this.loadcenter(this);
    wx.stopPullDownRefresh();//停止刷新操作
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom() 
  {
  },

})