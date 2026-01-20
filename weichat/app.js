//app.js
App({
  globalData: {
    tid: 0,
    hyid: 0,
    openid: '',
    hyIdentity: 0,
    refresh: 0,
    hyname: '',
    hytel: '',
    height: 0,
    employ: false,
    navHeight: 0,
    navTop: 0,
    navObj: 0,
    navObjWid: 0,
  },
  //
  onLaunch: function (options) {
    this.getuserinfo();
    let menuButtonObject = wx.getMenuButtonBoundingClientRect();
    wx.getSystemInfo({
      success: res => {
        //导航高度
        let statusBarHeight = res.statusBarHeight,
          navTop = menuButtonObject.top,
          navObjWid = res.windowWidth - menuButtonObject.right + menuButtonObject.width, // 胶囊按钮与右侧的距离 = windowWidth - right+胶囊宽度
          navHeight = statusBarHeight + menuButtonObject.height + (menuButtonObject.top - statusBarHeight) * 2;
        this.globalData.navHeight = navHeight; //导航栏总体高度
        this.globalData.navTop = navTop; //胶囊距离顶部距离
        this.globalData.navObj = menuButtonObject.height; //胶囊高度
        this.globalData.navObjWid = navObjWid; //胶囊宽度(包括右边距离)
        this.globalData.height = navHeight
        // console.log(navHeight,navTop,menuButtonObject.height,navObjWid)
      },
      fail(err) {
        console.log(err);
      }
    })
  },

  //obj,name,photo
  getuserinfo: function () {
    var that = this;
    wx.login({
      success(res) {
        if (res.code) {
          //发起网络请求
          wx.request({
            url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
            data: {
              tag: "getopenid",
              code: res.code
            },
            header: {
              'content-type': 'application/x-www-form-urlencoded'
            },
            success: function (res) {
              console.log(res)
              //这里需要在服务器端判断用户是否已经注册，如果是的话，则判断其权限是否有，就不需要下一步操作了  
              that.globalData.hyid = res.data.id;
              that.globalData.tid = res.data.tId;
              that.globalData.openid = res.data.weid;
              that.globalData.hytel = res.data.hytel;
              that.globalData.hyIdentity = res.data.hyIdentity;
              that.globalData.hyname = res.data.hyname;
              that.globalData.employ = true;
              if (that.employCallback)
                that.employCallback(true);
            }
          })
        } else {
          console.log('登录失败！' + res.errMsg)
        }
      }
    })
  },
  getuseradds: function (obj, qqmapsdk) {
    var that = this
    wx.getLocation({
      type: 'wgs84',
      isHighAccuracy: true,
      success: function (res) {
        // console.log(res)
        var latitude = res.latitude;
        var longitude = res.longitude;
        qqmapsdk.reverseGeocoder({
          location: {
            latitude: latitude,
            longitude: longitude
          },
          coord_type: 1,
          get_poi: 1,
          poi_options: 'policy=2;radius=600;page_size=20;page_index=1',
          success: function (res) {
            //console.log(JSON.stringify(res));
            let district = res.result.formatted_addresses.recommend

            that.globalData.addrname = district;
            that.globalData.latitude = latitude;
            that.globalData.longitude = longitude;
            that.globalData.employadd = true;
            if (that.employaddCallback)
              that.employaddCallback(true);
          },
          fail: function (res) { console.log(res); },
          complete: function (res) { }
        });
      },
      fail: function () {
        wx.getSetting({
          success: function (res) {
            var statu = res.authSetting;
            if (!statu['scope.userLocation']) {
              wx.showModal({
                title: '是否授权当前位置',
                content: '需要获取您的位置，请确认授权，否则无法为您匹配附近门店',
                success: function (tip) {
                  if (tip.confirm) {
                    wx.openSetting({
                      success: function (data) {
                        if (data.authSetting["scope.userLocation"] === true) {
                          wx.showToast({
                            title: '您已开启定位，下拉刷新数据',
                            icon: 'none',
                            duration: 3000
                          })
                        } else {
                          wx.showToast({
                            title: '您未开启定位权限，将无法为您匹配附近门店',
                            icon: 'none',
                            duration: 3000
                          })
                        }
                      }, fail: function (res) {
                        console.log(res);
                      }
                    })
                  } else {
                    wx.showToast({
                      title: '您未开启定位权限，将无法为您匹配附近门店',
                      icon: 'none',
                      duration: 3000
                    })
                    that.globalData.addrname = "没有开启定位，点我授权";
                    that.globalData.latitude = latitude;
                    that.globalData.longitude = longitude;
                    that.globalData.employadd = true;
                    if (that.employaddCallback)
                      that.employaddCallback(true);
                  }
                }, fail: function (res) {
                  console.log(res);
                }
              })
            }
          },
          fail: function (res) {
            console.log(res);
          }
        })
      }

    })
  },
  setshipin: function (e) {
    wx.setEnable1v1Chat({
      enable: true,
    })
  },
  getshipin: function (e) {
    wx.getSetting({
      success(res) {
        if (!res.authSetting['scope.camera']) { //获取摄像头权限
          wx.authorize({
            scope: 'scope.camera',
            success() {
              console.log('相机授权成功')
            },
            fail() {
              console.log('相机授权fail')
              wx.showModal({
                title: '提示',
                content: '尚未进行授权，部分功能将无法使用',
                cancelText: '取消授权',
                confirmText: '去授权',
                success(res) {
                  console.log(res)
                  if (res.confirm) {
                    console.log('用户点击确定')
                    wx.openSetting({ //这里的方法是调到一个添加权限的页面，这里可以测试在拒绝授权的情况下设置中是否存在相机选项
                      success: (res) => {
                        if (!res.authSetting['scope.camera']) {
                          wx.authorize({
                            scope: 'scope.camera',
                            success() {
                              console.log('授权成功')
                            },
                            fail() {
                              console.log('用户点击取消')
                            }
                          })
                        }
                      },
                      fail: function () {
                        console.log("相机授权设置失败");
                      }
                    })
                  } else if (res.cancel) {
                    console.log('用户点击取消')
                  }
                }
              })
            }
          })
        }
        if (!res.authSetting['scope.record']) { //获取录音权限
          wx.authorize({
            scope: 'scope.record',
            success() {
              console.log('授权成功')
            },
            fail() {
              wx.showModal({
                title: '提示',
                content: '尚未进行授权，部分功能将无法使用',
                showCancel: false,
                success(res) {
                  if (res.confirm) {
                    wx.openSetting({
                      success: (res) => {
                        if (!res.authSetting['scope.record']) {
                          wx.authorize({
                            scope: 'scope.record',
                            success() {
                              console.log('授权成功')
                            },
                            fail() {
                              console.log('用户点击取消')
                            }
                          })
                        }
                      },
                      fail: function () {
                        console.log("授权设置录音失败");
                      }
                    })
                  } else if (res.cancel) {
                    console.log('用户点击取消')
                  }
                }
              })
            }
          })
        }
      },
      fail(res) {
        console.log("失败" + JSON.stringify(res));
      }
    })
  }
})