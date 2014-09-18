<?php
defined("__WINGMAN__") or exit("Chucks! this ain't working!");

/**
 * @package     wingman
 * @author      William Leemans <willie@elaba.net>
 * @version     5
 * 
 * @copyright   Copyright (C) 2006 - 2014 Critter BVBA All rights reserved.
 * @license     GNU General Public License version 3 or later; see license.txt
 * 
 * import_from_csv.php
 * 
 * import inventory from csv
 * 
 */
 
 // enable or disable logging
error_reporting(E_ALL);
ini_set('display_errors', '1');  // 0 - disabled; 1 - enabled
//
include_once(FS_LANGUAGE.APP_LANGUAGE."/default.php");

$sql = "SELECT `id`,`name` FROM `##_".$_SESSION['space']."_inventory_item_type` WHERE `id`>0 AND `deleted`='0';";
$DBO->query($sql);
$item_type = $DBO->result("objectlist");

$sql = "SELECT `id`,`name` FROM `##_".$_SESSION['space']."_vat` WHERE `id`>0 AND `deleted`='0';";
$DBO->query($sql);
$vat = $DBO->result("objectlist");

$sql = "SELECT `id`,`name` FROM `##_".$_SESSION['space']."_currency` WHERE `id`>0 AND `deleted`='0';";
$DBO->query($sql);
$currency = $DBO->result("objectlist");

?>
<style>
.advanced .window { width: 900px; }
.advanced .window .result { width: 100%; overflow: hidden; }
.advanced .window .result .table { width: 100%; overflow-x: hidden; overflow-y:auto; height: 200px; }
.advanced .window .result .table .row { width: 900px; }
.advanced .window .result .table .row.header { font-weight: bold }
.advanced .window .result .table .row .cell { width: 100px; padding: 1px; }
.advanced .window .input-text { width: 40px; }
</style>
<div class="table">
    <div class="row">
        <div class="cell"><?php echo ucfirst(ADVANCED_DELIMITER); ?></div>
        <div class="cell"><input type="text" class="input-text" value=";" id="separator" /></div>
        <div class="cell"><?php echo ucfirst(ADVANCED_TEXT_DELIMITER); ?></div>
        <div class="cell"><input type="text" class="input-text" value='"' id="delimiter" /></div>
    </div>
            
    <div class="row">
        <div class="cell"><?php echo ucfirst(ADVANCED_SELECT_FILE); ?></div>
        <div class="cell"><input type="file" id="filereader" /></div>
    </div>
