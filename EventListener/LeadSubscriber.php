<?php
/**
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace MauticPlugin\MauticBrixCRMBundle\EventListener;

use Mautic\LeadBundle\Event\LeadEvent;
use Mautic\LeadBundle\LeadEvents;
use Mautic\PluginBundle\Entity\IntegrationEntityRepository;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\MauticBrixCRMBundle\Integration\BrixCRMIntegration;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class LeadSubscriber implements EventSubscriberInterface {

	/**
	 * @var IntegrationHelper
	 */
	private $helper;

    /**
     * @var Request|null
     */
	private $request;

    /**
     * @var IntegrationEntityRepository
     */
	private $integrationEntityRepository;

	public function __construct(IntegrationHelper $helper, RequestStack $requestStack, IntegrationEntityRepository $integrationEntityRepository) {
		$this->helper  = $helper;
		$this->request = $requestStack->getCurrentRequest();
		$this->integrationEntityRepository = $integrationEntityRepository;
	}

	/**
	 * @return array
	 */
	public static function getSubscribedEvents() {
		return [
			LeadEvents::LEAD_POST_SAVE => ['onLeadPostSave', 0],
		];
	}


	/**
	 * @param LeadEvent $event
	 */
	public function onLeadPostSave(LeadEvent $event) {
		if (!$event->isNew() && (!$this->request || !$this->request->headers->has('SugarCRM'))) {
			$this->addToSugarQueue($event);
		}

		if ($this->request && $this->request->headers->has('SugarCRM')) {
			$this->updateIntegration($event);
		}
	}

	/**
	 * @param LeadEvent $event
	 */
	protected function addToSugarQueue(LeadEvent $event) {
	    /** @var BrixCRMIntegration $integration */
		$integration = $this->helper->getIntegrationObject('BrixCRM');

		if ($integration && $integration->getIntegrationSettings()->getIsPublished()) {
			$integrationId = $this->integrationEntityRepository->getIntegrationsEntityId($integration->getName(), $integration->getIntegrationObject(), 'lead', $event->getLead()->getId());

			if (!empty($integrationId)) {
				try {
					$integration->getApiHelper()->addToSugarQueue($event->getLead(), 'save');
					$integration->updateIntegrationEntity($event->getLead());
				} catch (\Exception $e) {
					$integration->logIntegrationError($e);
				}
			}
		}
	}

	/**
	 * @param LeadEvent $event
	 */
	protected function updateIntegration(LeadEvent $event) {
	    /** @var BrixCRMIntegration $integration */
		$integration = $this->helper->getIntegrationObject('BrixCRM');

		if ($integration && $integration->getIntegrationSettings()->getIsPublished()) {
			$integration->updateIntegrationEntity($event->getLead());
		}
	}
}
