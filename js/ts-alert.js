$(function(){
  function get_cookie(c_name) {
    var i, x, y, ARRcookies = document.cookie.split(";");
    for (i = 0; i < ARRcookies.length; i++) {
      x = ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
      y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
      x = x.replace(/^\s+|\s+$/g, "");
      if (x == c_name) {
        return unescape(y);
      }
    }
    return null;
  }
  
  function set_cookie(c_name, value, exdays) {
    var c_value, exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
    document.cookie = c_name + "=" + c_value;
  }
  
  function ts_cookie_name() {
    return "contest" + window.contest_id + "ts";
  }
  
  function cookie_ts() {
    return get_cookie(ts_cookie_name());
  }
  
  function update_cookie_ts(new_value) {
    set_cookie(ts_cookie_name(), new_value, 1);
  }
  
  function page_ts() {
    var latest = "0000-00-00 00:00:00";
    $('.timestamp').each(function(){
      var ts = $(this).text();
      if (ts > latest) {
        latest = ts;
      }
    });
    return latest;
  }
  
  console.log("cookie_ts=" + cookie_ts());
  console.log("page_ts=" + page_ts());
  
  if (cookie_ts() != page_ts()) {
    update_cookie_ts(page_ts());
    window.alert("有新消息！");
    if (window.do_refresh) {
      window.do_refresh();
    }
  }
});
