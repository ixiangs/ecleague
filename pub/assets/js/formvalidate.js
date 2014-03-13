Toys.FormValidator = new Class({
  Implements: [Options, Events],
  form: null,
  options: {
    validateOnChange: true,
    validateOnKeyup: true,
    validateOnSubmit: true,
    inputContainer: 'div',
    errorClass: 'alert',
    failureClass: 'has-error',
    successClass: 'has-success'
  },

  initialize: function(frm, options) {
    this.setOptions(options);
    this.form = typeOf(frm) == "string" ? $(frm) : frm;
    
    this.inputs = {};
    Toys.FormValidator.selectors.each(function(selector){
    	selector(this).each(function(item) {
		    var n = item.get('name');
		    if (n) {
		      if (!Object.keys(this.inputs).contains(n)) {
		        this.inputs[n] = [];
		      }
		      this.inputs[n].push(item);
		    }
		  }.bind(this));
    }.bind(this));
    
    this.fields = {};
    Toys.FormValidator.matchers.each(function(matcher){
    	Object.merge(this.fields, matcher(this));
    }.bind(this));    

    if (this.options.validateOnChange) {
      Object.each(this.inputs, function(input) {
        input.each(function(item) {
          this.bindEvent(item);
        }.bind(this));
      }.bind(this));
    }

    if (this.options.validateOnSubmit) {
      this.form.addEvent('submit', function(event) {
        return this.validate();
      }.bind(this));
    }
  },

  bindEvent: function(input) {
    switch (input.get("tag")) {
      case "input":
        switch (input.get("type")) {
          case "text":
          case "password":
          case "email":
          case "number":
            input.addEvent("blur", function() {
              this.validateInput(input.get("name"));
            }.bind(this));
            if (this.options.validateOnKeyup) {
              input.addEvent("keyup", function() {
                this.validateInput(input.get("name"));
              }.bind(this));
            }
            break;
          case "radio":
          case "checkbox":
            input.addEvent("click", function() {
              this.validateInput(input.get("name"));
            }.bind(this));
            break;
        }
        break;
      case "select":
        input.addEvent("change", function() {
          this.validateInput(input.get("name"));
        }.bind(this));
        break;
      case "textarea":
        input.addEvent("blur", function() {
          this.validateInput(input.get("name"));
        }.bind(this));
        break;
    }
  },

  validate: function() {
    var success = true;
    Object.each(this.inputs, function(item, name) {
      if (!this.validateInput(name)) {
        success = false;
      }
    }.bind(this));

    if (!success) {
      this.fireEvent('failure');
    } else {
      this.fireEvent('success');
    }
    return success;
  },

  validateInput: function(name) {
    var t = this.inputs[name];
    var msgs = {};

    Object.each(this.fields[name], function(param, rule) {
      var checker = Toys.FormValidator.checkers[rule];
      if (!checker.check(t, param, this)) {
        // var msg = null;
        // if (this.options.message && this.options.messages[name] && this.options.messages[name][rule]) {
          // msg = this.options.messages[name][rule];
        // }
        msgs[rule] = checker.message(param, t, this);
      }
    }.bind(this));

    for(var i = 0; i < Toys.FormValidator.renderers.length; i++){
    	if(Toys.FormValidator.renderers[i](t, msgs, this)){
    		break;
    	}
    }

    return Object.getLength(msgs) == 0;
  }
});

Toys.FormValidator.selectors = [
	function(validator){
    return validator.form.getElements('input[type="text"],input[type="checkbox"],input[type="raido"],input[type="email"],input[type="password"],input[type="file"],select,textarea');
	}
];

Toys.FormValidator.matchers = [
	function(validator) {
	  var result = {};
	  Object.each(validator.inputs, function(input, inputName) {
	    Object.each(Toys.FormValidator.checkers, function(checker, checkerName) {
	      var r = checker.match(input);
	      if (r) {
	        if (!result[inputName]) {
	          result[inputName] = {};
	        }
	        result[inputName][checkerName] = r;
	      }
	    });
	  });
	  return result;
 }
];

