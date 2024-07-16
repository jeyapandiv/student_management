<?php
require_once 'config/database.php';

class Student
{
    private $db; //db connection instance

    //initialize database connection
    public function __construct()
    {
        $this->db = new Database(); //new db instance
    }

    //fetch students with optional search, sorting, pagination
    public function getStudents($search = '', $sort = 'id', $order = 'ASC', $limit = 10, $offset = 0)
    {
        $search = "%$search%"; //preparing wildcard character search term for full text search

        //query with placeholders for search,sorting,limit,offset
        $this->db->query("SELECT * FROM students WHERE name LIKE :search OR roll_number LIKE :search ORDER BY $sort $order LIMIT :limit OFFSET :offset");
        //binding search term,limit,offset values to prepared statement
        $this->db->bind(':search', $search);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT); //limit as integer
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        return $this->db->resultSet(); //execution & return result set
    }

    //get count of students with optional search
    public function getStudentCount($search = '')
    {
        $search = "%$search%";
        
        //query to count students matching the search term
        $this->db->query("SELECT COUNT(*) as count FROM students WHERE name LIKE :search OR roll_number LIKE :search");
        //binding search term 
        $this->db->bind(':search', $search);
        return $this->db->single();
    }

    //fetch result by ID
    public function getStudentById($id)
    {
        //query to fetch student by ID
        $this->db->query("SELECT * FROM students WHERE id = :id");
        //binding student ID 
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    //add new student
    public function addStudent($name, $roll_number)
    {
        //query to insert new student record
        $this->db->query("INSERT INTO students (name, roll_number) VALUES (:name, :roll_number)");
        //binding name,roll number 
        $this->db->bind(':name', $name);
        $this->db->bind(':roll_number', $roll_number);
        return $this->db->execute();
    }

    //update existing  record
    public function updateStudent($id, $name, $roll_number)
    {
        //query to update name and roll number by ID
        $this->db->query("UPDATE students SET name = :name, roll_number = :roll_number WHERE id = :id");
        //binding ID,name,roll number 
        $this->db->bind(':id', $id);
        $this->db->bind(':name', $name);
        $this->db->bind(':roll_number', $roll_number);
        return $this->db->execute();
    }

    //delete student record by ID
    public function deleteStudent($id)
    {
        //query to delete the student record by ID
        $this->db->query("DELETE FROM students WHERE id = :id");
        //binding student ID
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
