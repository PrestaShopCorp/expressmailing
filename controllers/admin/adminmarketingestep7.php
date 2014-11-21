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

/**
 * Description of AdminMarketingEStep7Controller
 *
 * @author ThierryA
 */
class AdminMarketingEStep7Controller extends ModuleAdminController
{
	private $session_api = null;
	private $checked_groups = array();
	private $checked_langs = array();
	private $checked_campaign_optin = null;
	private $checked_campaign_newsletter = null;
	private $checked_campaign_active = null;
	private $campaign_infos = array();
	private $campaign_sended = false;

	public function __construct()
	{
		$this->name = 'adminmarketingestep7';
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

		// On retrouve les informations de la campagne
		// -------------------------------------------
		$this->campaign_infos = $this->getCampaignInfos();

		// Initialisation de l'API
		// -----------------------
		include _PS_MODULE_DIR_.$this->module->name.'/controllers/admin/session_api.php';
		$this->session_api = new SessionApi();

		// Vérification de la session
		// --------------------------
		if (!$this->session_api->connectFromCredentials('email'))
		{
			Tools::redirectAdmin('index.php?controller=AdminMarketingEStep5&token='.Tools::getAdminTokenLite('AdminMarketingEStep5'));
			exit;
		}
	}

	public function setMedia()
	{
		parent::setMedia();
		$this->addJS(_PS_MODULE_DIR_.'expressmailing/js/emcharts.js', 'all');
	}

	public function renderList()
	{
		if ($this->campaign_sended == false)
		{
			$this->initFilters();
			$this->syncCustomersAPI();
			$this->setSmartyVars();

			$diplay = $this->getTemplatePath().'marketinge_step7/display.tpl';
			$footer = $this->getTemplatePath().'footer.tpl';

			$output = $this->context->smarty->fetch($diplay);
			$output .= $this->context->smarty->fetch($footer);

			return $output;
		}
	}

	public function postProcess()
	{
		if (Tools::isSubmit('sendCampaign'))
		{
			$yes = (string)Tools::getValue('YES', '');
			$yes = Tools::strtoupper($yes);

			if ($yes == Tools::strtoupper($this->module->l('YES', 'adminmarketingestep7')))
			{
				if ($this->sendCampaignAPI())
				{
					$this->confirmations[] = $this->module->l('The campaign is now sending', 'adminmarketingestep7');
					$this->campaign_sended = true;
					return true;
				}
			}

			$this->errors[] =	sprintf(Tools::displayError('Please fill the %s field'),
								'&laquo;&nbsp;'.$this->module->l('YES', 'adminmarketingestep7').'&nbsp;&raquo;'); 	/* [VALIDATOR 160 CAR MAX] */
			return false;
		}
	}

	private function syncCustomersAPI()
	{
		$response_array = array();
		$parameters = array(
			'account_id' => $this->session_api->account_id,
			'list_id' => $this->campaign_infos['campaign_api_list_id']
		);

		if ($this->session_api->call('email', 'list', 'clear', $parameters, $response_array))
		{
			$customers = $this->getCustomers();
			$recipients = array();
			foreach ($customers as $customer)
			{
				$recipients[] = array(
					'target' => $customer['email'],
					'lastname' => $customer['lastname'],
					'firstname' => $customer['firstname']
				);
			}

			$parameters = array(
				'account_id' => $this->session_api->account_id,
				'list_id' => $this->campaign_infos['campaign_api_list_id'],
				'recipients' => $recipients
			);

			if ($this->session_api->call('email', 'recipients', 'add', $parameters, $response_array))
				return true;
			else
			{
				$this->errors[] = sprintf(Tools::displayError('Error during communication with Express-Mailing API : %s'), $this->session_api->getError());
				return false;
			}
		}
		else
		{
			$this->errors[] = sprintf(Tools::displayError('Error during communication with Express-Mailing API : %s'), $this->session_api->getError());
			return false;
		}
	}

	private function getValidationStatisticsAPI()
	{
		$response_array = array();
		$parameters = array(
			'account_id' => $this->session_api->account_id,
			'campaign_id' => $this->campaign_infos['campaign_api_message_id']
		);

		if ($this->session_api->call('email', 'campaign', 'get_validation_statistics', $parameters, $response_array))
			return $response_array;

		$this->errors[] = sprintf(Tools::displayError('Error during communication with Express-Mailing API : %s'), $this->session_api->getError());
		return false;
	}

