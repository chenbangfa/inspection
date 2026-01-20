// pages/dtlist/dtlist.js
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    navbarData: {
      showCapsule: 1, //是否显示左上角图标   1表示显示    0表示不显示
      title: '绑定设备', //导航栏 中间的标题
    },
    height: app.globalData.height, 
    isdata:true,
    devices: [],
    id:0,
    dropNo:'',
    name:''
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad(options)
  {
    this.setData({
      id:options.id,      
      dropNo:options.dropNo,
      name:options.name
    })
    this.search();
  },
  search:function(e)
  {
    wx.showLoading({title: '搜索中...',mask: true });
      wx.openBluetoothAdapter({
        success: (res) => {
          console.log('第一步、蓝牙初始化成功', res)
          // 开始搜索附近蓝牙
          this.startBluetoothDevicesDiscovery()
        },
        fail: (res) => {
          console.log("第一步、蓝牙初始化失败", res);
          wx.showToast({ title: '蓝牙初始化失败', icon: 'none' })
        }
      })
  },
    // 第二步 开始搜索附近的蓝牙设备
    startBluetoothDevicesDiscovery() {
      wx.startBluetoothDevicesDiscovery({
        allowDuplicatesKey: false,
        success: (res) => {
          console.log('开始搜索附近的蓝牙设备', res)
          this.onBluetoothDeviceFound()
        },
      })
    },
     // 第三步 监听发现附近的蓝牙设备
     onBluetoothDeviceFound() 
     {
       var that=this;
      wx.onBluetoothDeviceFound((res) => {
        res.devices.forEach(device => {
          if (!device.name && !device.localName) { return }
          console.log("发现的蓝牙设备", device.localName)
          var bName=device.localName;
          var bId=device.deviceId;
          if(bName!=""&&bName!=null)
          {
            if(bName.indexOf("K")==0)
            {
              var devices=this.data.devices
              if(devices.indexOf(bName)==-1)
              {
                wx.request({
                  url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
                  data: {
                    tag: "getbluestate",
                    bName:bName,
                  },
                  header: {
                    'content-type': 'application/x-www-form-urlencoded'
                  },
                  success: function (res)
                  {
                    var bstate=res.data.bstate;    
                    wx.hideLoading();   
                    var obj = { bName: bName,bId:bId,bstate: bstate};
                    devices.push(obj)
                    that.setData({
                      devices: devices,    
                      isdata:false
                   })
                   console.log(that.data.devices)
                  }
                })
              }

           }
          }
        })
      })
    },
    bindblue:function(e)
    {
      var bName=e.currentTarget.dataset.droname;
      var state=e.currentTarget.dataset.state;
     var that=this;
      var id=that.data.id;
      var tit=state=="0"?"确定绑定该设备吗？":"该设备已被绑定，确定更换吗？";
      
      console.log(bName+"/"+id)
      wx.showModal({
        title: '提示',
        content: tit,
        success (res)
        {
          if (res.confirm) 
          {
            wx.request({
              url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
              data: {
                tag: "setbluebind",
                id:id,
                bName:bName,
              },
              header: {
                'content-type': 'application/x-www-form-urlencoded'
              },
              success: function (res)
              {
                console.log(res.data)
                wx.showToast({
                  title: '绑定成功！',
                  duration:2000, 
                  icon: 'success',
                  success: function ()
                  {
                    app.globalData.refresh=1;
                    setTimeout(function ()
                    {
                     wx.navigateBack()
                    },2000)
                  }
                })  
              }
            })
          } else if (res.cancel)
          {
              console.log('用户点击取消')
          }
        }
      })

      console.log(bName+"/"+id)
    },
    onBlueChange:function(e)
    {
      var deviceId=e.currentTarget.dataset.tid;
      console.log(deviceId)
      this.createBLEConnection(deviceId)
    },
    // 第四步、 建立连接
    createBLEConnection(deviceId) {
      wx.createBLEConnection({
        deviceId: deviceId,
        success: (res) => {
          wx.showToast({ title: '蓝牙连接成功', icon: 'none' })
          this.setData({
            deviceId: deviceId,
            connection:true,
            deviceName: res.name,
          })
          //this.getBLEDeviceServices(deviceId)
        },
        fail(res) { wx.showToast({ title: '蓝牙连接失败', icon: 'none' }) }
      })
    },

    
  onPullDownRefresh: function ()
  {    
    wx.closeBluetoothAdapter({
      success (res) {
        console.log(res)
      }
    })

    this.setData({
      devices: [],
    })
    this.search();
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
  onUnload()
  {
    wx.closeBluetoothAdapter({
      success (res) {
        console.log(res)
      }
    })
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