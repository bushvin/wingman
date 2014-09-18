/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * wingman.js
 * 
 * javascript for the masses
 * 
 */
var app = { cls: {} };
app.cls.cookie = function(name) {
    if ( name===undefined ) throw new Error("app.cls.cookie requires a name!");
    this._name = name;

    this.set = function(key,value) {
        if ( key===undefined ) throw new Error("app.cls.cookie::set requires a key!");
        if ( value===undefined ) throw new Error("app.cls.cookie::set requires a value!");
        document.cookie = this._name+':'+key+'='+value;
        return true;
    };

	this.get = function(key) {
		var r=false;
		if ( document.cookie.length>0 ) {
			var cs = document.cookie.indexOf(this._name+':'+key+'=');
			var ce;
			if ( cs!=-1 ) {
				cs=cs+this._name.length+key.length+2;
				ce=document.cookie.indexOf(';',cs);
				if (ce==-1) ce=document.cookie.length;
				r = document.cookie.substring(cs,ce);
			}
		}
		return r;
	};
	
	this.del = function(key) {
		this._cookie = this._name+':'+key+'=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
		return true;
	};
	
	this.clear = function() {
		var cookies = document.cookie.split('; ');
		for (var i=0;i<cookies.length;i++) {
			cookies[i] = cookies[i].replace('=',':');
			if (cookies.split(':').length >1) this.del(cookies.split(':')[1]);
		}
	}
};

app.cls.envelope = function(id,token,api,data) {
    if (id === undefined) throw new Error("app.cls.envelope requires an id!");
    if (token === undefined) throw new Error("app.cls.envelope requires a token!");
    this._e = {id: id, token: token, api: '', data: {}};
    if (api !== undefined) this._e.api = api;
    if (data !== undefined) this._e.data = data;
    
    
    this.setAPI = function(api) {
        this._e.api = api;
        return true;
    };
    
    this.setData = function(key, value) {
        this._e.data[key] = value;
        return true;
    };
    
    this.toJSON = function() {
        return JSON.stringify(this._e);
    };
    
};

app.cls.api = function() {
    this._cookie = new app.cls.cookie("wingman");

    this.reset = function() {
        this._callback = function() {};
        this._errorTo = function() {};
    };

    this.request = function() {
        var _this = this;
        if (this._cookie.get('id') === false) this._cookie.set('id',-1);
        if (this._cookie.get('token') === false) this._cookie.set('token',"");
        if (this._cookie.get('id') == -1) this._cookie.set('token',"");
        if (this._cookie.get('token') == "") this._cookie.set('id',-1);
        var e = new app.cls.envelope(this._cookie.get('id'), this._cookie.get('token'), this._api, this._data);
        $.ajax({
            url:  'api.php?_ts='+(new Date().getTime()),
            type: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            processData: false,
            data: e.toJSON(),
            success: function(data) { _this.request_return(data) },
            error: function(data) {_this.request_error(data) },
            statusCode: {
                401: function() { if (app.control!==undefined) location.reload(true); },    /* Unauthorized */
                403: function() {  },                          /* Forbidden */
                404: function() {  },                          /* Not Found */
                405: function() {  }                           /* Method not allowed */
            }
        });

        return true;
    };
    
    this.request_return = function(data) {
        if (data!==undefined && data.code!==undefined && data.code>=1000 && app.error !==undefined) {
            app.error.show(data.code);
        }
        this._callback(data);
    };

    this.request_error = function(data) {
        this._errorTo(data);
    };

    this.setAPI = function( api ) {
        if (api !== undefined) this._api = api;
        return true;
    };
    
    this.setData = function( data ) {
        if ( data !== undefined ) this._data = data;
        return true;
    };
    
    this.setReturnTo = function( callback ) {
        if (typeof(callback) == "function") this._callback = callback;
        return true;
    };
    
    this.setErrorTo = function( callback ) {
        if (typeof(callback) == "function") this._errorTo = callback;
        return true;
    };
    
    this.initialize = function() {
        this.reset();
    };
    
    this.initialize();    
};

app.cls.dialog = function() {
    this._cookie = new app.cls.cookie("wingman");
    this._data = {dialog:'',view:''};
    this._object = undefined;
    this._errorTo = function() {};
    this._onload = function() {};

    this.request = function() {
        var _this = this;
        var e = new app.cls.envelope(this._cookie.get('id'), this._cookie.get('token'), undefined, this._data);
        $.ajax({
            url:  'dialog.php?_ts='+(new Date().getTime()),
            type: 'POST',
            contentType: 'application/json',
            dataType: 'html',
            processData: false,
            data: e.toJSON(),
            success: function(data) { _this.request_return(data) },
            error: function(data) {_this.request_error(data) }
        });
        
        return true;
    };

    this.request_return = function(data) {
        this._object.html(data);
        this._onload(data);
    };

    this.request_error = function(data) {
        this._errorTo(data);
    };
    
    this.setData = function(key,value) {
        if (key === undefined) return false;
        if (value === undefined) return false;
        this._data[key] = value;
        return true;
    };

    this.setDialog = function( view, dialog ) {
        if (view !== undefined) this._data.view = view;
        if (dialog !== undefined) this._data.dialog = dialog;
        return true;
    };
    
    this.setReturnTo = function( object ) {
        if (typeof(object) == "object") this._object = object;
        return true;
    };
    
    this.setErrorTo = function( callback ) {
        if (typeof(callback) == "function") this._errorTo = callback;
        return true;
    };
    
    this.load = function( callback ) {
        if (typeof(callback)=="function") this._onload = callback;
        return true;
    };

    this.reset = function() {
        this._data = {dialog:'',view:''};
        this._object = undefined;
        this._errorTo = function() {};
    };

    this.initialize = function() {
        this.reset();
    };
    
    this.initialize();    
    
};

