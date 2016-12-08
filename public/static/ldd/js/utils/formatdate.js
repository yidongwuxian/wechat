/*
** @param 时间戳转换日期格式
** @param formatTime.format(1425830400,'yyyy-MM-dd h:m:s')
*/
var formatTime = new Date();
Date.prototype.format = function(obj,format) {
       formatTime.setTime(obj * 1000);
       var date = {
              "M+": (this.getMonth()+1 < 10 ? '0'+(this.getMonth()+1) : this.getMonth()+1),
              "d+": (this.getDate() < 10 ? '0'+(this.getDate()) : this.getDate()),
              "h+": (this.getHours() < 10 ? '0'+(this.getHours()) : this.getHours()),
              "m+": (this.getMinutes() < 10 ? '0'+(this.getMinutes()) : this.getMonth()),
              "s+": (this.getSeconds() < 10 ? '0'+(this.getSeconds()) : this.getSeconds()),
              "q+": Math.floor((this.getMonth() + 3) / 3),
              "S+": this.getMilliseconds()
       };
       if (/(y+)/i.test(format)) {
              format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
       }
       for (var k in date) {
              if (new RegExp("(" + k + ")").test(format)) {
                     format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? date[k] : ("00" + date[k]).substr(("" + date[k]).length));
              }
       }
       return format;
}
