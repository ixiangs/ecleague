function deleteConfirm(callback){
	var html = '<div id="deleteComfirmModal" class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
    html += '<div class="modal-dialog">';
    html += '<div class="modal-content">';
    html += '<div class="modal-header">';
    html += '<h4 class="modal-title" id="myModalLabel">';
    html += Toys.Locale.get('Default.delete_confirm');
    html += '</h4>';
    html += '</div><div class="modal-body">';
    html += '<p class="error-text"><i class="icon-warning-sign modal-icon"></i>' + Toys.Locale.get('Default.delete_confirm_msg') + '</p>';
    html += '</div>';
    html += '<div class="modal-footer">';
    html += '<button class="btn" data-dismiss="modal" aria-hidden="true">' + Toys.Locale.get('Default.cancel') + '</button>';
    html += '<button id="deleteConfirmOk" class="btn btn-danger" data-dismiss="modal">' + Toys.Locale.get('Default.delete') + '</button>';
    html += '</div></div></div></div>';
    	
    if($('#deleteComfirmModal').length == 0){
    	$('body').append(html);
    }
    if(typeOf(callback) == 'function'){
    	$('#deleteConfirmOk').click(callback);
    }else if(typeOf(callback) == 'string'){
    	$('#deleteConfirmOk').click(function(){
    		window.location.href = callback;
    	});
    }
	$('#deleteComfirmModal').modal('show');
}