app.cls.auth = function(data) {
    this._cookie = new app.cls.cookie("wingman");
    this._api = new app.cls.api();
    this._isAuthenticated = false;
    this._doIfAuthenticated = function() {};
    this._doIfNotAuthenticated = function() {};
    this._heartbeatId = -1;
    if (data!==undefined && typeof(data.authenticated)==="function") this._doIfAuthenticated = data.authenticated;
    if (data!==undefined && typeof(data.nauthenticated)==="function") this._doIfNotAuthenticated = data.nauthenticated;
    
    this.isAuthenticated = function() {
        return this._isAuthenticated;
    };
    
    this.setPassword = function(oldpwd, newpwd) {
        var _this = this;
        this._api.setAPI('auth_passwd');
        this._api.setData({oldpwd:oldpwd, newpwd:newpwd});
        this._api.setReturnTo(function(data) {_this.setPassword_return(data)});
        this._api.request();
    };
    
    this.setPassword_return = function(data) {
        
    };

    this.signin = function(login, password) {
        var _this = this;
        this._api.setAPI('auth_signin');
        this._api.setData({login:login, password:password});
        this._api.setReturnTo(function(data) { _this.signin_return(data)});
        this._api.request();
        $('form .error').attr('style','display: none;');
    };
    
    this.signin_return = function(data) {
		if (data.code < 1000) {
			this._cookie.set('id', data.id); 
			this._cookie.set('token', data.token);
			location.reload(true);
			return true;
		}
		switch (data.code) {
            case 1001:
                $('form .error').attr('style','display: block;');
                break;
		}
    };
    
    this.signout = function() {
        var _this = this;
        this._api.setAPI('auth_signout');
        this._api.setReturnTo(function(data) {_this.signout_return(data)});
        this._api.request();
    };
    
    this.signout_return = function(data) {
		this._cookie.set('id', -1); 
		this._cookie.set('token', "");
        location.reload(true);
    };
    
    this.validateToken = function( returnTo ) {
        if (returnTo === undefined || typeof(returnTo) !== "function") {
            returnTo = function() {};
        }
        var _this = this;
        this._api.setAPI('auth_validateToken');
        this._api.setReturnTo(function(data) { _this.validateToken_return(data); returnTo() });
        this._api.setErrorTo(function(){
            _this._cookie.set("id",-1);
            _this._cookie.set("token","");
        });
        this._api.request();
        return true;
    };
    
    this.validateToken_return = function(data) {
        if (data.code < 1000) {
            this._isAuthenticated = true;
            this._doIfAuthenticated();
            this._doIfAuthenticated = function() {};
        } else {
            this._isAuthenticated = false;
			this._cookie.set('id', -1); 
			this._cookie.set('token', '');
            this._doIfNotAuthenticated();
            this._doIfNotAuthenticated = function() {};
        }
        return this._isAuthenticated;
    };
    
    this.heartbeat = function() {
        this.validateToken();
    };
    
    this.startHeartbeat = function() {
        var _this = this;
        if (this._heartbeatId!==-1) return false;
        this.heartbeat();
        this._heartbeatId = setInterval(function() { _this.heartbeat() },60000);
    };
    
    this.stopHeartbeat = function() {
        clearInterval(this._heartbeatId);
        this._heartbeatId = -1;
    };
    
    this.initialize = function() {
        this.startHeartbeat();
    };
    
    this.initialize();
    
};

