window.addEvent('domready', function () {
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
    })
});
