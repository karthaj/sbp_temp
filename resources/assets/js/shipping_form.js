// refer shopbox js for vue components

$(document).ready(function() {
  form.validateZoneForm();
  //form.validateZoneForm()
  form.saveFreeShip()
  form.saveFlatRate()
  form.saveStorePickup()

  $('#modalFreeShipping').on('hidden.bs.modal', function (e) {;
    $('#modalFreeShipping .error').remove();
  })
});

$(document).on('click', '.delete-shipping-class', function(e) {
  e.preventDefault();

  var endpoint = $(this).attr('href');

  swal({
      title: 'Delete class ?',
      text: "Are you sure you want to delete this class? This action cannot be reversed.",
      type: 'warning',
      showCancelButton: true,
      showConfirmButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete class!',
      allowOutsideClick: false              
  }).then((result) => {
    if (result.value) {
      axios.delete(endpoint).then((response) => {
          swal('Deleted!', 'Shipping class deleted successfully!', 'success');
          setTimeout(function(){ 
            window.location.href = window.location.href
          }, 1000);
        
      }).catch((error) => {  
          swal('Oops...', 'Something went wrong!', 'error');
      });
    }; 
  });
})

$(document).on('click', '.delete-shipping-zone', function(e) {
  e.preventDefault();

  var endpoint = $(this).attr('href');

  swal({
      title: 'Delete shipping zone ?',
      text: "Are you sure you want to delete this shipping zone? This action cannot be reversed.",
      type: 'warning',
      showCancelButton: true,
      showConfirmButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete zone!',
      allowOutsideClick: false              
  }).then((result) => {
    if (result.value) {
      axios.delete(endpoint).then((response) => {
          swal('Deleted!', 'Shipping zone deleted successfully!', 'success');
          setTimeout(function(){ 
            window.location.href = window.location.href
          }, 1000);
        
      }).catch((error) => {  
          swal('Oops...', 'Something went wrong!', 'error');
      });
    }; 
  });
})

$(document).on('change', '#store_pickup', function(e){ 

  if($(this).prop('checked')) {
    $('#storePickup').collapse('show');
  } else {
    $('#storePickup').collapse('hide');
  }

});

$(document).on('change', '#zone_type', function(e){  
    var type = $("#zone_type").val();
    switch(type) {
    case 'country':
        form.toggleCountry()
        break;
    case 'state':
        form.toggleCountries()
        break;
    case 'zip_code':
        form.toggleZipCode()
        break;
    default:
        return
    } 
});
$(document).on('change', '#state_country', function(e){ 
      form.getStates($("#state_country").val())
});
$(document).on('change', '#zip_country', function(e){ 
    if($(this).val() == 197) {
      form.getCities($(this).val())
    } else {
      elem = '<div class="col-sm-12 form-group"><label for="zip_code">Zip / Postal codes</label>'+
             '<input class="tagsinput form-control" type="text" id="zip_code" name="zip_code">';
      $("#zipCode").html(elem)
      $('#zip_code').tagsinput()
    }
});

$(document).on('change', '#limit_order', function(e){ 
    if($("#limit_order").prop('checked')) {
      $("#amount").removeAttr('disabled');
    } else {
      $("#amount").attr('disabled','disabled');
    }
});

function intSwitchery(el)
{
  new Switchery(el, {
      color: (el.getAttribute("data-color") != null ?  $.Pages.getColor(el.getAttribute("data-color")) : $.Pages.getColor('success')),
      size : (el.getAttribute("data-size") != null ?  el.getAttribute("data-size") : "default"),
  });
}

$(document).on('change', '#free_shipping', function(e){ 
    var status = $(this).prop('checked');
    var method = $(this).attr('data-id');

    if(status) {
     
      $("#flat_rate").closest('div').html('<input type="checkbox" id="flat_rate" data-init-plugin="switchery" data-size="small" data-color="primary" value="1" name="flat_rate" data-id="'+ $("#flat_rate").attr('data-id') +'">');
      $("#ship_weight_order").closest('div').html('<input type="checkbox" id="ship_weight_order" data-init-plugin="switchery" data-size="small" data-color="primary" value="1" name="ship_weight_order" data-id="'+ $("#ship_weight_order").attr('data-id') +'">');
      intSwitchery(document.getElementById("flat_rate"));
      intSwitchery(document.getElementById("ship_weight_order"));
      form.saveStatus(status, method);
    
    } else {
      $("#free_shipping").closest('div').html('<input type="checkbox" id="free_shipping" data-init-plugin="switchery" data-size="small" data-color="primary" value="1" name="free_shipping" data-id="'+ $("#free_shipping").attr('data-id') +'" checked>');
      intSwitchery(document.getElementById("free_shipping"));
      form.saveStatus(true, method);
    }
    
    
});