app.cls.control = function() {
    this._cookie = new app.cls.cookie("wingman");
    this._data = {};
    this._item = new app.cls.item();
    this._actionlist = null;
    this._settingsShown = false;
    this._passwordShown = false;
    this._recordcount = 0;
    this._itemcount = 0;
    this._sm = undefined;
    this._itemlist = [];
    this._fieldlist = [];
    this._fieldTlist = [];

    this.itemlist = function() {
        var r = [];
        for (var i=0; i<this._itemlist.length; i++) {
            r.push(this._itemlist[i]);
        }
        return r;
    };
    
    this.deleteItems = function( ids ) {
        if ( this._sm!==undefined ) {
            this._sm.destroy();
            delete this._sm;
        }
        if (ids.length===0) {
            this._sm = new app.cls.statusmessage({message:'NO_ITEMS_SELECTED'});
            this._sm.show();
            return false;
        } else if (ids.length===1) {
            this._sm = new app.cls.statusmessage({message:'ITEM_DELETED'});
        } else {
            this._sm = new app.cls.statusmessage({message:'ITEMS_DELETED'});
        }
        var api = new app.cls.api();
        var _this = this;
        api.setAPI(this._cookie.get('current_view')+'_rm');
        api.setData({id:ids});
        api.setReturnTo(function(data){_this._sm.show();_this.deleteItems_return(data)});
        api.request();
    };
    
    this.deleteItems_return = function( data ) {
        this.view();
    };

    this.toggleActionlist = function() {
        if (this._actionlist !== null ) this._actionlist.toggleVisibility();
    };

    this.showActionlist = function() {
        if (this._actionlist !== null ) this._actionlist.show();
    };
    
    this.hideActionlist = function() {
        if (this._actionlist !== null ) this._actionlist.hide();
    };
    
    this.actionlist = function(data) {
        if (this._actionlist!==null) this._actionlist.destroy();
        delete this._actionlist;
        this._actionlist = new app.cls.actionlist(data);
    };
    
    this.view = function(view, force) {
        force = (typeof(force)!=="boolean"?false:force);
        if (view===undefined) view = this._cookie.get('current_view');
        if (view !== this._cookie.get('current_view')) {
            force = true;
            this._cookie.set('current_view',view);
        }

        $('.menu .item').removeClass('selected');
        $('.menu .item.'+view).addClass('selected');
        
        if ( force === true ) {
            $('.view').empty();
            $('.actionbar').empty();
            $.fn.spin.presets.custom = {
                    lines: 13,
                    length: 20,
                    width: 10,
                    radius: 30,
                    corners: 1,
                    rotate: 0,
                    direction: 1,
                    speed: 1,
                    trail: 60,
                    shadow: true,
                    hwaccel: false,
                    className: 'spinner',
                    zIndex: 2e9
            };
            $('.view').spin('custom');
            var _this = this;
            switch (view) {
                case "dashboard":
                    var a = new app.cls.dialog();
                    a.setDialog(this._cookie.get('current_view'),'actions');
                    a.setReturnTo($('.actionbar'));
                    a.request();
        
                    var v = new app.cls.dialog();
                    v.setDialog('dashboard','dashboard');
                    v.setReturnTo($('.view'));
                    v.request();
                    break;
                default:
                    var a = new app.cls.dialog();
                    a.setDialog(this._cookie.get('current_view'),'actions');
                    a.setReturnTo($('.actionbar'));
                    a.request();
        
                    var v = new app.cls.api();
                    v.setAPI(view+'_ls');
//                    v.setData(data);
                    v.setReturnTo(function(data){_this.view_return(data)});
                    v.request();
            }
            return true;
        }
    };
    
    this.view_return = function(data) {
        if (data===undefined) data = {code: 1000};
        data.code = (isNaN(parseInt(data.code,10))?1000:parseInt(data.code,10));
        if (data.code <1000) {
            this._itemlist    = data.data.records;
            this._fieldlist   = data.data.fields;
            this._fieldTlist  = data.data.fields_translated;
            this._itemcount = data.data.records.length;
        } else {
            this._itemlist    = [];
            this._fieldlist   = [];
            this._fieldTlist  = [];
            this._itemcount = 0;
        }
        
        this.redrawView();
    };

    this.filterItems = function(items) {
        var view = this._cookie.get('current_view');
        var filter = JSON.parse(this._cookie.get(view+':filter'));
        var show = $('#filter_enable').prop('checked');

        var filteritems = $('.filter .cell input[type=text]');
        var filter = {};
        var old = items;
        var i = 0;
        var pcount=0;
        for (i=0; i<filteritems.length;i++) {
            var el = $(filteritems[i]);
            if (el.val()!='') {
                filter[el.attr('id')] = el.val();
                pcount++;
            }
        }
        this._cookie.set(view+':filter',JSON.stringify({enabled:show,filter:filter}));
        if (pcount===0 || show===false) return items;
        
        var filterfn = function(el,idx) {
            var mcount = 0;
            var fcount = 0;
            for (key in filter) {
                fcount++;
                var rgx = new RegExp(filter[key],"i");
                if ((''+el[key]).search(rgx) > -1) mcount++;
            }
            return (mcount===fcount);
        }
        items = old.filter(filterfn);
        return items;
    };
    
    this.sortItems = function(items) {
        var view = this._cookie.get('current_view');
        var sort = JSON.parse(this._cookie.get(view+':sort'));
        // { field: a, direction: b }
        items.sort(function(a,b) {
            if (a[sort.field] < b[sort.field]) {
                return (sort.direction=='asc'?-1:1);
            }
            if (a[sort.field] > b[sort.field]) {
                return (sort.direction=='asc'?1:-1);
            }
            return 0;
        });
        return items;
    };

    this.limitItems = function(items) {
        var view = this._cookie.get('current_view');
        var limit = JSON.parse(this._cookie.get(view+':limit'));
        // { offset: a, count: b }
        items = items.splice(limit.offset,limit.count);
        return items;
    };
    
    this.toggleFilter = function() {
        var show = $('#filter_enable').prop('checked');
        $('.filter .cell input').val('');
        if (show === true) {
            $('.filtered').removeClass('n');
            $('.filtered').addClass('y');
        } else {
            $('.filtered').removeClass('y');
            $('.filtered').addClass('n');
            this.redrawListItems();
            this.redrawPager();
        }
    };
    
    this.redrawView = function() {
        var view = this._cookie.get('current_view');
        var _this = this;
        var i = 0;

        var filter = JSON.parse(this._cookie.get(view+':filter'));
        var sort = JSON.parse(this._cookie.get(view+':sort'));
        var limit = JSON.parse(this._cookie.get(view+':limit'));
        
        if (typeof(limit)!=='object') limit = {offset: 0, count: 0};
        limit.offset = (isNaN(parseInt(limit.offset,10))?0:parseInt(limit.offset,10));
        limit.count = this._cookie.get('settings:showitems');
        this._cookie.set(view+':limit',JSON.stringify(limit));
        
        if (typeof(filter)!=='object') filter = {enabled: false, filter: {}};
        filter.enabled = (typeof(filter.enabled)=='boolean'?filter.enabled:false);
        filter.filter = (typeof(filter.filter)=='object'?filter.filter:{});

        this._cookie.set(view+':filter',JSON.stringify(filter));
        
        var v = $('<div></div>')
                    .addClass('filtered')
                    .addClass((filter.enabled?'y':'n'));
        
        var f = $('<div></div>')
                    .addClass('filter')
                    .addClass(view);
            v.append(f);
                    
        var h = $('<div></div>')
                    .addClass('header')
                    .addClass(view);
            v.append(h);
                    
        var l = $('<div></div>')
                    .addClass('list')
                    .addClass(view);
            v.append(l);
                    
        var p = $('<div></div>')
                    .addClass('pager');
            v.append(p);


        f.append($('<div></div>')
                        .addClass('selector')
                        .append($('<div></div>')
                                .addClass('cell')
                                .append(new app.cls.checkbox({id:'filter_enable',checked:filter.enabled,event:function(){_this.toggleFilter();}}).domNode))
                        .append($('<div></div>')
                                .addClass('cell')
                                .append($('<label></label>')
                                            .addClass('text')
                                            .attr('for','filter_enable')
                                            .html(' Filter')
                                            ))
                        );
        var fi = $('<form></form>')
                .addClass('filters')
                .submit(function(){_this.redrawListItems();_this.redrawPager();});
        fi.submit(function(e){e.preventDefault(e);});    

        f.append(fi);
        fi.append($('<div></div>')
                    .addClass('cell')
                    .addClass('toggle'));
        for (i=0; i<this._fieldTlist.length;i++) {
            if ( this._fieldTlist[i] == 'id' ) continue;
            fi.append($('<div></div>')
                        .addClass('cell')
                        .addClass(this._fieldlist[i].toLowerCase())
                        .append($('<input/>')
                                    .attr('type','text')
                                    .addClass('input-text')
                                    .val((filter.filter[this._fieldlist[i].toLowerCase()]!==undefined?filter.filter[this._fieldlist[i].toLowerCase()]:''))
                                    .attr('id',this._fieldlist[i].toLowerCase())));
        }
        fi.append($('<div></div>')
                    .addClass('cell')
                    .addClass('action')
                    .append($('<input/>')
                                .attr('type','submit')
                                .addClass('button')
                                .attr('style','visibility: hidden;')
                                .click()))

        h.append($('<div></div>')
                    .addClass('cell')
                    .addClass('toggle')
                    .append(new app.cls.checkbox({id:'master',event:function() { _this.toggleAll()}}).domNode));
        for (i=0; i<this._fieldTlist.length;i++) {
            if ( this._fieldTlist[i] == 'id' ) continue;
            eval('var fnm = function() { _this.sort(\''+this._fieldlist[i].toLowerCase()+'\')};');
            h.append($('<div></div>')
                        .addClass('cell')
                        .addClass(this._fieldlist[i].toLowerCase())
                        .addClass((sort.field==this._fieldlist[i]?sort.direction:''))
                        .html(this._fieldTlist[i])
                        .click(fnm));
        }


        $('.view').empty();
        $('.view').append(v);
        this.redrawListItems();
        this.redrawPager();
    };
    
    this.redrawListItems = function() {
        var view = this._cookie.get('current_view');
        var _this = this;
        var i = 0;
        var j = 0;
        var showItems = this.itemlist();
        var filter = JSON.parse(this._cookie.get(view+':filter'));
        var sort = JSON.parse(this._cookie.get(view+':sort'));
        var limit = JSON.parse(this._cookie.get(view+':limit'));
        $('.view .list').empty();


        var l = $('<div></div>');
        limit.count = this._cookie.get('settings:showitems');
        this._cookie.set(view+':limit',JSON.stringify(limit));
        
        showItems = this.filterItems(showItems);
        showItems = this.sortItems(showItems);
        showItems = this.limitItems(showItems);
        var li = null;

        for (i=0; i<showItems.length;i++) {
            eval('var fn = function() {_this._item.show('+showItems[i].id+');}');
            li = $('<div></div>')
                    .addClass('listitem')
                    .click(fn)
            l.append(li);
            li.append($('<div></div>')
                        .addClass('cell')
                        .addClass('toggle')
                        .append(new app.cls.checkbox({id:'cb_'+showItems[i].id,value:showItems[i].id}).domNode));
            for (j=0;j<this._fieldlist.length;j++) {
                if (this._fieldlist[j] == 'id') continue;
                li.append($('<div></div>')
                            .addClass('cell')
                            .addClass(this._fieldlist[j])
                            .html(showItems[i][this._fieldlist[j]]));
            }
        }
        $('.view .list').empty();
        $('.view .list').append(l);
        
    };
    
    this.redrawPager = function() {
        var view = this._cookie.get('current_view');
        var _this = this;
        var limit = JSON.parse(this._cookie.get(view+':limit'));
        var p = $('<div></div>');
        var showItems = this.itemlist();
        showItems = this.filterItems(showItems);

        var pagecount = Math.ceil(showItems.length/limit.count);
        var page_current = Math.floor(limit.offset/limit.count)+1;
        var page_start = 1;
        var page_end = pagecount;

        if (pagecount>7) {
            page_start = page_current-2;
            if (page_start<2) {
                page_start = 2;
            }
            page_end = page_start +4;
            if ( page_end > pagecount) {
                page_end = pagecount-2;
                page_start = page_end -4;
            }
        }
        
        if (pagecount>7) {
            p.append($('<div></div>')
                            .addClass('page')
                            .addClass((page_current==1?'current':''))
                            .html('1')
                            .click(function() { _this.gotoPage(1);}));
        }
        for (i=page_start;i<=page_end;i++) {
            eval('var fn = function() { _this.gotoPage('+i+');};');
            p.append($('<div></div>')
                            .addClass('page')
                            .addClass((page_current==i?'current':''))
                            .html(i)
                            .click(fn));
         }

        if (pagecount>7) {
            p.append($('<div></div>')
                            .addClass('page')
                            .addClass((page_current==pagecount?'current':''))
                            .html(pagecount)
                            .click(function() { _this.gotoPage(pagecount);}));
        }        
        $('.view .pager').empty();
        $('.view .pager').append(p);
    };
    
    this.sort = function(field,direction) {
        var ident = this._cookie.get('current_view')+':sort';
        if ( this._cookie.get(ident) === false ) {
            this._cookie.set(ident,JSON.stringify({field:this._data.fields[0],direction:'asc'}));
        }
        var sort = JSON.parse(this._cookie.get(ident));
        if (field === undefined) field = sort.field;
        if (direction!==undefined) { 
            sort.direction = direction;
        } else {
            if (sort.field !=field) {
                sort.direction='asc';
            } else if (sort.direction=='asc') {
                sort.direction='desc';
            } else {
                sort.direction='asc';
            }
        }
        sort.field = field;
        this._cookie.set(ident,JSON.stringify(sort));
        $('.header .cell').removeClass('asc');
        $('.header .cell').removeClass('desc');
        $('.header .cell.'+sort.field).addClass(sort.direction);
        this.redrawListItems();
    };
    
    this.toggleAll = function() {
        $('.view .list .listitem .checkbox input[type=checkbox]').prop('checked',$('.view .header #master').prop('checked'));
    };
    
    this.getSelected = function() {
        var sObj = $('.view .list .cell .checkbox.checked');
        var r = [];
        for (var i=0;i<sObj.length;i++) {
            r.push($(sObj[i]).attr('value'));
        }
        return r;
    };

    this.loadSettingsDialog = function() {
        var _this = this;
        if ( $(".settingsdialog").length===0 ) {
            $('body').append($('<div></div>')
                                .addClass('settingsdialog')
                                .attr("style","display: none;")
                                .mouseleave(function() {_this.hideSettingsDialog();}));
        }
        $(".settingsdialog").empty();
        var dialog = new app.cls.dialog();
        dialog.setDialog('settings','settings');
        dialog.setReturnTo($('.settingsdialog'));
        dialog.request();
    } ;
    
    this.toggleSettingsDialog = function() {
        if (this._settingsShown === true) {
            this.hideSettingsDialog();
        } else {
            this.showSettingsDialog();
        }
    };

    this.showSettingsDialog = function() {

        var pos = $('.button.settings').position();
        var w = $('.button.settings').outerWidth();
        var s = $(".settingsdialog").outerWidth();
        $(".settingsdialog").attr("style","display: block;");
        $(".settingsdialog").css({  position: 'absolute',
                            top: (pos.top-1)+'px', 
                            left:(pos.left+w-s)+'px'});
        this._settingsShown = true;
    };
    
    this.hideSettingsDialog = function() {
        $(".settingsdialog").attr("style","display: none;");
        this._settingsShown = false;
    };
    
    this.togglePasswordDialog = function() {
        if (this._passwordShown === true) {
            this.hidePasswordDialog();
        } else {
            this.showPasswordDialog();
        }
    };
    
    this.showPasswordDialog = function() {
        if ( $(".passwordshadow").length >0) $(".passwordshadow").attr("style","display: block;");
        if ( $(".passworddialog").length >0) {
            $(".passworddialog").attr("style","display: block;");
            $(".passworddialog #oldpasswd").val("");
            $(".passworddialog #newpasswd").val("");
            $(".passworddialog #confirmpasswd").val("");
        }
        app.verifyPassword();
        this._passwordShown = true;
    };
    
    this.hidePasswordDialog = function() {
        if ( $(".passwordshadow").length >0) $(".passwordshadow").attr("style","display: none;");
        if ( $(".passworddialog").length >0) {
            $(".passworddialog").attr("style","display: none;");
            $(".passworddialog #oldpasswd").val("");
            $(".passworddialog #newpasswd").val("");
            $(".passworddialog #confirmpasswd").val("");
        }
        this._passwordShown = false;
    };
    
    this.gotoPage = function(pagenum) {
        if (isNaN(parseInt(pagenum,10))) return false;
        var lIdent = this._cookie.get('current_view')+':limit';
        var limit = { offset: (pagenum-1)*this._cookie.get('settings:showitems'), count: this._cookie.get('settings:showitems') };
        this._cookie.set(lIdent,JSON.stringify(limit));
        this.redrawListItems();
        this.redrawPager();
        return true;
    };
    
    this.nextPage = function() {
        var lIdent = this._cookie.get('current_view')+':limit';
        var limit = { offset: 0, count: this._cookie.get('settings:showitems') };
        if (this._cookie.get(lIdent)!==false) limit = JSON.parse(this._cookie.get(lIdent));
        limit.offset = (parseInt(limit.offset,10)+parseInt(limit.count,10)>this._recordcount?parseInt(limit.offset,10):parseInt(limit.offset,10)+parseInt(limit.count,10));
        this._cookie.set(lIdent,JSON.stringify(limit));
        this.view();
        return true;
    };
    
    this.previousPage = function() {
        var lIdent = this._cookie.get('current_view')+':limit';
        var limit = { offset: 0, count: 20 };
        if (this._cookie.get(lIdent)!==false) limit = JSON.parse(this._cookie.get(lIdent));
        limit.offset = (parseInt(limit.offset,10)-parseInt(limit.count,10)<0?0:parseInt(limit.offset,10)-parseInt(limit.count,10));
        this._cookie.set(lIdent,JSON.stringify(limit));
        this.view();
    };
    
    this.initialize = function() {
        if (this._cookie.get("settings:showitems") === false) this._cookie.set("settings:showitems",20);
        if (this._cookie.get('current_view') === false) this._cookie.set('current_view','dashboard');
        this.view(this._cookie.get('current_view'),true);
    };

    this.initialize();
    this.loadSettingsDialog();
};

