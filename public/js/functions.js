$( function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })


    const userAnswers = new Array();

    let id = (window.location.hash)?location.hash.substring(1):1; //this.id;


    getQuestion(id);

    $('.nxtButton').click(function() {
        id = this.id;
        getQuestion(id);
    });

    $('.prevButton').click(function() {
        id = this.id;
        getQuestion(id);
    });

    function getQuestion(id) {
        let questionID = $('.question').attr('id');
        let subject = $('.subject').html().toLowerCase();
        let class_id = $('.class').html();
        $.ajax({
            url:'/getQuestions',
            method: 'GET',
            data: {
                subject: subject,
                class:class_id,
                question_id: id
            },
            success:function(response) {
                window.location.hash = id;
                let selected = document.getElementsByName('options');
                selected.checked = false;
                console.log(response);
                let length = response.options.length;
                $('.question').html(response.question)

                if (length >= 4) {
                    $('.options').each(function(index) {
                        $(this).html(response.options[index].body)
                    })
                }

                else {
                    $('.options').each(function(index) {
                        if (index <= length-1) {
                            $(this).html(response.options[index].body)
                        }
                        else {
                            $(this).html('No Option')
                        }
                    })
                }

                let exists = userAnswers.find(function(element) {
                    return element.question_id == questionID
                })

                if (exists != undefined) {
                    selected[exists.answer].checked = true;
                }
                else {
                    selected.checked = false;
                }

                $('.nxtButton').attr('id',parseInt(id)+1);
                $('.prevButton').attr('id',parseInt(id)-1);
                let answer = $('input[name="options"]:checked').val();
                if (answer) {
                    userAnswers.forEach(function(element,index) {
                        if (element.question_id == questionID) {
                            userAnswers.splice(index,1)
                        }
                    });
                    userAnswers.push({'question_id':questionID,'answer':answer});
                }


                // document.getElementById('options').reset();
                $('.question').attr('id',response.id);
                console.log(exists)
                console.log(userAnswers)
            },
            error: function(response) {
                console.log(response);
            }
        })
    }
})
