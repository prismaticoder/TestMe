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

    $('#addBtn').click(function() {
        addStudent();
    })

    $('.deleteBtn').click(function() {
        let id = this.id;
        deleteStudent(id);
    })

    $('.restoreBtn').click(function() {
        let id = this.id;
        restoreStudent(id);
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

    function deleteStudent(id) {
        let firstname = $('.firstname'+id).html();
        let lastname = $('.lastname'+id).html()
        if (confirm('Are you sure you want to delete ' + firstname.toUpperCase() + ' ' + lastname.toUpperCase() +' from the exam sheet thereby disabling his/her access to the examination?')) {
            $.ajax({
                url: '/deleteStudent/'+id,
                method:'POST',
                success:function(response) {
                    alert(response)
                    window.location.reload(true);
                },
                error:function(response) {
                    console.log(response)
                }
            })
        }
    }

    function restoreStudent(id) {
        let firstname = $('.firstname'+id).html();
        let lastname = $('.lastname'+id).html()
        if (confirm('Are you sure you want to restore ' + firstname.toUpperCase() + ' ' + lastname.toUpperCase() +' to the examination sheet thereby making him/her eligible to write the examination?')) {
            $.ajax({
                url: '/restoreStudent/'+id,
                method:'POST',
                success:function(response) {
                    alert(response)
                    window.location.reload(true);
                },
                error:function(response) {
                    console.log(response)
                }
            })
        }
    }

    function addStudent() {
        let firstname = $('#addFirstname').val();
        let lastname = $('#addLastname').val();
        let class_id = $('#class_id').text();

        $.ajax({
            url:'/addStudent',
            data: {
                firstname:firstname,
                lastname:lastname,
                class_id:class_id
            },
            method:'POST',
            success:function(response) {
                alert(response)
                window.location.reload(true);
            },
            error:function(response) {
                console.log(response)
            }
        })
    }
})
