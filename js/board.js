$(function() {
  function isDigit(ch) {
    return ch >= '0' && ch <= '9';
  }
  var n = $("#userscores").children(":first").children(":first").find("th").size();
  $.tablesorter.addParser({
    id : 'firstInteger',
    is : function(s) {
      return true;
    },
    format : function(s) {
      if (/^\(-\d+\)/.test(s)) {
        // tried but failed
        return 1e4 - this.getInteger(s);
      }
      if (/^\d+\(\d+Y\)/.test(s)) {
        // accepted with penalty
        return 1e8 - this.getInteger(s);
      }
      return this.getInteger(s);
    },
    getInteger: function(s) {
      var n = 0, i = 0;
      while (i < s.length && !isDigit(s[i])) {
        i = i + 1;
      }
      while (i < s.length && isDigit(s[i])) {
        n = n * 10 + (s[i] - '0');
        i = i + 1;
      }
      return n;
    },
    type : 'numeric'
  });
  $.tablesorter.addWidget({
    id: "indexFirstColumn",
    // format is called when the on init and when a sorting has finished
    format: function(table) {
      var i = 0;
      // loop all tr elements and set the value for the first column
      for (i = 1; i <= table.tBodies[0].rows.length; i++) {
        $("tbody tr:eq(" + (i-1) + ") td:first", table).html(i);
      }
    }
  });
  $("#userscores").tablesorter({
    widgets : [ 'zebra', 'indexFirstColumn' ],
    sorter : 'firstInteger',
    sortList : [ [ n - 1, 1 ], [ n - 3, 0 ], [ n - 2, 0 ] ],
    headers : { 0 : { sorter: false } }
  });
});
