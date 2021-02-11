function newCourse() {
    console.log('newCourse');

    $.ajax({
        url: 'http://localhost/course/add',
        type: 'POST',
        success: function(data) {
            if (data != undefined || data != '' || data != []) {
                //window.location.href = `http://localhost/course/edit/${data.id}`;
                editCourse(data.id);
            } else {
                console.log('error');
                //do stuff
            }
        },
        error: function(request) {
            console.log(JSON.stringify(request));
        }
    });
}

function startCourse(id) {
    console.log('startCourse id: ', id);

    $.ajax({
        url : 'http://localhost/coursesession',
        type : 'POST',
        data : id,
        success: function(data) {
            if (data != undefined || data != '' || data != []) {
                window.location.href = `http://localhost/coursesession/' + ${data.code}`;
            } else {
                console.log('error');
                //do stuff
            }
        },
        error : function(request) {
            console.log(JSON.stringify(request));
        }
    });
    
}

function editCourse(id) {
    console.log('editCourse id: ', id);

    //maybe find better solution
    window.location.href = `http://localhost/course/edit/' + ${id}`;
}

function deleteCourse(id) {
    console.log('deleteCourse id: ', id);

    $.ajax({
        url: 'http://localhost/course/delete',
        type: 'POST',
        data: id,
        success: function(data) {
            if (data != undefined || data != '' || data != []) {
                console.log('deleted successfully');
                removeCourseElement(id);
            }
        }
    });
}

function removeCourseElement(id) {
    $(`#course_${id}`).remove();
}