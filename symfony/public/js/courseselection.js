function newCourse() {
    $.ajax({
        url: '/course/add/',
        type: 'POST',
        data: 'New Course',
        success: function(data) {
            if (data != undefined || data != '' || data != []) {
                window.location.href = '/course/edit/' + data.id;
            }
        }
    });
}

function startCourse(id) {
    window.location.href = '/session/start/' + id;
}

function editCourse(id) {
    window.location.href = '/course/edit/' + id;
}

function deleteCourse(id) {
    $.ajax({
        url: 'http://localhost/course/delete/' + id,
        type: 'GET',
        success: function(data) {
            if (data != undefined || data != '' || data != []) {
                removeCourseElement(id);
            }
        }
    });
}

function removeCourseElement(id) {
    $(`#course_${id}`).remove();
}