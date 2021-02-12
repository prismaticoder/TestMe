$(function() {
    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    // $('#showModal').on('click', function() {
    //     $('#exampleModalCenter').show();
    // })

    $("#summmernote").trigger("focus");

    $("#questionList").on("click", ".questionBtn", function() {
        $(this).addClass("active");
        $(".questionBtn")
            .not(this)
            .removeClass("active");
        id = this.id;
        getSelectedQuestion(id);
    });

    $(".markSubmit").on("submit", function(event) {
        event.preventDefault();
        var buttonType = $(this).attr("data-form-type");
        var hours = $(".hours").val();
        var minutes = $(".minutes").val();
        var score = $(".scores").val();
        var subject_id = $("#subject_id").val();
        var class_id = $("#class_id").val();
        if (buttonType == "set") {
            $.ajax({
                url: "/setSubjectMark",
                method: "POST",
                data: {
                    hours: hours,
                    minutes: minutes,
                    mark: score,
                    subject_id: subject_id,
                    class_id: class_id
                },
                success: function(response) {
                    alert(response);
                    window.location.reload(true);
                },
                error: function(response) {
                    console.log(response);
                }
            });
        } else {
            $.ajax({
                url: "/updateSubjectMark",
                method: "POST",
                data: {
                    hours: hours,
                    minutes: minutes,
                    mark: score,
                    id: $(this).attr("id")
                },
                success: function(response) {
                    alert(response);
                    window.location.reload(true);
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }
    });

    $(".addBtn").click(function() {
        $("#summernote").summernote("reset");
        $(".option").each(function() {
            $(this).summernote("reset");
        });
        $("#summmernote").trigger("focus");
        document.getElementById("myForm").reset();
        $(".submitBtn").html("Submit Question");
        $(".submitBtn").attr("data-button-type", "add-question");
        $(".submitBtn").attr("id", "");
        $(".questionBtn").removeClass("active");
    });

    $(".submitBtn").on("click", function(event) {
        event.preventDefault();
        var type = $(this).attr("data-button-type");
        var id = $(this).attr("id");
        if (type == "question-update") {
            // alert(id);
            updateQuestion(id);
        } else {
            addQuestion();
        }
    });

    $("#questionList").on("click", ".deleteBtn", function() {
        var id = this.id;
        deleteQuestion(id);
    });

    function getSelectedQuestion(id) {
        $.ajax({
            url: "/admin/findQuestion/" + id,
            method: "GET",
            success: function(response) {
                $("#summernote").summernote("code", response.question);
                $(".submitBtn").html("Update Question");
                $(".submitBtn").attr("data-button-type", "question-update");
                $(".submitBtn").attr("id", id);
                $(".addBtn").attr("disabled", false);
                console.log(response);
                var length = response.options.length;

                if (length >= 4) {
                    $(".option").each(function(index) {
                        $(this).summernote(
                            "code",
                            response.options[index].body
                        );
                        $(this).attr(
                            "data-option-id",
                            response.options[index].id
                        );
                    });
                } else {
                    $(".option").each(function(index) {
                        if (index <= length - 1) {
                            $(this).summernote(
                                "code",
                                response.options[index].body
                            );
                            $(this).attr(
                                "data-option-id",
                                response.options[index].id
                            );
                        } else {
                            $(this).summernote("code", "No Option");
                        }
                    });
                }

                for (var index = 0; index < response.options.length; index++) {
                    var element = response.options[index];
                    if (element.isCorrect == 1) {
                        var options = document.getElementsByName("correct");
                        options[index].checked = true;
                    }
                }
            },
            error: function(response) {
                console.log(response);
            }
        });
    }

    //Update Existing Question
    function updateQuestion(id) {
        var question = $("#summernote").val();
        var options1 = $(".option");
        var options = [];
        options1.each(function() {
            options.push({
                id: $(this).attr("data-option-id"),
                value: $(this).summernote("code")
            });
        });
        var correct = $('input[name="correct"]:checked').val();
        $.ajax({
            url: "/updateQuestion/" + id,
            method: "POST",
            data: {
                question: question,
                options: options,
                correct: correct
            },
            success: function(response) {
                alert("Question Updated Successfully");
            },
            error: function(response) {
                console.log(response);
            }
        });
    }

    function addQuestion() {
        var question = $("#summernote").val();
        var options1 = $(".option");
        var options = [];
        var subject_id = $("#subject_id").val();
        var class_id = $("#class_id").val();
        options1.each(function() {
            options.push($(this).summernote("code"));
        });
        console.log(options);
        var correct = $('input[name="correct"]:checked').val();
        $.ajax({
            url: "/addQuestion",
            method: "POST",
            data: {
                question: question,
                options: options,
                correct: correct,
                subject_id: subject_id,
                class_id: class_id
            },
            success: function(response) {
                alert("Question Added Successfully");
                console.log(response);
                $(".list-group").append(
                    '<span class="questionBtn list-group-item list-group-item-action" id="' +
                        response.question.id +
                        '">Question ' +
                        response.count +
                        ' <i id="' +
                        response.question.id +
                        '" title="Delete Question" class="deleteBtn fa fa-2x fa-close" style="float:right"></i></span>'
                );
                document.getElementById("myForm").reset();
                $(".option").each(function() {
                    $(this).summernote("reset");
                });
                $("#summernote").summernote("reset");
                window.location.hash = "#top";
            },
            error: function(response) {
                console.log(response);
            }
        });
    }

    function deleteQuestion(id) {
        if (confirm("Are you sure you want to delete this question?")) {
            $.ajax({
                url: "/deleteQuestion/" + id,
                method: "POST",
                success: function(response) {
                    alert(response);
                    window.location.reload(true);
                },
                error: function(response) {
                    console.log(response);
                }
            });
        }
    }
});