Toys.FormValidator.renderers = [
	function(input, errors, validator) {
	  var newErrorElement = function(rule) {
	    var eid = input[0].get('name') + '_' + rule;
	    var result = $(eid);
	    if (!result) {
	      result = new Element('div', {
	        'id': eid,
	        'class': 'alert'
	      });
	      input[0].getParent().adopt(result);
	    }
	    return result;
	  };
	
	  var renderFailure = function() {
	    input[0].getParent()
	      .removeClass(validator.options.successClass)
	      .addClass(validator.options.failureClass);
	    Object.each(errors, function(msg, rule) {
	      newErrorElement(rule)
	        .set('text', msg)
	        .addClass(validator.options.errorClass)
	        .setStyle('display', '');
	    }.bind(validator));
	  };
	
	  var renderSuccess = function() {
	    input[0].getParent()
	      .removeClass(validator.options.failureClass)
	      .addClass(validator.options.successClass)
	      .getElements('.' + validator.options.errorClass).each(function(item) {
	        item.setStyle('display', 'none');
	      });
	  };
	
	  if (Object.getLength(errors) > 0) {
	    renderFailure();
	  } else {
	    renderSuccess();
	  }
	  return true;
	}
];
Toys.FormValidator.checkers = {
  'required': new(new Class({
    match: function(input) {
      return input.some(function(item) {
        return item.get('data-validate-required') || item.get('required');
      }) ? true : false;
    },
    check: function(input, params, validator) {
      if (input[0].isInput('radio', 'checkbox')) {
        return input.some(function(item) {
          return item.get('checked');
        });
      } else {
        if (input[0].isTag('select')) {
          return input[0].getSelected()[0].value != '';
        } else if(input[0].isTag('textarea')){
        	return input[0].getValue().length > 0;
        } else if (input[0].isInput('text', 'email', 'password', 'number')) {
          return input[0].getValue().length > 0;
        }
      }
    },
    message: function(params, input, validator) {
    	var msg = input[0].get('date-validate-request-msg');
      return msg ? msg : Locale.get('Validate.required');
    }
  }))(),

  'integer': new(new Class({
    match: function(input) {
      return input[0].get('data-validate-integer') ? true : false;
    },
    check: function(input, params, validator) {
      return input[0].getValue() == '' ? true : input[0].getValue().test(/^[1-9][0-9]*$/);
    },
    message: function(params, input, validator) {
    	var msg = input[0].get('date-validate-integer-msg');
      return msg ? msg : Locale.get('Validate.integer');
    }
  }))(),

  'number': new(new Class({
    match: function(input) {
      return input[0].get('data-validate-number') ? true : false;
    },
    'check': function(input, params, validator) {
      return input[0].getValue() == '' ? true : input[0].getValue().test(/^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/);
    },
    message: function(params, input, validator) {
    	var msg = input[0].get('date-validate-number-msg');
      return msg ? msg : Locale.get('Validate.number');
    }
  }))(),

  'digits': new(Class({
    match: function(input) {
      return input[0].get('data-validate-digits') != null ? true : false;
    },
    check: function(input, params, validator) {
      return input[0].getValue() == '' ? true : input[0].getValue().test(/^\d+$/);
    },
    message: function(params, input, validator) {
    	var msg = input[0].get('date-validate-digits-msg');
      return msg ? msg : Locale.get('Validate.digits');
    }
  }))(),

  'email': new(Class({
    match: function(input) {
      return (input[0].isInput('email') || input[0].get('data-validate-email')) ? true : false;
    },
    check: function(input, params, validator) {
      return input[0].getValue() == '' ? true : input[0].getValue().test(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i);
    },
    message: function(params, input, validator) {
    	var msg = input[0].get('date-validate-email-msg');
      return msg ? msg : Locale.get('Validate.email');
    }
  }))(),

  'character': new(new Class({
    match: function(input) {
      return input[0].get('data-validate-character') != null ? true : false;
    },
    check: function(input, params, validator) {
      return input[0].getValue() == '' ? true : input[0].getValue().test(/^[a-zA-Z0-9_\-]+$/);
    },
    message: function(params, input, validator) {
    	var msg = input[0].get('date-validate-character-msg');
      return msg ? msg : Locale.get('Validate.character');
    }
  }))(),

  'minlength': new(new Class({
    match: function(input) {
      var p = input[0].get('data-validate-minlength') || input[0].get('minlength');
      return p ? p : false;
    },
    check: function(input, params, validator) {
      return input[0].getValue() == '' ? true : input[0].getValue().length >= parseInt(params);
    },
    message: function(params, input, validator) {
    	var msg = input[0].get('date-validate-minlength-msg');
      return msg ?
        msg.substitute({
          "len": params
        }) :
        Locale.get('Validate.minlength').substitute({
          "len": params
        });
    }
  }))(),

  'maxlength': new(new Class({
    match: function(input) {
      var p = input[0].get('data-validate-maxlength') || input[0].get('maxlength');
      return p ? p : false;
    },
    check: function(input, params, validator) {
      return input[0].getValue() == '' ? true : input[0].getValue().length <= parseInt(params);
    },
    message: function(params, input, validator) {
    	var msg = input[0].get('date-validate-maxlength-msg');
      return msg ?
        msg.substitute({
          "len": params
        }) :
        Locale.get('Validate.maxlength').substitute({
          "len": params
        });
    }
  }))(),

  'minvalue': new(new Class({
    match: function(input) {
      var p = input[0].get('data-validate-minvalue');
      return p ? p : false;
    },
    check: function(input, params, validator) {
      if (input[0].getValue() == '') {
        return true;
      }
      return parseFloat(input[0].getValue()) >= parseFloat(params);
    },
    message: function(params, input, validator) {
    	var msg = input[0].get('date-validate-minvalue-msg');
      return msg ?
        msg.substitute({
          "val": params
        }) :
        Locale.get('Validate.minvalue').substitute({
          "val": params
        });
    }
  }))(),

  'maxvalue': new(new Class({
    match: function(input) {
      var p = input[0].get('data-validate-minvalue');
      return p ? p : false;
    },
    check: function(input, params, validator) {
      if (input[0].getValue() == '') {
        return true;
      }
      return parseFloat(input[0].getValue()) <= parseFloat(params);
    },
    message: function(params, input, validator) {
    	var msg = input[0].get('date-validate-maxvalue-msg');
      return msg ?
        msg.substitute({
          "val": params
        }) :
        Locale.get('Validate.maxvalue').substitute({
          "val": params
        });
    }
  }))(),
  
  'regexp': new(new Class({
    match: function(input) {
      var p = input[0].get('data-validate-regexp');
      return p ? p : false;
    },
    check: function(input, params, validator) {
      return new RegExp(params).test(input[0].getValue());
    },
    message: function(params, input, validator) {
    	var msg = input[0].get('date-validate-regexp-msg');
      return msg ?
        msg:
        Locale.get('Validate.regexp');
    }
  }))(),
  
  'equalto': new(new Class({
    match: function(input) {
      var p = input[0].get('data-validate-equalto');
      return p ? p : false;
    },
    check: function(input, params, validator) {
    	return input[0].getValue() == $(params).getValue();
    },
    message: function(params, input, validator) {
    	var msg = input[0].get('date-validate-equalto-msg');
      return msg ?
        msg:
        Locale.get('Validate.equalto');
    }
  }))()  
};