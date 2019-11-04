$( function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })


    // const userAnswers = new Array();

    // let id = (window.location.hash)?location.hash.substring(1):1; //this.id;


    // getQuestion(id);

    $('.newButton').click(function() {
        id = $(this).attr('data-question');
        buttonType = $(this).attr('data-button-type');
        if (buttonType == 'next') {
            getQuestion(id,'next');
        }
        else if (buttonType == 'start') {
            getQuestion(id,'start');
        }
        else {
            submitQuestion();
        }

    });

    // $('.prevButton').click(function() {
    //     id = this.id;
    //     getQuestion(id);
    // });

    function getQuestion(id,buttonType) {
        let questionID = $('.question').attr('id');
        let subject = $('.subject').html().toLowerCase();
        let class_id = $('.class').html();
        let count = $('.questionCount').text();
        let answerExists = $('input[name="options"]:checked').val();
        let answer = $('input[name="options"]:checked').attr('data-option-id');
        $.ajax({
            url:'/getQuestions',
            method: 'GET',
            data: {
                subject: subject,
                class:class_id,
                question_id: id
            },
            success:function(response) {
                // window.location.hash = id;
                let selected = $('.options');
                // selected.checked = false;
                console.log(response);

                let chosenOption = localStorage.getItem('option'+response.id)
                if (chosenOption != undefined) {
                    document.getElementsByName('options')[chosenOption].checked = true
                }
                else {
                    let arr1 = document.getElementsByName('options');

                    arr1.forEach(element => {
                        element.checked = false
                    });
                }


                if (buttonType == 'next') {
                    if (answerExists != undefined) {
                        localStorage.setItem('option'+questionID,answerExists)
                        calculateScore(questionID,answer)
                    }

                }
                else {
                    const handle = setInterval(startTimer(),1000);
                    $('.nxtButton').attr('data-button-type','next')
                    $('.nxtButton').html('Next Question');
                    $('.questionList').each(function() {
                        $(this).removeClass('disabled')
                    })
                    $('.prevButton').removeClass('disabled')
                }

                if ($('.nxtButton').html() != "Next Question") {
                    $('.nxtButton').html("Next Question")
                    $('.nxtButton').attr("data-button-type",'next')
                }
                let length = response.options.length;
                $('.question').html(response.question)

                let shuffledOptions = response.options;

                if (length >= 4) {
                    $('.options').each(function(index) {
                        $(this).html(shuffledOptions[index].body)
                        $(this).siblings('input').attr('data-option-id',shuffledOptions[index].id)
                    })
                }

                else {
                    $('.options').each(function(index) {
                        if (index <= length-1) {
                            $(this).html(shuffledOptions[index].body)
                            $(this).siblings('input').attr('data-option-id',shuffledOptions[index].id)
                        }
                        else {
                            $(this).html('No Option')
                            $(this).siblings('input').attr('data-option-id',"")
                        }
                    })
                }

                if (id == count) {
                    $('.nxtButton').html('SUBMIT')
                    $('.nxtButton').attr('data-button-type','submit')
                }

                $('.questionList').each(function() {
                    if ($(this).attr('id') == response.id) {
                        $(this).addClass('active');
                        $('.questionList').not(this).removeClass('active');
                    }
                })

                $('.questionNo').text(id);

                // let exists = userAnswers.find(function(element) {
                //     return element.question_id == questionID
                // })

                // if (exists != undefined) {
                //     selected[exists.answer].checked = true;
                // }
                // else {
                //     selected.checked = false;
                // }

                $('.nxtButton').attr('data-question',parseInt(id)+1);
                $('.prevButton').attr('data-question',parseInt(id)-1);


                $('.question').attr('id',response.id);
                // if (answer) {
                //     userAnswers.forEach(function(element,index) {
                //         if (element.question_id == questionID) {
                //             userAnswers.splice(index,1)
                //         }
                //     });
                //     userAnswers.push({'question_id':questionID,'answer':answer});
                // }


                // document.getElementById('options').reset();
                // console.log(exists)
                // console.log(userAnswers)
            },
            error: function(response) {
                console.log(response);
            }
        })
    }

    function calculateScore(question_id,option_id) {
        $.ajax({
            url:'/calculateScore',
            method: 'POST',
            data: {
                question_id: question_id,
                option_id: option_id
            },
            success: function(response) {
                console.log(response)
            },
            error:function(response) {
                console.log(response)
            }
        })
    }

    function shuffle(arr) {
        var currentIndex = arr.length, temporaryValue, randomIndex;

        while(0 !== currentIndex) {
            randomIndex = Math.floor(Math.random() * currentIndex);
            currentIndex-=1

            temporaryValue = arr[currentIndex];
            arr[currentIndex] = arr[randomIndex];
            arr[randomIndex] = temporaryValue
        }

        return arr
    }

    function startTimer() {
        let hours = $('#hours').html();
        let minutes = $('#minutes').html();

        let currentTime = new Date().getTime();
        let currentDay = new Date().toDateString();

        let currentHour = new Date().getHours()
        let futureHour = Number(currentHour) + Number(hours)

        let currentMinute = new Date().getMinutes();
        let futureMinute = Number(currentMinute) + Number(minutes);

        if (futureMinute > 60) {
            futureMinute -= 60;
            if (hours == 0) {
                futureHour += 1
            }
            else if (hours == 1) {
                futureHour += 2
            }
        }

        let futureTime = new Date(currentDay + " " + futureHour + ":" + futureMinute).getTime();

        let distance = futureTime - currentTime;

        console.log(distance)

        let newHours = Math.abs(Math.round(distance/(1000*60*60)));
        let newMinutes = Math.abs(Math.round(distance/(1000*60))) - (newHours*60);
        let newSeconds = Math.abs(Math.round(distance/(1000*60*60)));

        $('#hours').html(newHours)
        $('#minutes').html(newMinutes)
        $('#seconds').html(newSeconds)


        // If the count down is finished, write some text
        if (distance < 0) {
            clearInterval(handle);
            document.getElementById("demo").innerHTML = "EXPIRED";
        }

        // clearInterval(handle);

    }
})
