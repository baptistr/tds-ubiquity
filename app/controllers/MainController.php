<?php
namespace controllers;

 use Ajax\php\ubiquity\JsUtils;
 use models\Basket;
 use models\Order;
 use models\Section;
 use Ubiquity\attributes\items\router\Route;
 use Ubiquity\controllers\auth\AuthController;
 use Ubiquity\controllers\auth\WithAuthTrait;
 use models\Product;
 use Ubiquity\orm\DAO;
 use Ubiquity\utils\http\USession;

 /**
  * Controller MainController
  * @property  JsUtils $jquery
  */

class MainController extends ControllerBase{

    use WithAuthTrait;

    #[Route('_default',name: 'home')]
    public function index(){
        //$this->jquery->renderView("MainController/index.html"); pourquoi ça marche sans ??
        $productPromo = DAO::getAll(Product::class, 'promotion< ?', false, [0]);
        $nbCommand = DAO::count(Order::class, 'idUser=?', [USession::get("idUser")]);
        $nbBasket = DAO::count(Basket::class, 'idUser=?', [USession::get("idUser")]);
        $this->loadDefaultView(['productPromo'=>$productPromo, 'nbCommand'=>$nbCommand, 'nbBasket'=>$nbBasket]);
    }

    #[Route('order', name:'order')]
    public function order(){
        $listOrder = DAO::getAll(Order::class, 'idUser=?', false, [USession::get("idUser")]);
        $this->loadDefaultView(['listOrder'=>$listOrder]);
    }

    #[Route('store', name:'store')]
    public function store(){
        $listSection = DAO::getAll(Section::class, false, ['products']);
        $this->loadDefaultView(['listSection'=>$listSection]);
    }

    #[Route('newBasket', name:'newBasket')]
    public function newBasket(){
        $newbasket = DAO::getAll(Order::class, 'idUser= ?', false, [USession::get("idUser")]);
        $this->loadDefaultView(['newbasket'=>$newbasket]);
    }

    #[Route('Basket', name:'basket')]
    public function basket(){
        $basket = DAO::getAll(Basket::class, 'idUser= ?', false, [USession::get("idUser")]);
        $this->loadDefaultView(['basket'=>$basket]);
    }

    protected function getAuthController(): AuthController
    {
        return new MyAuth($this);
    }

}
