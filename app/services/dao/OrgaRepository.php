<?php


namespace services\dao;

use models\Organization;
use Ubiquity\controllers\Controller;
use Ubiquity\orm\repositories\ViewRepository;

class OrgaRepository extends ViewRepository
{

    public function __construct(Controller $ctrl)
    {
        parent::__construct($ctrl, Organization::actions);
    }
}