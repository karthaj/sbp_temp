"use strict";
//Drag n Drop up-loader
// Disable auto discover for all elements:
Dropzone.autoDiscover = false;
var drop = new Dropzone("#file", { 
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },
    url: __dirname+"admin/modules/upload",
    uploadMultiple: true,
    acceptedFiles: '.zip',
    parallelUploads: 10,
    maxFiles: 10,
});
drop.on("successmultiple", function(file, response) { 
    $('.page-content-wrapper').pgNotification({
        style: 'bar',
        message: response.message,
        position: 'bottom',
        timeout: 5000,
        type: response.type
    }).show();
});
