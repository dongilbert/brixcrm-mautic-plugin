<?php

return [
	'name' => 'BrixCRM',
	'description' => 'Custom BrixCRM extensions',
	'version' => '1.0',
	'author' => 'BrixCRM',
	'services' => [
		'events' => [
			'mautic.brixcrm.leadbundle.subscriber' => [
				'class' => 'MauticPlugin\MauticBrixCRMBundle\EventListener\LeadSubscriber',
				'arguments' => [
					'mautic.helper.integration',
				],
			],
			'mautic.brixcrm.webhookbundle.subscriber' => [
				'class' => 'MauticPlugin\MauticBrixCRMBundle\EventListener\WebhookSubscriber',
				'methodCalls' => [
					'setWebhookModel' => ['mautic.webhook.model.webhook'],
				],
				'arguments' => [
					'mautic.helper.integration',
				],
			],
		],
	],
];
