<?php

return [
	'name' => 'BrixCRM',
	'description' => 'Custom BrixCRM extensions',
	'version' => '2.0',
	'author' => 'BrixCRM',
	'services' => [
		'events' => [
			'mautic.brixcrm.leadbundle.subscriber' => [
				'class' => 'MauticPlugin\MauticBrixCRMBundle\EventListener\LeadSubscriber',
				'arguments' => [
					'mautic.helper.integration',
				],
			],
		],
	],
];
