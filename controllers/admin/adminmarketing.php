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

class AdminMarketingController extends ModuleAdminController
{
	public function __construct()
	{
		$this->name = 'adminmarketing';
		$this->bootstrap = true;
		$this->module = 'expressmailing';
		$this->context = Context::getContext();
		$this->lang = false;
		$this->default_form_language = $this->context->language->id;

		parent::__construct();
	}

	public function renderList()
	{
		$step0 = $this->getTemplatePath().'step0.tpl';
		$this->content = $this->context->smarty->fetch($step0);
	}

	public function postProcess()
	{
		if (Tools::isSubmit('submitMarketingAll'))
		{
			if (Tools::getValue('campaign_type') == 'marketing_f')
			{
				// Redirection vers l'envoi de fax mailing
				Tools::redirectAdmin('index.php?controller=AdminMarketingF&token='.Tools::getAdminTokenLite('AdminMarketingF'));
				exit;
			}
			elseif (Tools::getValue('campaign_type') == 'marketing_s')
			{
				// Redirection vers l'envoi de sms mailing
				Tools::redirectAdmin('index.php?controller=AdminMarketingS&token='.Tools::getAdminTokenLite('AdminMarketingS'));
				exit;
			}
			else
			{
				// On crée une nouvelle campagne EMAILING dans l'état "0 - invisible"
				// L'état passera à "1 - En cours de rédaction" dès validation du formulaire step1
				// -------------------------------------------------------------------------------
				Db::getInstance()->insert('expressmailing_email', array(
					'campaign_state' => 0,
					'campaign_date_create' => date('Y-m-d H:i:s'),
					'campaign_date_send' => date('Y-m-d H:i:00', time() + 60)
				));

				$this->campaign_id = Db::getInstance()->Insert_ID();

				// Redirection vers l'étape 1 EMAILING
				// -----------------------------------
				Tools::redirectAdmin('index.php?controller=AdminMarketingEStep1&campaign_id='.
										$this->campaign_id.
										'&token='.Tools::getAdminTokenLite('AdminMarketingEStep1'));		/* [VALIDATOR MAX 150 CAR] */
				exit;
			}
		}
	}

}