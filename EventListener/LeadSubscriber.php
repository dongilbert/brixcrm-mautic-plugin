<?php

namespace MauticPlugin\MauticBrixCRMBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\LeadBundle\Event\LeadEvent;
use Mautic\LeadBundle\LeadEvents;
use Mautic\PluginBundle\Helper\IntegrationHelper;

class LeadSubscriber extends CommonSubscriber {

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
			LeadEvents::LEAD_PRE_SAVE => ['onLeadPreSave', 0],
		];
	}

	/**
	 * @param LeadEvent $event
	 */
	public function onLeadPreSave(LeadEvent $event) {
		if ($this->request && $this->request->headers->has('SugarCRM')) {
			$integration = $this->helper->getIntegrationObject('BrixCRM');

			if ($integration && $integration->getIntegrationSettings()->getIsPublished()) {
				$settings = $integration->getIntegrationSettings()->getFeatureSettings();

				if (isset($settings['sugar_sync_flag'])) {
					$event->getLead()->addUpdatedField($settings['sugar_sync_flag'], true);
				}
			}
		}
	}
}
