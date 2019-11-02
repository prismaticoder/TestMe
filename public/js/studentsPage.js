$(function() {

    $('.updateBtn').click(function() {
        let id = this.id;
        updateStudent(id);
    })

    function updateStudent(id) {
        $.ajax({
            url:'/updateStudent/'+id,
            method:'POST',
            data: {
                'firstname': $('#firstname'+id),
                'lastname': $('#lastname'+id),
            },
            success:function(response) {
                alert(response)
            },
            error:function(response) {
                alert(response)
            }
        })
    }
})
