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
        //$productRecent = USession::get('recentlyViewedProducts');
        $this->loadDefaultView(['productPromo'=>$productPromo, 'nbCommand'=>$nbCommand, 'nbBasket'=>$nbBasket, 'productRecent'=>$productRecent]);
    }

    #[Route('order', name:'order')]
    public function order(){
        $listOrder = DAO::getAll(Order::class, 'idUser=?', false, [USession::get("idUser")]);
        $this->loadDefaultView(['listOrder'=>$listOrder]);
    }

    #[Route('store', name:'store')]
    public function store(){
        $listSection = DAO::getAll(Section::class, false, ['products']);
        $productPromo = DAO::getAll(Product::class, 'promotion< ?', false, [0]);
        $this->loadDefaultView(['listSection'=>$listSection, 'productPromo'=>$productPromo]);
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

    #[Route ('section/{id}', name:'section')]
    public function section($id){
        $listProductBySection = DAO::getAll(Product::class, 'idSection= '.$id, [USession::get("idSection")]);
        $listSection = DAO::getAll(Section::class, false, ['products']);
        $actualSection = DAO::getById(Section::class, $id, false);
        $this->loadDefaultView(['listSection'=>$listSection, 'listProductBySection'=>$listProductBySection, 'actualSection'=>$actualSection]);
    }

    #[Route ('product/{idS}/{idP}', name:'productUnit')]
    public function product($idS, $idP){
        $actualSection = DAO::getById(Section::class, $idS, false);
        $actualProduct = DAO::getById(Product::class,$idP,['sections']);
        $listSection = DAO::getAll(Section::class, false, ['products']);
        $this->loadDefaultView(['listSection'=>$listSection, 'actualSection'=>$actualSection, 'actualProduct'=>$actualProduct]);
    }

    protected function getAuthController(): AuthController
    {
        return new MyAuth($this);
    }

}
