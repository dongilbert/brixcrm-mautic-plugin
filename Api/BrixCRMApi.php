<?php

namespace MauticPlugin\MauticBrixCRMBundle\Api;

use MauticPlugin\MauticCrmBundle\Api\SugarcrmApi;

class BrixCRMApi extends SugarcrmApi {

	public function addToSugarQueue($lead) {
		$data = [
			'event' => 'push',
			'entity' => 'contact',
			'id' => $lead->getId(),

		];

		return $this->request('Mautic/receiveRequest', $data, 'POST');
	}

}