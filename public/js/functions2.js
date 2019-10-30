$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })


    function getSelectedQuestion(id) {
        $.ajax({
            url: '/findQuestion/' + id,
            method: 'GET',
            success: function(response) {
                $('#summernote').html(response.question);
                let length = response.options.length;

                if (length >= 4) {
                    $('.option').each(function(index) {
                        $(this).html(response.options[index].body)
                    })
                }

                else {
                    $('.option').each(function(index) {
                        if (index <= length-1) {
                            $(this).html(response.options[index].body)
                        }
                        else {
                            $(this).html('No Option')
                        }
                    })
                }
            }
        })
    }
})
