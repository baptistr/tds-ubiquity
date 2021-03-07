<?php
namespace services\ui;

use Ajax\php\ubiquity\UIService;
use models\Group;
use models\Organization;
use Ubiquity\controllers\Controller;
use Ubiquity\controllers\Router;

/**
 * Class UIGroups
 */
class UIGroups extends UIService{
    public function __construct(Controller $controller){
        parent::__construct($controller);
        $this->jquery->getHref('a[data-target]',
            parameters: ['historize'=>false,'hasLoader'=>'internal','listenerOn'=>'body']);
    }

    public function listGroups(array $groups){
        $dt=$this->semantic->dataTable('dt-groups',Group::class,$groups);
        $dt->setFields(['name','email']);
        $dt->fieldAsLabel('email','mail');
        $dt->addDeleteButton();
    }

    public function orgaForm(Organization $orga) {
        $frm=$this->semantic->dataForm('frmOrga',$orga);
        $frm->setFields(['id','name','domain','submit']);
        $frm->fieldAsHidden('id');
        $frm->fieldAsLabeledInput('name',['rules'=>'empty']); //obligatoire
        $frm->fieldAsLabeledInput('domain',['rules'=>['empty','email']]); //obligatoire et email
        $frm->setValidationParams(["on"=>"blur","inline"=>true]);//controle de validité
        $frm->fieldAsSubmit('submit','positive',Router::path('addOrga'),'#response');
    }
}