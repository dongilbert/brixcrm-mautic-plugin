<?php

namespace MauticPlugin\MauticBrixCRMBundle\Integration;

use MauticPlugin\MauticCrmBundle\Integration\SugarcrmIntegration;
use Mautic\LeadBundle\Entity\Lead;

class BrixCRMIntegration extends SugarcrmIntegration {

	public function getName() {
		return 'BrixCRM';
	}

	public function populateLeadData($lead, $config = []) {
		$matched =  parent::populateLeadData($lead, $config);
		if (isset($config['mautic_id']) && $lead instanceof Lead) {
			$matched[$config['mautic_id']] = $lead->getId();
		}
		return $matched;
	}

	public function getApiHelper() {
		static $helper;
		if (empty($helper)) {
			$class = '\\MauticPlugin\\MauticBrixCRMBundle\\Api\\' . $this->getName() . 'Api';
			$helper = new $class($this);
		}

		return $helper;
	}

	public function appendToForm(&$builder, $data, $formArea) {
		if ($formArea == 'features') {
			$builder->add('mautic_id', 'text', [
				'label' => 'mautic.brixcrm.form.mautic_id',
				'label_attr' => ['class' => 'control-label'],
				'attr' => ['class' => 'form-control'],
				'required' => false,
			]);
		}
		parent::appendToForm($builder, $data, $formArea);
	}
}
