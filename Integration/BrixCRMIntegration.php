<?php

namespace MauticPlugin\MauticBrixCRMBundle\Integration;

use MauticPlugin\MauticCrmBundle\Integration\SugarcrmIntegration;
use Symfony\Component\Validator\Constraints\NotBlank;

class BrixCRMIntegration extends SugarcrmIntegration {

	public function getName() {
		return 'BrixCRM';
	}

	public function getSupportedFeatures() {
		return ['push_lead'];
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
		$leadBooleanFields = $this->factory->getModel('lead.field')->getFieldList(false, false, [
			'isPublished' => true,
			'object' => 'lead',
			'type' => 'boolean'
		]);
		if ($formArea == 'features') {
			$builder->add('sugar_sync_flag', 'choice', [
				'label' => 'mautic.brixcrm.form.sugar_sync_flag',
				'label_attr' => ['class' => 'control-label'],
				'attr' => ['class' => 'form-control'],
				'required' => false,
				'choices' => $leadBooleanFields,
			]);
		}
		if ($formArea == 'keys') {
			$builder->add('version', 'button_group', [
				'choices' => [
					'7' => '7.x',
				],
				'label' => 'mautic.sugarcrm.form.version',
				'constraints' => [
					new NotBlank([
						'message' => 'mautic.core.value.required',
					]),
				],
				'required' => true,
			]);
		}
	}

	public function getFormLeadFields($settings = []) {
		return [];
	}

	public function getFormCompanyFields($settings = []) {
		return [];
	}

	public function pushLead($lead, $config = []) {
		try {
			if ($this->isAuthorized()) {
				$this->getApiHelper()->addToSugarQueue($lead);

				return true;
			} else {
				throw new \Exception('BrixCRMIntegration: Not authorized');
			}
		} catch (\Exception $e) {
			$this->logIntegrationError($e);
		}

		return false;
	}
}
