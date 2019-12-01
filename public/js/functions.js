$( function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })


    // var userAnswers = new Array();

    // var id = (window.location.hash)?location.hash.substring(1):1; //this.id;
    var handle;

    // getQuestion(id);

    if (sessionStorage.getItem('distance')) {
        var distance = sessionStorage.getItem('distance')
        var newHours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var newMinutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var newSeconds = Math.floor((distance % (1000 * 60)) / 1000);

        $('#hours').html(newHours)
        $('#minutes').html(newMinutes)
        $('#seconds').html(newSeconds)

        handle = setInterval(startTimer,1000);
    }


     window.hours = $('#hours').html();
     window.minutes = $('#minutes').html();
     window.seconds = $('#seconds').html();

     window.currentDay = new Date().toDateString();

     window.currentHour = new Date().getHours()
     window.futureHour = Number(currentHour) + Number(hours)

     window.currentMinute = new Date().getMinutes();
     window.futureMinute = Number(currentMinute) + Number(minutes);

     window.futureSecond = Number(seconds);

    if (futureMinute > 60) {
        futureMinute -= 60;
        futureHour += 1
    }



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
            if (confirm("Are you sure you are ready to submit your examination?")) {
                sessionStorage.clear();
                submitQuestion();
            }
        }

    });

    // $('.prevButton').click(function() {
    //     id = this.id;
    //     getQuestion(id);
    // });

    function getQuestion(id,buttonType) {
        var questionID = $('.question').attr('id');
        var subject = $('.subject').html().toLowerCase();
        var class_id = $('.class').html();
        var count = $('.questionCount').text();
        var answerExists = $('input[name="options"]:checked').val();
        var answer = $('input[name="options"]:checked').attr('data-option-id');
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
                var selected = $('.options');
                // selected.checked = false;
                console.log(response);

                var chosenOption = sessionStorage.getItem('option'+response.id)
                if (chosenOption != undefined) {
                    document.getElementsByName('options')[chosenOption].checked = true
                }
                else {
                    var arr1 = document.getElementsByName('options');

                    for (var index = 0; index < arr1.length; index++) {
                        var element = arr1[index];
                        element.checked = false
                    }
                }


                if (buttonType == 'next') {
                    if (answerExists != undefined) {
                        sessionStorage.setItem('option'+questionID,answerExists)
                        calculateScore(questionID,answer)
                    }

                }
                else {
                    handle = setInterval(startTimer,1000);
                    $('.nxtButton').attr('data-button-type','next')
                    $('.nxtButton').html('Next Question');
                    $('.reloader').val("1")
                    $('.questionList').each(function() {
                        $(this).removeClass('disabled')
                    })
                    $('.prevButton').removeClass('disabled')
                    $('.submitBtn').prop('disabled',false)
                }

                if ($('.nxtButton').html() != "Next Question") {
                    $('.nxtButton').html("Next Question")
                    $('.nxtButton').attr("data-button-type",'next')
                }
                var length = response.options.length;
                $('.question').html(response.question)

                var shuffledOptions = response.options;

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

                $('.nxtButton').attr('data-question',parseInt(id)+1);
                $('.prevButton').attr('data-question',parseInt(id)-1);


                $('.question').attr('id',response.id);
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
        var currentTime = new Date().getTime();

        var futureTime = new Date(currentDay + " " + futureHour + ":" + futureMinute + ":" + futureSecond).getTime();

        var reloader = $('.reloader').val()


        var distance = futureTime - currentTime;

        // console.log(distance)
        sessionStorage.setItem('distance',distance)

        var newHours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        var newMinutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var newSeconds = Math.floor((distance % (1000 * 60)) / 1000);

        $('#hours').html(newHours)
        $('#minutes').html(newMinutes)
        $('#seconds').html(newSeconds)


        // If the count down is finished, write some text
        if (distance <= 0) {
            sessionStorage.clear();
            clearInterval(handle);
            submitQuestion();
            // document.getElementById("demo").innerHTML = "EXPIRED";
        }

        // clearInterval(handle);

    }

    function submitQuestion() {
        var questionID = $('.question').attr('id');
        var count = $('.questionCount').text();
        var subject = $('.subject').html().toLowerCase();
        var class_id = $('.class').html();
        var answerExists = $('input[name="options"]:checked').val();
        var answer = $('input[name="options"]:checked').attr('data-option-id');
        if (answerExists!=undefined) {
            calculateScore(questionID,answer)
        }

        $.ajax({
            url:'/submitQuestion',
            data: {
                subject:subject,
                class_id:class_id,
                count:count
            },
            method:'GET',
            success:function(response) {
                // console.log(response);
                window.location.href = "/success";
                sessionStorage.clear();
            },
            error:function(response) {
                console.log(response);
            }
        })
    }
})
