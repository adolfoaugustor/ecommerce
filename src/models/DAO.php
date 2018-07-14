<?php

namespace src\models;

use src\db\Database;

abstract class DAO
{
    public function _select(array $fields = array(), array $whereAnd = array(), $orderBy = [], $limit = null) {

        $className = get_class($this);

        $fields = getDatabaseFields($fields, $className);

        return Database::select($className, $className::TABLE, $fields, $whereAnd, $orderBy, $limit);
    }

    public function _find($id = null, array $fields = array()) {

        $className = get_class($this);

        $fields = getDatabaseFields($fields, $className);

        $whereAnd = ['id = '.$id];

        return Database::select($className, $className::TABLE, $fields, $whereAnd);
    }

    public function _insert(array $data = array()) {

        $className = get_class($this);

        return Database::insert($className, $className::TABLE, $data);
    }

    public function _delete(array $id = []) {

        $className = get_class($this);

        return Database::delete($id, $className::TABLE);
    }
}