	private function sendCampaignAPI()
	{
		$response_array = null;
		$parameters = array(
			'account_id' => $this->session_api->account_id,
			'campaign_id' => $this->campaign_infos['campaign_api_message_id']
		);

		if ($this->session_api->call('email', 'campaign', 'send', $parameters, $response_array))
			return true;
		else
		{
			$this->errors[] = sprintf(Tools::displayError('Error during communication with Express-Mailing API : %s'), $this->session_api->getError());
			return false;
		}
	}

	private function initFilters()
	{
		$sql = new DbQuery();
		$sql->select('group_id');
		$sql->from('expressmailing_email_groups');
		$sql->where('campaign_id = '.$this->campaign_id);

		if ($result = Db::getInstance()->ExecuteS($sql))
		{
			foreach ($result as $row)
			{
				$this->checked_groups[] = $row['group_id'];
				$this->fields_value['groups[]_'.$row['group_id']] = '1';
			}
		}

		$sql = new DbQuery();
		$sql->select('lang_id');
		$sql->from('expressmailing_email_langs');
		$sql->where('campaign_id = '.$this->campaign_id);

		if ($result = Db::getInstance()->ExecuteS($sql))
		{
			foreach ($result as $row)
			{
				$this->checked_langs[] = $row['lang_id'];
				$this->fields_value['langs[]_'.$row['lang_id']] = '1';
			}
		}

		$req = new DbQuery();
		$req->select('campaign_optin, campaign_newsletter, campaign_active');
		$req->from('expressmailing_email');
		$req->where('campaign_id = '.$this->campaign_id);

		$result = Db::getInstance()->getRow($req->build());

		$this->checked_campaign_optin = $result['campaign_optin'];
		$this->checked_campaign_newsletter = $result['campaign_newsletter'];
		$this->checked_campaign_active = $result['campaign_active'];
	}

	private function getCustomers()
	{
		$req = new DbQuery();
		$req->select('SQL_CALC_FOUND_ROWS customer.id_customer, customer.id_lang, customer.firstname, customer.lastname, customer.email');
		$req->from('customer', 'customer');
		$req->leftJoin('customer_group', 'customer_group', 'customer_group.id_customer = customer.id_customer');

		$where = array();

		if (isset($this->checked_langs) && !empty($this->checked_langs))
			$where[] = 'customer.id_lang IN('.implode(', ', $this->checked_langs).')';

		if (isset($this->checked_groups) && !empty($this->checked_groups))
			$where[] = 'customer_group.id_group IN('.implode(', ', $this->checked_groups).')';

		if ($this->checked_campaign_optin)
			$where[] = 'customer.optin = 1';

		if ($this->checked_campaign_newsletter)
			$where[] = 'customer.newsletter = 1';

		if ($this->checked_campaign_active)
			$where[] = 'customer.active = 1';

		$req->where(implode(' AND ', $where));
		$req->orderby('id_customer');
		$req->limit(20);

		$user_list = Db::getInstance()->executeS($req->build());
		$this->list_total = Db::getInstance()->getValue('SELECT FOUND_ROWS()');
		return $user_list;
	}

	private function getCampaignInfos()
	{
		$req = new DbQuery();
		$req->select('*');
		$req->from('expressmailing_email');
		$req->where('campaign_id = '.$this->campaign_id);
		$result = Db::getInstance()->getRow($req->build());

		return $result;
	}

	private function setSmartyVars()
	{
		$validation_stats = $this->getValidationStatisticsAPI();

		$this->context->smarty->assign(array(
			'campaign_id' => $this->campaign_id,
			'nb_total' => $validation_stats['count_total_recipients'],
			'nb_redlist' => $validation_stats['count_recipients_on_personnal_redlist'],
			'nb_suspended' => $validation_stats['count_cancelled_recipients'],
			'nb_already_sent' => $validation_stats['count_sent_recipients'],
			'nb_to_send' => $validation_stats['count_recipients_to_send'],
			'mail_weight' => $this->humanFilesize($validation_stats['email_weight']),
			'mail_cost' => $validation_stats['email_cost']
		));
	}

	private function humanFilesize($bytes, $decimals = 2)
	{
		$size = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		$factor = floor((Tools::strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)).' '.$size[$factor];
	}

}