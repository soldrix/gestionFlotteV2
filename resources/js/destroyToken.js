function destroyToken() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        type:"POST",
        url: '/logout',
    })
}
window.addEventListener('beforeunload',destroyToken)
