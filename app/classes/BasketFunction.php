<?php

namespace classes;

use Ajax\semantic\widgets\datatable\DataTable;
use models\Product;
use ArrayObject;
use models\Basket;
use models\Basketdetail;
use models\User;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\UResponse;
use Ubiquity\utils\http\USession;

class BasketFunction{

    private $basket;

    public function __construct($basket)
    {
        $this->idBasket = $basket->getId();
        $this->basket = $basket;
    }

    public function addProduct($article, $quantity)
    {
        if(DAO::getOne(Basketdetail::class,'idProduct = ?',false,[$article->getId()])){
            echo("Un produit existe déjà");
        }else{
            $basketDetail = new Basketdetail();
            $basketDetail->setBasket($this->basket);
            $basketDetail->setProduct($article);
            $basketDetail->setQuantity($quantity);
            DAO::save($basketDetail);
        }
    }

}