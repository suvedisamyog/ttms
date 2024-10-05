<?php

namespace App\TTMS\Database\Operations;

use PDO;

class UserOperations extends BaseOperation {

	private $table = 'users';

	/**
	 * Get all data from the users table.
	 *
	 * @return array
	 */
    public function get_all_data(): array {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

	/**
	 * Get individual data from the users table.
	 *
	 * @param int $id
	 * @return array
	 */
    public function get_individual_data(int $id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

	/**
	 * Insert data into the users table.
	 *
	 * @param array $data
	 * @return bool
	 */
    public function insert_data(array $data): bool {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (username, email, password, role) VALUES (:username, :email, :password, :role)");
        return $stmt->execute($data);
    }


	/**
	 * Update data in the users table.
	 *
	 * @param int $id
	 * @param array $data
	 * @return bool
	 */
    public function update_data(int $id, array $data): bool {
        $stmt = $this->conn->prepare("UPDATE $this->table SET username = :username, email = :email, password = :password, role = :role WHERE id = :id");
        $data['id'] = $id;
        return $stmt->execute($data);
    }


	/**
	 * Delete data from the users table.
	 *
	 * @param int $id
	 * @return bool
	 */
    public function delete_data(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = :id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

	/**
	 * Check if an email exists in the users table.
	 *
	 * @param string $email
	 * @return bool
	 */
	public function check_email_exists(string $email): bool {
		$stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE email = :email");
		$stmt->bindParam(':email', $email);
		$stmt->execute();
		return $stmt->rowCount() > 0 ? true : false;
	}
}
