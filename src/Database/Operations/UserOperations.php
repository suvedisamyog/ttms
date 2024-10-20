<?php

namespace App\TTMS\Database\Operations;

use PDO;

class UserOperations extends BaseOperation {

	/**
	 * Get all data from the users table.
	 *
	 * @return array
	 */
    public function get_all_data($data = array()): array {
		if(! empty($data)){
			$where_clause = $data['where_clause'];
			$where_clause_value = $data['where_clause_value'];
			$stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE $where_clause = $where_clause_value ORDER BY id DESC");


		}else{
			$stmt = $this->conn->prepare("SELECT * FROM $this->table ORDER BY id DESC");
		}
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

	/**
	 * Get individual data from id.
	 *
	 * @param int $id
	 * @return array
	 */
    public function get_individual_data_from_id(int $id ) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

	/**
	 * Get individual data from email.
	 * @param string $email
	 * @return array
	 */
	public function get_individual_data_from_email(string $email ) {
		$stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE email = :email");
		$stmt->bindParam(':email', $email);
		$stmt->execute();
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 * Insert data into the users table.
	 *
	 * @param array $data
	 * @return bool
	 */
    public function insert_data(array $data): bool | array {
		try{

			if(isset($data['email']) && $this->check_email_exists($data['email'])){
				return ['status' => 0, 'message' => 'Email already exists'];
			}
			if ( isset($data['password'] ) ){
				$data['password'] = $this->hash_password($data['password']);
			}
			if( isset($data['name']) && $this->check_name_exists($data['name'])){
				return ['status' => 0, 'message' => 'Name already exists'];

			}
			if( isset($data['confirm_password'])){
				unset($data['confirm_password']);
			}


			$columns = implode(", ", array_keys($data)); // Column names
			$values = ":" . implode(", :", array_keys($data)); // Values to bind

			$stmt = $this->conn->prepare("INSERT INTO $this->table ($columns) VALUES ($values)");
			foreach ($data as $key => &$value) {
				if (is_array($value)) {
					$value = json_encode($value);
				}
				$stmt->bindParam(":$key", $value);
			}
			return $stmt->execute();
		}catch(\Exception $e){
			lg($e->getMessage());
			return false;
		}
    }


	/**
	 * Update data in the users table.
	 *
	 * @param int $id
	 * @param array $data
	 * @return bool
	 */
    public function update_data(int $id, array $data): bool {
		//Key values to update.
		$setClause = implode(", ", array_map(function($key) {
			return "$key = :$key";
		}, array_keys($data)));

        $stmt = $this->conn->prepare("UPDATE $this->table SET $setClause WHERE id = :id");
		$data['id'] = $id;
        return $stmt->execute($data );
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
	/**
	 * Check if an email exists in the users table.
	 *
	 * @param string $email
	 * @return bool
	 */
	public function check_name_exists(string $name): bool {
		$stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE name = :name");
		$stmt->bindParam(':name', $name);
		$stmt->execute();
		return $stmt->rowCount() > 0 ? true : false;
	}

	/**
	 * Hash a password.
	 */
	public function hash_password(string $password): string {
		return password_hash($password, PASSWORD_BCRYPT);
	}
}
