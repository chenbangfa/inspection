
const GetPeriod = require("../../utils/getperiod.js");
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    navbarData: {
      showCapsule: 1, //是否显示左上角图标   1表示显示    0表示不显示
      title: '日常巡检记录', //导航栏 中间的标题
    },
    height: app.globalData.height,
    odlist: [],
    page: 0,
    isdata: false,
    count: 0,
    period: '',
    editshow: false,
    peopleshow: false,
    people: [],
    zyshow: false,
    zylist: [],
    teamshow: false,
    teamlist: [],
    tid: app.globalData.tid,
    tName: '',

    sdate: '',
    edate: '',
    zgState: '',
    zgIndex: 0,
    zgArray: ['全部', '待整改', '整改中', '已整改'],

    // Batch Assign Data
    isBatchMode: false,
    selectedItems: [], // Array of selected inspect IDs
    allSelected: false,

    // Assignment Modal Data
    assignShow: false,
    assignUserIndex: -1,
    assignUsers: [], // List of users to assign to (from teamlist/hylist)
    assignRequest: '',
    assignDeadline: '',
  },

  // Rectification State Picker
  bindZgStateChange: function (e) {
    var index = e.detail.value;
    var state = '';
    if (index == 1) state = '0'; // Pending (assuming 0 is default/pending)
    else if (index == 2) state = '1'; // Assigned/In Progress
    else if (index == 3) state = '2'; // Completed

    this.setData({
      zgIndex: index,
      zgState: state,
      odlist: []
    });
    this.loadmydongtai(this, 0);
  },

  // Batch Mode Toggle
  toggleBatchMode: function () {
    this.setData({
      isBatchMode: !this.data.isBatchMode,
      selectedItems: [],
      allSelected: false
    });
  },

  // Checkbox Change
  checkboxChange: function (e) {
    this.setData({
      selectedItems: e.detail.value
    });
  },

  // Select All
  selectAll: function () {
    var allSelected = !this.data.allSelected;
    var selectedItems = [];
    if (allSelected) {
      this.data.odlist.forEach(pageList => {
        if (pageList) {
          pageList.forEach(item => selectedItems.push(item.id.toString()));
        }
      });
    }
    this.setData({
      allSelected: allSelected,
      selectedItems: selectedItems
    });
  },

  // Show Assignment Modal
  showAssignModal: function () {
    if (this.data.selectedItems.length === 0) {
      wx.showToast({ title: '请先选择记录', icon: 'none' });
      return;
    }

    // Load team members if not already loaded
    if (this.data.people.length === 0) {
      this.getpeople();
    }

    this.setData({
      assignShow: true,
      assignDeadline: app.getNowDate() // Default to today or handle empty
    });
  },

  hideAssignModal: function () {
    this.setData({ assignShow: false });
  },

  bindAssignUserChange: function (e) {
    this.setData({ assignUserIndex: e.detail.value });
  },

  bindAssignDateChange: function (e) {
    this.setData({ assignDeadline: e.detail.value });
  },

  inputAssignRequest: function (e) {
    this.setData({ assignRequest: e.detail.value });
  },

  // Submit Assignment
  submitAssignment: function () {
    var that = this;
    var zgUserId = '';
    var zgName = '';

    if (this.data.assignUserIndex > -1 && this.data.people.length > 0) {
      zgUserId = this.data.people[this.data.assignUserIndex].id;
      zgName = this.data.people[this.data.assignUserIndex].hyName;
    } else {
      wx.showToast({ title: '请选择整改人', icon: 'none' });
      return;
    }

    if (!this.data.assignRequest) {
      wx.showToast({ title: '请填写整改要求', icon: 'none' });
      return;
    }

    if (!this.data.assignDeadline) {
      wx.showToast({ title: '请选择整改期限', icon: 'none' });
      return;
    }

    wx.showLoading({ title: '分配中...' });

    wx.request({
      url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
      data: {
        tag: "assign_rectification",
        ids: this.data.selectedItems.join(','),
        zgUserId: zgUserId,
        zgName: zgName,
        zgAsk: this.data.assignRequest,
        zgTime: this.data.assignDeadline
      },
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: function (res) {
        wx.hideLoading();
        if (res.data.code == "200") {
          wx.showToast({ title: '分配成功' });
          that.setData({
            assignShow: false,
            isBatchMode: false,
            selectedItems: [],
            odlist: []
          });
          that.loadmydongtai(that, 0);
        } else {
          wx.showToast({ title: res.data.msg || '分配失败', icon: 'none' });
        }
      }
    });
  },

  closedate: function () {
    this.setData({
      sdate: '',
      edate: '',
      odlist: []
    })
    this.loadmydongtai(this, 0);
  },
  closezy: function () {
    this.setData({
      zysearch: '',
      odlist: []
    })
    this.loadmydongtai(this, 0);
  },
  closehy: function () {
    this.setData({
      together: '',
      odlist: []
    })
    this.loadmydongtai(this, 0);
  },
  zychange: function (e) {
    let checked = e.detail.value;
    var zysearch = '';
    var i = 0;
    checked.forEach(element => {
      if (i == 0)
        zysearch = element;
      else
        zysearch = zysearch + "," + element;
      i++;
    });
    //select * from inspect where FIND_IN_SET(hyName,'周勤杰,老陈')
    this.setData({
      zysearch: zysearch
    })
  },
  peoplechange: function (e) {
    let checked = e.detail.value;
    var together = '';
    var i = 0;
    checked.forEach(element => {
      if (i == 0)
        together = element;
      else
        together = together + "," + element;
      i++;
    });
    this.setData({
      together: together
    })
    //select * from inspect where FIND_IN_SET(hyName,'周勤杰,老陈')
  },
  teamchange: function (e) {
    var tid = e.detail.value;
    var carArray1 = this.data.teamlist.filter(item => item.tId == tid);
    this.setData({
      tid: e.detail.value,
      tName: carArray1[0].tName,
      teamshow: false,
      odlist: []
    })
    console.log(carArray1[0].tName);
    this.loadmydongtai(this, 0);
  },
  getpeople: function (e) {
    var that = this;
    var tid = app.globalData.tid;
    wx.request({
      url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
      data: {
        tag: "gethylist",
        tid: tid,
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res) {
        wx.hideLoading();
        that.setData({
          people: res.data.hylist,
          peopleshow: true,
        });
      }
    })
  },
  getzylist: function (e) {
    var that = this;
    var tid = app.globalData.tid;
    wx.request({
      url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
      data: {
        tag: "getzylist",
        tid: tid,
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res) {
        wx.hideLoading();
        that.setData({
          zylist: res.data.zylist,
          zyshow: true,
        });
      }
    })
  },
  hidezyshow: function (e) {
    this.setData({
      zyshow: false
    })
  },
  hideeditshow: function (e) {
    this.setData({
      editshow: false
    })
  },
  hidepeople: function (e) {
    this.setData({
      peopleshow: false
    })
  },
  datashow: function (e) {
    this.setData({
      editshow: true
    })
  },
  hideteam: function (e) {
    this.setData({
      teamshow: false
    })
  },
  showteamlist: function (e) {
    this.getteamlist();
    this.setData({
      teamshow: true
    })
  },
  getteamlist: function (e) {
    var that = this;
    var hytel = app.globalData.hytel;
    wx.request({
      url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
      data: {
        tag: "welcome",
        hytel: hytel,
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res) {
        console.log(res.data)
        var tid = that.data.tid;
        var carArray1 = res.data.teamlist.filter(item => item.tId == tid);
        var tName = carArray1[0].tName;
        that.setData({
          teamlist: res.data.teamlist,
          tName: tName
        });
      }
    })
  },
  dataserach: function (e) {
    var typedata = e.currentTarget.dataset.typedata;
    switch (typedata) {
      case '全部':
        this.setData({
          sdate: '',
          edate: '',
          editshow: false,
          odlist: []
        })
        this.loadmydongtai(this, 0);
        break;
      case '今日':
        var time = this.data.period.getNowDate();
        this.setData({
          sdate: time + " 00:00:00",
          edate: time + " 23:59:59",
          editshow: false,
          odlist: []
        })
        this.loadmydongtai(this, 0);
        break;
      case '昨日':
        var time = this.data.period.getYesterday();
        this.setData({
          sdate: time + " 00:00:00",
          edate: time + " 23:59:59",
          editshow: false,
          odlist: []
        })
        this.loadmydongtai(this, 0);
        break;
      case '本周':
        var startDate = this.data.period.getWeekStartDate();
        var endDate = this.data.period.getWeekEndDate();
        this.setData({
          sdate: startDate + " 00:00:00",
          edate: endDate + " 23:59:59",
          editshow: false,
          odlist: []
        })
        this.loadmydongtai(this, 0);
        break;
      case '上周':
        var time = this.data.period.getTimeLastWeek(7);
        var times = this.data.period.getTimeLastWeek(1);
        this.setData({
          sdate: time + " 00:00:00",
          edate: times + " 23:59:59",
          editshow: false,
          odlist: []
        })
        this.loadmydongtai(this, 0);
        break;
      case '本月':
        var time = this.data.period.getMonthStartDate();
        var times = this.data.period.getMonthEndDate();
        this.setData({
          sdate: time + " 00:00:00",
          edate: times + " 23:59:59",
          editshow: false,
          odlist: []
        })
        this.loadmydongtai(this, 0);
        break;
      case '本季':
        var time = this.data.period.getQuarterStartDate();
        var times = this.data.period.getQuarterEndDate();
        this.setData({
          sdate: time + " 00:00:00",
          edate: times + " 23:59:59",
          editshow: false,
          odlist: []
        })
        this.loadmydongtai(this, 0);
        break;
      case '本年':
        var time = this.data.period.getYearStartDate();
        var times = this.data.period.getYearEndDate();
        this.setData({
          sdate: time + " 00:00:00",
          edate: times + " 23:59:59",
          editshow: false,
          odlist: []
        })
        this.loadmydongtai(this, 0);
        break;
    }
  },
  searchhy: function (e) {
    var together = this.data.together;
    if (together == '') {
      wx.showToast({
        title: "请选择巡检人",
        icon: "none",
        duration: 1500
      })
      return false;
    } else {
      this.setData({
        together: together,
        peopleshow: false,
        odlist: []
      })
      this.loadmydongtai(this, 0);
    }
  },
  searchzy: function (e) {
    var zysearch = this.data.zysearch;
    if (zysearch == '') {
      wx.showToast({
        title: "请选择隐患分类",
        icon: "none",
        duration: 1500
      })
      return false;
    } else {
      this.setData({
        zysearch: zysearch,
        zyshow: false,
        odlist: []
      })
      this.loadmydongtai(this, 0);
    }
  },
  searchdate: function (e) {
    var time = this.data.sdate;
    var times = this.data.edate;
    if (time == '') {
      wx.showToast({
        title: "请选择开始日期",
        icon: "none",
        duration: 1500
      })
      return false;
    } else if (times == '') {
      wx.showToast({
        title: "请选择结束日期",
        icon: "none",
        duration: 1500
      })
      return false;
    } else {
      this.setData({
        sdate: time + " 00:00:00",
        edate: times + " 23:59:59",
        editshow: false,
        odlist: []
      })
      this.loadmydongtai(this, 0);
    }
  },
  bindDateChange: function (e) {
    this.setData({
      sdate: e.detail.value + " 23:59:59"
    })
  },
  endDateChange: function (e) {
    this.setData({
      edate: e.detail.value + " 23:59:59"
    })
  },
  onLoad(options) {
    this.data.period = new GetPeriod();
    var hyIdentity = app.globalData.hyIdentity;
    this.setData({
      hyIdentity: hyIdentity,
    })
    var that = this;
    var page = that.data.page;
    if (app.globalData.employ && app.globalData.employ != '')
      that.loadmydongtai(that, page);
    else {
      app.employCallback = employ => {
        if (employ != '')
          that.loadmydongtai(that, page);
      }
    }
  },
  loadmydongtai: function (e, page) {
    wx.showLoading({ title: '数据加载中...', mask: true });
    var tid = this.data.tid;
    var hyid = app.globalData.hyid;
    var hyIdentity = app.globalData.hyIdentity;
    var time = this.data.sdate;
    var times = this.data.edate;
    var zysearch = this.data.zysearch;
    var together = this.data.together;
    var zgState = this.data.zgState; // Add zgState
    wx.request({
      url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
      data: {
        tag: "inspectlist",
        p: page + 1,
        tid: tid,
        hyid: hyid,
        hyIdentity: hyIdentity,
        time: time,
        times: times,
        zysearch: zysearch,
        together: together,
        zgState: zgState, // Pass zgState
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res) {
        wx.hideLoading();
        if (res.data.odlist.length > 0) {
          e.setData({
            ["odlist[" + page + "]"]: res.data.odlist,
            page: page,
            count: res.data.odlist[0].count,
            isdata: false,
          });
        } else {
          e.setData({
            ["odlist[" + page + "]"]: [],
            page: page - 1,
            isdata: true,
          });
        }
      }
    });
  },
  // 查看导出返回的字节流
  download() {
    var tid = app.globalData.tid
    var hyid = app.globalData.hyid
    var time = this.data.sdate;
    var times = this.data.edate;
    var zysearch = this.data.zysearch;
    var together = this.data.together;
    wx.showLoading({ title: '表格生成中...', mask: true });
    wx.request({
      url: "https://xj.tajian.cc/servers/sendexcel/inspect.php",
      data: {
        tid: tid,
        hyid: hyid,
        time: time,
        times: times,
        zysearch: zysearch,
        together: together,
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      responseType: "arraybuffer", //注意这里的responseType
      success(res) {
        wx.getFileSystemManager().writeFile({
          filePath: wx.env.USER_DATA_PATH + "/日常巡检记录表.xlsx",
          data: res.data,
          encoding: "binary",
          success: res => {
            wx.hideLoading();
            wx.openDocument({
              showMenu: "true",
              filePath: wx.env.USER_DATA_PATH + "/日常巡检记录表.xlsx",
            })
          }
        })
      },
      fail(err) {
        wx.hideLoading();
        wx.showToast({
          title: err,
          icon: "none",
          duration: 3000
        })
      }
    })
  },
  onPullDownRefresh: function () {
    var that = this;
    that.setData({
      odlist: []
    })
    var page = 0;
    that.loadmydongtai(that, page);
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
  onReachBottom() {
    if (!this.data.isdata) {
      wx.showLoading({ title: '数据加载中...', mask: true });
      var that = this;
      var page = that.data.page;
      page = page + 1;
      that.loadmydongtai(that, page);
    }
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage() {

  }
})