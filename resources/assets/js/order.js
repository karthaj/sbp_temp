// refer shopbox js for vue components

$(document).on('click', '#btnCartDelete', function (e) {
	$(this).attr('disabled', 'disabled')
})

$(document).on('click', '#btnSend', function (e) {
	cart.sendMail($(this).attr('data-url'));
})

$(document).on('click', '#btnTrack', function (e) {
    cart.saveTrackingNumber($(this).attr('data-url'));
})

$(document).on('click', '#btnPayment', function (e) {
    cart.savePayment($("#paymentForm").attr('action'));
})

$(document).on('click', '#btnStatus', function (e) {
    cart.changeStatus($(this).attr('data-url'), $("#status").val());
})


var cart = (function() {
  return {
    'sendMail': function(url) {
     	axios.patch(url).then((response) => {
            $('.page-content-wrapper').pgNotification({
                style: 'simple',
                message: response.data.data,
                position: 'top-right',
                timeout: 5000,
                type: "success"
            }).show();
            $('#sendEmailModal').modal('hide');
        }).catch((error) => {
            $('.page-content-wrapper').pgNotification({
                style: 'simple',
                message: error,
                position: 'top-right',
                timeout: 5000,
                type: "danger"
            }).show();
        })
    },
    'saveTrackingNumber': function(url) {
        var track_no = $("#tracking_number").val()
        axios.post(url, {
            track_no: track_no,
        }).then((response) => {
            $('.page-content-wrapper').pgNotification({
                style: 'simple',
                message: response.data.data,
                position: 'top-right',
                timeout: 5000,
                type: "success"
            }).show();
            $("#tracking_number").val(track_no)
            $('#shippingModal').modal('hide');
        }).catch((error) => {
            $('.page-content-wrapper').pgNotification({
                style: 'simple',
                message: error,
                position: 'top-right',
                timeout: 5000,
                type: "danger"
            }).show();
            $("#tracking_number").val('')
            $('#shippingModal').modal('hide');
        })
    },
    'savePayment': function(url) {
        axios.post(url, $("#paymentForm").serialize()).then((response) => {
            $('.page-content-wrapper').pgNotification({
                style: 'simple',
                message: response.data.data,
                position: 'top-right',
                timeout: 5000,
                type: "success"
            }).show();
            $("#paymentForm").remove();
            $("#order_amount").text(response.data.order_amt);
            $("#payment_status").text(response.data.status);
        }).catch((error) => {
            $('.page-content-wrapper').pgNotification({
                style: 'simple',
                message: error,
                position: 'top-right',
                timeout: 5000,
                type: "danger"
            }).show();
            $("#amount-error").remove()
            $('#currency-error').remove();

            if(error.response.data.amount) {
                $('<label id="amount-error" class="error" for="amount">'+error.response.data.amount[0]+'</label>').insertAfter($("#amount").closest('.row'));
            } 
            if(error.response.data.currency) {
                $('<label id="currency-error" class="error" for="currency">'+error.response.data.currency[0]+'</label>').insertAfter($("#currency").closest('.row'));
            }
            if(error.response.data.transaction_id) {
                $('<label id="transaction_id-error" class="error" for="transaction_id">'+error.response.data.transaction_id[0]+'</label>').insertAfter($("#transaction_id").closest('.row'));
            }
        })
    },
    'changeStatus': function(url, value) {
        axios.post(url, {
            status: value,
        }).then((response) => {
            $('.page-content-wrapper').pgNotification({
                style: 'simple',
                message: response.data.msg,
                position: 'top-right',
                timeout: 5000,
                type: "success"
            }).show();
            $("#order_status").text(response.data.status)
            $("#order_status").css("background-color", response.data.color);
        }).catch((error) => {
            $('.page-content-wrapper').pgNotification({
                style: 'simple',
                message: error,
                position: 'top-right',
                timeout: 5000,
                type: "danger"
            }).show();
        })
    },
  };
})();