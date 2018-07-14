<?php

namespace src\models;

use src\traits\DAOStaticFunctions;

class Product extends DAO
{
    use DAOStaticFunctions;

    const TABLE = 'products';

    public $id;
    public $name;
    public $slug;
    public $image;
    public $price;
    public $description;
    public $weight;
    public $height;
    public $width;
    public $length;
    public $diameter;
    public $updated_at;
    public $created_at;

    public function getPrice($prefix = 'R$', $decimals = 2, $decimalPoint = ',', $thousandsSeparator = '')
    {
        return $prefix.' '.number_format($this->price, $decimals, $decimalPoint, $thousandsSeparator);
    }

    public function promotions() {

        $now = date('Y-m-d');

        return Promotion::getAll(['*'], [
            'product_id = '.$this->id,
            "start_date <= '$now'",
            "end_date >= '$now'",
        ]);
    }

    public function latestPromotion()
    {
        $promotions = $this->promotions();

        return array_shift($promotions);
    }

    public function remove() {

        $publicFolder = __DIR__.'/../../public';

        $image = !empty($this->image) ? $publicFolder.'/'.$this->image : '';

        $result = self::delete($this->id);

        if ($result && !empty($image)) {

            @unlink($image);
        }

        return $result;
    }
}
