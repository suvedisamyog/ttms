<?php

namespace App\TTMS\Database\Operations;

use App\TTMS\Database\Config;

use PDO;

abstract class BaseOperation {
    protected PDO $conn;


    public function __construct() {
        $this->conn = Config::getConnection();
    }

    abstract public function get_all_data(): array;

    abstract public function get_individual_data_from_id(int $id);

    abstract public function insert_data(array $data): bool | array;

    abstract public function update_data(int $id, array $data): bool;

    abstract public function delete_data(int $id): bool;
}
