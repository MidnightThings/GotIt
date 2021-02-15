function newQuestion(courseId) {
    console.log('newQuestion');

    $.ajax({
        url: 'http://localhost/question/add/'+courseId,
        type: 'GET',
        success: function() {
            //TODO: optimize reloads
            window.location.href = 'http://localhost/course/' + courseId;
        },
        error: function(request) {
            console.log(JSON.stringify(request));
        }
    });
}

function deleteQuestion(courseId, questionId) {
    console.log('deleting Question id: ', questionId, 'in course id: ', courseId);

    $.ajax({
        url: 'http://localhost/question/delete/'+courseId+'/'+questionId,
        type: 'GET',
        success: function() {
            window.location.href = 'http://localhost/course/' + courseId;
        },
        error: function(request) {
            console.log(JSON.stringify(request));
        }
    });
}

function changeQuestion(courseId, questionId, newString) {
    console.log('question: '+questionId+' changed')

    $.ajax({
        url: 'http://localhost/question/edit/'+courseId+'/'+questionId,
        type: 'POST',
        data: newString,
        success: function() {
            console.log('changed successfully');
        },
        error: function(request) {
            console.log(JSON.stringify(request));
        }
    });
}

function changeCourseName(courseId, newName) {
    console.log('question: '+courseId+' changed')
    newName = $('#courseName').val();

    $.ajax({
        url: 'http://localhost/course/edit/'+courseId,
        type: 'POST',
        data: newName,
        success: function(data) {
            console.log(data.message);
        },
        error: function(request) {
            console.log(JSON.stringify(request));
        }
    });
}
