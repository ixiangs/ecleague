Element.implement({

  isInput: function() {
    if (this.get('tag') != 'input') {
      return false;
    }

		var l = arguments.length;
    if (l == 0) {
      return true;
    }

    var t = this.get('type');
    for (var i = 0; i < l; i++) {
      if (t == arguments[i]) {
        return true;
      }
    }
    return false;
  },

  isTag: function() {
    if (arguments.length > 0) {
      var t = this.get('tag');
      var l = arguments.length;
      for (var i = 0; i < l; i++) {
        if (t == arguments[i]) {
          return true;
        }
      }
    }
    return false;
  },

  getValue: function() {
    var v = this.get('value');
    if (v) {
      return v.trim();
    }
    return "";
  }
});

Array.implement({
  find: function(func) {
    var len = this.length;
    for (var i = 0; i < len; i++) {
      if (func(this[i], i, this)) {
        return this[i];
      }
    }
    return null;
  }
});
var Toy = {};