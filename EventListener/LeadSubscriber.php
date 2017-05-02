<?php

namespace MauticPlugin\MauticBrixCRMBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\LeadBundle\Event\LeadEvent;
use Mautic\LeadBundle\LeadEvents;

class LeadSubscriber extends CommonSubscriber {

	/**
	 * @return array
	 */
	public static function getSubscribedEvents() {
		return [
			LeadEvents::LEAD_POST_SAVE => ['leadPostSave', 0],
		];
	}

	/**
	 * @param LeadEvent $event
	 */
	public function leadPostSave(LeadEvent $event) {
		if (!$event->isNew() && !$this->request->headers->has('SugarCRM')) {
			//TODO: push lead to Sugar. queueing?
		}
	}
}
