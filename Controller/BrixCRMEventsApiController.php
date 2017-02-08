<?php

namespace MauticPlugin\MauticBrixCRMBundle\Controller;

use Mautic\ApiBundle\Controller\CommonApiController;
use FOS\RestBundle\Util\Codes;

class BrixCRMEventsApiController extends CommonApiController {

	public function indexAction($id) {

		$leadModel = $this->getModel('lead');
		$lead = $leadModel->getEntity($id);
		$events = $leadModel->getEngagements($lead, null, ['timestamp', 'DESC']);

		$view = $this->view($events, Codes::HTTP_OK);

		return $this->handleView($view);
	}
}
