// pages/dtlist/dtlist.js
var app = getApp();
Page({

  /**
   * 页面的初始数据
   */
  data: {
    navbarData: {
      showCapsule: 1, //是否显示左上角图标   1表示显示    0表示不显示
      title: '蓝牙巡检', //导航栏 中间的标题
    },
    height: app.globalData.height, 
    odlist: [],
    isdata:true,
    devices: [],
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad(options)
  {
    wx.showLoading({title: '搜索中...',mask: true });
    this.search();
  },
  search:function(e)
  {
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
      var tid=app.globalData.tid;
      var hyid=app.globalData.hyid;
       var that=this;
      wx.onBluetoothDeviceFound((res) => {
        res.devices.forEach(device => {
          if (!device.name && !device.localName) { return }
         // console.log("发现的蓝牙设备", device)
          var bName=device.localName;
          var deviceId=device.deviceId;

          if(bName!=""&&bName!=null)
          {
            if(bName.indexOf("K")==0)
            {
              var devices=this.data.devices
              if(devices.indexOf(bName)==-1)
              {
                devices.push(bName);
                this.setData({
                  devices: devices,      
                })

                wx.request({
                  url: 'https://xj.tajian.cc/servers/data/ajaxchat.php',
                  data: {
                    tag: "getblueone",
                    tid:tid,
                    hyid:hyid,
                    bName:bName,
                  },
                  header: {
                    'content-type': 'application/x-www-form-urlencoded'
                  },
                  success: function (res)
                  {
                    var odlist=that.data.odlist;   
                    if(res.data.odlist.length>0)
                    {  
                      var newlist=res.data.odlist;
                      newlist[0].deviceId=deviceId;
                      wx.hideLoading();   
                      odlist.push(newlist)
                      that.setData({
                        odlist:odlist,
                        isdata:false
                      })
                  }

                  console.log(that.data.odlist)
                  
                  }
                })
              }

           }
          }
        })
      })
    },

    onBlueChange:function(e)
    {
      var deviceId=e.currentTarget.dataset.devid;
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
         this.getBLEDeviceServices(deviceId)
        },
        fail(res) { wx.showToast({ title: '蓝牙连接失败', icon: 'none' }) }
      })
    },

  // 第五步、获取蓝牙设备所有服务(service)。
  getBLEDeviceServices(deviceId) {
    wx.getBLEDeviceServices({
      deviceId: deviceId,
      success: (res) => {
        console.log('蓝牙设备所有服务', res)
        for (var i = 0; i < res.services.length; i++) {
          if (res.services[i].isPrimary) {
            this.getBLEDeviceCharacteristics(deviceId, res.services[i].uuid);
            return;
          }
        }
      },
      fail(res) { console.log('蓝牙设备所有服务失败', res) }
    })
  },
  // 第六步、 获取蓝牙设备某个服务中所有特征值(characteristic)
  getBLEDeviceCharacteristics(deviceId, serviceId) {
    console.log("广播"+serviceId);
    wx.getBLEDeviceCharacteristics({
      deviceId, serviceId,
      success: (res) => {
        for (let i = 0; i < res.characteristics.length; i++)
        {
         // console.log(JSON.stringify(res.characteristics));
          let item = res.characteristics[i]
          this.onBLEValue(deviceId,serviceId,item.uuid);

        }
      }
    })
  },

  // 第七步、获取蓝牙的返回信息
  onBLEValue(deviceId,serviceId,characteristicId)
  {
        
    wx.onBLECharacteristicValueChange(function(res) 
    {
      console.log(res.value)
    })
    
    wx.readBLECharacteristicValue({
      deviceId: deviceId,
      serviceId:serviceId,
      characteristicId:characteristicId,
      success(res) {  }
      ,fail(res){
        console.log(res)
      }
    })

  },
    //第八步、 向蓝牙设备发送数据
    writeBLECharacteristicValue() {
      // 向蓝牙设备发送一个0x00的16进制数据
        let buffer = new ArrayBuffer(1)
        let dataView = new DataView(buffer)
        dataView.setUint8(0, Math.random() * 255 | 0)
        wx.writeBLECharacteristicValue({
          deviceId: this._deviceId,
          serviceId: this._deviceId,
          characteristicId: this._characteristicId,
          value: buffer,
        })
      },
    

    
  onPullDownRefresh: function ()
  {    
    wx.closeBluetoothAdapter({
      success (res) {
        console.log(res)
      }
    })

    wx.showLoading({title: '搜索中...',mask: true });
    this.setData({
      odlist: [],
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
  onShow()
  {
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