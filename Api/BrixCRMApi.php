<?php

namespace MauticPlugin\MauticBrixCRMBundle\Api;

use Mautic\PluginBundle\Exception\ApiErrorException;
use MauticPlugin\MauticCrmBundle\Api\CrmApi;

class BrixCRMApi extends CrmApi {

	public function addToSugarQueue($lead, $event) {
		$keys = $this->integration->getKeys();
		$request_url = sprintf('%s/rest/v10/%s', $keys['sugarcrm_url'], 'Mautic/receiveRequest');
		$method = 'POST';

		$data = [
			'event' => $event,
			'entity' => 'contact',
			'id' => $lead->getId(),

		];

		$settings = [
			'request_timeout' => 50,
			'encode_parameters' => 'json',
		];
		$response = $this->integration->makeRequest($request_url, $data, $method, $settings);

		if (isset($response['error'])) {
			throw new ApiErrorException(isset($response['error_message']) ? $response['error_message'] : $response['error']['message'], ($response['error'] == 'invalid_grant') ? 1 : 500);
		}

		return $response;
	}

}