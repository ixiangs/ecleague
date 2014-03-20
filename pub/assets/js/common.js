function deleteConfirm(callback) {
    var html = '<div id="deleteComfirmModal" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
    html += '<div class="modal-dialog">';
    html += '<div class="modal-content">';
    html += '<div class="modal-header modal-info">';
    html += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
    html += '<h4 class="modal-title" id="myModalLabel">';
    html += Toy.Locale.get('Default.delete_confirm');
    html += '</h4>';
    html += '</div><div class="modal-body">';
    html += '<div class="alert alert-warning">';
    html += Toy.Locale.get('Default.delete_confirm_msg');
    html += '</div>';
    html += '</div>';
    html += '<div class="modal-footer">';
    html += '<button class="btn" data-dismiss="modal" aria-hidden="true">' + Toy.Locale.get('Default.cancel') + '</button>';
    html += '<button id="deleteConfirmOk" class="btn btn-danger" data-dismiss="modal">' + Toy.Locale.get('Default.delete') + '</button>';
    html += '</div></div></div></div>';

    if ($('#deleteComfirmModal').length == 0) {
        $('body').append(html);
    }
    if (typeOf(callback) == 'function') {
        $('#deleteConfirmOk').click(callback);
    } else if (typeOf(callback) == 'string') {
        $('#deleteConfirmOk').click(function () {
            window.location.href = callback;
        });
    }
    $('#deleteComfirmModal').modal('show');
}

$(function () {
    $('form[data-validate]').each(function () {
        new Toy.Validation.Validator($(this));
    });
    $('button[data-submit]').click(function(){
        $('#' + $(this).attr('data-submit')).submit();
    });
});