app.cls.actionlist = function(data) {
    this._data = {items:[]};
    this.domNode = null;
    if (data!==undefined) this._data = data;
    this._isShown = false;
    this._sm = undefined;
    
    this.redraw = function() {
        var _this = this;
        this.destroy();
        
        this.domNode = $('<div></div>')
                            .addClass('actionlist')
                            .attr("style","display: none;")
                            .mouseleave(function() {_this.hide();})

        for(var i=0; i<this._data.items.length; i++) {
            eval('var fn = function() {_this.execute('+_this._data.items[i].id+'); _this.hide();}');
            this.domNode.append($('<div></div>')
                                    .addClass('item')
                                    .html(this._data.items[i].name)
                                    .click(fn));
        }
        $('body').append(this.domNode);
    };
    
    this.execute = function(id) {
        if (this._sm!==undefined) {
            this._sm.destroy();
            delete(this._sm);
        }
        
        var type = undefined;
        var _this = this;
        for (var i=0; i<this._data.items.length; i++) {
            if (this._data.items[i].id==id) {
                type = this._data.items[i].type;
                break;
            }
        }
        if (type === undefined) return false;
        var itemIds = [];
        $(".view .listitem input[type=checkbox]:checked").each(function(){itemIds.push(parseInt($(this).val(),10));});

        switch (type) {
            case 'single_b':
                if (itemIds.length===0) {
                    this._sm = new app.cls.statusmessage({message:'NO_ITEMS_SELECTED'});
                    this._sm.show();
                    return false;
                }
                this._sm = new app.cls.statusmessage({message:'ACTION_EXECUTED'});
                for(var i=0; i<itemIds.length; i++) {
                    $('body').append($('<iframe></iframe>')
                                        .attr("style","display: none;")
                                        .attr("src","advanced.php?_ts="+(new Date().getTime())+"&id="+id+"&itemIds=["+itemIds[i]+"]")
                                        .load(function() {_this.show(); _this._sm.show(); $(this).remove()})
                                        );
                }
                break;
            case 'multi_b':
                if (itemIds.length===0) {
                    this._sm = new app.cls.statusmessage({message:'NO_ITEMS_SELECTED'});
                    this._sm.show();
                    return false;
                }
                this._sm = new app.cls.statusmessage({message:'ACTION_EXECUTED'});
                $('body').append($('<iframe></iframe>')
                                    .attr("style","display: none;")
                                    .attr("src","advanced.php?_ts="+(new Date().getTime())+"id="+id+"&itemIds="+JSON.stringify(itemIds))
                                    .load(function() { _this.show(); _this._sm.show(); $(this).remove()})
                                    );
                break;
            case 'multi_f':
                $('body').append($('<div></div>')
                                    .addClass('advanced')
                                    .append($('<div></div>')
                                            .addClass('shadow'))
                                    .append($('<div></div>')
                                            .addClass('window')
                                            .append($('<div></div>')
                                                    .addClass('close')
                                                    .click(function() {$('.advanced').remove();}))
                                            .append($('<div></div>')
                                                    .addClass('dialog'))
                                    ));
                $.ajax({
                    url:  'advanced.php?_ts='+(new Date().getTime())+'id='+id+"&itemIds="+JSON.stringify(itemIds)+'&_ts='+(new Date().getTime()),
                    type: 'POST',
                    dataType: 'html',
                    processData: false,
                    success: function(data) { $('.advanced .dialog').html(data) },
                    error: function(data) { console.log(data) }
                });

                break;
            case 'internal':
//            var api = new app.cls.api();
//            api.setAPI('advanced_execute');
//            api.setData({item_id:ids});
//            api.setReturnTo(function(data) {_this.signin_return(data)});
//            api.request();
                break;
        }
    };
    
    this.toggleVisibility = function() {
        if (this._isShown === true) {
            this.hide();
        } else {
            this.show();
        }
    };
    this.show = function() {
        var pos = $('.button.more').position();
        this.domNode.attr("style", "display: block;");
        this.domNode.css({  position: 'absolute',
                            top: (pos.top-1)+'px', 
                            left:pos.left+'px'});
        this._isShown = true;
    };
    
    this.hide = function() {
        this.domNode.attr("style", "display: none;");
        this._isShown = false;
    };
    
    this.destroy = function() {
        if (this.domNode!==null) {
            this.domNode.remove();
            delete this.domNode;
        }
    };
    
    this.initialize = function() {
        this.redraw();
    }
    
    this.initialize();
}

