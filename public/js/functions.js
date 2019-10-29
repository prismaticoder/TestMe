$( function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })


    const userAnswers = new Array();
$('.nxtButton').click(function() {
    let id = this.id;
    let questionID = $('.question').attr('id');


    $.ajax({
        url:'/getQuestions',
        method: 'GET',
        data: {
            subject: 'english',
            class:2,
            question_id: id
        },
        success:function(response) {
            console.log(response);
            $('.question').html(response.question)
            $('.options').each(function(index) {
                $(this).html(response.options[index].body)
            })
            $('.nxtButton').attr('id',parseInt(id)+1);
            let answer = $('input[name="options"]:checked').val();
            if (answer) {
                userAnswers.push({'question_id':questionID,'answer':answer});
            }
            document.getElementById('options').reset();
            $('.question').attr('id',response.id);
            console.log(userAnswers)
        },
        error: function(response) {
            console.log(response);
        }
    })
})

})
