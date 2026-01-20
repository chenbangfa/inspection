
const GetPeriod = require("../../utils/getperiod.js");
var app = getApp();
Page({

    /**
     * 页面的初始数据
     */
    data: {
        navbarData: {
            showCapsule: 1,
            title: '我的任务',
        },
        height: app.globalData.height,
        odlist: [],
        page: 0,
        isdata: false,
        count: 0,
        period: '',

        // Core Filter Data
        sdate: '',
        edate: '',
        zgState: '',    // '0', '1', '2'
        zgStateName: '',

        // Drawer & Temp Filter Data
        showDrawer: false,
        dateLimit: '',
        tempSdate: '',
        tempEdate: '',
        tempZgIndex: 0, // 0:All, 1:Pending, 2:In Progress, 3:Completed
    },

    onLoad(options) {
        this.data.period = new GetPeriod();
        var that = this;

        // Init loads
        if (app.globalData.employ && app.globalData.employ != '') {
            that.loadmytasks(that, 0);
        } else {
            app.employCallback = employ => {
                if (employ != '') {
                    that.loadmytasks(that, 0);
                }
            }
        }
    },

    // Navigate to Detail
    itemclick: function (e) {
        wx.navigateTo({
            url: '../inspectinfo/inspectinfo?id=' + e.currentTarget.dataset.id,
        })
    },

    // =========================
    // Drawer & Filter Logic
    // =========================

    openFilterDrawer: function () {
        let zgIdx = 0;
        if (this.data.zgState === '0') zgIdx = 1;
        else if (this.data.zgState === '1') zgIdx = 2;
        else if (this.data.zgState === '2') zgIdx = 3;

        this.setData({
            showDrawer: true,
            tempSdate: this.data.sdate ? this.data.sdate.split(' ')[0] : '',
            tempEdate: this.data.edate ? this.data.edate.split(' ')[0] : '',
            tempZgIndex: zgIdx,
            dateLimit: ''
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

    // Reset & Apply
    resetFilters: function () {
        this.setData({
            tempSdate: '',
            tempEdate: '',
            dateLimit: '',
            tempZgIndex: 0,
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

        this.setData({
            sdate: sdate,
            edate: edate,
            zgState: zgState,
            zgStateName: zgName,
            showDrawer: false,
            odlist: [] // Reset list
        });

        // 2. Refresh
        this.loadmytasks(this, 0);
    },

    // =========================
    // Chip Removal Logic
    // =========================
    closedate: function () {
        this.setData({ sdate: '', edate: '', odlist: [] });
        this.loadmytasks(this, 0);
    },
    closeZgState: function () {
        this.setData({ zgState: '', zgStateName: '', odlist: [] });
        this.loadmytasks(this, 0);
    },


    // =========================
    // Data Loading
    // =========================

    loadmytasks: function (e, page) {
        wx.showLoading({ title: '数据加载中...', mask: true });

        // Use actual filter state
        var time = this.data.sdate;
        var times = this.data.edate;
        var zgState = this.data.zgState;
        var openid = app.globalData.openid; // Or however we identify "my"
        // Assuming backend will use session or passed ID to identify 'my' tasks
        // Since we need to pass 'mytasklist' tag.

        wx.request({
            url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
            data: {
                tag: "mytasklist", // NEW TAG
                p: page + 1,
                time: time,
                times: times,
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
        this.loadmytasks(this, 0);
        wx.stopPullDownRefresh();
    },

    onReachBottom() {
        if (!this.data.isdata) {
            var page = this.data.page + 1;
            this.loadmytasks(this, page);
        }
    },
})
