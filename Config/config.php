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

return [
	'name' => 'BrixCRM',
	'description' => 'Custom BrixCRM extensions',
	'version' => '2.0',
	'author' => 'BrixCRM',
	'services' => [
        'integrations' => [
            'mautic.integration.brixcrm' => [
                'class'     => \MauticPlugin\MauticBrixCRMBundle\Integration\BrixCRMIntegration::class,
                'arguments' => [
                    'event_dispatcher',
                    'mautic.helper.cache_storage',
                    'doctrine.orm.entity_manager',
                    'session',
                    'request_stack',
                    'router',
                    'translator',
                    'logger',
                    'mautic.helper.encryption',
                    'mautic.lead.model.lead',
                    'mautic.lead.model.company',
                    'mautic.helper.paths',
                    'mautic.core.model.notification',
                    'mautic.lead.model.field',
                    'mautic.plugin.model.integration_entity',
                    'mautic.lead.model.dnc',
                ],
            ],
        ],
	    'events' => [
			'mautic.integration.brixcrm.leadbundle.subscriber' => [
				'class' => \MauticPlugin\MauticBrixCRMBundle\EventListener\LeadSubscriber::class,
				'arguments' => [
					'mautic.helper.integration',
                    'request_stack',
                    'mautic.plugin.repository.integration_entity',
				],
			],
		],
	],
];