app.cls.item = function() {
    this._cookie = new app.cls.cookie("wingman");
    this._id = -1;
    this._sm = undefined;

    this.show = function(id) {
        if ($('.shade').length >0 || $('.itemview').length >0 ) return false;
        var _this = this;
        $('body').append($('<div></div>')
                            .addClass('shade'));
        $('body').append($('<div></div>')
                            .addClass('itemview')
                            .addClass(this._cookie.get('current_view'))
                            .append($('<div></div>')
                                .addClass('object'))
                            .append($('<div></div>')
                                .addClass('close')
                                .click(function() {_this.hide()}))
                            );
        this._id = id;
        $.fn.spin.presets.custom = {
                lines: 13,
                length: 20,
                width: 10,
                radius: 30,
                corners: 1,
                rotate: 0,
                direction: 1,
                speed: 1,
                trail: 60,
                shadow: true,
                hwaccel: false,
                className: 'spinner',
                zIndex: 2e9
        };
        $('.itemview .object').spin('custom');

        this.reload();
    };
    
    this.reload = function() {
        var d = new app.cls.dialog();
        d.setDialog(this._cookie.get('current_view'),'showitem');
        d.setData('id',this._id);
        d.setReturnTo($('.itemview .object'));
        d.request();  
        
    };
    
    this.save = function() {
        this.apply(true);
    };
    
    this.apply = function(close) {
        if (this._sm!==undefined) {
            this._sm.destroy();
            delete this._sm;
        }
        this._sm = new app.cls.statusmessage({message:'CHANGES_APPLIED'});
        if (typeof(close)!=='boolean') close = false;
        var t =$('.itemview .object .form').find('input,select,textarea');
        var d = {};
        var _this = this;
        for (var i=0; i<t.length;i++) {
            if ($(t[i]).attr('id') !== undefined) {
                d[$(t[i]).attr('id')] = $(t[i]).val();
            }
        }
        t =$('.itemview .object .form').find('input[type=checkbox]');
        for (i=0; i<t.length;i++) {
            if ($(t[i]).attr('id') !== undefined) {
                if ($(t[i]).prop('checked')) {
                    d[$(t[i]).attr('id')] = $(t[i]).val();
                } else {
                    delete d[$(t[i]).attr('id')];
                }
            }
        }
        d = this.parse(d);
        $('.itemview .object').spin('custom');
        var api = new app.cls.api();
        api.setAPI(this._cookie.get('current_view')+'_set');
        api.setData(d);
        api.setReturnTo(function(data){_this._sm.show();});
        if (close === false) api.setReturnTo(function(data){_this._sm.show(); _this._id = data.data.id; _this.reload(); app.control.view(undefined, true)});
        api.request();
        if (close === true) {
            app.control.view(undefined, true);
            this.hide();
        }
    };
    
    this.parse = function( obj ) {
        var nObj  = {};
        for (var key in obj) {
            var k = key.split('.');
            var v = obj[key];
            var tt = nObj;
            for (var i=0; i<k.length;i++) {
                var nKey = k[i];
                var idx = nKey.match(/\[[0-9]*\]$/);
                var nVal = {};
                if (idx !== null) {
                    nKey = nKey.substr(0,nKey.length-idx[0].length);
                    idx = idx[0].substr(1,idx[0].length-2);
                }
                if (i==k.length-1) {
                    nVal = v;
                }
                if ( tt[nKey] === undefined ) {
                    if (idx !== null) {
                        tt[nKey] = [];
                        tt[nKey][idx] = nVal;
                    } else {
                        tt[nKey] = nVal;
                    }
                }
                if (idx !== null) {
                    if (tt[nKey][idx]===undefined) tt[nKey][idx] = nVal;
                    tt = tt[nKey][idx];
                } else {
                    tt = tt[nKey];
                }
            }
        }

        return nObj;
        
    };

    this.hide = function() {
        $('.itemview').remove();
        $('.shade').remove();
    };
    
    this.delete = function(id) {
        var api = new app.cls.api();
        api.setAPI(this._cookie.get('current_view')+'_rm');
        api.setData({id:id});
        if (close === false) api.setReturnTo(function(data){app.control.view()});
        api.request();
    };
};

