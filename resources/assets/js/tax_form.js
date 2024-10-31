// refer shopbox js for vue components


$(document).ready(function() {
  form.validateZoneForm();
  form.validateTaxConfigForm();
});
$(document).on('change', '#country_type', function(e){  
     form.toggleCountry()
});
$(document).on('change', '#state', function(e){  
     form.toggleCountries()
});
$(document).on('change', '#zip', function(e){  
     form.toggleZipCode()
});
$(document).on('change', '#state_country', function(e){ 
      form.getStates($("#state_country").val())
});
window.form = (function() {
  return {
    'init': function() {
      $("#zone_country, #state_country, #zip_country").select2();
      $('#zip_code').tagsinput()
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
    'validateZoneForm': function () {
      $("#zoneForm").validate({ 
        rules: {
            zone_name : { required: true, maxlength: 191 },
            zone_country : { required: "#country_type:checked" },
            zip_country : { required: "#zip:checked" },
            zip_code : { required: "#zip:checked" },
            state_country : { required: "#state:checked" },
            states : { required: "#state:checked" },
            
        },
        messages: {
            zone_name: {
              required: 'Please enter a name for this tax zone.'
            },
            zone_country: {
              required: 'Please select one or more countries.'
            },
            zip_code : {
              required: 'Please enter one or more ZIP/postal codes.'
            },
            state_country : {
              required: 'Please select one or more countries.'
            },
            states : {
              required: 'Please select one or more states.'
            },
        }
      });
    },
    'validateRateForm': function () {
      $("#taxRateForm").validate({
        rules: {
            tax_name : { required: true, maxlength: 191 }, 
            tax_zone : { required: true, digits: true }, 
            calculation_order: { required: true, digits: true }, 
        },
      })
    },
    'validateTaxConfigForm': function () {
      $("#taxOptionForm").validate({
        rules: {
            tax_label : { required: true, maxlength: 191 }, 
            price_tax : { digits: true }, 
            shipping_tax: { digits: true }, 
            charge_tax: { digits: true }, 
            product_listing_tax: { digits: true }, 
            product_page_tax: { digits: true }, 
            cart_tax: { digits: true }, 
            cart_charge: { digits: true }, 
            order_invoice_tax: { digits: true }, 
            order_tax: { digits: true }, 
        },
      })
    },
  };
})();


