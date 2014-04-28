Toy = {};

Toy.Request = new Class({

    address: '',
    data: '',

    initialize:function(addr, data, settings){
        this.address = addr;
        this.data = data;
        this.ajax = null;
    },

    get:function(){
        $.ajax({

        });
    },

    post:function(){

    },

    _buildAjax:function(){

        this.ajax = $.ajax({

        });
    }
});