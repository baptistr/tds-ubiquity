<?php
namespace controllers;

use models\Basket;
use Ubiquity\controllers\Controller;
use Ubiquity\orm\DAO;
use Ubiquity\utils\http\URequest;
use Ubiquity\utils\http\USession;

/**
 * controllers$ControllerBase
 */
abstract class ControllerBase extends Controller {

	protected $headerView = "@activeTheme/main/vHeader.html";

	protected $footerView = "@activeTheme/main/vFooter.html";

	public function initialize() {

        $nbProduct = 0;
        $montant = 0;
        $basket = USession::get('defaultBasket');
        $idDuPanier = $basket->getId();
        $basket = DAO::getById(Basket::class, $idDuPanier, ['basketdetails.product']);
        $basketDetail = $basket->getBasketdetails();
        foreach ($basketDetail as $content) {
            $montant += (($content->getProduct()->getPrice()+$content->getProduct()->getPromotion()) * $content->getQuantity());
        }
        foreach ($basketDetail as $content) {
            $nbProduct += $content->getQuantity();
        }

		if (! URequest::isAjax()) {
            $this->loadView($this->headerView,['montant'=>$montant, 'nbProduct'=>$nbProduct, 'idDuPanier'=>$idDuPanier]);
		}
	}

	public function finalize() {
		if (! URequest::isAjax()) {
			$this->loadView($this->footerView);
		}
	}
}

