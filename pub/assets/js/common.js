function deleteConfirm(callback){
	html = '<div class="modal small hide fade" id="deleteComfirmModal" tabindex="-1">';
    html += '<div class="modal-header">';
    html += '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
    html += '<h3 id="myModalLabel">' + Toys.Locale.get('Default.delete_confirm') + '</h3>';
    html += '</div>';
    html += '<div class="modal-body">';
    html += '<p class="error-text"><i class="icon-warning-sign modal-icon"></i>' + Toys.Locale.get('Default.delete_confirm_msg') + '</p>';
    html += '</div>';
    html += '<div class="modal-footer">';
    html += '<button class="btn" data-dismiss="modal" aria-hidden="true">' + Toys.Locale.get('Default.cancel') + '</button>';
    html += '<button id="deleteConfirmOk" class="btn btn-danger" data-dismiss="modal">' + Toys.Locale.get('Default.delete') + '</button>';
    html += '</div></div>';
    	
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

