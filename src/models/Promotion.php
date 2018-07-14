<?php

namespace src\models;

use src\traits\DAOStaticFunctions;

class Promotion extends DAO
{
    use DAOStaticFunctions;

    const TABLE = 'promotions';

    public $id;
    public $product_id;
    public $start_date;
    public $end_date;
    public $price;
    public $updated_at;
    public $created_at;

    public function getProduct()
    {
        return Product::find($this->product_id);
    }

    public function getPrice($prefix = 'R$', $decimals = 2, $decimalPoint = ',', $thousandsSeparator = '')
    {
        return $prefix.' '.number_format($this->price, $decimals, $decimalPoint, $thousandsSeparator);
    }

    public function remove() {

        $result = self::delete($this->id);

        return $result;
    }
}
