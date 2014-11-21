<?php
/**
* 2014 (c) Axalone France - Express-Mailing
*
* This file is a commercial module for Prestashop
* Do not edit or add to this file if you wish to upgrade PrestaShop or
* customize PrestaShop for your needs please refer to
* http://www.express-mailing.com for more information.
*
* @author    Axalone France <info@express-mailing.com>
* @copyright 2014 (c) Axalone France
* @license   http://www.express-mailing.com
*/

class AdminMarketingEStep1Controller extends ModuleAdminController
{
	private $campaign_id = null;

	public function __construct()
	{
		$this->name = 'adminmarketingestep1';
		$this->bootstrap = true;
		$this->module = 'expressmailing';
		$this->context = Context::getContext();
		$this->lang = false;
		$this->default_form_language = $this->context->language->id;

		$this->campaign_id = Tools::getValue('campaign_id');

		if (empty($this->campaign_id))
		{
			Tools::redirectAdmin('index.php?controller=AdminMarketingE&token='.Tools::getAdminTokenLite('AdminMarketingE'));
			exit;
		}

		parent::__construct();
	}

	public function setMedia()
	{
		parent::setMedia();
		$this->addCSS(_PS_MODULE_DIR_.'expressmailing/css/expressmailing.css', 'all');
	}

	public function renderList()
	{
		$this->fields_form = array(
			'legend' => array(
				'title' => $this->module->l('Campaign configuration (1)', 'adminmarketingestep1'),
				'icon' => 'icon-cogs'
			),
			'input' => array(
				array(
					'type' => _PS_MODE_DEV_ ? 'text' : 'hidden',
					'lang' => false,
					'label' => 'Ref :',
					'name' => 'campaign_id',
					'col' => 1,
					'readonly' => 'readonly'
				),
				array(
					'type' => 'text',
					'lang' => false,
					'label' => $this->module->l('campaign_name', 'adminmarketingestep1'),
					'name' => 'campaign_name',
					'col' => 7,
					'required' => true
				),
				array(
					'type' => 'datetime',
					'lang' => false,
					'name' => 'campaign_date_send',
					'label' => $this->module->l('campaign_date_send', 'adminmarketingestep1'),
					'required' => true
				),
				array(
					'type' => 'switch',
					'lang' => false,
					'class' => 'chosen',
					'label' => $this->module->l('campaign_tracking', 'adminmarketingestep1'),
					'name' => 'campaign_tracking',
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->module->l('yes', 'adminmarketingestep1'),
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->module->l('no', 'adminmarketingestep1'),
						)
					)
				),
				array(
					'type' => 'switch',
					'lang' => false,
					'label' => $this->module->l('campaign_linking', 'adminmarketingestep1'),
					'name' => 'campaign_linking',
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->module->l('yes', 'adminmarketingestep1'),
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->module->l('no', 'adminmarketingestep1'),
						)
					)
				),
				array(
					'type' => 'switch',
					'lang' => false,
					'label' => $this->module->l('campaign_redlist', 'adminmarketingestep1'),
					'name' => 'campaign_redlist',
					'values' => array(
						array(
							'id' => 'active_on',
							'value' => 1,
							'label' => $this->module->l('yes', 'adminmarketingestep1'),
						),
						array(
							'id' => 'active_off',
							'value' => 0,
							'label' => $this->module->l('no', 'adminmarketingestep1'),
						)
					)
				)
			),
			'submit' => array(
				'title' => $this->module->l('Next', 'adminmarketingestep1'),
				'name' => 'submitEmailingStep1',
				'icon' => 'process-icon-next'
			)
		);

		$this->getFieldsValues();
		$output = parent::renderForm();

		$footer = $this->getTemplatePath().'footer.tpl';
		$output .= $this->context->smarty->fetch($footer);

		return $output;
	}

	public function postProcess()
	{
		if (Tools::isSubmit('submitEmailingStep1'))
		{
			// Vérification de la date
			// -----------------------
			$now = time();
			$date_send = DateTime::createFromFormat('Y-m-d H:i:s', (string)Tools::getValue('campaign_date_send'));
			$campaign_date_send = max($now, $date_send->getTimestamp());

			$this->campaign_date_send = date('Y-m-d H:i:s', $campaign_date_send);
			$this->campaign_name = (string)Tools::getValue('campaign_name');
			$this->campaign_tracking = (string)Tools::getValue('campaign_tracking');
			$this->campaign_linking = (string)Tools::getValue('campaign_linking');
			$this->campaign_redlist = (string)Tools::getValue('campaign_redlist');

			if (empty($this->campaign_id) || empty($this->campaign_name) || empty($this->campaign_date_send))
			{
				// [VALIDATOR => Laisser cette ligne de commentaire]
				$this->errors[] = Tools::displayError('Please verify the required fields');
			}
			else
			{
				// On mémorise els info, même si la date n'est pas bonne
				// -----------------------------------------------------
				Db::getInstance()->update('expressmailing_email',
					array(
						'campaign_state' => 1,
						'campaign_date_update' => date('Y-m-d H:i:s'),
						'campaign_date_send' => $this->campaign_date_send,
						'campaign_name' => pSQL($this->campaign_name),
						'campaign_tracking' => $this->campaign_tracking,
						'campaign_linking' => $this->campaign_linking,
						'campaign_redlist' => $this->campaign_redlist
					),
					'campaign_id = '.$this->campaign_id
				);

				if ($campaign_date_send > mktime(0, 0, 0, date('m') + 3, date('d'), date('Y')))
				{
					$this->errors[] = Tools::displayError('Invalid date (max 3 months)');
					return false;
				}

				Tools::redirectAdmin('index.php?controller=AdminMarketingEStep2&campaign_id='.
										$this->campaign_id.
										'&token='.Tools::getAdminTokenLite('AdminMarketingEStep2'));		/* [VALIDATOR MAX 150 CAR] */
				exit;
			}
		}
	}

	private function getFieldsValues()
	{
		$sql = new DbQuery();
		$sql->select('*');
		$sql->from('expressmailing_email');
		$sql->where('campaign_id = '.$this->campaign_id);
		$result = Db::getInstance()->getRow($sql);

		$this->fields_value['campaign_id'] = $this->campaign_id;

		$this->fields_value['campaign_name'] = $result['campaign_name'];
		$this->fields_value['campaign_date_send'] = $result['campaign_date_send'];
		$this->fields_value['campaign_tracking'] = $result['campaign_tracking'];
		$this->fields_value['campaign_linking'] = $result['campaign_linking'];
		$this->fields_value['campaign_redlist'] = $result['campaign_redlist'];

		return true;
	}

}