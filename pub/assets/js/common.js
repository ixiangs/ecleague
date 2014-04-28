function deleteConfirm(callback) {
    if(confirm(Toy.Locale.get('Default.delete_confirm_msg'))){
        if (typeOf(callback) == 'function') {
            callback();
        } else if (typeOf(callback) == 'string') {
            window.location.href = callback;
        }
    }

}

function deleteSelectedRow(fid, url){
    if($('#' + fid + ' .selectable:checked').length == 0){
        alert(Toy.Locale.get('Default.please_select_row'));
        return;
    }
    if(confirm(Toy.Locale.get('Default.delete_confirm_msg'))){
        $('#' + fid).parents('form').attr('action', url).submit();
    }
}

$(function () {
    $('input.selectable-head').click(function(){
        var c = $(this).prop('checked');
        if(c){
            $(this).parents('table').find(' .selectable').prop('checked', true);
        }else{
            $(this).parents('table').find(' .selectable').prop('checked', false);
        }
    });

    $('form[data-validate]').each(function () {
        new Toy.Validation.Validator($(this));
    });
    $('button[data-submit]').click(function(){
        var $form = $('#' + $(this).attr('data-submit'));
        var args = $(this).attr('data-submit-arguments');
        if(args){
            args = JSON.decode(args);
            Object.each(args, function(item, key){
                if($form.find('#' + key).length == 0){
                    var h = '<input type="hidden" name="{id}" id="{id}" value="{val}"/>'.substitute({
                        'id': key, 'val':item
                    });
                    $form.append(h);
                }
            });
        }
        $form.submit();
    });
});

