<?php
namespace controllers;
use Ubiquity\attributes\items\router\Get;
use Ubiquity\controllers\Router;
use Ubiquity\controllers\Startup;
use Ubiquity\orm\DAO;
use Ubiquity\utils\flash\FlashMessage;
use Ubiquity\utils\http\UResponse;
use Ubiquity\utils\http\USession;
use Ubiquity\utils\http\URequest;
use Ubiquity\controllers\auth\AuthFiles;
use Ubiquity\attributes\items\router\Route;
use models\User;

#[Route(path: "/login",inherited: true,automated: true)]
class MyAuth extends \Ubiquity\controllers\auth\AuthController{

    public function _displayInfoAsString() {
        return true;
    }

    protected function finalizeAuth() {
        if(!URequest::isAjax()){
            $this->loadView('@activeTheme/main/vFooter.html');
        }
    }

    protected function initializeAuth() {
        if(!URequest::isAjax()){
            $this->loadView('@activeTheme/main/vHeader.html');
        }
    }

    public function _getBodySelector() {
        return '#page-container';
    }

    protected function noAccessMessage(FlashMessage $fMessage) {
        $fMessage->setTitle('Accès interdit !');
        $fMessage->setContent("Vous n'êtes pas autorisé à accéder à cette page.");
    }

    protected function terminateMessage(FlashMessage $fMessage) {
        $fMessage->setTitle('Fermeture !');
        $fMessage->setContent("Vous avez été correctement déconnecté de l'application.");
    }

    public function index()
    {
        // TODO: Implement index() method.
    }

    protected function onConnect($connected) {
        $urlParts=$this->getOriginalURL();
        USession::set($this->_getUserSessionKey(), $connected);
        if(isset($urlParts)){
            $this->_forward(implode("/",$urlParts));
        }else{
            UResponse::header('location','/');
        }
    }

    #[Get(name:'login.direct')]
    public function direct($name){
        $name=urldecode($name);
        $user = DAO::getOne(User::class, 'email= ?', false, [$name]);
        if($user) {
            USession::set('idOrga', $user->getOrganization());
            return $this->onConnect($user);
        }
        $this->_invalid=true;
        $this->initializeAuth();
        $this->onBadCreditentials ();
        $this->finalizeAuth();
    }

    protected function _connect() {
        if(URequest::isPost()){
            $email=URequest::post($this->_getLoginInputName());
            if($email!=null) {
                $password = URequest::post($this->_getPasswordInputName());
                $user = DAO::getOne(User::class, 'email= ?', false, [$email]);
                if(isset($user)) {
                    USession::set('idOrga', $user->getOrganization());
                    return $user;
                }
            }
        }
        return;
    }

    public function _isValidUser($action = null)
    {
        // TODO: Implement _isValidUser() method.
    }
}
