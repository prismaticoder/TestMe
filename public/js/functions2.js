$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

    $('.questionBtn').on('click', function() {
        id = this.id;
        getSelectedQuestion(id);
    })

    function getSelectedQuestion(id) {
        $.ajax({
            url: '/admin/findQuestion/' + id,
            method: 'GET',
            success: function(response) {
                $('#summernote').summernote('code', response.question);
                $('.submitBtn').html('Update Question');
                $('.submitBtn').attr('id','question-update');
                console.log(response);
                let length = response.options.length;

                if (length >= 4) {
                    $('.option').each(function(index) {
                        $(this).val(response.options[index].body)
                    })
                }

                else {
                    $('.option').each(function(index) {
                        if (index <= length-1) {
                            $(this).val(response.options[index].body)
                        }
                        else {
                            $(this).val('No Option')
                        }
                    })
                }

                response.options.forEach(function(element,index) {
                    if (element.isCorrect == 1) {
                        let options = document.getElementsByName('correct');
                        options[index].checked = true;
                    }
                });
            },
            error: function(response) {
                console.log(response)
            }
        })
    }

    //Update Existing Question
    function updateQuestion(id) {
        let question = $('#summernote').val();
        let options = document.getElementsByClassName('option');
        let correct = $('input:name["correct"]:checked').val();
        $.ajax({
            url: '/updateQuestion/' + id,
            method: 'POST',
            data: {
                question:question,
                options:options,
                correct:correct
            }
        })
    }
})
