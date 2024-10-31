
window.Shopbox = window.Shopbox || {};
window._ = require('lodash');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.Vue = require('vue');
window.introJs = require('intro.js');
import Vuex from 'vuex'
import queryString from 'query-string'

Vue.use(Vuex)

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} 

Vue.component('user-table', require('./components/users/Index.vue'));
Vue.component('product-table', require('./components/products/Index.vue'));
Vue.component('variation-table', require('./components/products/Variation.vue'));
Vue.component('order-table', require('./components/orders/OrderDatatable.vue'));
Vue.component('payout-table', require('./components/payouts/Index.vue'));
Vue.component('return-table', require('./components/orders/ReturnDatatable.vue'));
Vue.component('add-return', require('./components/orders/Return.vue'));
Vue.component('cart-table', require('./components/carts/CartDatatable.vue'));
Vue.component('category-table', require('./components/categories/Index.vue'));
Vue.component('category-list', require('./components/categories/CategoryList.vue'));
Vue.component('attribute-table', require('./components/attributes/Index.vue'));
Vue.component('brand-table', require('./components/brands/Index.vue'));
Vue.component('customer-table', require('./components/customers/CustomerDatatable.vue'));
Vue.component('group-table', require('./components/customers/GroupDatatable.vue'));
Vue.component('discount-table', require('./components/discounts/DiscountDatatable.vue'));
Vue.component('page-table', require('./components/pages/PageDatatable.vue'));
Vue.component('shipping-zone-table', require('./components/shippings/ZoneDatatable.vue'));
Vue.component('shipping-class-table', require('./components/shippings/ShippingClassDatatable.vue'));
Vue.component('zone-table', require('./components/taxes/Zone.vue'));
Vue.component('taxclass-table', require('./components/taxes/Class.vue'));
Vue.component('taxrate-table', require('./components/taxes/TaxDatatable.vue'));
Vue.component('store-table', require('./components/stores/StoreDatatable.vue'));
Vue.component('cod-table', require('./components/cod/CashOnDeliveryDatatable.vue'));
Vue.component('stock-table', require('./components/stocks/StockDatatable.vue'));
Vue.component('stock-management', require('./components/stocks/Stock.vue'));
Vue.component('transfer', require('./components/stocks/StoreTransfer.vue'));
Vue.component('transfer-stock', require('./components/stocks/TransferStock.vue'));
Vue.component('request-stock', require('./components/stocks/RequestStock.vue'));
Vue.component('stock-return', require('./components/stocks/Return.vue'));
Vue.component('return-stock', require('./components/stocks/StockReturn.vue'));
Vue.component('blog-table', require('./components/blogs/BlogDatatable.vue'));
Vue.component('manage-attribute', require('./components/attributes/Manage.vue'));
Vue.component('modal-weight-order', require('./components/shippings/ModalWeightOrder.vue'));
Vue.component('service-card', require('./components/billing/Service.vue'));
Vue.component('invoice-table', require('./components/billing/Invoice.vue'));
Vue.component('featured-products', require('./components/products/featured/Index.vue'));
Vue.component('rangedate-picker', require('./components/DateRangePicker.vue'));

// template editor
Vue.component('template-editor', require('./components/theme/Editor.vue'));
Vue.component('storefont-iframe', require('./components/theme/Frame.vue'));

// charts
Vue.component('store-visit', require('./components/reports/StoreVisit.vue'));
Vue.component('chart-orders', require('./components/reports/Order.vue'));
Vue.component('chart-sales', require('./components/reports/Sale.vue'));

import store from './store/editor'

const app = new Vue({
    el: '#app',
    store
});

Vue.component('save', require('./components/theme/btn-save.vue'));
const app2 = new Vue({
    el: '#editorAction',
    store
});


$(document).ready(function() {
    $('form.sodirty').dirtyForms();

    $('#resendStaffEmail').on('click', function(e) {
      e.preventDefault();
      axios.post($(this).data('url')).then((response) => {
        $('.page-content-wrapper').pgNotification({
            style: 'simple',
            message: 'Email sent successfully.',
            position: 'top-right',
            timeout: 5000,
            type: "success"
         }).show();
      }).catch((error) => {
        $('.page-content-wrapper').pgNotification({
            style: 'simple',
            message: 'Something went wrong.',
            position: 'top-right',
            timeout: 5000,
            type: "danger"
         }).show();
      });
    })

});


