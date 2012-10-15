$(function() {
  function isDigit(ch) {
    return ch >= '0' && ch <= '9';
  }
  $.tablesorter.addParser({
    id : 'ratio',
    is : function(s) {
      return true;
    },
    format : function(s) {
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
  $("#problems").tablesorter({
    widgets: [ 'zebra' ],
    headers: {
      0: { sorter: 'numeric' }, // ID
      1: { sorter: 'text'  }, // Title
      2: { sorter: 'text'  }, // Author
      3: { sorter: 'ratio' }, // Ratio
      4: { sorter: false   }  // Action
    }
  });
});
