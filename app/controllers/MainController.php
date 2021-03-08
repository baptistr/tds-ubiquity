<?php
namespace controllers;
 use Ubiquity\controllers\admin\popo\Route;

 /**
  * Controller MainController
  */
class MainController extends ControllerBase{

    #[Route('_default', name : 'home')]
    public function index(){
        $this->jquery->renderView("MainController/index.html");
    }
}
