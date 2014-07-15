Toy.Validation = {};

Toy.Validation.Validator = new Class({
    Implements: [Options, Events],
    form: null,
    options: {
        autoSubmit: false,
        validateOnChange: true,
        validateOnSubmit: true,
        fieldContainer: '.form-group',
        inputContainer: '.form-group',
        errorClass: 'alert-error',
        failureClass: 'has-error',
        successClass: 'has-success'
    },

    initialize: function (frm, options) {
        this.setOptions(options);
        this.form = frm;

        this.reload();

        if (this.options.autoSubmit) {
            this.form.addEvent('submit', function (event) {
                if (!this.validate()) {
                    event.preventDefault();
                }
            }.bind(this));
        }

        this.form.store('validator', this);
    },

    reload: function () {
        this.fields = {};

        inputs = {};
        Toy.Validation.selectors.each(function (selector) {
            selector(this).each(function (item) {
                var n = $(item).get('name');
                if (n) {
                    if (!Object.keys(inputs).contains(n)) {
                        inputs[n] = [];
                    }
                    inputs[n].push(item);
                }
            }.bind(this));
        }.bind(this));

        Object.each(inputs, function (item, name) {
            this.fields[name] = new Toy.Validation.Field(this, name, item);
        }.bind(this));

        Object.each(this.fields, function (field, fieldName) {
            Object.each(Toy.Validation.rules, function (rule, ruleName) {
                var r = rule.match(field);
                if (r) {
                    field.rules[ruleName] = r;
                }
            }.bind(this));
        }.bind(this));
    },

    validate: function () {
        this.fireEvent('before');
        var success = true;
        Object.each(this.fields, function (field, name) {
            if (!field.check()) {
                success = false;
            }
        }.bind(this));

        if (!success) {
            this.fireEvent('failure');
        } else {
            this.fireEvent('success');
        }
        return success;
    }
});

Toy.Validation.Field = new Class({
    validator: null,
    name: null,
    inputs: null,
    rules: null,

    initialize: function (validator, name, inputs) {
        this.validator = validator;
        this.name = name;
        this.inputs = inputs;
        this.rules = {};
    },

    test: function () {
        result = true;
        Object.each(this.rules, function (ruleParams, ruleName) {
            var rule = Toy.Validation.rules[ruleName];
            if (!rule.check(this, ruleParams)) {
                result = false;
            }
        }.bind(this));
        return result;
    },

    check: function () {
        msgs = {};
        Object.each(this.rules, function (ruleParams, ruleName) {
            var rule = Toy.Validation.rules[ruleName];
            if (!rule.check(this, ruleParams)) {
                msgs[ruleName] = rule.message(this, ruleParams);
            }
        }.bind(this));

        this.render(msgs);
        return Object.getLength(msgs) == 0;
    },

    render: function (msgs) {
        for (var i = 0; i < Toy.Validation.renderers.length; i++) {
            if (Toy.Validation.renderers[i](this, msgs)) {
                break;
            }
        }
    }
});

Toy.Validation.selectors = [
    function (validator) {
        return validator.form.getElements('input[type="text"],input[type="hidden"],input[type="checkbox"],input[type="raido"],input[type="email"],input[type="password"],input[type="file"],select,textarea');
    }
];

Toy.Validation.renderers = [
    function (field, errors) {
        var $input = field.inputs[0];
        var ops = field.validator.options;
        var newErrorElement = function (rule) {
            var eid = $input.get('name').replace(/\[/g, '').replace(/\]/g, '') + '_' + rule;
            var result = $(eid);
            if (!result) {
                result = Elements.from('<small class="help-block" id="' + eid + '"></small>');
                $input.getParent().adopt(result);
            }
            return result;
        };

        var renderFailure = function () {
            $input.getParents(ops.fieldContainer).each(function (item) {
                item.removeClass(ops.successClass)
                    .addClass(ops.failureClass);
            });
            Object.each(errors, function (msg, rule) {
                newErrorElement(rule).set('text', msg)
                    .addClass(ops.errorClass)
                    .setStyle('display', '');
            });
        };

        var renderSuccess = function () {
            $input.getParents(ops.fieldContainer).each(function (item) {
                item.removeClass(ops.failureClass)
                    .addClass(ops.successClass);
                item.getElements('.' + ops.errorClass).each(function (item) {
                    item.setStyle('display', 'none');
                });
            });
        };
        renderSuccess();
        if (Object.getLength(errors) > 0) {
            renderFailure();
        }
        return true;
    }
];

