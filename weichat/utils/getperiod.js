class GetPeriod {
  constructor() {
      this.now = new Date();
      this.nowYear = this.now.getYear(); //当前年 
      this.nowMonth = this.now.getMonth(); //当前月 
      this.nowDay = this.now.getDate(); //当前日 
      this.nowDayOfWeek = this.now.getDay(); //今天是本周的第几天 
      this.nowYear += (this.nowYear < 2000) ? 1900 : 0;

  }
  //格式化数字
  formatNumber(n) {
      n = n.toString()
      return n[1] ? n : '0' + n
  }
  //格式化日期
  formatDate(date) {
      let myyear = date.getFullYear();
      let mymonth = date.getMonth() + 1;
      let myweekday = date.getDate();
      return [myyear, mymonth, myweekday].map(this.formatNumber).join('/');
  }
  //获取某月的天数
  getMonthDays(myMonth) {
      let monthStartDate = new Date(this.nowYear, myMonth, 1);
      let monthEndDate = new Date(this.nowYear, myMonth + 1, 1);
      let days = (monthEndDate - monthStartDate) / (1000 * 60 * 60 * 24);
      return days;
  }
  //获取本季度的开始月份
  getQuarterStartMonth() {
      let startMonth = 0;
      if (this.nowMonth < 3) {
          startMonth = 0;
      }
      if (2 < this.nowMonth && this.nowMonth < 6) {
          startMonth = 3;
      }
      if (5 < this.nowMonth && this.nowMonth < 9) {
          startMonth = 6;
      }
      if (this.nowMonth > 8) {
          startMonth = 9;
      }
      return startMonth;
  }

  //获取今天的日期
  getNowDate() {
      return this.formatDate(new Date(this.nowYear, this.nowMonth, this.nowDay));
  }
    //获取昨天的日期
    getYesterday() {
      return this.formatDate(new Date(this.nowYear, this.nowMonth, this.nowDay-1));
  }
//获取上周开始 7开开始 1 结束
getTimeLastWeek(n)
{
  var now = new Date();
  var year = now.getFullYear();
  var month = now.getMonth() + 1;
  var day = now.getDay(); //返回星期几的某一天;
  n = day == 0 ? n + 6 : n + (day - 1)
  now.setDate(now.getDate() - n);
  var date = now.getDate();
  var s = year + "-" + (month < 10 ? ('0' + month) : month) + "-" + (date < 10 ? ('0' + date) : date);
  return s;
}
  //获取本周的开始日期
  getWeekStartDate() 
  {
    if(this.nowDayOfWeek==0)    
      return this.formatDate(new Date(this.nowYear, this.nowMonth, this.nowDay - this.nowDayOfWeek + 1-7));
    else
      return this.formatDate(new Date(this.nowYear, this.nowMonth, this.nowDay - this.nowDayOfWeek + 1));
  }
  //获取本周的结束日期
  getWeekEndDate()
  {
    if(this.nowDayOfWeek==0)   
     return this.formatDate(new Date(this.nowYear, this.nowMonth, this.nowDay + (6 - this.nowDayOfWeek + 1-7)));
    else
      return this.formatDate(new Date(this.nowYear, this.nowMonth, this.nowDay + (6 - this.nowDayOfWeek + 1)));
  }
  //获取本月的开始日期
  getMonthStartDate() {
      return this.formatDate(new Date(this.nowYear, this.nowMonth, 1));
  }
  //获取本月的结束日期
  getMonthEndDate() {
      return this.formatDate(new Date(this.nowYear, this.nowMonth, this.getMonthDays(this.nowMonth)));
  }
  
  //获取上月的开始日期
  getMonthStartDate1() {
    return this.formatDate(new Date(this.nowYear, this.nowMonth-1, 1));
}
  //获取上月的结束日期
  getMonthEndDate1() {
      return this.formatDate(new Date(this.nowYear, this.nowMonth-1, this.getMonthDays(this.nowMonth-1)));
  }

  //获取本季度的开始日期
  getQuarterStartDate() {
      return this.formatDate(new Date(this.nowYear, this.getQuarterStartMonth(), 1));
  }
  //获取本季度的结束日期 
  getQuarterEndDate() {
      return this.formatDate(new Date(this.nowYear, this.getQuarterStartMonth() + 2, this.getMonthDays(this.getQuarterStartMonth() + 2)));
  }
  
  //获取上季度的开始日期
  getQuarterStartDate1() {
    return this.formatDate(new Date(this.nowYear, this.getQuarterStartMonth()-3, 1));
}
//获取上季度的结束日期 
getQuarterEndDate1() {
    return this.formatDate(new Date(this.nowYear, this.getQuarterStartMonth() - 1, this.getMonthDays(this.getQuarterStartMonth() - 1)));
}

  //获取本年的开始日期
  getYearStartDate() {
      return this.formatDate(new Date(this.nowYear, 0, 1));
  }
  //获取本年的结束日期
  getYearEndDate() {
      return this.formatDate(new Date(this.nowYear, 11, 31));
  }
  
  //获取本年的开始日期
  getYearStartDate1() {
    return this.formatDate(new Date(this.nowYear-1, 0, 1));
}
//获取本年的结束日期
getYearEndDate1() {
    return this.formatDate(new Date(this.nowYear-1, 11, 31));
}
  //获取时段方法
  getPeriod(obj) {
      let opts = obj || {}, time = null;
      opts = {
          periodType: opts.periodType || 'now',
          spaceType: opts.spaceType || '~'
      }
      function formatNumber(param1, param2) {
          return [param1, param2].join(opts.spaceType);
      }
      if (opts.periodType == 'week') {
          time = formatNumber(this.getWeekStartDate(), this.getWeekEndDate());
      } else if (opts.periodType == 'month') {
          time = formatNumber(this.getMonthStartDate(), this.getMonthEndDate());
      } else if (opts.periodType == 'quarter') {
          time = formatNumber(this.getQuarterStartDate(), this.getQuarterEndDate());
      } else if (opts.periodType == 'year') {
          time = formatNumber(this.getYearStartDate(), this.getYearEndDate());
      } else {
          time = formatNumber(this.getNowDate(), this.getNowDate());
      }
      return time;
  }
}
module.exports = GetPeriod;
