function newQuestion(courseId) {
    $.ajax({
        url: '/question/add/' + courseId,
        type: 'GET',
        success: function() {
            window.location.href = '/course/edit/' + courseId;
        }
    });
}

function deleteQuestion(courseId, questionId) {
    $.ajax({
        url: '/question/delete/' + courseId + '/' + questionId,
        type: 'GET',
        success: function(data) {
            if (data != undefined || data != '' || data != []) {
                removeQuestionFromUI(questionId);
            }
        }
    });
}

function updateQuestion(courseId, questionId) {
    var questionData = getQuestionData(questionId);

    $.ajax({
        url: '/question/edit/' + courseId + '/' + questionId,
        type: 'POST',
        data: questionData,
        success: function() {
            //window.location.href = '/course/edit/' + courseId;
        }
    });
}

function updateCourseName(courseId, newName) {
    newName = $('#courseName').val();

    $.ajax({
        url: '/course/name/' + courseId,
        type: 'POST',
        data: newName,
        success: function() {
            //window.location.href = '/course/edit/' + courseId;
        }
    });
}

function getQuestionData(questionId) {
    var questionData = {};
    questionData.questionContent = getQuestionContent(questionId);
    questionData.sortOrderValue = getSortOrderValue(questionId);

    return questionData;
}

function getQuestionContent(questionId) {
    return $('#question_' + questionId + ' .questionContents').val();
}

function getSortOrderValue(questionId) {
    return parseInt($('#question_' + questionId + ' .questionSortOrder').val());
}

function removeQuestionFromUI(questionId) {
    $('#question_' + questionId).remove();
}