app.cls.checkbox = function(data) {
    this.domNode = null;
    this._id = '';
    this._event = function() {};
    this._checked = false;
    this._value = undefined;
    if (data!==undefined && data.id!==undefined) this._id = data.id;
    if (data!==undefined && data.value!==undefined) this._value = data.value;
    if (data!==undefined && data.checked!==undefined) this._checked = (typeof(data.checked)=='boolean'?data.checked:false);
    if (data!==undefined && typeof(data.event) == "function") this._event = data.event;

    this.redraw = function() {
        var id = '';
        var _this = this;
        if (this._id!=='') {
            id = this._id;
        } else {
            id = this.randomizeID();
        }
            
        this.domNode = $('<div></div>')
                            .click(function(event) {event.stopPropagation();})
                            .addClass('checkbox')
                            .append($('<input />')
                                    .attr('type','checkbox')
                                    .attr('id',id)
                                    .prop('checked',this._checked)
                                    .change(function() { _this._event() }))
                            .append($('<label></label>')
                                    .click(function(event) {event.stopPropagation();})
                                    .attr('for',id));
        if (this._value!==undefined) {
            this.domNode.find('input[type=checkbox]').val(this._value);
        }
    };
    
    this.randomizeID = function() {
        var result = "";
        var random = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        
        for ( var i = 0; i< 32; i++) {
            result += random.charAt(Math.floor(Math.random() * random.length));
            random = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        }
        return result;        
    };
    
    this.initialize = function() {
        this.redraw();
    };
    
    this.initialize();
};

