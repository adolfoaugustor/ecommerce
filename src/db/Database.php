<?php

namespace src\db;

class Database
{
    private static $host = '127.0.0.1';
    private static $user = 'root';
    private static $pass = '1234';
//    private static $host = '192.168.10.10';
//    private static $user = 'homestead';
//    private static $pass = 'secret';
    private static $db = 'e_commerce';

    static function getConnection() {
        $connectionString = sprintf('mysql:host=%s;dbname=%s', self::$host, self::$db);
        $con = new \PDO($connectionString, self::$user, self::$pass);
        $con->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $con;
    }

    static function select($className = '\stdClass', $table = null, array $fields = [], array $whereAnd = [], $orderBy = [], $limit = null)
    {
        //echo json_encode([$whereAnd]); exit;
        $sql = sprintf(
            'SELECT %s FROM %s %s',
            implode(', ', $fields),
            $table,
            !empty($whereAnd) ? 'WHERE '.implode(' AND ', $whereAnd) : ''
        );

        if (!empty($orderBy)) {

            $sql .= ' ORDER BY ';

            $sql .= implode(', ', array_map(function($item) {
                return sprintf('%s %s', $item[0], $item[1]);
            }, $orderBy));
        }

        if (!empty($limit)) {

            $sql .= ' LIMIT '.intval($limit);
        }

//        echo json_encode([$sql]); exit;

        $stmnt = self::getConnection()->prepare($sql);

        $stmnt->execute();

        return $stmnt->fetchAll(\PDO::FETCH_CLASS, $className);
    }

    static function insert($className = '\stdClass', $table = '', $data = [])
    {
        $fields = array_keys($data);

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $table,
            implode(', ', array_map(function($item) { return '`'.$item.'`'; }, $fields)),
            implode(', ', array_map(function($item) { return ':'.$item; }, $fields))
        );

        $data = array_combine(array_map(function($item) {
            return ':'.$item;
        }, array_keys($data)), array_values($data));

        $con = self::getConnection();

        $con->beginTransaction();

        $stmnt = $con->prepare($sql);

        $stmnt->execute($data);

        $con->commit();

//        $id = $con->lastInsertId();
//
//        if (intval($id) > 0) {
//
//            $select = self::select($className, $table, ['*'], ['id = '.$id]);
//
//            if (empty($select)) return null;
//
//            return $select[0];
//        }

        return $stmnt->rowCount();
    }

    static function delete($id, $table = '')
    {
        $sql = sprintf(
            'DELETE FROM %s WHERE ID IN (:id)',
            $table
        );

        $stmnt = self::getConnection()->prepare($sql);

        $stmnt->execute([
            ':id' => implode(', ', $id)
        ]);

        return $stmnt->rowCount();
    }

    static function createTableProducts() {

        $sql = <<<EOT
            CREATE TABLE IF NOT EXISTS `products` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(191) NOT NULL,
                `slug` VARCHAR(191) NOT NULL UNIQUE,
                `image` VARCHAR(191) NULL DEFAULT NULL,
                `price` FLOAT NOT NULL DEFAULT 0.00,
                `description` TEXT NULL,
                `weight` VARCHAR(191) NOT NULL,
                `height` INT NOT NULL,
                `width` INT NOT NULL,
                `length` INT NOT NULL,
                `diameter` INT NOT NULL,
                `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
                `created_at` TIMESTAMP NOT NULL DEFAULT NOW()
            )
EOT;
        return self::getConnection()->exec($sql);
    }

    static function createTablePromotions()
    {
        $sql = <<<EOT
            CREATE TABLE IF NOT EXISTS `promotions` (
                `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `product_id` INT UNSIGNED NOT NULL,
                `start_date` DATE NOT NULL,
                `end_date` DATE NOT NULL,
                `price` FLOAT NOT NULL DEFAULT 0.00,
                `updated_at` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
                `created_at` TIMESTAMP NOT NULL,
                CONSTRAINT `promotions_product_id_fk` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`)
            )
EOT;

        return self::getConnection()->exec($sql);
    }
}