Toy.Validation.rules = {
    'required': new (new Class({
        match: function (field) {
            return field.inputs.some(function (item) {
                return item.get('data-validate-required') || item.get('required');
            }) ? true : false;
        },
        check: function (field, params) {
            var $input = field.inputs[0];
            var tn = $input.get('tag').toUpperCase();
            switch (tn) {
                case 'INPUT':
                    var ty = $input.get('type');
                    switch (ty) {
                        case 'text':
                        case 'email':
                        case 'password':
                        case 'number':
                        case 'hidden':
                            return $input.get('value').length > 0;
                        case 'radio':
                        case 'checkbox':
                            for (var i = 0; i < field.inputs.length; i++) {
                                if (field.inputs[i].get('checked') == true) {
                                    return true;
                                }
                            }
                            return false;
                        case 'file':
                            return $input.get('value').length > 0;
                    }
                    break;
                case 'SELECT':
                    return $input.get('value').trim().length > 0;
                    break;
                case 'TEXTAREA':
                    return $input.get('value').length > 0;
                    break;
            }
        },
        message: function (field, params) {
            var msg = field.inputs[0].get('date-validate-required-msg');
            return msg ? msg : Locale.get('Validate.required');
        }
    }))(),

    'integer': new (new Class({
        match: function (field) {
            return field.inputs[0].get('data-validate-integer') ? true : false;
        },
        check: function (field, params) {
            var $input = field.inputs[0];
            return $input.get('value') == '' ? true : $input.get('value').test(/^[1-9][0-9]*$/);
        },
        message: function (field, params) {
            var msg = field.inputs[0].get('date-validate-integer-msg');
            return msg ? msg : Locale.get('Validate.integer');
        }
    }))(),

    'number': new (new Class({
        match: function (field) {
            var $input = field.inputs[0];
            return ($input.get('data-validate-number') || $input.get('type') == 'number') ? true : false;
        },
        check: function (field, params) {
            var $input = field.inputs[0];
            return $input.get('value') == '' ? true : $input.get('value').test(/^-?(?:\d+|\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/);
        },
        message: function (field, params) {
            var msg = field.inputs[0].get('date-validate-number-msg');
            return msg ? msg : Locale.get('Validate.number');
        }
    }))(),

    'digits': new (Class({
        match: function (field) {
            return field.inputs[0].get('data-validate-digits') != null ? true : false;
        },
        check: function (field, params) {
            var $input = field.inputs[0];
            return $input.get('value') == '' ? true : $input.get('value').test(/^\d+$/);
        },
        message: function (field, params) {
            var msg = field.inputs[0].get('date-validate-digits-msg');
            return msg ? msg : Locale.get('Validate.digits');
        }
    }))(),

    'email': new (Class({
        match: function (field) {
            var $input = field.inputs[0];
            return ($input.get('type') == 'email' || $input.get('data-validate-email')) ? true : false;
        },
        check: function (field, params) {
            var $input = field.inputs[0];
            return $input.get('value') == '' ? true : $input.get('value').test(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i);
        },
        message: function (field, params) {
            var $input = field.inputs[0];
            var msg = $input.get('date-validate-email-msg');
            return msg ? msg : Locale.get('Validate.email');
        }
    }))(),

    'letter': new (new Class({
        match: function (field) {
            var $input = field.inputs[0];
            return $input.get('data-validate-letter') != null ? true : false;
        },
        check: function (field, params) {
            var $input = field.inputs[0];
            return $input.get('value') == '' ? true : $input.get('value').test(/^[a-zA-Z0-9_\-]+$/);
        },
        message: function (field, params) {
            var msg = input[0].get('date-validate-letter-msg');
            return msg ? msg : Locale.get('Validate.letter');
        }
    }))(),

    'minlength': new (new Class({
        match: function (field) {
            var $input = field.inputs[0];
            var p = $input.get('data-validate-minlength') || $input.get('minlength');
            return p ? p : false;
        },
        check: function (field, params) {
            var $input = field.inputs[0];
            return $input.get('value') == '' ? true : $input.get('value').length >= parseInt(params);
        },
        message: function (field, params) {
            var $input = field.inputs[0];
            var msg = $input.get('date-validate-minlength-msg');
            return msg ?
                msg.substitute({"len": params}) :
                Locale.get('Validate.minlength').substitute({"len": params});
        }
    }))(),

    'maxlength': new (new Class({
        match: function (field) {
            var $input = field.inputs[0];
            var p = $input.get('data-validate-maxlength') || $input.get('maxlength');
            return p ? p : false;
        },
        check: function (field, params) {
            var $input = field.inputs[0];
            return $input.get('value') == '' ? true : $input.get('value').length <= parseInt(params);
        },
        message: function (field, params) {
            var msg = input[0].get('date-validate-maxlength-msg');
            return msg ?
                msg.substitute({"len": params}) :
                Locale.get('Validate.maxlength').substitute({"len": params});
        }
    }))(),

    'minvalue': new (new Class({
        match: function (field) {
            var $input = field.inputs[0];
            var p = $input.get('data-validate-minvalue');
            return p ? p : false;
        },
        check: function (field, params) {
            var $input = field.inputs[0];
            if ($input.get('value') == '') {
                return true;
            }
            return parseFloat($input.get('value')) >= parseFloat(params);
        },
        message: function (field, params) {
            var $input = $(field.inputs[0]);
            var msg = $input.attr('date-validate-minvalue-msg');
            return msg ?
                msg.substitute({"val": params}) :
                Locale.get('Validate.minvalue').substitute({"val": params});
        }
    }))(),

    'maxvalue': new (new Class({
        match: function (field) {
            var $input = field.inputs[0];
            var p = $input.get('data-validate-minvalue');
            return p ? p : false;
        },
        check: function (field, params) {
            var $input = field.inputs[0];
            if ($input.get('value') == '') {
                return true;
            }
            return parseFloat($input.get('value')) <= parseFloat(params);
        },
        message: function (field, params) {
            var $input = field.inputs[0];
            var msg = $input.get('date-validate-maxvalue-msg');
            return msg ?
                msg.substitute({"val": params}) :
                Locale.get('Validate.maxvalue').substitute({"val": params});
        }
    }))(),

    'regexp': new (new Class({
        match: function (field) {
            var $input = field.inputs[0];
            var p = $input.get('data-validate-regexp');
            return p ? p : false;
        },
        check: function (field, params) {
            var $input = field.inputs[0];
            return new RegExp(params).test($input.get('value'));
        },
        message: function (field, params) {
            var $input = field.inputs[0];
            var msg = $input.get('date-validate-regexp-msg');
            return msg ? msg : Locale.get('Validate.regexp');
        }
    }))(),

    'equalto': new (new Class({
        match: function (field) {
            var $input = field.inputs[0];
            var p = $input.get('data-validate-equalto');
            return p ? p : false;
        },
        check: function (field, params) {
            var $input = field.inputs[0];
            return $input.get('value') == $(params).get('value');
        },
        message: function (field, params) {
            var $input = field.inputs[0];
            var msg = $input.get('date-validate-equalto-msg');
            return msg ? msg : Locale.get('Validate.equalto');
        }
    }))(),

    'greatto': new (new Class({
        match: function (field) {
            var $input = field.inputs[0];
            var p = $input.get('data-validate-greatto');
            return p ? p : false;
        },
        check: function (field, params) {
            var $input = field.inputs[0];
            if ($input.get('value').trim().length == 0) {
                return true;
            }
            if ($(params).get('value').trim().length == 0) {
                return true;
            }
            return $input.get('value') > $(params).get('value');
        },
        message: function (field, params) {
            var $input = field.inputs[0];
            var msg = $input.get('data-validate-greatto-msg');
            return msg ? msg : Locale.get('Validate.greatto');
        }
    }))()
};
Toy.Widget = {};
Toy.Widget.Dropdown = new Class({

    Implements: [Options, Events],

    options: {
        ignore: 'input, select, label'
    },

    initialize: function (container, options) {
        this.element = document.id(container);
        this.setOptions(options);
        this.boundHandle = this._handle.bind(this);
        document.id(document.body).addEvent('click', this.boundHandle);
    },

    hideAll: function () {
        var els = this.element.removeClass('open').getElements('.open').removeClass('open');
        this.fireEvent('hide', els);
        return this;
    },

    show: function (subMenu) {
        this.hideAll();
        this.fireEvent('show', subMenu);
        subMenu.addClass('open');
        return this;
    },

    destroy: function () {
        this.hideAll();
        document.body.removeEvent('click', this.boundHandle);
        return this;
    },

    _handle: function (e) {
        var el = e.target;
        var open = el.getParent('.open');
        if (!el.match(this.options.ignore) || !open) this.hideAll();
        if (this.element.contains(el)) {
            var parent;
            if (el.match('[data-toggle="dropdown"]') || el.getParent('[data-toggle="dropdown"] !')) {
                parent = el.getParent('.dropdown, .btn-group');
            }

            if (!parent) parent = el.match('.dropdown-toggle') ? el.getParent() : el.getParent('.dropdown-toggle !');
            if (parent) {
                e.preventDefault();
                if (!open) this.show(parent);
            }
        }
    }
});
Toy.Widget.Modal = new Class({

    Implements: [Options, Events],

    options: {
        persist: true,
        closeOnClickOut: true,
        closeOnEsc: true,
        mask: true,
        animate: true,
        changeDisplayValue: true
    },

    initialize: function (element, options) {
        this.element = document.id(element).store('modal', this);
        this.setOptions(options);
//        this.bound = {
//            hide: this.hide.bind(this),
//            bodyClick: function (e) {
//                if (!e.target.getParent('.modal-content')) this.hide();
//            }.bind(this),
//            keyMonitor: function (e) {
//                if (e.key == 'esc') this.hide();
//            }.bind(this)
//            animationEnd: this._animationEnd.bind(this)
//        };

        var showNow = false
        if ((this.element.hasClass('fade') && this.element.hasClass('in')) ||
            (!this.element.hasClass('hide') && !this.element.hasClass('hidden') && !this.element.hasClass('fade'))) {
            if (this.element.hasClass('fade')) this.element.removeClass('in');
            showNow = true;
        }

//        this._checkAnimate();

        if (showNow) this.show();

//        if (Bootstrap.version > 2){
//            if (this.options.closeOnClickOut){
//                this.element.addEvent('click', this.bound.bodyClick);
//            }
//        }
    },

    toElement: function () {
        return this.element;
    },

//    _checkAnimate: function(){
//        this._canAnimate = this.options.animate !== false && Browser.Features.getCSSTransition() && (this.options.animate || this.element.hasClass('fade'));
//        if (!this._canAnimate) {
//            this.element.removeClass('fade').addClass('hidden');
//            if (this._mask) this._mask.removeClass('fade').addClass('hidden');
//        } else if (this._canAnimate) {
//            this.element.addClass('fade');
//            if (Bootstrap.version >= 3) this.element.removeClass('hide').removeClass('hidden');
//            if (this._mask){
//                this._mask.addClass('fade');
//                if (Bootstrap.version >= 3) this._mask.removeClass('hide').removeClass('hidden');
//            }
//        }
//    },

    show: function () {
        if (this.visible) return;
        this.element.addEvent('click:relay(.close, .dismiss, [data-dismiss=modal])', this.hide);
        if (this.options.closeOnEsc) document.addEvent('keyup', this.keyMonitor);
//        this._makeMask();
//        if (this._mask) this._mask.inject(document.body);
////        this.animating = true;
//        if (this.options.changeDisplayValue) this.element.show();
//        if (this._canAnimate) {
//            this.element.offsetWidth; // force reflow
//            this.element.addClass('in');
//            if (this._mask) this._mask.addClass('in');
//        } else {
        this.element.removeClass('hide')
            .removeClass('hidden')
//            .setStyle('visibility', 'hidden')
            .show();
//        var mwidth = this.element.getElement('.modal-dialog').getWidth();
//        var mheight = this.element.getElement('.modal-dialog').getHeight();
//        var bwidth = document.body.getWidth();
//        var bheight = document.body.getHeight();
//        this.element.getElement('.modal-dialog').setStyle({
//            top: (bheight - mheight) / 2,
//            left: (bwidth - mwidth) / 2
//
//        });
//        alert(document.body.getHeight());
//        alert(bsize.height);
//        alert(bwidth);
//        alert(bheight);
//        alert(mwidth);
//        alert(mheight);
//            if (this._mask) this._mask.show();
//        }
        this.visible = true;
//        this._watch();
    },

    _watch: function () {
//        if (this._canAnimate) this.element.addEventListener(Browser.Features.getCSSTransition(), this.bound.animationEnd);
//        else this._animationEnd();
    },

//    _animationEnd: function(){
//
//    },

    _keyMonitor: function (e) {
        if (e.key == 'esc') this.hide();
    },

    destroy: function () {
        if (this._mask) this._mask.destroy();
        this.fireEvent('destroy', this.element);
        this.element.destroy();
        this._mask = null;
        this.destroyed = true;
    },

    hide: function (event, clicked) {
//        if (clicked) {
//            var immediateParentPopup = clicked.getParent('[data-behavior~=BS.Popup]');
//            if (immediateParentPopup && immediateParentPopup != this.element) return;
//        }
//        if (!this.visible || this.animating) return;
//        this.animating = true;
//        if (event && clicked && clicked.hasClass('stopEvent')){
//            event.preventDefault();
//        }
//
//        document.removeEvent('keyup', this.bound.keyMonitor);
//        this.element.removeEvent('click:relay(.close, .dismiss, [data-dismiss=modal])', this.bound.hide);
//
//        if (this._canAnimate){
//            this.element.removeClass('in');
//            if (this._mask) this._mask.removeClass('in');
//        } else {
//            this.element.addClass('hidden').hide();
//            if (this._mask) this._mask.hide();
//        }
//        this.visible = false;
//        this._watch();
    },

    _makeMask: function () {
        if (this.options.mask) {
            if (!this._mask) {
                this._mask = new Element('div.modal-backdrop.in');
                if (this._canAnimate) this._mask.addClass('fade');
            }
        }
        if (this.options.closeOnClickOut) {
            if (this._mask) this._mask.addEvent('click', this.bound.hide);
            else document.id(document.body).addEvent('click', this.bound.hide);
        }
    }

});
Toy.Widget.ProgressModal = {

    show: function () {
        if ($('#progress_modal').length == 0) {
            element = '<div id="progress_modal" class="modal fade" role="dialog" tabindex="-1">'
                + '<div class="modal-dialog" style="margin-top: 20%;"><div class="modal-content">'
                + '<div class="progress progress-striped active">'
                + '<div class="progress-bar progress-bar-warning" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">'
                + '</div></div></div></div></div>';
            $(element).appendTo('body')
            $('#progress_modal').modal({
                'show': false,
                'keyboard': false,
                'backdrop': 'static'
            });
        }
        $('#progress_modal').modal('show');
    },

    hide: function () {
        $('#progress_modal').modal('hide');
    }
}