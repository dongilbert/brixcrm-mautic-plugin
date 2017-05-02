<?php

namespace MauticPlugin\MauticBrixCRMBundle\Api;

use MauticPlugin\MauticCrmBundle\Api\SugarcrmApi;

class BrixCRMApi extends SugarcrmApi {

	public function addToSugarQueue($lead) {
		$data = [
			'sender' => 'mautic',
			'action' => 'create',
			'mautic_type' => 'contact',
			'mautic_id' => $lead->getId(),

		];

		return $this->request('BRX_MauticQueue', $data, 'POST');
	}

}