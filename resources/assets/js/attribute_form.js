// refer shopbox js for vue components

$(document).ready(function() {
    //attributes.initColorpicker('swatch_color_0', 'swatch_preview_0');
    attributes.initColorpicker();
});

attributes = (function() {
    return {
        'initColorpicker' : function () {
            $(".colorpicker-component").colorpicker()
            .on('colorpickerChange', function (e) {
                if(e.color === false) {
                    $(".colorpicker-component").colorpicker('setValue', '#000000');
                }
            });
       },
    };
})()

$(document).on('change', '#display_type', function(e){ 
    var value = $("#display_type").val();
    if(value == 'multiple choice') {
        $(".multiple-option").removeClass('hide');
        $(".text-rules, .date-restriction, .swatch").addClass('hide');
        
    } else if(value == 'swatch') {
        $(".swatch").removeClass('hide');
        $(".multiple-option, .date-restriction, .text-rules").addClass('hide');
    }
});
var i = 0;
$(document).on('click', '.row-add', function(e){ 
    e.preventDefault();
    addRow($("#sortable li").length);
});

$(document).on('click', '.row-add-swatch', function(e){ 
    e.preventDefault();
    addRowSwatch($("#swatchConfig .swatch-item").length);
});

$(document).on('click', '.row-del-swatch', function(e){
    e.preventDefault();
    if($('#swatchConfig .swatch-item').length == 1) {
        $(this).hide()
    } else {
        $(this).closest('.align-items-center').remove();
    }
});

$(document).on('click', '.row-del', function(e){
    e.preventDefault();
    if($('#sortable li').length == 1) {
        $(".row-del").hide()
    } else {
        $(this).closest('li').remove();
    }
});

$(document).on('change', '.swatch-type', function(e){
    e.preventDefault();
    var index = $("#swatchConfig .swatch-item").length - 1;
    if(this.value == 'pattern') { 
       $(this).closest("div").next('.colorpicker-component').remove();
       $(this).closest("div").next("span").remove();
       var elm = '<div class="col-sm-4 swatch-pattern">'+
                    '<input type="file" name="swatch['+index+'][pattern]">'+
                 '</div>'+
                 '<span class="col-sm-2">'+
                    '<a href="javascript:;" class="row-add-swatch px-1" title=""><i class="pg-plus_circle"></i></a>'+
                    '<a href="javascript:;" class="row-del-swatch px-1" title=""><i class="pg-minus_circle"></i></a>'+
                 '</span>';
        $(this).closest('.align-items-center').append(elm);

    } else if(this.value == 'color') {
        $(this).closest("div").next(".swatch-pattern").remove();
        $(this).closest("div").next("span").remove();
        var elm = '<div class="col-sm-4 input-group colorpicker-component" data-color="#000000">'+
                    '<input type="text" class="form-control color-picker" name="swatch['+index+'][color]">'+
                    '<span class="input-group-addon">'+
                        '<i></i>'+
                    '</span>'+
                   '</div>'+
                 '<span class="col-sm-2">'+
                    '<a href="javascript:;" class="row-add-swatch px-1" title=""><i class="pg-plus_circle"></i></a>'+
                    '<a href="javascript:;" class="row-del-swatch px-1" title=""><i class="pg-minus_circle"></i></a>'+
                 '</span>';
        $(this).closest('.align-items-center').append(elm);
        attributes.initColorpicker()
    }
   
});

function addRow (index) {
    var row  =  '<li class="ui-state-default mb-3 col-sm-12 px-0">'+
                    '<div class="DraggableHolder"></div>'+
                    '<input type="text" class="draggable-input" name="value['+index+'][label]">'+
                    '<span>'+
                    '<a href="javascript:;" class="row-add  px-1" title=""><i class="pg-plus_circle"></i></a>'+                        
                    '<a href="javascript:;" class="row-del px-1"><i class="pg-minus_circle"></i></a>'+                      
                    '</span>'+                       
                '</li>'                           
                                        
    $("#sortable").append(row);
}

function addRowSwatch (index) {
    var row  =  '<div class="row align-items-center mb-2 swatch-item">'+
                    '<div class="DraggableHolder"></div>'+
                    '<div class="col-sm-3 pl-0">'+
                        '<input type="text" name="swatch['+index+'][label]" class="form-control" placeholder="Swatch name">'+
                    '</div>'+
                    '<div class="col-sm-2">'+
                        '<select name="swatch['+index+'][type]" class="form-control swatch-type">'+
                            '<option value="color">Solid</option>'+
                            '<option value="pattern">Pattern</option>'+
                        '</select>'+
                    '</div>'+
                    '<div class="col-sm-4 input-group colorpicker-component" data-color="#000000">'+
                        '<input type="text" class="form-control color-picker" name="swatch['+index+'][color]">'+
                        '<span class="input-group-addon">'+
                            '<i></i>'+
                        '</span>'+
                    '</div>'+
                    '<span class="col-sm-2">'+
                        '<a href="javascript:;" class="row-add-swatch px-1" title=""><i class="pg-plus_circle"></i></a>'+
                        '<a href="javascript:;" class="row-del-swatch px-1" title=""><i class="pg-minus_circle"></i></a>'+
                    '</span>'                           
                '</div>';                           
                                        
    $("#swatchConfig").append(row);
    attributes.initColorpicker();
}
