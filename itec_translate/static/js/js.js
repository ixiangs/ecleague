$(function() {
	convertTextToChange();
	$.ajax({
		url : saveUrl,
		data : 'p=' + pageName
	}).done(function(data) {
		var jd = $.parseJSON(data);
		for (var i = 0; i < jd.length; i++) {
			var l = jd[i];
			$('#' + l['formid'] + ' a.modify').each(function(index, item) {
				var $item = $(item);
				if ($item.attr('data-source') == l.source) {
					if (l.inputid) {
						if ($item.parents('td').children('#' + l.inputid).length > 0) {
							if (l.modified == '-') {
								$item.css({
									'color' : 'red',
									'text-decoration' : 'line-through'
								}).text(l.source);
							} else {
								$item.text(l.modified);
							}
						}
					} else {
						if (l.modified == '-') {
							$item.css({
								'color' : 'red',
								'text-decoration' : 'line-through'
							}).text(l.source);
						} else {
							$item.text(l.modified);
						}
					}
				}
			});
		}
	});

});

function convertTextToChange() {
	$('.tl').each(function(index, item) {
		$(item).html(wrapChange($(item).text()));
	});
	$('span.changeable').each(function(index, item) {
		$(item).html(wrapChange($(item).text()));
	});
	$('h2').each(function(index, item) {
		$(item).html(wrapChange($(item).text()));
	});
	$('select').each(function(index, item) {
		$item = $(item);
		if (!$item.hasClass('ignore')) {
			$item.hide();
			$item.children().each(function(index, op) {
				$op = $(op);
				$item.parent().append($('<p>' + wrapChange($op.text(), $item.attr('id')) + '</p>'));
			});
		}
	});
	$('label').each(function(index, item) {
		var $item = $(item);
		var $first = $($item.children()[0]);
		if ($first.attr('type') == 'checkbox') {
			var txt = $item.text();
			$item.empty();
			$item.append($first);
			$item.append(wrapChange(txt));
		}
	});
}

function wrapChange(text, inputid) {
	return '<a class="modify" data-input="' + inputid + '" data-source="' + text + '" href="javascript:void(0);" onclick="javascript:showModal(this);">' + text + '</a>';
}

var currentEl = null;
function showModal(el) {
	currentEl = el;
	var txt = $.trim($(el).text());
	$('#modal_content').val(txt);
	$('.modal-title').text($(el).attr('data-source'));
	$('#myModal').modal('show');
}

function submitChanged(act) {
	$el = $(currentEl);
	var s = $el.attr('data-source');
	var id = $el.parents('form').attr('id');
	var r = $('#modal_content').val();
	var input = $el.attr('data-input') == 'undefined' ? '' : $el.attr('data-input');
	switch(act) {
		case 'delete':
			r = "-";
			break;
		case 'reset':
			r = s;
			break;
	}

	$.ajax({
		url : saveUrl,
		method : 'post',
		data : 'i=' + input + '&s=' + $.trim(s) + '&m=' + $.trim(r) + '&f=' + id + '&p=' + pageName
	}).done(function(data) {
		if (data == "") {
			if (r == '-') {
				$el.css({
					'color' : 'red',
					'text-decoration' : 'line-through'
				}).text(s);
			} else {
				$el.css({
					'color' : '',
					'text-decoration' : 'none'
				}).text(r);
			}
			$('#myModal').modal('hide');
		}
	});
}