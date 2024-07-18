$(document).ready(function() {
    //loading student list 
    loadStudents();

    //adding or updating student
    $('#studentForm').submit(function(e) {
        e.preventDefault(); 
        
        //student data from form
        var studentData = {
            id: '',
            name: $('#name').val(),
            roll_number: $('#roll_number').val()
        };

        // console.log(studentData);

        // AJAX request to save data
        $.ajax({
            url: '/students/save', //endpoint to save
            type: 'POST', 
            contentType: 'application/json', //for sending JSON data
            data: JSON.stringify(studentData), 
            //convertion to string
            success: function(response) {
                // console.log(response);
                alert('Student saved successfully'); //success message
                $('#studentForm')[0].reset(); //clearing form
                loadStudents(); //reloading student list
            }
        });
    });

    //handle click event for editing a student
    $(document).on('click', '.edit', function() {
        var id = $(this).data('id'); //get student ID from data attribute
        
        //AJAX request to fetch student details
        $.get('/students/' + id, function(data) {
            var student = JSON.parse(data); //parse JSON response into JavaScript object
            $('#studentId').val(student.id); //populate form fields with student details
            $('#name').val(student.name);
            $('#roll_number').val(student.roll_number);
        });
    });

    //handle click event for deleting a student
    $(document).on('click', '.delete', function() {
        var id = $(this).data('id'); //get student ID from data attribute
        
        //AJAX request to delete student
        $.ajax({
            url: '/students/delete/' + id, //endpoint to handle delete operation
            type: 'DELETE', //HTTP method
            success: function(response) {
                alert('Student deleted successfully'); //show success message
                loadStudents(); //reload student list
            }
        });
    });

    //handle input event for search input
    $('#search').on('input', function() {
        loadStudents(); //reload student list when search input changes
    });

    //handle click event for sorting students
    $(document).on('click', '.sort', function() {
        var sort = $(this).data('sort'); //get sorting parameter from data attribute
        loadStudents(sort); //reload student list with specified sorting
    });
});

//load students based on search, sort, order, and pagination
function loadStudents(sort = 'id', order = 'ASC', page = 1) {
    var search = $('#search').val(); //search value
    
    
    //AJAX request to fetch list of students
    $.get('/students/list', { 
        search: search, 
        sort: sort, 
        order: order, 
        page: page 
    }, function(data) {
        // console.log(data);
        var response = JSON.parse(data); //parse JSON response (object)
        var students = response.students; //extract students(array) from response
        var studentList = ''; //empty string for HTML list of students
        
        //HTML for student list
        students.forEach(function(student) {
            studentList += `<tr>
                <td>${student.name}</td>
                <td>${student.roll_number}</td>
                <td>
                    <button class="edit" data-id="${student.id}">Edit</button>
                    <button class="delete" data-id="${student.id}">Delete</button>
                </td>
            </tr>`;
        });
        
        //update student list in the HTML
        $('#studentList').html(studentList);

        //pagination logic
        var total = response.total; //total number of students
        var limit = response.limit; //number of students per page
        var totalPages = Math.ceil(total / limit); //calculate total pages
        
        var pagination = ''; //initialize empty string for pagination links
        
        //build pagination links
        for (var i = 1; i <= totalPages; i++) {
            pagination += `<a href="#" class="page-link" data-page="${i}">${i}</a> `;
        }
        
        //update pagination links in the HTML
        $('#pagination').html(pagination);
    });
}




