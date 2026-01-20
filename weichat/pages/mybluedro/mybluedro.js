// pages/dtlist/dtlist.js
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    navbarData: {
      showCapsule: 0, //是否显示左上角图标   1表示显示    0表示不显示
      title: '我的巡检', //导航栏 中间的标题
    },
    height: app.globalData.height, 
    odlist: [],
    page:0,
    isdata:false,
    group:[],
    count:0,

    zqvalue:'',
    result:'',
    states:'',
    gname:'',
    droname:'',
    drores:'',
    drostate:''
    
  }, 
   
  dronameclase:function(e)
  {
    this.setData({
      droname:'',
      odlist: []
    })
    this.loadmydongtai(this,0);    
  },  
   
  gnameclase:function(e)
  {
    this.setData({
      gname:'',
      odlist: []
    })
    this.loadmydongtai(this,0);    
  },  
  searchname:function()
  {
    var gname=this.data.gname;
    var droname=this.data.droname;
    if(gname==''&&droname=='')
    {
      wx.showToast({
        title: '请选择分类或输入巡检点查询！',
      })
    }else
    {
      this.setData({
        odlist: [],
        groupshow:false
      })
      this.loadmydongtai(this,0);   
    }
  },
  zychange:function(e)
  {
    let checked =e.detail.value; 
    var zysearch='';
    var i=0;
    checked.forEach(element => {
      if(i==0)
      zysearch=element;
      else
      zysearch=zysearch+","+element;
      i++;
   });
   //select * from inspect where FIND_IN_SET(hyName,'周勤杰,老陈')
   this.setData({
    gname:zysearch
   })
  },
  stateserach:function(e)
  {
    var zdval=e.currentTarget.dataset.typedata;
    var drores=e.currentTarget.dataset.drores;
    this.setData({
      states:zdval,
      drores:drores,
      odlist: [],
      stateshow:false
    })
    this.loadmydongtai(this,0);    
  },    
  statesclase:function(e)
  {
    this.setData({
      states:'',
      drores:'',
      odlist: []
    })
    this.loadmydongtai(this,0);    
  },  
  resserach:function(e)
  {
    var zdval=e.currentTarget.dataset.typedata;
    var drostate=e.currentTarget.dataset.drostate;
    this.setData({
      result:zdval,
      drostate:drostate,
      odlist: [],
      resshow:false
    })
    this.loadmydongtai(this,0);    
  },  
  
  resclase:function(e)
  {
    this.setData({
      result:'',
      drostate:'',
      odlist: []
    })
    this.loadmydongtai(this,0);    
  },  
  dataclase:function(e)
  {
    this.setData({
      zqvalue:'',
      odlist: []
    })
    this.loadmydongtai(this,0);    
  },  
  dataserach:function(e)
  {
    var zdval=e.currentTarget.dataset.zdval;
    this.setData({
      zqvalue:zdval,
      odlist: [],
      editshow:false
    })
    this.loadmydongtai(this,0);    
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
  viewWacth(e)
  {
    let views = e.currentTarget.dataset.views;
    let isval = e.currentTarget.dataset.isval;
    this.setData(
      {
        [views]: isval
      }
    )
  },
  onLoad(options)
  {
    var that=this;
    var page=that.data.page;
    if (app.globalData.employ && app.globalData.employ!= '') 
      that.loadmydongtai(that,page);
    else
    {
      app.employCallback = employ => {
      if (employ!= '')
        that.loadmydongtai(that,page);
      }
    }
  },
  loadmydongtai:function(e,page)
  {
    wx.showLoading({title: '数据加载中...',mask: true });
    var hyIdentity=app.globalData.hyIdentity;
      var tid=app.globalData.tid;
      var hyid=app.globalData.hyid;
      var zqvalue=e.data.zqvalue;
      var drostate=e.data.drostate;
      var drores=e.data.drores;
      var gname=e.data.gname;
      var droname=e.data.droname;
      wx.request({
        url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
        data: {
          tag: "getbluelist",
          p:page+1,
          tid:tid,
          hyid:hyid,
          hyIdentity:hyIdentity,
          zqvalue:zqvalue,
          drostate:drostate,
          drores:drores,
          gname:gname,
          droname:droname,
          myblue:'myblue'
        },
        header: {
          'content-type': 'application/x-www-form-urlencoded'
        },
        success: function (res)
        {
          if(res.data.odlist.length>0)
          {
            e.setData({
              ["odlist["+page+"]"]:res.data.odlist,
              page:page,
              isdata:false,
              group:res.data.group,
              count:res.data.count,
            });
          }else{
            e.setData({
              ["odlist["+page+"]"]:[],
              page:page-1,
              isdata:true,
            });
          }
          wx.hideLoading();
        }
      })
  },  
  onPullDownRefresh: function ()
  {
    this.setData({
      odlist: []
    })
    this.loadmydongtai(this,0);
    wx.stopPullDownRefresh();//停止刷新操作
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
      this.loadmydongtai(this,0)
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
   * 页面上拉触底事件的处理函数
   */
  onReachBottom() 
  {
    if(!this.data.isdata)
    {
    wx.showLoading({title: '数据加载中...',mask: true });
    var that=this;
    var page=that.data.page;
    page=page+1;
    that.loadmydongtai(that,page);
    }
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage() {

  }
})