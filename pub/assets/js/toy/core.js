Toy = {};

Toy.Request = new Class({
    Implements: [Events, Options],
    initialize: function (options) {
        this.setOptions(options);
    },

    get: function (url, data) {
        this.options['method'] = 'get';
        this._send(url, data);
        return this;
    },

    post: function (url, data) {
        this.options['method'] = 'post';
        this._send(url, data);
        return this;
    },

    _send: function (url, data) {
        var settings = Object.merge(this.options, {
            'url': url,
            'data': data,
            'beforeSend': function (xhr) {
                this.fireEvent('ready', [xhr]);
            }.bind(this)
        });
        this.ajax = $.ajax(settings).done(function (data, textStatus, jqXHR) {
                this.fireEvent('success', [data, textStatus, jqXHR]);
            }.bind(this)).fail(function (jqXHR, textStatus, errorThrown) {
                this.fireEvent('failure', [textStatus, jqXHR, errorThrown]);
            }.bind(this)).always(function () {
                this.fireEvent('finish');
            }.bind(this));
    }
});