app.cls.locale = function() {
    this.separator = { decimal: ',', thousand: '' };
    
    this.number_format = function(number, decimals) {
        number = (isNaN(parseFloat(number))?0:parseFloat(number));
        
        if (isNaN(parseInt(decimals,10))===false) {
            number = number.toFixed(parseInt(decimals,10));
        }
        return (""+number).replace(/\./,this.separator.decimal);
    };
    
    this.initialize = function() {
        var api = new app.cls.api();
        var _this = this;
        api.setAPI('locale_get');
        api.setReturnTo(function(data){_this.parse_get(data)});
        api.request();
        
    };
    
    this.parse_get = function(data) {
        if (data.separator!==undefined && data.separator.decimal!==undefined) this.separator.decimal = data.separator.decimal;
        if (data.separator!==undefined && data.separator.thousand!==undefined) this.separator.thousand = data.separator.thousand;
    };
    
    this.initialize();
};

app.cls.error = function() {
    this.domNode = null;
    this._isShown = false;
    
    this.redraw = function() {
        var _this = this;
        this.destroy();
        this.domNode = $('<div></div>')
                            .addClass('errordialog')
                            .attr('style','display: none;')
                            .append($('<div></div>')
                                            .addClass('shadow'))
                            .append($('<div></div>')
                                        .addClass('dialog')
//                                        .append($('<div></div>')
//                                            .addClass('close')
//                                            .click(function() {_this.hide()}))
                                        .append($('<div></div>')
                                                    .addClass('message'))
                                        )
        $('body').append(this.domNode);
    };
    
    this.show = function(eid) {
        if (this._isShown === true) return false;
        var d = new app.cls.dialog();
        d.setDialog('error','error');
        d.setData('eid',eid);
        d.setReturnTo($('.errordialog .dialog .message'));
        d.request();
        
        this.domNode.attr('style','display: block;');
        this._isShown = true;
    };
    
    this.hide = function() {
        this.domNode.attr('style','display: none;');
        this._isShown = false;
    };
    
    this.initialize = function() {
        this.redraw();
    };

    this.destroy = function() {
        if (this.domNode!==null) {
            this.domNode.remove();
            delete this.domNode;
        }
    };
    
    this.initialize();
};

app.cls.statusmessage = function(data) {
    this._message = '';
    this.domNode = undefined;
    this._isShown = false;
    this._timeoutID = -1;
    
    if (data===undefined) data = {};
    if (data.message !==undefined) this._message = data.message;
    
    this.destroy = function() {
        if (this.domNode!==undefined) {
            this.domNode.remove();
            delete this.domNode;
        }
    };
    
    this.redraw = function() {
        this.destroy();
        this.domNode = $('<div></div>')
                                .addClass('statusmessage')
                                .attr('style','display: none;')
                                .append($('<div></div>'));
        $('body').append(this.domNode);
        var d = new app.cls.dialog();
        d.setDialog(app.cookie.get('current_view'),'statusmessage');
        d.setData('message',this._message);
        d.setReturnTo($('body .statusmessage div'));
        d.request();        
    };
    
    this.show = function() {
        var _this = this;
        this.domNode.css('display','block;');
        this._isShown = true;
        
        setTimeout(function(){_this.hide(); _this.destroy();},3000);
    };
    
    this.hide = function() {
        this.domNode.attr('style','display: none;');
        this._isShown = false;
    };
    
    this.initialize = function() {
        this.redraw();
    };
    
    this.initialize();
};

app.start = function() {
    app.cookie = new app.cls.cookie('wingman');
    var fn = function() {
        app.error = new app.cls.error();
        app.control = new app.cls.control();
        app.locale = new app.cls.locale();
    };
    app.auth = new app.cls.auth({authenticated:fn});
};

app.signin = function() {
    app.auth.signin($('#username').val(),$('#password').val());
    return false;
};

app.signout = function() {
    app.auth.signout();
    return false;
};

app.changepassword = function() {
    var oldpwd = $('.passworddialog #oldpasswd').val();
    var newpwd = $('.passworddialog #newpasswd').val();
    if ( app.verifypassword() ) {
        app.control.hidePasswordDialog();
        app.auth.setPassword(oldpwd,newpwd);
        return true;
    }
    return false;
};

app.event = {input:{ decimal:{}}};

app.event.input.decimal.change = function(obj) {
    if (obj.find('.whole').length === 0 || obj.find('.fraction').length === 0 || obj.find('.raw') === 0) return false;
    var w = obj.find('.whole').val();
    var f = obj.find('.fraction').val();
    var bw = w;
    var bf = f;
    w = w.replace('.','');
    w = w.replace(/^0*/,'');
    f = f.replace('.','');
    if (w.length > 7) w = w.substr(0,7);
    if (f.length > 3) f = f.substr(0,3);
    if (w.length < 1) w = 0;
    if (f.length < 1) f = 0;
    
    if (bw != w) obj.find('.whole').val(w);
    if (bf != f) obj.find('.fraction').val(f);
    obj.find('.raw').val(w+'.'+f);
    return true;
};

app.entity = { address: {}};

app.entity.address.add = function() {
    var c = 0;
    if ( $('.itemview .object .form .column .address:last').attr('class')!==undefined ) {
        c = $('.itemview .object .form .column .address:last').attr('class').split(" ");
        for (var i=0; i<c.length;i++) {
            if (c[i].substr(0,2)=='id') {
                c = parseInt(c[i].substr(2),10)+1;
                break;
            }
        }
    }
//    var c = $('.itemview .object .form .column .address:last').attr('class').split(" ");
    for (var i=0; i<c.length;i++) {
        if (c[i].substr(0,2)=='id') {
            c = parseInt(c[i].substr(2),10)+1;
            break;
        }
    }
    $('.itemview .object .form .column:last').append($('<div></div>')
                                                        .attr('class','address id'+c));
    var d = new app.cls.dialog();
    d.setDialog(app.cookie.get('current_view'),'add_entity_address');
    d.setData('count',c);
    d.setReturnTo($('.itemview .object .form .column:last .address.id'+c));
    d.request();        
};