Shopbox.deletePlugin = function(url) { 
    Shopbox.swalDelete(url);
    
}
Shopbox.swalDelete = function(url) {
    swal({
        title: 'Are you sure?',
        text: "It will be deleted permanently!",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!',
        showLoaderOnConfirm: true,
        preConfirm: function() {
            return new Promise(function(resolve) { 
                axios({
                  method:'delete',
                  url: url,
                  responseType: 'json'
                })
                .then(function (response) { 
                    swal('Deleted!', response.data.message, response.data.type);
                })
                .catch(function (error) {
                    console.log(error);
                    swal('Oops...', 'Something went wrong!', 'error');
                });
            });
        },
        allowOutsideClick: false              
    });        
}

$(document).on('click', '#btnDeletePhoto', function(e){  
     $("#userAvatar").removeClass('dirty');
     $('.user-avatar__gravatar-image').attr('src', '');
     $("#btnDeletePhoto").attr('disabled', 'disabled');
     $('#alertUnsavedChanges').addClass('hide');
});
$(document).on('click', '#btnDeleteUploadedPhoto', function(e){  
     $("#userAvatar").addClass('dirty');
     $("#userAvatar").val(1);
     $('.user-avatar__gravatar-image').attr('src', '');
     $('#alertUnsavedChanges').removeClass('hide');
     $(".user-avatar").addClass('user-avatar--style-2');
});
Shopbox.initTable = function() {
    var table = $('#table');

    var settings = {
        "sDom": "<t><'row'<p i>>",
        "destroy": true,
        "scrollCollapse": true,
        "oLanguage": {
            "sLengthMenu": "_MENU_ ",
            "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
        },
        "iDisplayLength": 5
    };

    table.dataTable(settings);

    // search box for table
    $('#search-table').keyup(function() {
        table.fnFilter($(this).val());
    });
}

Shopbox.initDropzone = function() {
    "use strict";
//Drag n Drop up-loader
// Disable auto discover for all elements:
Dropzone.autoDiscover = false;
    var drop = new Dropzone("#image", { 
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "/",
        uploadMultiple: true,
        parallelUploads: 10,
        maxFiles: 10,
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        previewTemplate: previewTemplate,
        previewsContainer: "#previews",
        autoQueue: false,
    });

   
}

Shopbox.toggleSEO = function() {
    $("#seoSection").removeClass('hide');
    $("#seoToggler").remove();
}


