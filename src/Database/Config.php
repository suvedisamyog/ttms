<?php

namespace App\TTMS\Database;

class Config {
	private static $host = "localhost";
	private static $db_name = "ttms";
	private static $username = "root";
	private static $password = "root";
	private static $conn;
	
	public static function getConnection() {
		self::$conn = null;
		try {
			self::$conn = new \PDO("mysql:host=" . self::$host . ";dbname=" . self::$db_name, self::$username, self::$password);
			self::$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		} catch(\PDOException $exception) {
			die ("Connection error: " . $exception->getMessage());
		}
		return self::$conn;
	}
}
