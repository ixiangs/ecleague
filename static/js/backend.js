function deleteConfirm(callback) {
    if (confirm(Locale.get('Default.delete_confirm_msg'))) {
        if (typeOf(callback) == 'function') {
            callback();
        } else if (typeOf(callback) == 'string') {
            window.location.href = callback;
        }
    }

}

function deleteSelectedRow(fid, url) {
    if ($('#' + fid + ' .selectable:checked').length == 0) {
        alert(Toy.Locale.get('Default.please_select_row'));
        return;
    }
    if (confirm(Toy.Locale.get('Default.delete_confirm_msg'))) {
        $('#' + fid).parents('form').attr('action', url).submit();
    }
}

function updateInput(id, value) {
    $(id).set('value', value);
//    var el = $(id);
//    var container = $(id + '_container');
//    var wells = container.getElements('.well');
//    var count = container.get('data-max-count');
//    if (wells.length >= count) {
//        wells[0].destroy();
//    }
//
//    var img = new Element('div.well')
//        .adopt(new Element('img.img-thumbnail', {'src': image.url}))
//        .adopt(new Element('div.operation', {
//            'html': '<a target="_blank" href="' + image.url + '">查看</a>&nbsp;' +
//                '<a href="javascript:void(0)" onclick="deleteUpload(this, ' + id + ')">删除</a>'
//        }));
//    container.adopt(img);
//
//    var values = [];
//    wells.each(function (item) {
//        values.push(item.getElement('.img-thumbnail(0)').get('src'));
//    });
//    el.set('value', values.join(','));
}

//function deleteUpload(el, id) {
//    var well = el.getParents('.well(0)');
//    var img = well.getElement('.img-thumbnail(0)');
//    var hidden = $(id);
//    hidden.set('value', hidden.get('value').replace(img.get('src'), ''));
//    well.destroy();
//}

window.addEvent('domready', function () {
    $$('li.dropdown').addEvent('click', function () {
        new Toy.Widget.Dropdown(this);
    });

//    $$('.upload-container').each(function (item) {
//        item.addEvent('mouseover:relay(.well)',function (e) {
//            this.getElement('.operation').show();
//        }).addEvent('mouseout:relay(.well)', function (e) {
//            this.getElement('.operation').hide();
//        });
//    });

    $('nav').getElements('.has_sub').each(function (item) {
        item.addEvent('click', function () {
            var a = this.getElement('a:first-child');
            if (a.hasClass('subdrop')) {
                a.removeClass('subdrop');
                this.getFirst('ul').setStyle('display', 'none');
                this.getElement('i').set('class', 'fa fa-chevron-left');
            } else {
                a.addClass('subdrop');
                this.getFirst('ul').setStyle('display', 'block');
                this.getElement('i').set('class', 'fa fa-chevron-down');
            }

        });
    });

    $$('a.menubutton(0)').addEvent('click', function () {
        var mainbar = $$('.mainbar(0)');
        var ml = mainbar.getStyle('margin-left').toString().toInt();
        if (ml > 0) {
            $$('.sidebar(0)').setStyle('display', 'none');
            mainbar.setStyle('margin-left', '0px');
        } else {
            $$('.sidebar(0)').setStyle('display', 'block');
            mainbar.setStyle('margin-left', '230px');
        }
    });

    $$('form[data-validate]').each(function (item) {
        new Toy.Validation.Validator(item);
    });

    $$('button[data-submit]').addEvent('click', function () {
        var $form = $(this.get('data-submit'));
        var args = this.get('data-submit-arguments');
        var ajax = this.get('data-ajax');
        var ajaxHandler = this.get('data-ajax-handler');
        var ajaxReady = this.get('data-ajax-ready');
        if (args) {
            args = JSON.decode(args);
            Object.each(args, function (item, key) {
                if ($form.getElements(key).length == 0) {
                    var h = '<input type="hidden" name="{id}" id="{id}" value="{val}"/>'.substitute({
                        'id': key, 'val': item
                    });
                    $form.adopt(h);
                }
            });
        }
        if (ajax) {
//            if($form.store('validator')){
//                if(!$form.retrieve('validator').validate()){
//                    return;
//                }
//            }
//            Toy.Widget.ProgressModal.show();
//            $form.addEvent('submit', function(){
//                $(this).ajaxSubmit({
//                    success:function(responseText, statusText, xhr, form){
//                        Toy.Widget.ProgressModal.hide();
//                        alert(window[ajaxHandler]);
//                        if(ajaxHandler && window[ajaxHandler]){
//                            window[ajaxHandler](responseText, statusText, xhr, form);
//                        }
//                    }
//                });
//                return false;
//            });
        } else {
            if ($form.retrieve('validator').validate()) {
                $form.submit();
            }
        }
//        $form.addEvent('submit', function (event) {
//            event && event.stop();
//            return false;
//        });

    });

//    $('input.selectable-head').addEvent('click', function(){
//        var c = this.get('checked');
//        if(c){
//            this.getParent('table').getElement(' .selectable').prop('checked', true);
//        }else{
//            this.getParent('table').find(' .selectable').prop('checked', false);
//        }
//    });
});

