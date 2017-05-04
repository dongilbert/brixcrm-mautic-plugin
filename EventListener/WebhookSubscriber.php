<?php

namespace MauticPlugin\MauticBrixCRMBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\LeadBundle\Event\LeadEvent;
use Mautic\LeadBundle\LeadEvents;
use Mautic\WebhookBundle\Event\WebhookBuilderEvent;
use Mautic\WebhookBundle\EventListener\WebhookModelTrait;
use Mautic\WebhookBundle\WebhookEvents;
use Mautic\PluginBundle\Helper\IntegrationHelper;

class WebhookSubscriber extends CommonSubscriber {

	use WebhookModelTrait;

	/**
	 * @var IntegrationHelper
	 */
	protected $helper;

	/**
	 * @param IntegrationHelper $helper
	 */
	public function __construct(IntegrationHelper $helper) {
		$this->helper = $helper;
	}

	/**
	 * @return array
	 */
	public static function getSubscribedEvents() {
		return [
			WebhookEvents::WEBHOOK_ON_BUILD => ['onWebhookBuild', 0],
			LeadEvents::LEAD_POST_SAVE => ['onLeadPostSave', 0],
		];
	}

	/**
	 * Add event triggers and actions.
	 *
	 * @param WebhookBuilderEvent $event
	 */
	public function onWebhookBuild(WebhookBuilderEvent $event) {
		$updateLead = [
			'label' => 'mautic.brixcrm.lead.webhook.event.lead.update',
			'description' => 'mautic.brixcrm.lead.webhook.event.lead.update_desc',
		];

		$event->addEvent(LeadEvents::LEAD_POST_SAVE . '_update_brix', $updateLead);
	}

	/**
	 * @param LeadEvent $event
	 */
	public function onLeadPostSave(LeadEvent $event) {
		if (!$event->isNew() && !$this->request->headers->has('SugarCRM')) {
			$integration = $this->helper->getIntegrationObject('BrixCRM');

			if ($integration && $integration->getIntegrationSettings()->getIsPublished()) {
				$settings = $integration->getIntegrationSettings()->getFeatureSettings();

				if (isset($settings['sugar_sync_flag']) && $event->getLead()->getFieldValue($settings['sugar_sync_flag'])) {
					$this->webhookModel->queueWebhooksByType(LeadEvents::LEAD_POST_SAVE . '_update_brix', [
						'event' => 'save',
						'entity' => 'contact',
						'id' => $event->getLead()->getId(),

					]);
				}
			}
		}
	}
}
