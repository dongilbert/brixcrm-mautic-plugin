<?php
/**
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

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