app.entity.address.del = function(obj) {
    obj.remove();
};

app.offer = { item: {}};
app.offer.item.add = function() {
    var c = 0;
    if ( $('.itemview .object .form .column:last .list .item:last').attr('class')!==undefined ) {
        c = $('.itemview .object .form .column:last .list .item:last').attr('class').split(" ");
        for (var i=0; i<c.length;i++) {
            if (c[i].substr(0,2)=='id') {
                c = parseInt(c[i].substr(2),10)+1;
                break;
            }
        }
    }

    $('.itemview .object .form .column:last .list').append($('<div></div>')
                                                        .attr('class','item id'+c));
    var d = new app.cls.dialog();
    d.setDialog(app.cookie.get('current_view'),'add_offer_item');
    d.setData('inventory_item_id',$('.itemview .add_item select').val());
    d.setData('count',c);
    d.setReturnTo($('.itemview .object .form .column:last .list .item.id'+c));
    d.load(function() {
        setTimeout(function(){app.offer.item.recalculate()},250);
        });
    d.request();        
};

app.offer.item.del = function(obj) {
    obj.remove();
    app.offer.item.recalculate();
};

app.offer.item.recalculate = function(obj) {
    if (obj !==undefined) {
        var v = obj.find('[id$=volume]').val();
        var p = obj.find('[id$=price]').val();
        var ltot = v*p;
        obj.find('.itemtotal').html(app.locale.number_format(ltot,3));
        obj.find('.rawtotal').html(ltot.toFixed(3));
    }
    var total = 0;
    $('.itemlist .rawtotal').each(function () {
        total += parseFloat($(this).html());
    });
    $('.itemlist .listtotal').html(app.locale.number_format(total,3));
};

app.offer.customerselect = function(id) {
    var d = new app.cls.dialog();
    var e = new app.cls.dialog();
    $('.object #invoice_address_id').empty();
    $('.object #delivery_address_id').empty();
    d.setDialog(app.cookie.get('current_view'),'get_options');
    d.setData('type','address');
    d.setData('entity_id',id);
    d.setReturnTo($('.object #invoice_address_id'));
    d.request();        

    e.setDialog(app.cookie.get('current_view'),'get_options');
    e.setData('type','address');
    e.setData('entity_id',id);
    e.setReturnTo($('.object #delivery_address_id'));
    e.request();        
    
};
app.transaction = { item: {}};

app.transaction.item.recalculate = function(obj) {
    if (obj !==undefined) {
        var v = obj.find('[id$=volume]').val();
        var p = obj.find('.rawprice').html();
        var ltot = v*p;
        obj.find('.itemtotal').html(app.locale.number_format(ltot,3));
        obj.find('.rawtotal').html(ltot.toFixed(3));
    }
    var total = 0;
    var tdt = $('.object .discount_type .value select').val();
    var td = (isNaN(parseFloat($('.object .discount .value .raw').val()))?0:parseFloat($('.object .discount .value .raw').val()));
    $('.itemlist .rawtotal').each(function () {
        total += parseFloat($(this).html());
    });

    if (tdt=="fixed") {
        total -= td;
    } else {
        total -= total/100*td;
    }
    $('.itemlist .listtotal').html(app.locale.number_format(total,3));
};

app.item = {};
app.item.create = function() {
    app.control._item.show(-1);
};

app.item.delete = function() {
    var ids = [];
    $(".view .listitem input[type=checkbox]:checked").each(function(){ids.push($(this).val());});
    app.control.deleteItems(ids);
};

app.goto = {};
app.goto.transaction = function(id) {
    app.control.view('transaction');
    app.control._item.show(id);
};

app.goto.offer = function(id) {
    app.control.view('offer');
    app.control._item.show(id);
};

app.verifypassword = function() {
    var count = 0;
    var opwd = $('.passworddialog #oldpasswd');
    var pwd = $('.passworddialog #newpasswd');
    var cpwd = $('.passworddialog #confirmpasswd');
    if (opwd.val() ==="") {
        count += 1;
    }
    if (pwd.val() ==="" || pwd.val() !== cpwd.val()) {
        pwd.removeClass("right");
        pwd.addClass("wrong");
        cpwd.removeClass("right");
        cpwd.addClass("wrong");
        count += 1;
    } else {
        pwd.addClass("right");
        pwd.removeClass("wrong");
        cpwd.addClass("right");
        cpwd.removeClass("wrong");
    }
    if (pwd.val().length < 8) {
        $('.policy .chars').removeClass("right");
        $('.policy .chars').addClass("wrong");
        count += 1;
    } else {
        $('.policy .chars').addClass("right");
        $('.policy .chars').removeClass("wrong");
    }
    if ( pwd.val().search(/[a-z]/) < 0 || pwd.val().search(/[A-Z]/) < 0) {
        $('.policy .alpha').removeClass("right");
        $('.policy .alpha').addClass("wrong");
        count += 1;
    } else {
        $('.policy .alpha').addClass("right");
        $('.policy .alpha').removeClass("wrong");
    }
    if ( pwd.val().search(/[0-9]/) < 0) {
        $('.policy .numbr').removeClass("right");
        $('.policy .numbr').addClass("wrong");
        count += 1;
    } else {
        $('.policy .numbr').addClass("right");
        $('.policy .numbr').removeClass("wrong");
    }
    if ( pwd.val().search(/[\!\@\#\$\%\&\*\(\)\-\_\+\[\]\:\;\.\,\<\>\/\?\~]/) < 0) {
        $('.policy .symbl').removeClass("right");
        $('.policy .symbl').addClass("wrong");
        count += 1;
    } else {
        $('.policy .symbl').addClass("right");
        $('.policy .symbl').removeClass("wrong");
    }
    
    if (count > 0) {
        $('.passworddialog .buttons .button.apply').addClass('disabled');
    } else {
        $('.passworddialog .buttons .button.apply').removeClass('disabled');
    }
    return count === 0;
};

$( document ).ready( function() {
    $("form").submit(function(e){e.preventDefault(e);});    
    app.start();

    } );