<?php

return [
	'name' => 'BrixCRM',
	'description' => 'Custom BrixCRM extensions',
	'version' => '1.0',
	'author' => 'BrixCRM',
	'routes' => [
		'api' => [
			'brixcrm_event_api' => [
				'path' => '/contacts/{id}/events',
				'controller' => 'MauticBrixCRMBundle:BrixCRMEventsApi:index',
			],
		]
	]
];
