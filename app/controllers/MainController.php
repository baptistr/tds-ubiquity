<?php
namespace controllers;

 use Ajax\php\ubiquity\JsUtils;
 use models\Basket;
 use models\Order;
 use models\Section;
 use models\User;
 use Ubiquity\attributes\items\router\Route;
 use Ubiquity\controllers\auth\AuthController;
 use Ubiquity\controllers\auth\WithAuthTrait;
 use models\Product;
 use Ubiquity\controllers\Router;
 use Ubiquity\orm\DAO;
 use Ubiquity\utils\http\URequest;
 use Ubiquity\utils\http\UResponse;
 use Ubiquity\utils\http\USession;

 /**
  * Controller MainController
  * @property  JsUtils $jquery
  */

class MainController extends ControllerBase{

    use WithAuthTrait;

    #[Route('_default',name: 'home')]
    public function index(){
        $productPromo = DAO::getAll(Product::class, 'promotion< ?', false, [0]);
        $nbCommand = DAO::count(Order::class, 'idUser=?', [USession::get("idUser")]);
        $nbBasket = DAO::count(Basket::class, 'idUser=?', [USession::get("idUser")]);
        $productsInSession = USession::get('productsInSession');
        $this->loadDefaultView(['productPromo'=>$productPromo, 'nbCommand'=>$nbCommand, 'nbBasket'=>$nbBasket, 'productsInSession'=>$productsInSession]);
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
        $productsInSession = USession::get('productsInSession');
        $this->loadDefaultView(['listSection'=>$listSection, 'productPromo'=>$productPromo, 'productsInSession'=>$productsInSession]);
    }

    #[Route('newBasket', name:'newBasket')]
    public function newBasket(){
        $listBasket = DAO::getAll(Order::class, 'idUser= ?', false, [USession::get("idUser")]);
        $reponse = URequest::post("name");
        if($reponse != null){
            $user = DAO::getById(User::class, USession::get("idUser"), false);
            $newBasket = new Basket();
            $newBasket->setUser($user);
            $newBasket->setName($reponse);
            UResponse::header('location', '/'.Router::path('basket'));
        }
        $this->loadDefaultView(['listBasket'=>$listBasket]);
    }

    #[Route('basket', name:'basket')]
    public function basket(){
        $basket = DAO::getAll(Basket::class, 'idUser= ?', false, [USession::get("idUser")]);
        $this->loadDefaultView(['basket'=>$basket]);
    }

    #[Route(path:"basket/add/{idP}", name:"addProduct")]
    public function addProduct($idP){
        $product = DAO::getById(Product::class, $idP, false);
        $basket = USession::get('defaultBasket');
        $basket->addProduct($product, 1);
        UResponse::header('location', '/'.Router::path('store'));
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
        $productsInSession = USession::get("productsInSession");
        if($productsInSession == null || count($productsInSession) == 0){
            $productsInSession[] = "val temporaire";
            \array_unshift($productsInSession, $actualProduct);
            unset($productsInSession[0]);
        }
        else
            \array_unshift($productsInSession, $actualProduct);
        USession::set('productsInSession', \array_slice($productsInSession,0,5));
        $this->loadDefaultView(['listSection'=>$listSection, 'actualSection'=>$actualSection, 'actualProduct'=>$actualProduct]);
    }

    protected function getAuthController(): AuthController
    {
        return new MyAuth($this);
    }

}
