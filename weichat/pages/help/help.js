var app = getApp()
Page({
  /**
   * 页面的初始数据
   */
  data: {
    navbarData: {
      showCapsule:1, //是否显示左上角图标   1表示显示    0表示不显示
      title: "帮助文档", //导航栏 中间的标题
    },
    // 此页面 页面内容距最顶部的距离
    height: app.globalData.height, 
    detail:'detail4'
  },

  jumpClick(e)
  {
    var id=e.currentTarget.dataset.detail;
    let query = wx.createSelectorQuery();
    let that = this;
    query.select('#'+id).boundingClientRect(function (rect) {

      console.log(rect.height);
      that.setData({
        heights: rect.height 
      })
    }).exec();
    setTimeout(function(){
      if (wx.pageScrollTo) {
        let height = that.data.heights
        wx.pageScrollTo({
            scrollTop: height
        })
      } else {
        wx.showModal({
            title: '提示',
            content: '当前微信版本过低，暂无法使用该功能，请升级后重试。'
        })
      }
    },100) 
  },
onLoad: function (options)
{
},
  
onShareAppMessage: function ()
{
  wx.showShareMenu({
    withShareTicket: true,
    menus: ['shareAppMessage', 'shareTimeline']
  })
},  
onShareTimeline: function ()
{
  return{
    title: '安全生产隐患巡检平台',
    query:
    {
      key: 'value' 
    },
    imageUrl: '' //默认logo
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
  onShow: function () 
  {    
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

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function ()
  {
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

})