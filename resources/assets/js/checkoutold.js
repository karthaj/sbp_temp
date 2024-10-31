var axios = require('axios');

$(document).ready(function() {
	cart.init();
});

var cart = (function() {
  return {
    'init': function() {
      $(document).on('click', '.order-summary-toggle', function(e) { 
      
        $(this).toggleClass("order-summary-toggle--show order-summary-toggle--hide")
        $(this).attr("aria-expanded",function(){return function(e,t){return"false"===t}}())
        $("#order-summary").toggleClass("order-summary--is-expanded order-summary--is-collapsed")
        $("#order-summary").addClass("order-summary--transition")
        
        $("#order-summary").one("click",function(){return function(e){if($("#order-summary").removeClass("order-summary--transition"),e&&$("#order-summary").is(e.target))return $("#order-summary").removeAttr("style")}}())
       
        
      });
      $(document).on('click', '.btn-discount', function(e) { 
        e.preventDefault();
        cart.applyDiscount();
      });
      $(document).on('click', '.applied-reduction-code__clear-button', function(e) { 
        e.preventDefault();
        cart.removeDiscount($(this).attr('data-url'), $(this).attr('data-cart'));
      });
  	  $(document).on('change', '.toggle-address', function(e){  
  		  e.preventDefault()
    		if($(this).val() == 1) {
    			$("#section--billing-address__different").removeClass('hidden');
    		} else {
    			$("#section--billing-address__different").addClass('hidden');
    		}
	    })
      $(document).on('change', '#checkout_shipping_address_country', function(e){  
        e.preventDefault()
        cart.getShippingCountryStates($(this).attr('data-url'), $(this).val())
      });
      $(document).on('change', '#checkout_billing_address_country', function(e){  
        e.preventDefault()
        cart.getBillingCountryStates($(this).attr('data-url'), $(this).val())
      });
  	  $(document).on('keyup keydown keypress input', '#discount_code', function(e){  
  	  	if($(this).val().length > 0) {
  	  		$(".btn-discount").removeClass('btn--disabled');
  	  		$(".btn-discount").removeAttr('disabled');
  	  	} else {
  	  		$(".btn-discount").addClass('btn--disabled');
  	  		$(".btn-discount").attr('disabled', 'disabled');
  	  	}
  	  })
  	  $(document).on('change', '.shipping-option', function(e){  
    		e.preventDefault()
    		cart.getShippingCost($(this).val(), $(this).attr('data-cost'))
  	  })
      $(document).on('change', '.payment-option', function(e){  
        e.preventDefault()
        cart.getServiceCharge($(this).attr('data-cost'))
      })
      $(document).on('change', '#checkout_shipping_address_id', function(e){  
        e.preventDefault()
        cart.getCustomerShippingAddress($(this).attr('data-url'), $(this).val())
      });
	    $(document).on('change', '#checkout_billing_address_id', function(e){  
        e.preventDefault()
        cart.getCustomerBillingAddress($(this).attr('data-url'), $(this).val())
      });
    },
    'getCustomerBillingAddress': function (url, address) {
      axios.get(url+'?address='+address)
      .then(function (response) {
        if(response.data.data.length) {
          $("#billingForm").html('');
          $(response.data.data).insertAfter($("#checkout_billing_address_id").closest(".field"));
        } else {
          $("#checkout_billing_address_first_name, #checkout_billing_address_last_name, #checkout_billing_address_address1, #checkout_billing_address_address2, #checkout_billing_address_city, #checkout_billing_address_state, #checkout_billing_address_zip, #checkout_billing_address_phone").val('');
          $("#checkout_billing_address_first_name, #checkout_billing_address_last_name, #checkout_billing_address_address1, #checkout_billing_address_address2, #checkout_billing_address_city, #checkout_billing_address_state, #checkout_billing_address_zip, #checkout_billing_address_phone").closest('.field').removeClass('field--show-floating-label');
        }
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    'getCustomerShippingAddress': function (url, address) {
      axios.get(url+'?address='+address)
      .then(function (response) {
        if(response.data.data.length) {
          $("#shippingForm").html('');
          $(response.data.data).insertAfter($("#checkout_shipping_address_id").closest(".field"));
        } else {
          $("#checkout_shipping_address_first_name, #checkout_shipping_address_last_name, #checkout_shipping_address_address1, #checkout_shipping_address_address2, #checkout_shipping_address_city, #checkout_shipping_address_state, #checkout_shipping_address_zip, #checkout_shipping_address_phone").val('');
          $("#checkout_shipping_address_first_name, #checkout_shipping_address_last_name, #checkout_shipping_address_address1, #checkout_shipping_address_address2, #checkout_shipping_address_city, #checkout_shipping_address_state, #checkout_shipping_address_zip, #checkout_shipping_address_phone").closest('.field').removeClass('field--show-floating-label');
        }
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    'getShippingCountryStates': function (url, country) {
      axios.get(url+'?country='+country)
      .then(function (response) {
        if(response.data.data != '') {

          $("#checkout_shipping_address_state").html(response.data.data);
          $("#checkout_shipping_address_state").closest(".field").removeClass('hidden');
          $("#checkout_shipping_address_country").closest(".field").removeClass('field--half').addClass('field--three-eights');
          $("#checkout_shipping_address_zip").closest(".field").removeClass('field--half').addClass('field--quarter');

        } else {

          $("#checkout_shipping_address_state").html('');
          $("#checkout_shipping_address_state").closest(".field").addClass('hidden');
          $("#checkout_shipping_address_country").closest(".field").removeClass('field--three-eights').addClass('field--half');
          $("#checkout_shipping_address_zip").closest(".field").removeClass('field--quarter').addClass('field--half');
        }
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    'getBillingCountryStates': function (url, country) {
      axios.get(url+'?country='+country)
      .then(function (response) {
        if(response.data.data != '') {

          $("#checkout_billing_address_state").html(response.data.data);
          $("#checkout_billing_address_state").closest(".field").removeClass('hidden');
          $("#checkout_billing_address_country").closest(".field").removeClass('field--half').addClass('field--three-eights');
          $("#checkout_billing_address_zip").closest(".field").removeClass('field--half').addClass('field--quarter');

        } else {

          $("#checkout_billing_address_state").html('');
          $("#checkout_billing_address_state").closest(".field").addClass('hidden');
          $("#checkout_billing_address_country").closest(".field").removeClass('field--three-eights').addClass('field--half');
          $("#checkout_billing_address_zip").closest(".field").removeClass('field--quarter').addClass('field--half');
        }
      })
      .catch(function (error) {
        console.log(error);
      });
    },
    'getShippingCost': function (shipper, cost) {
    	axios.post('/checkout/get-shipping', {
	        shipper: shipper,
	        cost: cost
	    }).then((response) => {
	        $(".total-line-table").html(response.data.data);
	       
	    }).catch((error) => {
	        console.log(error)
	    })
    },
    'getServiceCharge': function (cost) {
      axios.post('/checkout/service-charge', {
          cost: cost
      }).then((response) => {
          $(".total-line-table").html(response.data.data);
         
      }).catch((error) => {
          console.log(error)
      })
    },
    'applyDiscount': function () {
      var url = $("#discountForm").attr('action');
      axios.post(url, 
        $('#discountForm').serialize()
      ).then((response) => {
        $(".total-line-table").html(response.data.data);
        if(response.data.error) {
        	$(".field__message").html(response.data.error);
        	$("#discount_code").addClass('field--error');
        } else {
        	$(".order-summary__section--discount").addClass('hidden');
          $(".field__message").html('');
          $("#discount_code").val('');
        }
        
      }).catch((error) => {
        console.log(error)
      })
    },
    'removeDiscount': function (url, cart) {
    
      axios.post(url, {
        cart: cart
      }).then((response) => {
        $(".order-summary__section--discount").removeClass('hidden');
        $("#discount_code").closest(".field").removeClass('field--show-floating-label');
        $(".total-line--discount").remove();
        $(".payment-due__price").html(response.data.total);
      }).catch((error) => {
        console.log(error)
      })
    },
  };
})();

