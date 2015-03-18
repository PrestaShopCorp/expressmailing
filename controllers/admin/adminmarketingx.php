<?php
/**
 * 2014-2015 (c) Axalone France - Express-Mailing
 *
 * This file is a commercial module for Prestashop
 * Do not edit or add to this file if you wish to upgrade PrestaShop or
 * customize PrestaShop for your needs please refer to
 * http://www.express-mailing.com for more information.
 *
 * @author    Axalone France <info@express-mailing.com>
 * @copyright 2014-2015 (c) Axalone France
 * @license   http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

include_once 'em_tools.php';

class AdminMarketingXController extends ModuleAdminController
{
	public function __construct()
	{
		$this->name = 'adminmarketingx';
		$this->bootstrap = true;
		$this->module = 'expressmailing';
		$this->context = Context::getContext();
		$this->lang = false;
		$this->default_form_language = $this->context->language->id;
		$this->display = 'view';
		$this->broadcast_max_daily = 400;

		parent::__construct();

		// API initialization
		// ------------------
		include _PS_MODULE_DIR_.$this->module->name.'/controllers/admin/session_api.php';
		$this->session_api = new SessionApi();
	}

	public function initToolbarTitle()
	{
		parent::initToolbarTitle();
		$this->toolbar_title = $this->module->l('Prepare a new campaign', 'adminmarketingx');

		$this->page_header_toolbar_btn['stat_email'] = array(
			'href' => $this->context->link->getAdminLink('AdminMarketingEList', true),
			'desc' => $this->module->l('My email stats', 'adminmarketingx'),
			'icon' => 'process-icon-stats'
		);

		$this->page_header_toolbar_btn['stat_fax'] = array(
			'href' => $this->context->link->getAdminLink('AdminMarketingFList', true),
			'desc' => $this->module->l('My fax stats', 'adminmarketingx'),
			'icon' => 'process-icon-stats'
		);

		$this->page_header_toolbar_btn['stat_sms'] = array(
			'href' => $this->context->link->getAdminLink('AdminMarketingSList', true),
			'desc' => $this->module->l('My sms stats', 'adminmarketingx'),
			'icon' => 'process-icon-stats'
		);
	}

	public function setMedia()
	{
		$this->addCSS(_PS_MODULE_DIR_.'expressmailing/css/icon-marketing.css');
		$this->addCSS(_PS_MODULE_DIR_.'expressmailing/css/expressmailing.css');
		parent::setMedia();
	}

	public function renderView()
	{
		$api_connected = false;

		$credential_email = '';
		$credential_fax = '';
		$credential_sms = '';

		$smarty_email_disabled = false;
		$smarty_fax_disabled = false;
		$smarty_sms_disabled = false;

		$smarty_media_checked = '';

		$smarty_fax_credits = '';
		$smarty_sms_credits = '';
		$smarty_email_credits = $this->module->l('Free of charge', 'adminmarketingx').' '.
						sprintf($this->module->l('up to %d email per day', 'adminmarketingx'), $this->broadcast_max_daily);

		$smarty_fax_tickets = '';
		$smarty_sms_tickets = '';

		$tool_tip = '';
		$output = '';

		// Checking the email session
		// ------------------------
		if ($this->session_api->connectFromCredentials('email'))
		{
			$smarty_media_checked = 'email';
			$credential_email = $this->session_api->account_login;
			$api_connected = true;

			// Recovering the max daily limit
			// ------------------------------
			$response_array = array();
			$parameters = array('account_id' => $this->session_api->account_id);

			if ($this->session_api->call('email', 'account', 'get_formula', $parameters, $response_array))
			{
				// Email account can have different name than API login !
				if (isset($response_array['account_name'])) $credential_email = $response_array['account_name'];
				if (isset($response_array['broadcast_max_daily']))
				{
					$this->broadcast_max_daily = $response_array['broadcast_max_daily'];
					$smarty_email_credits = $this->module->l('Free of charge', 'adminmarketingx').' '.
						sprintf($this->module->l('up to %d email per day', 'adminmarketingx'), $this->broadcast_max_daily);
				}
				if (isset($response_array['broadcast_restrictions']))
					if ($response_array['broadcast_restrictions'] == 'BLOCKED')
					{
						$smarty_email_disabled = true;
						$smarty_media_checked = '';
					}
			}
			else
			{
				$smarty_email_disabled = true;
				$smarty_media_checked = '';
			}

		}

		// Checking the fax session
		// ------------------------
		if ($this->session_api->connectFromCredentials('fax'))
		{
			$credential_fax = $this->session_api->account_login;
			$api_connected = true;

			// Recovering the credit balance
			// -----------------------------
			$response_array = array();
			$parameters = array('account_id' => array($this->session_api->account_id));

			if ($this->session_api->call('fax', 'account', 'enum_credit_balances', $parameters, $response_array))
			{
				foreach ($response_array as $credit)
				{
					switch ((string)$credit['balance'])
					{
						case '0':
							$smarty_fax_disabled = false;
							$tmp_credits = '<span class="red no-bold">'.$this->module->l('You have no credit %s', 'adminmarketingx').'</span>';
							break;
						case '1':
							if (empty($smarty_media_checked) || ($this->context->controller->controller_name == 'AdminMarketingF'))
								$smarty_media_checked = 'fax';
							$smarty_fax_disabled = false;
							$tmp_credits = $this->module->l('You have 1 credit %s', 'adminmarketingx');
							break;
						default:
							if (empty($smarty_media_checked) || ($this->context->controller->controller_name == 'AdminMarketingF'))
								$smarty_media_checked = 'fax';
							$smarty_fax_disabled = false;
							$tmp_credits = sprintf($this->module->l('You have %s credits %s', 'adminmarketingx'), $credit['balance'], '%s');
							break;
					}
					$smarty_fax_credits .= sprintf($tmp_credits, '&laquo;&nbsp;'.$credit['credit_name'].'&nbsp;&raquo;<br>');
				}
			}
		}

				// Checking the sms session
		// ------------------------
		if ($this->session_api->connectFromCredentials('sms'))
		{
			$credential_sms = $this->session_api->account_login;
			$api_connected = true;

			// Recovering the credit balance
			// -----------------------------
			$response_array = array();
			$parameters = array('account_id' => $this->session_api->account_id);

			if ($this->session_api->call('sms', 'account', 'enum_credit_balances', $parameters, $response_array))
			{
				foreach ($response_array as $credit)
				{
					switch ((string)$credit['balance'])
					{
						case '0':
							$smarty_sms_checked = false;
							$smarty_sms_disabled = false;
							$tmp_credits = '<span class="red no-bold">'.$this->module->l('You have no credit %s', 'adminmarketingx').'</span>';
							break;
						case '1':
							if (empty($smarty_media_checked) || ($this->context->controller->controller_name == 'AdminMarketingS'))
								$smarty_media_checked = 'sms';
							$smarty_sms_disabled = false;
							$tmp_credits = $this->module->l('You have 1 credit %s', 'adminmarketingx');
							break;
						default:
							if (empty($smarty_media_checked) || ($this->context->controller->controller_name == 'AdminMarketingS'))
								$smarty_media_checked = 'sms';
							$smarty_sms_disabled = false;
							$tmp_credits = sprintf($this->module->l('You have %s credits %s', 'adminmarketingx'), $credit['balance'], '%s');
							break;
					}
					$smarty_sms_credits .= sprintf($tmp_credits, '&laquo;&nbsp;'.$credit['credit_name'].'&nbsp;&raquo;<br>');
				}
			}
		}

		if (!$api_connected)
		{
			// If the subscriber has not yet opened an account
			// We display the TPL with prices but without buying block (needs to be connected)
			// -------------------------------------------------------------------------------
			$smarty_fax_credits = $this->module->l('0,035 € per page (to France Metropolitan)', 'adminmarketingx');
			$smarty_sms_credits = $this->module->l('0,065 € per sms (to France Metropolitan)', 'adminmarketingx');

			// Remove the stats toolbar buttons
			// --------------------------------
			$this->page_header_toolbar_btn = array();
		}
		else
		{
			// Tool tip that display the account(s) id(s)
			// ------------------------------------------
			if (($credential_email == $credential_fax) && ($credential_fax == $credential_sms))
				$tool_tip = $this->module->l('Account ID :', 'adminmarketingx').'<br>'.$credential_email;
			else
			{
				$tool_tip = $this->module->l('Email account :', 'adminmarketingx').'<br>';
				$tool_tip .= empty($credential_email) ? $this->module->l('None', 'adminmarketingx') : $credential_email;
				$tool_tip .= '<hr>'.$this->module->l('Fax account :', 'adminmarketingx').'<br>';
				$tool_tip .= empty($credential_fax) ? $this->module->l('None', 'adminmarketingx') : $credential_fax;
				$tool_tip .= '<hr>'.$this->module->l('Sms account :', 'adminmarketingx').'<br>';
				$tool_tip .= empty($credential_sms) ? $this->module->l('None', 'adminmarketingx') : $credential_sms;
			}

			if (empty($smarty_fax_credits))
				$smarty_fax_credits = '<span class="red">'.sprintf($this->module->l('You have no credit %s', 'adminmarketingx'), 'fax').'</span>';

			if (empty($smarty_sms_credits))
				$smarty_sms_credits = '<span class="red">'.sprintf($this->module->l('You have no credit %s', 'adminmarketingx'), 'sms').'</span>';

			if ($smarty_email_disabled)
				$smarty_email_credits = '<span class="red no-bold">'.sprintf($this->module->l('You have no credit %s', 'adminmarketingx'), 'email').'</span>';

			// Get all the tickets available for Prestashop
			// --------------------------------------------
			$response_array = array();
			$parameters = array(
				'application_id' => $this->session_api->application_id,
				'account_id' => $this->session_api->account_id
			);

			if ($this->session_api->callExternal('http://www.express-mailing.com/api/cart/ws.php',
												'common', 'account', 'enum_credits', $parameters, $response_array))
			{
				if (isset($response_array['fax']))
					$smarty_fax_tickets = $response_array['fax'];

				if (isset($response_array['sms']))
					$smarty_sms_tickets = $response_array['sms'];
			}
		}

		// Smarty variables assign
		// -----------------------
		$tools = new EMTools;
		$this->context->smarty->assign(
			array(
				'smarty_email_disabled' => $smarty_email_disabled,
				'smarty_fax_disabled' => $smarty_fax_disabled,
				'smarty_sms_disabled' => $smarty_sms_disabled,
				'smarty_media_checked' => $smarty_media_checked,
				'smarty_email_credits' => $smarty_email_credits,
				'smarty_fax_credits' => $smarty_fax_credits,
				'smarty_sms_credits' => $smarty_sms_credits,
				'smarty_fax_tickets' => $smarty_fax_tickets,
				'smarty_sms_tickets' => $smarty_sms_tickets,
				'credential_email' => $credential_email,
				'credential_fax' => $credential_fax,
				'credential_sms' => $credential_sms,
				'broadcast_max_daily' => $this->broadcast_max_daily,
				'tool_tip' => $tool_tip,
				'tool_date' => $tools
			)
		);

		// And we display step 0
		// ---------------------
		$step0 = $this->getTemplatePath().'marketing_step0/marketing_step0.tpl';
		$output .= $this->context->smarty->fetch($step0);

		// And the purchase part (only if the user have fax or sms account)
		// ----------------------------------------------------------------
		if (!empty($credential_fax) || !empty($credential_sms))
		{
			$buy0 = $this->getTemplatePath().'marketing_step0/buy_step0.tpl';
			$output .= $this->context->smarty->fetch($buy0);
		}

		// And we end with the footer
		// --------------------------
		$footer = $this->getTemplatePath().'footer.tpl';
		$output .= $this->context->smarty->fetch($footer);

		return $output;
	}

	public function postProcess()
	{
		if (Tools::isSubmit('submitMarketingAll'))
		{
			if (Tools::getValue('campaign_type') == 'marketing_f')
			{
				// Create a new fax campaign
				// -------------------------
				Db::getInstance()->insert('expressmailing_fax', array(
					'campaign_state' => 0,
					'campaign_date_create' => date('Y-m-d H:i:s'),
					'campaign_date_send' => date('Y-m-d H:i:00', time() + 60),
					'campaign_week_limit' => 'LMCJVS'
				));
				$this->campaign_id = Db::getInstance()->Insert_ID();

				// Redirect to send fax mailing
				// ----------------------------
				Tools::redirectAdmin('index.php?controller=AdminMarketingFStep1&campaign_id='.
					$this->campaign_id.
					'&token='.Tools::getAdminTokenLite('AdminMarketingFStep1'));
				exit;
			}
			elseif (Tools::getValue('campaign_type') == 'marketing_s')
			{
				// Create a new sms campaign
				// -------------------------
				Db::getInstance()->insert('expressmailing_sms', array(
					'campaign_state' => 0,
					'campaign_date_create' => date('Y-m-d H:i:s'),
					'campaign_date_send' => date('Y-m-d H:i:00', time() + 60)
				));
				$this->campaign_id = Db::getInstance()->Insert_ID();

				// Redirect to send sms mailing
				// ----------------------------
				Tools::redirectAdmin('index.php?controller=AdminMarketingSStep1&campaign_id='.
					$this->campaign_id.
					'&token='.Tools::getAdminTokenLite('AdminMarketingSStep1'));
				exit;
			}
			elseif (Tools::getValue('campaign_type') == 'marketing_e')
			{
				// Recovering the max broadcast limit per day
				// ------------------------------------------

				if ($this->session_api->connectFromCredentials('email'))
				{
					$response_array = array();
					$parameters = array('account_id' => $this->session_api->account_id);

					if ($this->session_api->call('email', 'account', 'get_formula', $parameters, $response_array))
						if (isset($response_array['broadcast_max_campaign']))
							$this->broadcast_max_daily = (int)$response_array['broadcast_max_campaign'];
				}

				// Create a new emailing campaign
				// ------------------------------
				Db::getInstance()->insert('expressmailing_email', array(
					'campaign_state' => 0,
					'campaign_lang' => Context::getContext()->country->iso_code,
					'campaign_date_create' => date('Y-m-d H:i:s'),
					'campaign_date_send' => date('Y-m-d H:i:00', time() + 60),
					'campaign_day_limit' => $this->broadcast_max_daily * 75 / 100,
					'campaign_week_limit' => 'LMCJVS'
				));

				$this->campaign_id = Db::getInstance()->Insert_ID();

				// Redirect to emailing step1
				// --------------------------
				Tools::redirectAdmin('index.php?controller=AdminMarketingEStep1&campaign_id='.
					$this->campaign_id.
					'&token='.Tools::getAdminTokenLite('AdminMarketingEStep1'));
				exit;
			}
			else
			{
				// Redirect to home
				// ----------------
				Tools::redirectAdmin('index.php?controller=AdminMarketingX&token='.Tools::getAdminTokenLite('AdminMarketingX'));
				exit;
			}
		}
	}

}
