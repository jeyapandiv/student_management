<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once 'models/Student.php';

class StudentController {
    private $studentModel; //student model instance

    //initialize the student model
    public function __construct() {
        $this->studentModel = new Student(); //new student model instance
    }

    //fetch paginated list of results
    public function getStudents() {
        //retrieve search,sorting,order,pagination parameters from query string ($_GET)
        $search = $_GET['search'] ?? '';
        $sort = $_GET['sort'] ?? 'id';
        $order = $_GET['order'] ?? 'ASC';
        $page = $_GET['page'] ?? 1;
        $limit = 10; //limit per page
        $offset = ($page - 1) * $limit; //offset calculation based on current page

        //list of results based on parameters
        $students = $this->studentModel->getStudents($search, $sort, $order, $limit, $offset);
        
        //total count of results matching the search criteria
        $total = $this->studentModel->getStudentCount($search)['count'];
        
        //return response(JSON) with fetched students, total count, page number, and limit
        echo json_encode([
            'students' => $students,
            'total' => $total,
            'page' => $page,
            'limit' => $limit
        ]);
    }

    //single result by ID
    public function getStudent($id) {
        //result by ID
        $student = $this->studentModel->getStudentById($id);
        
        //return response(JSON) with fetched result
        echo json_encode($student);
    }

    //add or update data
    public function saveStudent() {
        //retrive data(JSON) from request, decode to associative array
        $data = json_decode(file_get_contents('php://input'), true);

        //checking the existance of 'id' in the data to decide to add or update
        if (empty($data['id'])) {
            //update the student record
            $this->studentModel->updateStudent($data['id'], $data['name'], $data['roll_number']);
        } else {
            //add student record
            $this->studentModel->addStudent($data['name'], $data['roll_number']);
        }
        header('Content-Type: application/json');
        //return success response(JSON)
        echo json_encode(['success' => true]);
    }

    //delete record by ID
    public function deleteStudent($id) {
        $this->studentModel->deleteStudent($id);
        
        //return success response(JSON)
        echo json_encode(['success' => true]);
    }
}
