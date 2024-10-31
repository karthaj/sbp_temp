
// refer shopbox js for vue components
$(document).ready(function() {
  form.validateStoreForm();
});

$(document).on('change', '#country', function(e){ 
      form.getStates($("#country").val())
});
window.form = (function() {
  return {
    'init': function() {
      $("#country").select2();
      $('#zip_code').tagsinput()
    },
    'getStates': function (country) {
      axios.get('/merchant/country/states', {
        params: {
          country: country
        }
      }).then((response) => { 
        if(response.data.result > 0){
          $("#stateList").html(response.data.states)
          $("#state").select2();
          $("#stateList").show();
        } else {
          $("#stateList").html('');
          $("#stateList").hide();
        }
      }).catch((error) => {
         console.log(error)
      })
    },
    'validateStoreForm': function () {
      $("#storeLocationForm").validate({ 
        rules: {
            name : { required: true, maxlength: 191 },
            address : { required: true},
            postal_code : { required: true },
            city : { required: true },
            country : { required: true, digits: true },
            state : { required: true },
            phone : { required: true, digits:true, maxlength: 10 },
            status : { digits: true },
            
        },
        messages: {
            name: {
              required: 'Name is required.'
            },
            address: {
              required: 'Address is required.'
            },
            postal_code : {
              required: 'Postal code is required.'
            },
            city : {
              required: 'City is required.'
            },
            country : {
              required: 'Country is required.'
            },
            state : {
              required: 'State is required.'
            },
            phone : {
              required: 'Phone number required.'
            },
        }
      });
    },
  };
})();


