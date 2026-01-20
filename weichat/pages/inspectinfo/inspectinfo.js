
const GetPeriod = require("../../utils/getperiod.js");
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    navbarData: {
      showCapsule: 1, //是否显示左上角图标   1表示显示    0表示不显示
      title: '巡检详情', //导航栏 中间的标题
    },
    // 此页面 页面内容距最顶部的距离
    height: app.globalData.height,
    dtinfo: [],
    id: 0,
    showcode: false,
    tCode: '',
    period: '',

    // Assignment Modal Data
    assignShow: false,
    assignUserIndex: -1,
    assignUsers: [], // List of users to assign to (from teamlist/hylist)
    assignRequest: '',
    assignDeadline: '',
    people: [],
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    this.data.period = new GetPeriod();
    this.setData({
      id: options.id
    })
    this.loaddongtai(this);
  },

  showcode: function (e) {
    var imgurl = e.currentTarget.dataset.imgurl;
    this.setData({
      tCode: imgurl,
      showcode: true
    })
  },
  hidecode: function () {
    this.setData({
      showcode: false,
    });
  },

  // Assignment Logic
  showAssignModal: function () {
    if (this.data.people.length === 0) {
      this.getpeople(false);
    }

    // Pre-fill with existing data if available
    var currentInfo = this.data.dtinfo[0];

    this.setData({
      assignShow: true,
      assignRequest: currentInfo.zgAsk || '',
      assignDeadline: currentInfo.zgTime || this.data.period.getNowDate()
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
          people: res.data.hylist
        });
      }
    })
  },

  submitAssignment: function () {
    var that = this;
    var zgUserId = '';
    var zgName = '';

    // If selecting a new user
    if (this.data.assignUserIndex > -1 && this.data.people.length > 0) {
      zgUserId = this.data.people[this.data.assignUserIndex].id;
      zgName = this.data.people[this.data.assignUserIndex].hyName;
    } else {
      // Use existing if not changed? Or force select?
      // For simplicity, if not selected, check if already exists in dtinfo, else require selection
      if (this.data.dtinfo[0].zgUserId) {
        zgUserId = this.data.dtinfo[0].zgUserId;
        zgName = this.data.dtinfo[0].zgName;
      } else {
        wx.showToast({ title: '请选择整改人', icon: 'none' });
        return;
      }
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
        ids: this.data.id,
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
            assignShow: false
          });
          that.loaddongtai(that);
        } else {
          wx.showToast({ title: res.data.msg || '分配失败', icon: 'none' });
        }
      }
    });
  },

  loaddongtai: function (e) {
    wx.showLoading({ title: '加载中', mask: true });
    wx.request({
      url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
      data: {
        tag: "inspectinfo",
        id: e.data.id
      },
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function (res) {
        console.log(res)
        wx.hideLoading();
        e.setData({
          dtinfo: res.data.dtinfo
        });
      }
    })
  },
  /**
  * 用户点击右上角分享
  */
  onShareAppMessage: function () {
  },
  onShareTimeline: function () {
  },
})