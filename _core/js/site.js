function AnimateScroll(id) {
	var position = $('#'+id).position();
	$('body,html').animate({
		scrollTop: position.top
	}, 800);
}
function s4() {
  return Math.floor((1 + Math.random()) * 0x10000)
             .toString(16)
             .substring(1);
};
function guid() {
  return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
         s4() + '-' + s4() + s4() + s4();
}
function OpenInNewTab(url) {
	var win=window.open(url, '_blank');
	win.focus();
}
$(document).ready(function() {
	$('[data-toggle=offcanvas]').click(function() {
		$('.row-offcanvas').toggleClass('active');
	});
	$('.date').datepicker();
	$(".numeric").numeric();
	$(".integer").numeric(false, function() { alert("Integers only"); this.value = ""; this.focus(); });
	$(".positive").numeric({ negative: false }, function() { alert("No negative values"); this.value = ""; this.focus(); });
	$(".positive-integer").numeric({ decimal: false, negative: false }, function() { alert("Positive integers only"); this.value = ""; this.focus(); });
});
var entityMap = {
	"&": "&amp;",
	"<": "&lt;",
	">": "&gt;",
	'"': '&quot;',
	"'": '&#39;',
	"/": '&#x2F;'
};
function escapeHtml(string) {
	return String(string).replace(/[&<>"'\/]/g, function (s) {
	  return entityMap[s];
	});
}
/* cookie functions */
function setCookie(c_name,value,exdays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}
function clearCookie(c_name)
{
	setCookie(c_name, '', -1);
}
function getCookie(c_name)
{
	var c_value = document.cookie;
	var c_start = c_value.indexOf(" " + c_name + "=");
	if (c_start == -1)
	{
		c_start = c_value.indexOf(c_name + "=");
	}
	if (c_start == -1)
	{
		c_value = null;
	}
	else
	{
		c_start = c_value.indexOf("=", c_start) + 1;
		var c_end = c_value.indexOf(";", c_start);
		if (c_end == -1)
		{
			c_end = c_value.length;
		}
		c_value = unescape(c_value.substring(c_start,c_end));
	}
	return c_value;
}
function checkCookie(c_name)
{
	var c_value=getCookie(c_name);
	if (c_value==null || c_value=="")
	{
		return false;
	}
	else
	{
		return true;
	}
}
function smartFilesizeDisplay(sizeInBytes) {
	var result = sizeInBytes;
	var converted = 0;
	if (sizeInBytes > 1073741824) { //change to gigabytes
		converted = sizeInBytes / (1024 * 1024 * 1024);
		result = (Math.round(converted*10)/10) + " GB";
	}
	else if (sizeInBytes > 1048576) { //change to megabytes
		converted = sizeInBytes / (1024 * 1024);
		result = (Math.round(converted*10)/10) + " MB";
	}
	else { //change to kilobytes
		converted = sizeInBytes / 1024;
		result = (Math.round(converted*10)/10) + " KB";
	}
	return result;
}
function addZeros(value) {
	if (value < 10) {
		return "0" + value;
	}
	else {
		return value;
	}
}
function return12Hour(value) {
	if (value > 12) {
		return value - 12;
	}
	else {
		return value;
	}
}
function returnAmPm(value) {
	if (value > 12) {
		return "p.m.";
	}
	else {
		return "a.m.";
	}
}