$(document).on('change', '#flat_rate', function(e){ 
    var status = $(this).prop('checked');
    var method = $(this).attr('data-id');

    if(status) {
      $("#free_shipping").closest('div').html('<input type="checkbox" id="free_shipping" data-init-plugin="switchery" data-size="small" data-color="primary" value="1" name="free_shipping" data-id="'+ $("#free_shipping").attr('data-id') +'">');
       $("#ship_weight_order").closest('div').html('<input type="checkbox" id="ship_weight_order" data-init-plugin="switchery" data-size="small" data-color="primary" value="1" name="ship_weight_order" data-id="'+ $("#ship_weight_order").attr('data-id') +'">');
      intSwitchery(document.getElementById("free_shipping"));
      intSwitchery(document.getElementById("ship_weight_order"));
      form.saveStatus(status, method);
    } else {
      $("#free_shipping").closest('div').html('<input type="checkbox" id="free_shipping" data-init-plugin="switchery" data-size="small" data-color="primary" value="1" name="free_shipping" data-id="'+ $("#free_shipping").attr('data-id') +'" checked>');
       intSwitchery(document.getElementById("free_shipping"));
       form.saveStatus(true, $("#free_shipping").attr('data-id'));
    }

});

$(document).on('change', '#ship_weight_order', function(e){ 
    var status = $(this).prop('checked');
    var method = $(this).attr('data-id');

    if(status) {
      $("#flat_rate").closest('div').html('<input type="checkbox" id="flat_rate" data-init-plugin="switchery" data-size="small" data-color="primary" value="1" name="flat_rate" data-id="'+ $("#flat_rate").attr('data-id') +'">');
      $("#free_shipping").closest('div').html('<input type="checkbox" id="free_shipping" data-init-plugin="switchery" data-size="small" data-color="primary" value="1" name="free_shipping" data-id="'+ $("#free_shipping").attr('data-id') +'">');
      intSwitchery(document.getElementById("free_shipping"));
      intSwitchery(document.getElementById("flat_rate"));
      form.saveStatus(status, method);
    } else {
      $("#free_shipping").closest('div').html('<input type="checkbox" id="free_shipping" data-init-plugin="switchery" data-size="small" data-color="primary" value="1" name="free_shipping" data-id="'+ $("#free_shipping").attr('data-id') +'" checked>');
       intSwitchery(document.getElementById("free_shipping"));
       form.saveStatus(true, $("#free_shipping").attr('data-id'));
    }

});

