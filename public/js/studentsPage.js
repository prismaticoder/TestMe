$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    $('.updateBtn').click(function() {
        let id = this.id;
        updateStudent(id);
    })

    function updateStudent(id) {
        $.ajax({
            url:'/updateStudent/'+id,
            method:'POST',
            data: {
                firstname: $('#firstname'+id).val(),
                lastname: $('#lastname'+id).val(),
            },
            success:function(response) {
                alert(response)
                $('.firstname'+id).html($('#firstname'+id).val())
                $('.lastname'+id).html($('#lastname'+id).val())
            },
            error:function(response) {
                console.log(response)
            }
        })
    }
})