</div>
<div class="result"></div>
<div class="buttons"></div>
<script>
$(function() {
//    $('.advanced .window').attr("style","width: 900px");
    $('#filereader').change(function() {
        if (window.File && window.FileReader && window.FileList && window.Blob) {
            reader = new FileReader();
        } else {
            alert('The File APIs are not fully supported by your browser. Fallback required.');
            return false;
        }
        var oFile = $(this)[0];
        if(oFile.files && oFile.files[0]) {           
            reader.onload = function (e) {
                var header = function(hdr,delimiter,separator) {
                    this._data = [];
                    this._delimiter = "";
                    this._separator = ",";
                    if (delimiter!==undefined) this._delimiter = delimiter;
                    if (separator!==undefined) this._separator = separator;

                    this.stringToArray = function(value) {
                        if (value.substr(0,this._delimiter.length) == this._delimiter) value = value.substr(this._delimiter.length);
                        if (value.substr(this._delimiter.length*-1) == this._delimiter) value = value.substr(0,value.length-this._delimiter.length);
                        value = value.split(this._delimiter+this._separator+this._delimiter);
                        return value;
                    }
                    
                    this.getRAWdata = function() {
                        return this._data;
                    }
                    
                    this.getdata = function() {
                        var t = [];
                        for (var i=0;i<this._data.length;i++) {
                            if (this._data[i]!=='unknown') t.push(this._data[i]);
                        }
                        return t;
                    }
                    
                    this.parsedata = function(hdr) {
                        this._data = this.stringToArray(hdr);
                        for (var i=0;i<this._data.length;i++) {
                            this._data[i] = this._data[i].toLowerCase();
                            switch (this._data[i]) {
                                case 'sku':
                                case 'name':
                                case 'description':
                                case 'field0':
                                case 'inventory_type':
                                case 'inventory_type_id':
                                case 'indicative_price':
                                case 'indicative_vat':
                                case 'indicative_vat_id':
                                case 'indicative_currency':
                                case 'indicative_currency_id':
                                    // don't really do anything
                                    break;
                                case 'price':
                                    this._data[i] = 'indicative_price';
                                    break;
                                case 'type':
                                    this._data[i] = 'inventory_type';
                                    break;
                                case 'vat':
                                    this._data[i] = 'indicative_vat';
                                    break;
                                case 'currency':
                                    this._data[i] = 'indicative_currency';
                                    break;
                                default:
                                    this._data[i] = 'unknown';
                            }
                        }
                        return this;
                    }
                    
                    return this.parsedata(hdr);
                }
                var record = function(hdr,recordline,delimiter,separator) {
                    this._data = {
                        id: -1,
                        sku: '',
                        name: '',
                        description: '',
                        field0: '',
                        inventory_type: '',
                        inventory_type_id: '',
                        indicative_price: 0,
                        indicative_vat: '',
                        indicative_vat_id: 0,
                        indicative_currency: '',
                        indicative_currency_id: 0
                    };
                    this._header = undefined;
                    this._delimiter = "";
                    this._separator = ",";
                    this._type = <?php echo json_encode($item_type); ?>;
                    this._vat = <?php echo json_encode($vat); ?>;
                    this._currency = <?php echo json_encode($currency); ?>;
                    if (hdr!==undefined) this._header = hdr;
                    if (delimiter!==undefined) this._delimiter = delimiter;
                    if (separator!==undefined) this._separator = separator;
                    
                    this.stringToArray = function(value) {
                        if (value.substr(0,this._delimiter.length) == this._delimiter) value = value.substr(this._delimiter.length);
                        if (value.substr(this._delimiter.length*-1) == this._delimiter) value = value.substr(0,value.length-this._delimiter.length);
                        value = value.split(this._delimiter+this._separator+this._delimiter);
                        return value;
                    }
                    
                    this.getdata = function() {
                        var header = this._header.getdata();
                        var res = [];
                        for(var i=0;i<header.length;i++) {
                            if (this._data[header[i]]!==undefined) res.push(this._data[header[i]]);
                        }
                        return res;
                    }
                    
                    this.getdataObject = function() {
                        return this._data;
                    }
                    
                    this.parsedata = function(record) {
                        if (record===undefined) return false;
                        record = this.stringToArray(record);
                        header = this._header.getRAWdata();
                        if (header.length!==record.length) return false;
                        
                        for(var i=0; i<header.length; i++) {
                            if ( this._data[header[i]]===undefined) continue;
                            switch (header[i]) {
                                case "inventory_type":
                                    this._data.inventory_type_id = this.match(this._type,record[i]);
                                    if (this._data.inventory_type_id===-1) record[i]='unknown';
                                    break;
                                case "indicative_vat":
                                    this._data.indicative_vat_id = this.match(this._vat,record[i]);
                                    if (this._data.indicative_vat_id===-1) record[i]='unknown';
                                    break;
                                case "indicative_currency":
                                    this._data.indicative_currency_id = this.match(this._currency,record[i]);
                                    if (this._data.indicative_currency_id===-1) record[i]='unknown';
                                    break;
                            }
                            this._data[header[i]] = record[i];
                        }
                    }
                    
                    this.match = function(haystack,needle) {
                        for (var i=0;i<haystack.length;i++) {
                            if (haystack[i].name.toLowerCase()==needle.toLowerCase()) return haystack[i].id;
                        }
                        return -1
                    }

                    
                    if (this._header!==undefined && recordline!==undefined) this.parsedata(recordline);
                    return this;
                }

                var t = e.target.result.split('\n');
                var del = $('#delimiter').val();
                var sep = $('#separator').val();
                var hdr = new header(t[0],del,sep);
                var rec = [];
                for (var i=1; i<t.length;i++) {
                    var tr = new record(hdr,t[i],del,sep)
                    rec.push(tr);
                }
                $(".result").empty();
                $(".result").append($('<div></div>')
                                        .addClass('table')
                                        .append($('<div></div>')
                                                .addClass('row')
                                                .addClass('header')))
                th = hdr.getdata();
                for (var i=0;i<th.length;i++) {
                    $('.result .table .row.header').append($('<div></div>')
                                                            .addClass('cell')
                                                            .html(th[i]))
                }
                for (var i=0;i<rec.length;i++) {
                    $('.result .table').append($('<div></div>')
                                                .addClass('row'))
                    tr = rec[i].getdata();
                    for (var j=0;j<tr.length;j++) {
                        $('.result .table .row:last').append($('<div></div>')
                                                                .addClass('cell')
                                                                .html(tr[j]))
                    }
                }
                var tfn = function() {
                    for (var i=0;i<rec.length;i++) {
                        var api = new app.cls.api();
                        api.setAPI('inventory_set');
                        api.setData(rec[i].getdataObject());
                        api.request();
                    }
                    app.control.view(undefined, true);
                    $('.advanced').remove();
                
                }
                $('.advanced .window .buttons').empty();
                $('.advanced .window .buttons').append($('<div></div>')
                                                            .addClass('button')
                                                            .html('<?php echo ucfirst(SAVE); ?>')
                                                            .click(function() { tfn(); }))
            };
            reader.readAsText(oFile.files[0]);
        }
    })

})
</script>