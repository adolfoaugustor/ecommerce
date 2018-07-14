<?php

namespace src\traits;

trait DAOStaticFunctions
{
    static function getFields() {

        return array_keys(get_class_vars(__CLASS__));
    }

    static function getAll(array $fields = array(), array $whereAnd = array(), array $orderBy = [], $limit = null) {

        $orderBy = array_merge([['updated_at', 'desc']], $orderBy);

        return (new self())->_select($fields, $whereAnd, $orderBy, $limit);
    }

    static function find($id = null, array $fields = array()) {

        $result = (new self())->_find($id, $fields);

        if (empty($result)) return null;

        return $result[0];
    }

    static function create(array $data = array()) {

        return (new self())->_insert($data);
    }

    static function findBySlug($slug = null, array $fields = array()) {

        $whereAnd = ["slug = '$slug'"];

        $result = (new self())->_select($fields, $whereAnd);

        if (empty($result)) return null;

        return $result[0];
    }

    static function delete($id)
    {
        if (!is_array($id)) $id = [$id];

        return (new self())->_delete($id);
    }
}
