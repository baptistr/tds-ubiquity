<?php
namespace controllers;

 use Ajax\php\ubiquity\JsUtils;
 use models\Order;
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
        $this->loadDefaultView(['productPromo'=>$productPromo, 'nbCommand'=>$nbCommand]);
    }

    protected function getAuthController(): AuthController
    {
        return new MyAuth($this);
    }

}
