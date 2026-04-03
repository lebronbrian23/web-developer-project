<?php

Class Database {

    private static $instance = null;

    // Get the PDO connection instance
    public static function getConnection() {
        
        if (self::$instance === null) {
            // Create a new PDO instance with the database connection parameters
            self::$instance = new PDO(
                'mysql:host=' . env('DB_HOST') . ';dbname=' . env('DB_NAME'),
                env('DB_USER'),
                env('DB_PASS'),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]
            );
        }
        return self::$instance;

    }
}