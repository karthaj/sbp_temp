// refer shopbox js for vue components
 
$(document).ready(function() {
  form.init();
  form.redactor();
  form.initCategory();
});

var form = (function() {
  var parentElem = $('#listOfvalues');
  var formFeature = $('#featureForm');
  var productType = $('#product_type');
  var master_qty = $("#master_qty");
  return {
    'init': function() {
      $('.autonumeric').autoNumeric('init');
      $('#datepicker-component, #available_date, #special_start_date, #special_end_date').datepicker({
        startDate: new Date(),
        format: 'yyyy-mm-dd',
        toggleActive: true
      });
      $('#timepicker, #special_start_time, #special_end_time').timepicker({
        minuteStep: 1,
        secondStep: 1
      }).on('show.timepicker', function(e) {
        var widget = $('.bootstrap-timepicker-widget');
        widget.find('.glyphicon-chevron-up').removeClass().addClass('pg-arrow_maximize');
        widget.find('.glyphicon-chevron-down').removeClass().addClass('pg-arrow_minimize');
      });
      $(document).on('keyup keydown keypress input', '#title', function(e){  
         var name = $("#title").val().toLowerCase();
         $("#url_handle").val(name.trim().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-'));
         $("#meta_title").val($("#title").val());
      });
      $('#short_description').on('summernote.change', function(we, contents, $editable) {
         $("#meta_description").val(jQuery(contents).text().substr(0, 160));
      });

      $(document).on('change', '#product_type', function(e){  
         switch($(this).val())
         {
            case 'variant':
              $('a[data-target="#productFile"]').closest('li').addClass('hide');
              $('a[data-target="#productVariant"]').closest('li').removeClass('hide');
              $("#shipping-header").removeClass('hide');
              $("#shipping-divider").removeClass('hide');
              $("#weight, #width, #height, #depth").closest('.column-seperation').removeClass('hide');
              break;
            case 'virtual':
              $('a[data-target="#productFile"]').closest('li').removeClass('hide');
              $('a[data-target="#productVariant"]').closest('li').addClass('hide');
              $("#shipping-header").addClass('hide');
              $("#shipping-divider").addClass('hide');
              $("#weight, #width, #height, #depth").closest('.column-seperation').addClass('hide');
              break;
            default:
              $('a[data-target="#productFile"]').closest('li').addClass('hide');
              $('a[data-target="#productVariant"]').closest('li').addClass('hide');
              $("#shipping-header").removeClass('hide');
              $("#shipping-divider").removeClass('hide');
              $("#weight, #width, #height, #depth").closest('.column-seperation').removeClass('hide');
              break;
         }
      });
  
      $("#related_products, #variation").select2();
      $("#brand").select2();
    
      $("#formCategory").validate({
        messages: {
          parent_category: "Please choose a parent category.",
        },
        submitHandler: function(form) {
          axios.post($(form).attr('data-url'), 
            $(form).serialize()
          ).then((response) => {
            $("#formCategory").closest(".alert").remove();
            $("#categoryTreeNew").dynatree("getTree").reload();
            $("#categoryTree").dynatree("getTree").reload();
            $("#createNewCategory").modal('hide');
          }).catch((error) => {
            if(error.response) {
              var errors = error.response.data;
              var alert = '<div class="alert alert-danger" role="alert">'
                     
              $.each(errors, function( index, value ) {
                alert += '<p>'+value+'</p>'
              });
                alert += '</div>'
              $(alert).insertBefore("#formCategory")
              }
            
          })
        }
      })

      $('#formCategory').submit(function(e){
        e.preventDefault()
      })

      $('#createNewCategory').on('hide.bs.modal', function (e) {
        $("#formCategory").closest(".alert").remove();
        $("#formCategory").find('#name').val('');
      })


      $("#formBrand").validate({
        messages: {
          name: "Name is required.",
        },
        submitHandler: function(form) {
          axios.post($(form).attr('data-url'), 
            $(form).serialize()
          ).then((response) => {
            $("#formBrand").closest(".alert").remove();
            $("#brand").html(response.data.brands);
            $("#createNewBrand").modal('hide');
         
          }).catch((error) => {
            if(error.response) {
              var errors = error.response.data;
              var alert = '<div class="alert alert-danger" role="alert">'
                     
              $.each(errors, function( index, value ) {
                alert += '<p>'+value+'</p>'
              });
                alert += '</div>'
              $(alert).insertBefore("#formBrand")
              }
            
          })
        }
      })

      $('#formBrand').submit(function(e){
        e.preventDefault()
      })

      $('#formCategory').submit(function(e){
        e.preventDefault()
      })

      $('#createNewBrand').on('hide.bs.modal', function (e) {
        $("#formBrand").closest(".alert").remove();
        $("#formBrand").find('#name').val('');
      })

      $("#formTaxClass").validate({
        messages: {
          name: "Name is required.",
        },
        submitHandler: function(form) {
          axios.post($(form).attr('data-url'), 
            $(form).serialize()
          ).then((response) => {
            $("#formTaxClass").closest(".alert").remove();
            $("#tax_class").html(response.data.classes);
            $("#createNewTaxClass").modal('hide');
         
          }).catch((error) => {
            if(error.response) {
              var errors = error.response.data;
              var alert = '<div class="alert alert-danger" role="alert">'
                     
              $.each(errors, function( index, value ) {
                alert += '<p>'+value+'</p>'
              });
                alert += '</div>'
              $(alert).insertBefore("#formTaxClass")
              }
            
          })
        }
      })

      $('#formTaxClass').submit(function(e){
        e.preventDefault()
      })

      $('#createNewTaxClass').on('hide.bs.modal', function (e) {
        $("#formTaxClass").closest(".alert").remove();
        $("#formTaxClass").find('#name').val('');
      })

      $("#formShippingClass").validate({
        messages: {
          name: "Name is required.",
        },
        submitHandler: function(form) {
          axios.post($(form).attr('data-url'), 
            $(form).serialize()
          ).then((response) => {
            $("#formShippingClass").closest(".alert").remove();
            $("#shipping_class").html(response.data.classes);
            $("#createShippingClass").modal('hide');
         
          }).catch((error) => {
            if(error.response) {
              var errors = error.response.data;
              var alert = '<div class="alert alert-danger" role="alert">'
                     
              $.each(errors, function( index, value ) {
                alert += '<p>'+value+'</p>'
              });
                alert += '</div>'
              $(alert).insertBefore("#formShippingClass")
              }
            
          })
        }
      })
      $('#formShippingClass').submit(function(e){
        e.preventDefault()
      })

      $(document).on('keyup keydown keypress input', '#special_price', function(e){
        var special_price = $(this).val().split(" ");
        if(special_price[1].length > 0 && special_price[1] != 0 && special_price[1] != '') {
          $("#special_start_date, #special_start_time, #special_end_date, #special_end_time").removeAttr('disabled');
        } else {
          $("#special_start_date, #special_start_time, #special_end_date, #special_end_time").attr('disabled', 'disabled');
        }
      })

      $(document).on('change', '[name="product_availability"]', function(e){  
        if($(this).val() === 'preorder') {
          $("#available_date").closest('.row').removeClass('hide');
        } else {
          $("#available_date").closest('.row').addClass('hide');
        }
      })

      $(document).on('click', '#delete_product_file', function(e){  
        e.preventDefault();
        var url = $(this).attr('href');
  
        axios.delete(url)
        .then((response) => { 
            $("#fileInfo").remove();
            $('.page-content-wrapper').pgNotification({
                style: 'simple',
                message: "File deleted successfully.",
                position: 'top-right',
                timeout: 5000,
                type: "success"
            }).show();

            var elem = `<div class="col-sm-11 form-group">
                          <label for="product_file">Upload a File</label>
                          <input type="file" name="product_file" id="product_file" class="form-control">
                       </div>`;
            $(this).closest('.row').append(elem);
            $(this).closest('.form-group').remove();

        }).catch((error) => { 
          $('.page-content-wrapper').pgNotification({
              style: 'simple',
              message: "Something went wrong!",
              position: 'top-right',
              timeout: 5000,
              type: "danger"
          }).show();
        });

      })
      
    },
    'redactor' : function () {
      $("#description").redactor({
        plugins: ['table', 'alignment', 'fontcolor', 'fontsize', 'imagemanager', 'inlinestyle', 'video', 'widget'],
        imageUpload: '/merchant/image/upload',
        imageManagerJson: '/merchant/images.json',
        imageResizable: true,
        imagePosition: true,
        callbacks: {
          image: {
              uploadError: function(response)
              {
                  console.log(response.message);
              }
          }
        }
      });

      $("#short_description").redactor({
        plugins: ['table', 'alignment', 'fontcolor', 'fontsize', 'imagemanager', 'inlinestyle', 'video', 'widget'],
        imageUpload: '/merchant/image/upload',
        imageManagerJson: '/merchant/images.json',
        imageResizable: true,
        imagePosition: true,
        callbacks: {
          image: {
              uploadError: function(response)
              {
                  console.log(response.message);
              }
          }
        }
      });
      
    },
    'initCategory': function() {
      var product = $("#categoryTree").attr('data-id');
      $("#categoryTree").dynatree({
        initAjax: {
          url: "/merchant/product/categories/"+product+"/tree",
        },
        debugLevel: 0,
        checkbox: true,
        onSelect: function(flag, node) {
            var selectedNodes = node.tree.getSelectedNodes();
            var selectedKeys = $.map(selectedNodes, function(node){
                return node.data.key;
            });
            $("#category").val(selectedKeys.join(", "));
        },
        onClick: function(node, event) {
          // We should not toggle, if target was "checkbox", because this
          // would result in double-toggle (i.e. no toggle)
          if( node.getEventTargetType(event) == "title" )
            node.toggleSelect();
        }
      });
      $("#categoryTreeNew").dynatree({
        initAjax: {
          url: "/merchant/product/categories/tree",
        },
        debugLevel: 0,
        selectMode: 1,
        onActivate: function(node) {
            $('#parent_category').val(node.data.key);
        }
      });
    }
  };
})();

/**
 * Feature collection management
 */
window.featuresCollection = (function() {

  var collectionHolder = $('.feature-collection');

  /** Add a feature */
  function add() {
    var newForm = collectionHolder.attr('data-prototype').replace(/__name__/g, collectionHolder.children('.row').length);
    collectionHolder.append(newForm);
    //prestaShopUiKit.initSelects();
  }

  return {
    'init': function() { 
      
      /** Click event on the add button */
      $('#features .add').on('click', function(e) { 
        
        e.preventDefault();
        add();
        $('#features-content').removeClass('hide');
      });

      /** Click event on the remove button */
      $(document).on('click', '.feature-collection .delete', function(e) {
        e.preventDefault();
        var _this = $(this);
        _this.parent().parent().remove();
        /*modalConfirmation.create(translate_javascripts['Are you sure to delete this?'], null, {
          onContinue: function() {
            _this.parent().parent().parent().remove();
          }
        }).show();*/
      });

      /** On feature selector event change, refresh possible values list */
      $(document).on('change', '.feature-collection select.feature-selector', function(event) {
        var that = event.currentTarget;
        var $row = $($(that).parents('.row')[0]);
        var $selector = $row.find('.feature-value-selector');

        if('' !== $(this).val()) {
          $.ajax({
            url: $(this).attr('data-action').replace(/\/\d+(?=\?.*)/, '/' + $(this).val()),
            success: function(response) {
              $selector.prop('disabled', response.length === 0);
              $selector.empty();
              $.each(response, function(index, elt) {
                // the placeholder shouldn't be posted.
                if ('0' == elt.id) {
                  elt.id = '';
                }
                $selector.append($('<option></option>').attr('value', elt.id).text(elt.value));
              });
            }
          });
        }
      });

      var $featuresContainer = $('#features-content');

      $featuresContainer.on('change', '.row select, .row input[type="text"]', function onChange(event){
        var that = event.currentTarget;
        var $row = $($(that).parents('.row')[0]);
        var $definedValueSelector = $row.find('.feature-value-selector');
        var $customValueSelector = $row.find('input[type=text]');

        // if feature has changed we need to reset values
        if ($(that).hasClass('feature-selector')) {
          $customValueSelector.val('');
          $definedValueSelector.val('');
        }
      });
    }
  };
})();

  /**
 * images product management
 */
window.imagesProduct = (function() {
  var dropZoneElem = $('#product-images-dropzone');
  var expanderElem = $('#product-images-container .dropzone-expander');

  function checkDropzoneMode() {
      if (!$('.dz-preview:not(.openfilemanager)').length) {
        $('#product-images-dropzone').removeClass('dz-started');
        $('#product-images-dropzone').find('.dz-preview.openfilemanager').hide();
      }
      else {
          $('#product-images-dropzone').find('.dz-preview.openfilemanager').show();
      }
  };

  return {
    'toggleExpand': function() {
        if ($('#product-images-container .dropzone-expander').hasClass('expand')) {
          $('#product-images-dropzone').css('height', 'auto');
          $('#product-images-container .dropzone-expander').removeClass('expand').addClass('compress');
        } else {
          $('#product-images-dropzone').css('height', '');
          $('#product-images-container .dropzone-expander').removeClass('compress').addClass('expand');
        }
    },
    'displayExpander': function() {
      $('#product-images-container .dropzone-expander').show();
    },
    'hideExpander': function() {
      $('#product-images-container .dropzone-expander').hide();
    },
    'shouldDisplayExpander': function () {
      var oldHeight = dropZoneElem.css('height');
      dropZoneElem.css('height', '');
      var closedHeight = $('#product-images-dropzone').outerHeight();
      var realHeight = dropZoneElem[0].scrollHeight;
      dropZoneElem.css('height', oldHeight);
      return (realHeight > closedHeight);
    },
    'updateExpander': function() {
      if (this.shouldDisplayExpander()) {
        this.displayExpander();
      }
    },
    'initExpander': function() {
      if (this.shouldDisplayExpander()) {
        this.displayExpander();
        $('#product-images-container .dropzone-expander').addClass('expand');
      }

      var self = this;
      $(document).on('click', '#product-images-container .dropzone-expander', function() { 
        self.toggleExpand();
      });
    },
    'init': function() { 
      Dropzone.autoDiscover = false;
      var errorElem = $('#product-images-dropzone-error');

      //on click image, display custom form
      $(document).on('click', '#product-images-dropzone .dz-preview', function() {
        if (!$(this).attr('data-id')) {
          return;
        }
        formImagesProduct.form($(this).attr('data-id'));
      });

      var dropzoneOptions = {
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: dropZoneElem.attr('url-upload'),
        maxFilesize: dropZoneElem.attr('data-max-size'),
        addRemoveLinks: true,
        clickable: '.openfilemanager',
        thumbnailWidth: 250,
        thumbnailHeight: null,
        acceptedFiles: 'image/*',
        dictRemoveFile: 'Delete',
        dictFileTooBig: 'file too large',
        dictCancelUpload: 'Cancel',
        sending: function(file, response) {
          checkDropzoneMode();
          expanderElem.addClass('expand').click();
          errorElem.html('');
        },
        queuecomplete: function() {
          checkDropzoneMode();
          dropZoneElem.sortable('enable');
          imagesProduct.updateExpander();
        },
        processing: function() {
          dropZoneElem.sortable('disable');
        },
        success: function(file, response) {
          //manage error on uploaded file
          if (response.error !== 0) {
            errorElem.append('<p>' + file.name + ': ' + response.error + '</p>');
            this.removeFile(file);
            return;
          }

          //define id image to file preview
          $(file.previewElement).attr('data-id', response.id);
          $(file.previewElement).attr('url-update', response.url_update);
          $(file.previewElement).attr('url-delete', response.url_delete);
          $(file.previewElement).addClass('ui-sortable-handle');
          if (response.cover === 1) { 
            imagesProduct.updateDisplayCover(response.id);
          }
        },
        error: function(file, response) {
          var message = '';
          if ($.type(response) === 'undefined') {
            return;
          } else if ($.type(response) === 'string') {
            message = response;
          } else if (response.message) {
            message = response.message;
          }

          if (message === '') {
            return;
          }

          //append new error
          errorElem.append('<p>' + file.name + ': ' + message + '</p>');

          //remove uploaded item
          this.removeFile(file);
        },
        init: function() {
          
          //if already images uploaded, mask drop file message
          if ($('.dz-preview:not(.openfilemanager)').length) {
            $('#product-images-dropzone').addClass('dz-started');
          } else {
            $('#product-images-dropzone').find('.dz-preview.openfilemanager').hide();
          }

          //init sortable
          $('#product-images-dropzone').sortable({
            items: "div.dz-preview:not(.disabled)",
            opacity: 0.9,
            containment: 'parent',
            distance: 32,
            tolerance: 'pointer',
            cursorAt: {
              left: 64,
              top: 64
            },
            cancel: '.disabled',
            stop: function(event, ui) {
              var sort = {};
              $.each(dropZoneElem.find('.dz-preview:not(.disabled)'), function(index, value) {
                if (!$(value).attr('data-id')) {
                  sort = false;
                  return;
                }
                sort[$(value).attr('data-id')] = index + 1;
              });
              
              //if sortable ok, update it
              if (sort) {
                axios({
                  method: 'patch',
                  url: dropZoneElem.attr('url-sort'),
                  headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  },
                  data: {
                    json: JSON.stringify(sort)
                  }
                });
              }
            },
            start: function(event, ui) {
              //init zindex
              dropZoneElem.find('.dz-preview').css('zIndex', 1);
              ui.item.css('zIndex', 10);
            }
          });

          dropZoneElem.disableSelection();
        }
      };

      $('#product-images-dropzone').dropzone(jQuery.extend(dropzoneOptions));
    },
    'updateDisplayCover': function(id_image) {
      $('#product-images-dropzone .dz-preview .iscover').remove();
      $('#product-images-dropzone .dz-preview[data-id="' + id_image + '"]')
        .append('<div class="iscover">Cover</div>');
    },
    'checkDropzoneMode': function() {
      checkDropzoneMode();
    },
    'getOlderImageId': function() {
      return Math.min.apply(Math,$('.dz-preview').map(function(){
        return $(this).data('id');
      }));
    }
  };
})();


