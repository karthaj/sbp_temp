
 @if(session()->has('status'))
    $('.page-content-wrapper').pgNotification({
        style: 'simple',
        message: "{{ $status }}",
        position: 'top-right',
        timeout: 5000,
        type: "{{ $type }}"
    }).show();
 @endif