/**
 * 这里是公共js函数调用库(2018/07/23)
 * 
 */

// 给日期类对象添加日期差方法，返回日期与diff参数日期的时间差，单位为天
// 对Date的扩展，将 Date 转化为指定格式的String
// 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符，
// 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字)
// 例子：
// (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423
// (new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18
// let time1 = new Date().Format("yyyy-MM-dd");
// let time2 = new Date().Format("yyyy-MM-dd HH:mm:ss");
 
Date.prototype.Format = function (fmt) { //author: meizz
    let o = {
        "M+": this.getMonth() + 1, //月份
        "d+": this.getDate(), //日
        "h+": this.getHours(), //小时
        "m+": this.getMinutes(), //分
        "s+": this.getSeconds(), //秒
        "q+": Math.floor((this.getMonth() + 3) / 3), //季度
        "S": this.getMilliseconds() //毫秒
    };
    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
    for (let k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
    return fmt;
};

// 201810141449
// 判断浏览器，IE或Edge不让使用
function checkBrowser () {
	var userAgent = navigator.userAgent; //取得浏览器的userAgent字符串
	// console.log(userAgent);
	var isFirefox = userAgent.indexOf("Firefox") > -1;
	var isChrome = userAgent.indexOf("Chrome") > -1;
	var isSafari = userAgent.indexOf("Safari") > -1;
	var isOpera = userAgent.indexOf("Opera") > -1 || userAgent.indexOf("OPR") > -1;
	if (isOpera) {
		// alert("Opera");
	}; //判断是否Firefox浏览器
	if (isFirefox) {
		// alert("FF");
	} //判断是否Chrome浏览器
	else if (isChrome){
		// alert("Chrome");
	} //判断是否Safari浏览器
	else if (isSafari) {
		// alert("Safari");
	}
	// if (userAgent.indexOf("compatible") > -1 && userAgent.indexOf("MSIE") > -1 && userAgent.indexOf(".NET") > -1 && !isOpera) {
	//判断是否IE浏览器
	else {
		// alert("IE");
		alert('请使用Chrome或Firefox浏览器！');
		document.execCommand("stop");
	};	
}
