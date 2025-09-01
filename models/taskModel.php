<?php

declare(strict_types=1);

final class TaskModel
{
    private $conn;
    
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getAllTasks() {
        $sql = "SELECT * FROM task";
        $stmt = $this->conn->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function newTask($title, $description) {
        $sql = "INSERT INTO task (`title`, `description`) VALUES (:title, :description)";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([':title'=>$title, ':description' => $description]);
        return $result;
    }

    public function removeTask($id){
        $sql = " DELETE FROM task WHERE id= :id";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([':id'=>$id]);
        return $result;
    }

    public function updateTask($title, $description, $id) {
        $sql = " UPDATE task SET title=:title, description = :description  WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([':title'=>$title, ':description' => $description,':id'=>$id ]);
        return $result;
    }


    
}
