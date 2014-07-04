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

window.addEvent('domready', function () {
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
        if(ajax){
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
        }else{
            if($form.retrieve('validator').validate()){
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

