
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

    // Core Filter Data (Actual applied filters)
    tid: app.globalData.tid,
    tName: '',
    sdate: '',
    edate: '',
    together: '',   // Comma separated names
    zysearch: '',   // Comma separated types
    zgState: '',    // '0', '1', '2'
    zgStateName: '', // Display name for chip

    // Drawer & Temp Filter Data
    showDrawer: false,
    dateLimit: '', // 'today', 'week', 'month'
    tempSdate: '',
    tempEdate: '',
    tempZgIndex: 0, // 0:All, 1:Pending, 2:In Progress, 3:Completed

    // Data Lists
    people: [],     // List of objects {hyName, checked}
    zylist: [],     // List of objects {gName, checked}
    teamlist: [],
    teamshow: false, // For team switcher modal

    // Batch Assign Data
    isBatchMode: false,
    selectedItems: [],
    allSelected: false,
    assignShow: false,
    assignUserIndex: -1,
    assignUsers: [],
    assignRequest: '',
    assignDeadline: '',
  },

  onLoad(options) {
    this.data.period = new GetPeriod();
    this.setData({
      hyIdentity: app.globalData.hyIdentity,
    })
    var that = this;

    // Init loads
    if (app.globalData.employ && app.globalData.employ != '') {
      that.loadmydongtai(that, 0);
      that.getteamlist(); // Load teams silently for switcher
    } else {
      app.employCallback = employ => {
        if (employ != '') {
          that.loadmydongtai(that, 0);
          that.getteamlist();
        }
      }
    }
  },

  // =========================
  // Drawer & Filter Logic
  // =========================

  openFilterDrawer: function () {
    // 1. Load data if needed
    if (this.data.people.length === 0) this.getpeople(false);
    if (this.data.zylist.length === 0) this.getzylist(false);

    // 2. Sync temp state from actual state
    // Map together string back to checked status
    let distinctPeople = this.data.people.map(p => {
      p.checked = this.data.together.indexOf(p.hyName) > -1;
      return p;
    });
    // Map zysearch string back to checked status
    let distinctZy = this.data.zylist.map(z => {
      z.checked = this.data.zysearch.indexOf(z.gName) > -1;
      return z;
    });

    // Map zgState to Index
    let zgIdx = 0;
    if (this.data.zgState === '0') zgIdx = 1;
    else if (this.data.zgState === '1') zgIdx = 2;
    else if (this.data.zgState === '2') zgIdx = 3;

    // Reset date limit highlight if custom dates don't match (simplification: just clear limit highlight when opening)
    // Or check if current sdate/edate matches a specific limit. For now, leave blank or retain if tracked.

    this.setData({
      showDrawer: true,
      people: distinctPeople,
      zylist: distinctZy,
      tempSdate: this.data.sdate ? this.data.sdate.split(' ')[0] : '',
      tempEdate: this.data.edate ? this.data.edate.split(' ')[0] : '',
      tempZgIndex: zgIdx,
      dateLimit: '' // Reset highlight for simplicity
    });
  },

  closeFilterDrawer: function () {
    this.setData({ showDrawer: false });
  },

  // Date Filter Logic
  selectDateLimit: function (e) {
    let type = e.currentTarget.dataset.type;
    let s = '', e_date = '';

    if (type === 'today') {
      let now = this.data.period.getNowDate();
      s = now; e_date = now;
    } else if (type === 'week') {
      s = this.data.period.getWeekStartDate();
      e_date = this.data.period.getWeekEndDate();
    } else if (type === 'month') {
      s = this.data.period.getMonthStartDate();
      e_date = this.data.period.getMonthEndDate();
    }

    this.setData({
      dateLimit: type,
      tempSdate: s,
      tempEdate: e_date
    });
  },

  bindTempDateChange: function (e) {
    let type = e.currentTarget.dataset.type;
    let val = e.detail.value;
    if (type === 'start') {
      this.setData({ tempSdate: val, dateLimit: '' });
    } else {
      this.setData({ tempEdate: val, dateLimit: '' });
    }
  },

  // Status Filter Logic
  selectTempZgState: function (e) {
    this.setData({ tempZgIndex: e.currentTarget.dataset.index });
  },

  // Tag Toggles
  toggleTempPeople: function (e) {
    let idx = e.currentTarget.dataset.index;
    let list = this.data.people;
    list[idx].checked = !list[idx].checked;
    this.setData({ people: list });
  },

  toggleTempZy: function (e) {
    let idx = e.currentTarget.dataset.index;
    let list = this.data.zylist;
    list[idx].checked = !list[idx].checked;
    this.setData({ zylist: list });
  },

  // Reset & Apply
  resetFilters: function () {
    // Clear all temp states (but keep people/zylist loaded, just uncheck)
    let listP = this.data.people.map(p => { p.checked = false; return p; });
    let listZ = this.data.zylist.map(z => { z.checked = false; return z; });

    this.setData({
      tempSdate: '',
      tempEdate: '',
      dateLimit: '',
      tempZgIndex: 0,
      people: listP,
      zylist: listZ
    });
  },

  applyFilters: function () {
    // 1. Commit Temp to Actual
    let sdate = this.data.tempSdate ? this.data.tempSdate + " 00:00:00" : '';
    let edate = this.data.tempEdate ? this.data.tempEdate + " 23:59:59" : '';

    // ZgState
    let zgState = '';
    let zgName = '';
    if (this.data.tempZgIndex == 1) { zgState = '0'; zgName = '待整改'; }
    else if (this.data.tempZgIndex == 2) { zgState = '1'; zgName = '整改中'; }
    else if (this.data.tempZgIndex == 3) { zgState = '2'; zgName = '已整改'; }

    // People
    let together = this.data.people.filter(p => p.checked).map(p => p.hyName).join(',');

    // ZY
    let zysearch = this.data.zylist.filter(z => z.checked).map(z => z.gName).join(',');

    this.setData({
      sdate: sdate,
      edate: edate,
      zgState: zgState,
      zgStateName: zgName,
      together: together,
      zysearch: zysearch,
      showDrawer: false,
      odlist: [] // Reset list
    });

    // 2. Refresh
    this.loadmydongtai(this, 0);
  },

  // =========================
  // Chip Removal Logic
  // =========================
  closedate: function () {
    this.setData({ sdate: '', edate: '', odlist: [] });
    this.loadmydongtai(this, 0);
  },
  closehy: function () {
    this.setData({ together: '', odlist: [] });
    this.loadmydongtai(this, 0);
  },
  closezy: function () {
    this.setData({ zysearch: '', odlist: [] });
    this.loadmydongtai(this, 0);
  },
  closeZgState: function () {
    this.setData({ zgState: '', zgStateName: '', odlist: [] });
    this.loadmydongtai(this, 0);
  },


  // =========================
  // Team Switcher Logic
  // =========================
  showteamlist: function () {
    if (this.data.teamlist.length === 0) this.getteamlist();
    this.setData({ teamshow: true });
  },

  hideteam: function () {
    this.setData({ teamshow: false });
  },

  teamchange: function (e) {
    var tid = e.detail.value;
    var team = this.data.teamlist.find(item => item.tId == tid);
    if (team) {
      app.globalData.tid = tid; // Sync global?
      this.setData({
        tid: tid,
        tName: team.tName,
        teamshow: false,
        odlist: []
      });
      this.loadmydongtai(this, 0);
    }
  },

  getteamlist: function () {
    var that = this;
    var hytel = app.globalData.hytel;
    wx.request({
      url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
      data: { tag: "welcome", hytel: hytel },
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: function (res) {
        var tid = that.data.tid;
        var team = res.data.teamlist.find(item => item.tId == tid);
        var tName = team ? team.tName : '';
        that.setData({
          teamlist: res.data.teamlist,
          tName: tName
        });
      }
    })
  },

  // =========================
  // Data Loading & Helpers (Keep Existing)
  // =========================

  getpeople: function (showModal = true) {
    var that = this;
    var tid = app.globalData.tid;
    wx.request({
      url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
      data: { tag: "gethylist", tid: tid },
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: function (res) {
        // Add checked prop
        let list = res.data.hylist.map(item => { item.checked = false; return item; });
        that.setData({ people: list });
      }
    })
  },

  getzylist: function (showModal = true) {
    var that = this;
    var tid = app.globalData.tid;
    wx.request({
      url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
      data: { tag: "getzylist", tid: tid },
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: function (res) {
        let list = res.data.zylist.map(item => { item.checked = false; return item; });
        that.setData({ zylist: list });
      }
    })
  },

  loadmydongtai: function (e, page) {
    wx.showLoading({ title: '数据加载中...', mask: true });
    var that = this; // Ensure 'this' context
    var tid = this.data.tid;
    var hyid = app.globalData.hyid;
    var hyIdentity = app.globalData.hyIdentity;

    // Use actual filter state
    var time = this.data.sdate;
    var times = this.data.edate;
    var zysearch = this.data.zysearch;
    var together = this.data.together;
    var zgState = this.data.zgState;

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
        zgState: zgState,
      },
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      success: function (res) {
        wx.hideLoading();
        if (res.data.odlist && res.data.odlist.length > 0) {
          e.setData({
            ["odlist[" + page + "]"]: res.data.odlist,
            page: page,
            count: res.data.odlist[0].count,
            isdata: false,
          });
        } else {
          // If empty list
          if (page === 0) {
            e.setData({ odlist: [], isdata: true, count: 0 });
          } else {
            e.setData({
              ["odlist[" + page + "]"]: [],
              page: page - 1,
            });
          }
        }
      }
    });
  },

  onPullDownRefresh: function () {
    this.setData({ odlist: [] });
    this.loadmydongtai(this, 0);
    wx.stopPullDownRefresh();
  },

  onReachBottom() {
    if (!this.data.isdata) {
      var page = this.data.page + 1;
      this.loadmydongtai(this, page);
    }
  },

  // =========================
  // Batch Assignment Logic
  // =========================
  toggleBatchMode: function () {
    this.setData({
      isBatchMode: !this.data.isBatchMode,
      selectedItems: [],
      allSelected: false
    });
  },

  checkboxChange: function (e) {
    this.setData({ selectedItems: e.detail.value });
  },

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

  showAssignModal: function () {
    if (this.data.selectedItems.length === 0) {
      wx.showToast({ title: '请先选择记录', icon: 'none' });
      return;
    }
    if (this.data.people.length === 0) {
      this.getpeople(false);
    }
    this.setData({
      assignShow: true,
      assignDeadline: this.data.period.getNowDate()
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
      header: { 'content-type': 'application/x-www-form-urlencoded' },
      responseType: "arraybuffer",
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
        wx.showToast({ title: err, icon: "none", duration: 3000 })
      }
    })
  },
})