<?php
declare(strict_types = 1);

namespace App\Repositories;

use App\Database;
use PDO;

class EmployeeRepository
{

    public function __construct(private Database $database)
    {

    }
    public function getAll(): array
    {
        $pdo = $this->database->getConnection();

        $stmt = $pdo->query('SELECT * FROM employees');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id) : array|bool
    {
        $sql = 'SELECT *
        FROM employees
        WHERE id = :id';

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id' , $id , PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);

    }

    public function create(array $data):string
    {
        $sql = 'INSERT INTO employees (name, designation, salary)
                VALUES (:name , :designation , :salary)';

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':name' , $data['name'] , PDO::PARAM_STR);
        $stmt->bindValue(':designation' , $data['designation'] , PDO::PARAM_STR);
        $stmt->bindValue(':salary' , $data['salary'] , PDO::PARAM_INT);
        $stmt->execute();

        return $pdo->lastInsertId();
    }

    public function update(int $id , array $data):int
    {
        $sql = 'UPDATE employees
                SET name = :name,
                    designation = :designation,
                    salary = :salary
                WHERE id = :id';

        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':name' , $data['name'] , PDO::PARAM_STR);
        $stmt->bindValue(':designation' , $data['designation'] , PDO::PARAM_STR);
        $stmt->bindValue(':salary' , $data['salary'] , PDO::PARAM_INT);
        $stmt->bindValue(':id' , $id , PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function delete(string $id):int
    {
        $sql = 'DELETE FROM employees
                WHERE id = :id';
        $pdo = $this->database->getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id' , $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount();
    }
}