window.formImagesProduct = (function() {
  var dropZoneElem = $('#product-images-dropzone');

  function toggleColDropzone(enlarge) {
      var smallCol = "col-md-8";
      var largeCol = "col-md-12";
      if (true === enlarge) {
          dropZoneElem.removeClass(smallCol).addClass(largeCol);
      } else {
          dropZoneElem.removeClass(largeCol).addClass(smallCol);
      }
  }

  return {
    'form': function(id) {
      $('#product-images-dropzone').find(".dz-preview.active").removeClass("active");
      $('#product-images-dropzone').find(".dz-preview[data-id='"+id+"']").addClass("active");

      $.ajax({
        url: $('#product-images-dropzone').find(".dz-preview[data-id='"+id+"']").attr('url-update'),
        success: function(response) { 
          $('#product-images-form-container').html(response.data);
        },
        complete: function() {
          $('#imageModal').modal('show')
        }
      });
    },
    'send': function(id) {
      $.ajax({
        type: 'POST',
        url: $('#product-images-dropzone').find(".dz-preview[data-id='"+id+"']").attr('url-update'),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: {
            _method: 'PATCH',
            alt_text: $('#product-images-form-container').find('#alt_text').val(),
            cover: $('#product-images-form-container').find('#form_image_cover').val()
        },
        beforeSend: function() {    
          $('#product-images-form-container').find('.actions button').prop('disabled', 'disabled');
          $('#product-images-form-container').find('ul.text-danger').remove();
          $('#product-images-form-container').find('*.has-danger').removeClass('has-danger');
        },
        success: function() {
          if ($('#product-images-form-container').find('#form_image_cover:checked').length) {
            imagesProduct.updateDisplayCover(id);
          }
        },
        error: function(response) {
          if (response && response.responseText) {
            $.each(jQuery.parseJSON(response.responseText), function(key, errors) {
              var html = '<ul class="list-unstyled text-danger">';
              $.each(errors, function(key, error) {
                html += '<li>' + error + '</li>';
              });
              html += '</ul>';

              $('#form_image_' + key).parent().append(html);
              $('#form_image_' + key).parent().addClass('has-danger');
            });
          }
        },
        complete: function() {
          $('#product-images-form-container').find('.actions button').removeAttr('disabled');
          $('#imageModal').modal('hide')
        }
      });
    },
    'delete': function(id) {
          $.ajax({
            type: 'POST',
            url: $('#product-images-dropzone').find('.dz-preview[data-id="' + id + '"]').attr('url-delete'),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                _method: 'DELETE'
            },
            complete: function() {
              $('#imageModal').modal('hide')
              var wasCover = !!dropZoneElem.find('.dz-preview[data-id="' + id + '"] .iscover').length;
              $('#product-images-dropzone').find('.dz-preview[data-id="' + id + '"]').remove();
              //$('.images .product-combination-image [value=' + id + ']').parent().remove();
              imagesProduct.checkDropzoneMode();
              if (true === wasCover) {
                // The controller will choose the oldest image as the new cover.
                imagesProduct.updateDisplayCover(imagesProduct.getOlderImageId());
              }
            }
          });
    },
    'close': function() {
      toggleColDropzone(true);
      dropZoneElem.css('height','');
      $('#product-images-form-container').find('#product-images-form').html('');
      $('#product-images-form-container').hide();
      dropZoneElem.find(".dz-preview.active").removeClass("active");
    }
  };
})();


