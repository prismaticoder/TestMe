$( function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })

$('.nxtButton').click(function() {
    let questionID = this.id;

    $.ajax({
        url:'/getQuestions',
        method: 'GET',
        data: {
            subject: 'english',
            class:2,
            question_id: questionID
        },
        success:function(response) {
            console.log(response);
            $('#question').html(response.question)
            $('.options').each(function(index) {
                $(this).html(response.options[index].body)
            })
            $('.nxtButton').attr('id',parseInt(questionID)+1);
            $('input[name="options"]:checked').attr('checked',false);
            $('#subjectH').val('english');
            $('#classH').val(2);
            $('#questionH').val(questionID);
            $('#reloadForm').submit()
        },
        error: function(response) {
            console.log(response);
        }
    })
})

})
