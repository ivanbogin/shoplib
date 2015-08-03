<?php

namespace ShopLib\Stock\Exception;

class OutOfStockException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Not enough products on stock.');
    }
}
