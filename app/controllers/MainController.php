<?php
namespace controllers;

 use Ajax\php\ubiquity\JsUtils;
 use Ubiquity\attributes\items\router\Route;
 use Ubiquity\controllers\auth\AuthController;
 use Ubiquity\controllers\auth\WithAuthTrait;

 /**
  * Controller MainController
  * @property  JsUtils $jquery
  */

class MainController extends ControllerBase{

    use WithAuthTrait;

    #[Route('_default',name: 'home')]
    public function index(){
        $this->jquery->renderView("MainController/index.html");
    }

    protected function getAuthController(): AuthController
    {
        return new MyAuth($this);
    }

}