window.Shopbox = (function() {
  return {
    'init': function() {
      $(document).on('change', '#country', function() { 
        Shopbox.getStates($(this).val())
        
      });

      $(document).on('change', '.payment_status', function(e){ 
          var status = $(this).prop('checked');
          var plugin = $(this).attr('data-id');
          Shopbox.savePaymentStatus(status, plugin);
          
      });

      $(document).on('click', '#partial_returns', function(e){ 
          
          $("#enable_returns").prop("checked", true);
      });

      $(document).on('click', '#enable_returns', function(e){ 
          
          $("#partial_returns").prop("checked", false);
      });

      
    
    },
    'fileInputChanged': function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader;

            reader.onload = function (e) {
                $('.user-avatar__gravatar-image').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
            $("#btnDeletePhoto").removeAttr('disabled');
            $("#userAvatar").addClass('dirty');
            $('#alertUnsavedChanges').removeClass('hide');
            $('.btn-save').removeAttr('disabled');
        } 
        
    },
    'logoPreview': function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader;
            
            reader.onload = function (e) {
                $('#logoPreview').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        } 
    },
    'faviconPreview': function(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader;
            
            reader.onload = function (e) {
                $('#faviconPreview').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        } 
    },
    'getStates': function (country) {
      axios.get('/merchant/country/'+country+'/states')
           .then((response) => { 
                $("#state").html(response.data.states)
           })
    }, 
    'savePaymentStatus': function (status, plugin) {
        axios.patch('/store/payments/'+plugin,{
          status: status
        } 
        ).then((response) => { 
         $('.page-content-wrapper').pgNotification({
            style: 'simple',
            message: response.data.message,
            position: 'top-right',
            timeout: 5000,
            type: "success"
         }).show();
        }).catch((error) => {
           $('.page-content-wrapper').pgNotification({
            style: 'simple',
            message: error.response.data.message,
            position: 'top-right',
            timeout: 5000,
            type: "danger"
          }).show();
        })
     
    },
    'planSubscription': function () {
      $(document).on('change', '[name="period"]', function(e){ 
          var data = $(this).val().split('|');
          var plan = $(this).attr('data-plan');
          var parts = data[0].toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          var price = parts.join(".");

          $("#"+plan+"_cost").text(price);
      });
    }, 
    'togglePaymentOption': function () {
      $(document).on('change', '[name="payment"]', function(e){ 
        $(".payment-option").addClass('hide');
        $("#payment_"+$(this).val()).removeClass('hide');
      });
    },
    filteredBy: function () {
      const filters = queryString.parse(location.search, {arrayFormat: 'bracket'});
    
      if(filters.sort_by && filters.sort_by === 'newest' || filters.sort_by === 'featured') {
        $('#sort_by').val(filters.sort_by);
      }

      if(filters.industry && filters.industry.length) {

        if(typeof filters.industry === 'object') {

          filters.industry.forEach((industry) => {
            $(`#${industry}`).prop('checked', true);
          });

        } else if (typeof filters.industry === 'string') {

          $(`#${filters.industry}`).prop('checked', true);

        }
        
      }

      if(filters.price && filters.price.length) {

        if(typeof filters.price === 'object') {

          filters.price.forEach((price) => {
            $(`#${price}`).prop('checked', true);
          });

        } else if (typeof filters.price === 'string') {

          $(`#${filters.price}`).prop('checked', true);

        }

      }

    },
    toggleFilter: function () {

      $(document).on('change', '[data-toggle="filter"]', function(e){ 

        var filter = $(this).attr('data-filter-type');
        var value = $(this).val();
        var url = window.location.href.split('?')[0];
        const filters = queryString.parse(location.search, {arrayFormat: 'bracket'});
        filters.sort_by = 'newest';

        if(filter === 'industry') {

          if(typeof filters.industry !== 'object') {
            filters.industry = [filters.industry];
          }

          if ($(this).prop("checked")) {

            if(filters.industry) {

              var exits = filters.industry.find((industry) => {
                return industry === value;
              });
             
              if(!exits) {
                filters.industry.push(value);
              }
              

            } else {
              filters.industry = [value];
            }

          } else {

            filters.industry = filters.industry.filter((industry) => {
              return industry !== value;
            });
            
          } 

          if(filters.industry && !filters.industry.length) {
            delete filters.industry
          }
                   
        } else if (filter === 'sort') {

          filters.sort_by = value;

        } else if (filter === 'price') {

          if(typeof filters.price !== 'object') {
            filters.price = [filters.price];
          }

          if ($(this).prop("checked")) {

            if(filters.price) {

              var exits = filters.price.find((price) => {
                  return price === value;
              });
             
              if(!exits) {
                filters.price.push(value);
              }

            } else {
              filters.price = [value];
            }

          } else {

            filters.price = filters.price.filter((price) => {
              return price !== value;
            });
            
          }
         
          if(filters.price && !filters.price.length) {
            delete filters.price
          }

        }

        url = url + '?' + encodeURI(queryString.stringify(filters, {arrayFormat: 'bracket'})); 

        if (history.pushState) {
            window.history.pushState({path:url},'',url);
        }

        axios.get(url).then((response) => { 
            $("#Themes").html(response.data);
        })


      });
    },
    activateTheme: function (e, theme_id) {
      
      e.preventDefault();

      axios.post(`/merchant/themes/${theme_id}/active`).then((response) => { 
          window.location.href = window.location.href;
      })
      
    },
    countryHasStates: function (e) {  
      var options = '<option value>Select a state</option>';

      axios.get(`/countries/${ e.target.value }/states`).then((response) => { 

          if(response.data.states.length) {
              response.data.states.forEach((state) => {
                  options += `<option value="${ state.id }">${ state.name }</option>`
              });

              $("#state").html(options);
              $("#state").closest('.form-group').removeClass('d-none');
          } else {
              $("#state").closest('.form-group').addClass('d-none');
          }
      })

    },
    domainAvailability: function (e) {

      if(!e.target.value) {
        return;
      }

      axios.get(`/check-availability?domain=${ e.target.value }`).then((response) => { 
        
        if(response.data.status) {
          $(e.target).closest('.form-group').removeClass('has-danger');
          $(e.target).removeClass('form-control-danger');
          $(e.target).closest('.form-control-feedback').remove();
        } else {
          $(e.target).closest('.form-group').addClass('has-danger');
          $(e.target).addClass('form-control-danger');

          if($(e.target).closest('.form-group').find('.form-control-feedback').length) {
            $(e.target).closest('.form-group').find('.form-control-feedback').text('Domain not available.');
          } else {
            $('<div class="form-control-feedback">Domain not available.</div>').insertAfter($(e.target).closest('.input-group'));
          }

        }

      })
    },
    generateDomain: function () {
      $(document).on('input', '#store_name', function(e){  
        var domain = $(this).val().trim().replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '');
        $("#domain").val(domain);

        $("#domain").trigger("change");
      });

    }
  };
})();