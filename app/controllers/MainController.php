<?php
namespace controllers;

 use Ajax\php\ubiquity\JsUtils;
 use models\Basket;
 use models\Basketdetail;
 use models\Order;
 use models\Section;
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
        $this->loadView('MainController/index.html',['productPromo'=>$productPromo, 'nbCommand'=>$nbCommand, 'nbBasket'=>$nbBasket, 'productsInSession'=>$productsInSession]);
    }

    #[Route('order', name:'order')]
    public function order(){
        $listOrder = DAO::getAll(Order::class, 'idUser=?', false, [USession::get("idUser")]);
        $this->loadView('MainController/order.html',['listOrder'=>$listOrder]);
    }

    #[Route('store', name:'store')]
    public function store(){
        $listSection = DAO::getAll(Section::class, false, ['products']);
        $productPromo = DAO::getAll(Product::class, 'promotion< ?', false, [0]);
        $productsInSession = USession::get('productsInSession');
        $this->loadView('MainController/store.html',['listSection'=>$listSection, 'productPromo'=>$productPromo, 'productsInSession'=>$productsInSession]);
    }

    #[Route('newBasket', name:'newBasket')]
    public function newBasket(){
        $basket = DAO::getAll(Basket::class, 'idUser= ?', false, [USession::get("idUser")]);
        $nomDuPanier = URequest::post("name");
        if(!empty($nomDuPanier)){
            $user = $this->getAuthController()->_getActiveUser();
            $newBasket = new Basket();
            $newBasket->setUser($user);
            $newBasket->setName($nomDuPanier);
            /*$newBasket->setId(1001);*/ //pas besoin car se créer automatiquement
            /*$newBasket->setDateCreation(date("Y-m-d H:i:s"));*/ //pas besoin car il prend la date par default de la db
            DAO::save($newBasket);
            UResponse::header('location', '/'.Router::path('basket'));
        }
        $this->loadView('MainController/newBasket.html',['basket'=>$basket]);
    }

    #[Route('basket', name:'basket')]
    public function basket(){
        $baskets = DAO::getAll(Basket::class, 'idUser= ?', ['basketdetails'], [USession::get("idUser")]);
        $this->loadView('MainController/basket.html',['baskets'=>$baskets]);
    }

    #[Route('basketContent/{idBasket}', name:'basketContent')]
    public function basketContent($idBasket){
        $baskets = DAO::getAll(Basket::class, 'idUser= ?', ['basketdetails.product'], [USession::get("idUser")]);
        $basketDetails = DAO::getAll(Basketdetail::class, 'idBasket= '.$idBasket,['product']);
        $this->loadView('MainController/basketContent.html',['baskets'=>$baskets, 'basketDetails'=>$basketDetails]);
    }

    #[Route("basketDeleteProduct/{idB}/{idP}",name: "basketDeleteProduct")]
    public function basketDeleteProduct($idB,$idP){
        $remove = DAO::getOne(Basketdetail::class,'idBasket = ? AND idProduct = ?',false,[$idB, $idP]);
        DAO::remove($remove);
        $this->basket();
    }

    #[Route("basketUpQuantity/{idB}/{idP}",name: "basketUpQuantity")]
    public function basketUpQuantity($idB,$idP){
        $basketDetail = DAO::getOne(Basketdetail::class,'idBasket = ? AND idProduct = ?',false,[$idB, $idP]);
        $quantity = $basketDetail->getQuantity();
        $basketDetail->setQuantity($quantity+1);
        DAO::save($basketDetail);
        $this->basket();
    }

    #[Route("basketDownQuantity/{idB}/{idP}",name: "basketDownQuantity")]
    public function basketDownQuantity($idB,$idP){
        $basketDetail = DAO::getOne(Basketdetail::class,'idBasket = ? AND idProduct = ?',false,[$idB, $idP]);
        $quantity = $basketDetail->getQuantity();
        if($quantity == 1)
            DAO::remove($basketDetail);
        else{
            $basketDetail->setQuantity($quantity-1);
            DAO::save($basketDetail);
        }
        $this->basket();
    }

    #[Route("basket/add/{idP}", name:"addProduct")]
    public function addProduct($idP){
        $product = DAO::getById(Product::class, $idP, false);
        $basket = USession::get('defaultBasket');
        $idDuPanier = $basket->getId();
        $basketContent=DAO::getOne(Basketdetail::class,'idProduct= ? AND idBasket= ?', false, [$idP,$idDuPanier]);
        if($basketContent!==null){//Le produit est présent dans le panier
            $quantity = $basketContent->getQuantity();
            $basketContent->setQuantity($quantity+1);
            DAO::save($basketContent);
        }
        else{
            $newBasketContent = new Basketdetail();
            $newBasketContent->setProduct($product);
            $newBasketContent->setBasket($basket);
            $newBasketContent->setQuantity(1);
            DAO::save($newBasketContent);
        }
        $this->store();
    }

    #[Route("basket/addTo/panierSpecifique/{idP}",name: "addProductTo")]
    public function addProductTo($idP){
        $product = DAO::getById(Product::class, $idP, false);
        $idDuPanier = URequest::post("id");
        $basket = DAO::getById(Basket::class, $idDuPanier, false);

        $basketContent=DAO::getOne(Basketdetail::class,'idProduct= ? AND idBasket= ?', false, [$idP,$idDuPanier]);
        if($basketContent!==null){//Le produit est présent dans le panier
            $quantity = $basketContent->getQuantity();
            $basketContent->setQuantity($quantity+1);
            DAO::save($basketContent);
        }
        else{
            $newBasketContent = new Basketdetail();
            $newBasketContent->setProduct($product);
            $newBasketContent->setBasket($basket);
            $newBasketContent->setQuantity(1);
            DAO::save($newBasketContent);
        }
        $this->store();
    }

    #[Route ('section/{id}', name:'section')]
    public function section($id){
        $listProductBySection = DAO::getAll(Product::class, 'idSection= '.$id, [USession::get("idSection")]);
        $listSection = DAO::getAll(Section::class, false, ['products']);
        $actualSection = DAO::getById(Section::class, $id, false);
        $this->loadView('MainController/section.html',['listSection'=>$listSection, 'listProductBySection'=>$listProductBySection, 'actualSection'=>$actualSection]);
    }

    #[Route ('product/{idS}/{idP}', name:'productUnit')]
    public function product($idS, $idP){
        $actualSection = DAO::getById(Section::class, $idS, false);
        $actualProduct = DAO::getById(Product::class,$idP,['sections']);
        $listSection = DAO::getAll(Section::class, false, ['products']);
        $basket = DAO::getAll(Basket::class, 'idUser= ?', false, [USession::get("idUser")]);
        $productsInSession = USession::get("productsInSession");
        if($productsInSession == null || count($productsInSession) == 0){
            $productsInSession[] = "val temporaire";
            \array_unshift($productsInSession, $actualProduct);
            unset($productsInSession[0]);
        }
        else
            \array_unshift($productsInSession, $actualProduct);
        USession::set('productsInSession', \array_slice($productsInSession,0,5));
        $this->loadView('MainController/product.html',['listSection'=>$listSection, 'actualSection'=>$actualSection, 'actualProduct'=>$actualProduct, 'basket'=>$basket]);
    }

    protected function getAuthController(): AuthController
    {
        return new MyAuth($this);
    }

}
