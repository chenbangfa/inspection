const GetPeriod = require("../../utils/getperiod.js");
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    navbarData: {
      showCapsule: 1, //是否显示左上角图标   1表示显示    0表示不显示
      title: '巡检统计', //导航栏 中间的标题
    },
    height: app.globalData.height, 

    period: '',
    editshow:false,
    peopleshow:false,
    people:[],
    zyshow:false,
    zylist:[],    
    typedata:'',
    sdate:'',
    edate:'',
    together:'',
    zysearch:'',
    hyIdentity:0,
    timelist:[],
    droname:''

  },
  closezt:function()
  {
    this.setData({
      droname:'', 
      odlist: []
    })
    this.loadmydongtai(this,0);
  },
  closedate:function()
  {
    this.setData({
      sdate:'',
      edate:'',        
      odlist: []
    })
    this.loadmydongtai(this,0);
  },
  closezy:function()
  {
    this.setData({
      zysearch:'',
      odlist: []
    })
    this.loadmydongtai(this,0);
  },
  closehy:function()
  {
    this.setData({
      together:'',
      odlist: []
    })
    this.loadmydongtai(this,0);
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
    zysearch:zysearch
   })
  },
  peoplechange:function(e)
  {
    let checked =e.detail.value; 
    var together='';
    var i=0;
    checked.forEach(element => {
      if(i==0)
      together=element;
      else
      together=together+","+element;
      i++;
   });
   this.setData({
    together:together
   })
   //select * from inspect where FIND_IN_SET(hyName,'周勤杰,老陈')
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
  getzylist:function(e)
  {
    var that=this;
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
        that.setData({
            zylist:res.data.group,
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
  hideeditshow:function(e)
  {
    this.setData({
      editshow:false
    })
  },
  hidepeople:function(e)
  {
    this.setData({
      peopleshow:false
    })
  },
  datashow:function(e)
  {
    this.setData({
      editshow:true
    })
  },  
  dataserach:function(e)
  {
   var typedata=e.currentTarget.dataset.typedata;
   switch(typedata)
   {
    case '全部':
      this.setData({
        sdate:'',
        edate:'',        
        editshow:false,
        odlist: []
      })
      this.loadmydongtai(this,0);
    break;
    case '今日':
      var time = this.data.period.getNowDate();
      this.setData({
        sdate:time+" 00:00:00",
        edate:time+" 23:59:59",
        editshow:false,
        odlist: []
      })
      this.loadmydongtai(this,0);
      break;
    case '昨日':
      var time = this.data.period.getYesterday();
      this.setData({
        sdate:time+" 00:00:00",
        edate:time+" 23:59:59",
        editshow:false,
        odlist: []
      })
      this.loadmydongtai(this,0);
      break;
    case '本周':
      var startDate = this.data.period.getWeekStartDate();
      var endDate = this.data.period.getWeekEndDate();
      this.setData({
        sdate:startDate+" 00:00:00",
        edate:endDate+" 23:59:59",
        editshow:false,
        odlist: []
      })
      this.loadmydongtai(this,0); 
      break;
    case '上周':
      var time = this.data.period.getTimeLastWeek(7);
      var times = this.data.period.getTimeLastWeek(1);
      this.setData({
        sdate:time+" 00:00:00",
        edate:times+" 23:59:59",
        editshow:false,
        odlist: []
      })
      this.loadmydongtai(this,0);    
      break;
    case '本月':
      var time = this.data.period.getMonthStartDate();
      var times = this.data.period.getMonthEndDate();      
      this.setData({
        sdate:time+" 00:00:00",
        edate:times+" 23:59:59",
        editshow:false,
        odlist: []
      })
      this.loadmydongtai(this,0);    
      break;      
    case '本季':
      var time = this.data.period.getQuarterStartDate();
      var times = this.data.period.getQuarterEndDate();     
      this.setData({
        sdate:time+" 00:00:00",
        edate:times+" 23:59:59",
        editshow:false,
        odlist: []
      })
      this.loadmydongtai(this,0);    
      break;
   case '本年':
        var time = this.data.period.getYearStartDate();
        var times = this.data.period.getYearEndDate();        
        this.setData({
          sdate:time+" 00:00:00",
          edate:times+" 23:59:59",
          editshow:false,
          odlist: []
        })
        this.loadmydongtai(this,0);    
      break;
   }
  },  
  searchhy:function(e)
  {
    var together=this.data.together;
    if(together=='')
    {
      wx.showToast({
        title: "请选择巡检人",
        icon: "none",
        duration: 1500
      })  
      return false;
    }else{
    this.setData({
      together:together,
      peopleshow:false,
      odlist: []
    })
    this.loadmydongtai(this,0);    
  }
  },
  searchzy:function(e)
  {
    var zysearch=this.data.zysearch;
    var droname=this.data.droname;
    if(zysearch==''&&droname=='')
    {
      wx.showToast({
        title: "请选择分类或输入名称",
        icon: "none",
        duration: 1500
      })  
      return false;
    }else{
    this.setData({
      zysearch:zysearch,
      zyshow:false,
      odlist: []
    })
    this.loadmydongtai(this,0);    
  }
  },
  searchdate:function(e)
  {
    var time = this.data.sdate;
    var times = this.data.edate;      
    console.log(time+"////"+times);
    if(time=='')  
    {
      wx.showToast({
        title: "请选择开始日期",
        icon: "none",
        duration: 1500
      })  
      return false;
    }else if(times=='')
    {
      wx.showToast({
        title: "请选择结束日期",
        icon: "none",
        duration: 1500
      })  
      return false;
    }else{
    this.setData({
      sdate:time,
      edate:times,
      editshow:false,
      odlist: []
    })
    this.loadmydongtai(this,0);    
  }
  },
  bindDateChange: function(e)
  {
    this.setData({
      sdate: e.detail.value+" 23:59:59"
    })
  },
  endDateChange: function(e)
  {
    this.setData({
      edate: e.detail.value+" 23:59:59"
    })
  },     
  onLoad(options)
  {    
    this.data.period = new GetPeriod();
    var hyIdentity=app.globalData.hyIdentity;
    var time = this.data.period.getMonthStartDate();
    var times = this.data.period.getMonthEndDate();   

    var year=this.data.period.nowYear;
    var month=this.data.period.nowMonth;
    var day=this.data.period.nowDay

    var that=this;
    this.setData({
      sdate:time+" 00:00:00",
      edate:times+" 23:59:59",
      hyIdentity:hyIdentity,
      month:month,
      day:day
    })
    if (app.globalData.employ && app.globalData.employ!= '') 
      that.loadmydongtai();
    else
    {
      app.employCallback = employ => {
      if (employ!= '')
        that.loadmydongtai();
      }
    }
  },
  loadmydongtai:function(e)
  {    
    var that=this;
    wx.showLoading({title: '数据加载中...',mask: true });
      var tid=app.globalData.tid
      var hyid=app.globalData.hyid
      var hyIdentity=app.globalData.hyIdentity;
      
      var time = this.data.sdate;
      var times = this.data.edate;  
      var zysearch=this.data.zysearch;
      var together=this.data.together;
      var droname=this.data.droname;

      wx.request({
        url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
        data: {
          tag: "getbluecount",
          tid:tid,
          hyid:hyid,
          hyIdentity:hyIdentity,
          droname:droname,
          time:time,
          times:times,
          zysearch:zysearch,
          together:together,
        },
        header: {
          'content-type': 'application/x-www-form-urlencoded'
        },
        success: function (res)
        {
          console.log(res.data);
          that.setData({
            timelist:res.data.timelist,
            xjcount:res.data.xjcount,
            yccount:res.data.yccount,
            wjcount:res.data.wjcount
          })
          wx.hideLoading();
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
  onPullDownRefresh: function ()
  {
    this.loadmydongtai(this);
    wx.stopPullDownRefresh();//停止刷新操作
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
   * 页面上拉触底事件的处理函数
   */
  onReachBottom() 
  {
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage() {

  }
})