window.form = (function() {
  return {
    'init': function() {
      $("#zone_country, #state_country, #zip_country").select2();
      $('#zip_code').tagsinput()
      $('.autonumeric').autoNumeric('init');
    },
    'initRedactor': function() {
       $("#instructions").redactor({
        plugins: ['table', 'alignment', 'fontsize'],
       });
    },
    'toggleCountry': function () {
        $("#zoneCountry").show();
        $("#stateCountry").hide();
        $("#stateList").hide();
        $("#zipCountry").hide();
        $("#zipCode").hide();
    },
    'toggleCountries': function () {
        $("#zoneCountry").hide();
        $("#zipCountry").hide();
        $("#stateCountry").show();
        $("#stateList").show();
        $("#zipCode").hide();
    },
    'toggleZipCode': function () {
        $("#zoneCountry").hide();
        $("#stateCountry").hide();
        $("#zipCountry").show();
        $("#zipCode").show();
        $("#stateList").hide();
    },
    'getStates': function (countries) {
      axios.get('/merchant/countries/states', {
        params: {
          countries: countries
        }
      }).then((response) => { 
        $("#states").html(response.data.states)
      }).catch((error) => {
         console.log(error)
      })
    },
     'getCities': function (country) {
      axios.get('/merchant/country/cities', {
        params: {
          country: country
        }
      }).then((response) => { 
        $("#zipCode").html(response.data.cities)
        $("#zip_code").select2()
      }).catch((error) => {
         console.log(error)
      })
    },
    // 'validateZoneForm': function () {
    //   $("#zoneForm").validate({ 
    //     rules: {
    //         zone_name : { required: true, maxlength: 191 },
    //         zone_country : { required: "#country_type:checked" },
    //         zip_country : { required: "#zip:checked" },
    //         zip_code : { required: "#zip:checked" },
    //         state_country : { required: "#state:checked" },
    //         states : { required: "#state:checked" },
            
    //     },
    //     messages: {
    //         zone_name: {
    //           required: 'Please enter a name for this tax zone.'
    //         },
    //         zone_country: {
    //           required: 'Please select one or more countries.'
    //         },
    //         zip_code : {
    //           required: 'Please enter one or more ZIP/postal codes.'
    //         },
    //         state_country : {
    //           required: 'Please select one or more countries.'
    //         },
    //         states : {
    //           required: 'Please select one or more states.'
    //         },
    //     }
    //   });
    // },
    'validateRateForm': function () {
      $("#taxRateForm").validate({
        rules: {
            tax_name : { required: true, maxlength: 191 }, 
            tax_zone : { required: true, digits: true }, 
            calculation_order: { required: true, digits: true }, 
        },
      })
    },
    'validateZoneForm': function () {
      $("#shippingZoneForm").validate({ 
        ignore: [],
        rules: {
            zone_name : { required: true, maxlength: 191 },
            'zone_country[]' : {
                required: {
                  depends: function (element) {
                    return $("#zone_type").val() === 'country';
                  }
                }
            },
            zip_country : { 
              required: {
                depends: function (element) {
                    return $("#zone_type").val() === 'zip_code';
                }
              }
            },
            zip_code : { 
              required: {
                depends: function (element) {
                    return $("#zone_type").val() === 'zip_code';
                }
              }
            },
            state_country : { 
              required: {
                depends: function (element) {
                    return $("#zone_type").val() === 'state';
                }
              }
            },
            'states[]' : { 
              required: {
                depends: function (element) {
                    return $("#zone_type").val() === 'state';
                }
              }
            },
            
        },
        messages: {
            zone_name: {
              required: 'Please enter a name for this shipping zone.'
            },
            'zone_country[]': {
              required: 'Please select one or more countries.'
            },
            zip_code : {
              required: 'Please enter one or more ZIP/postal codes.'
            },
            zip_country: {
              required: 'Country is required.'
            },
            state_country : {
              required: 'Please select one or more countries.'
            },
            'states[]' : {
              required: 'Please select one or more states.'
            },
        }
      });
    },
    'saveStatus': function (status, method) {
        axios.patch('/merchant/store/shipping/zone/configure/'+method,{
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
            message: 'Something went wrong',
            position: 'top-right',
            timeout: 5000,
            type: "danger"
          }).show();
        })
     
    },
    'saveFreeShip': function () {
      $(document).on('click', '#btnFreeShip', function(e) { 
        e.preventDefault()
        var url = $("#formFreeShip").attr('action');
        axios.patch(url, 
          $("#formFreeShip").serialize(),
        ).then((response) => { 
          $("#amount").val(response.data.data.rate)
          $("#modalFreeShipping").modal('hide')
          $('.page-content-wrapper').pgNotification({
            style: 'simple',
            message: 'Free Shipping saved.',
            position: 'top-right',
            timeout: 5000,
            type: "success"
         }).show();
        }).catch((error) => {
           if(error.response.data.amount) {
            $('<label id="amount-error" class="error" for="amount">'+error.response.data.amount[0]+'</label>').insertAfter($("#amount").closest('.form-group'));
           } 

           if(error.response.data.email) {
             $('<label id="email-error" class="error" for="email">'+error.response.data.email[0]+'</label>').insertAfter($("#email").closest('.form-group'));
           }
        })
      })
    },
    'saveFlatRate': function () {
      $(document).on('click', '#btnFlatRate', function(e) { 
        e.preventDefault()
        var url = $("#formFlatRate").attr('action');
        axios.patch(url, 
          $("#formFlatRate").serialize(),
        ).then((response) => { 
          $("#shipping_rate").val(response.data.data.rate)
          $("#modalFlatRate").modal('hide')
          $('.page-content-wrapper').pgNotification({
            style: 'simple',
            message: 'Flat Rate saved.',
            position: 'top-right',
            timeout: 5000,
            type: "success"
         }).show();
        }).catch((error) => {
           if(error.response.data.display_name) {
            $('<label id="display_name-error" class="error" for="display_name">'+error.response.data.display_name[0]+'</label>').insertAfter("#display_name");
           } 
           if(error.response.data.shipping_rate) {
            $('<label id="shipping_rate-error" class="error" for="shipping_rate">'+error.response.data.shipping_rate[0]+'</label>').insertAfter($("#shipping_rate").closest('.row'));
           }
           if(error.response.data.charge_type) {
            $('<label id="charge_type-error" class="error" for="charge_type">'+error.response.data.charge_type[0]+'</label>').insertAfter("#charge_type");
           } 
           if(error.response.data.flat_rate_email) {
             $('<label id="email-error" class="error" for="email">'+error.response.data.flat_rate_email[0]+'</label>').insertAfter($("#flat_rate_email").closest('.form-group'));
           } 
        })
      })
    },
    'saveStorePickup': function () {
      $(document).on('click', '#btnStorePickup', function(e) { 
        e.preventDefault()
        var url = $("#formStorePickup").attr('action');
        axios.patch(url, 
          $("#formStorePickup").serialize(),
        ).then((response) => { 
          $("#modalStorePickUp").modal('hide')
          $('.page-content-wrapper').pgNotification({
            style: 'simple',
            message: 'Store Pickup saved.',
            position: 'top-right',
            timeout: 5000,
            type: "success"
         }).show();
        }).catch((error) => {
           if(error.response.data.display_name) {
            $('<label id="display_name-error" class="error" for="display_name">'+error.response.data.display_name[0]+'</label>').insertAfter("#display_name");
           } 

          if(error.response.data.store_pickup_email) {
             $('<label id="email-error" class="error" for="email">'+error.response.data.store_pickup_email[0]+'</label>').insertAfter($("#store_pickup_email").closest('.form-group'));
          }
        })
      })
    },
  };
})();


