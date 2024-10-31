<script>
	
$('.page-content-wrapper').pgNotification({
    style: 'simple',
    message: "{{ $slot }}",
    position: 'top-right',
    timeout: 5000,
    type: "{{ $type }}"
}).show();

</script>

