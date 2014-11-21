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

class AdminMarketingEStep6Controller extends ModuleAdminController
{
	private $campaign_id = null;
	private $session_api = null;

	public function __construct()
	{
		$this->name = 'adminmarketingestep6';
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

	public function renderList()
	{
		// 1er bloc : Tests avant envoi
		// ----------------------------
		$this->fields_form = array(
			'legend' => array(
				'title' => $this->module->l('Test your e-mailing before his validation (6)', 'adminmarketingestep6'),
				'icon' => 'icon-envelope-alt'
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
					'label' => $this->module->l('Your email address', 'adminmarketingestep6'),
					'name' => 'campaign_last_tester',
					'prefix' => '<i class="icon-envelope-o"></i>',
					'required' => true
				)
			),
			'submit' => array(
				'title' => $this->module->l('Send a test', 'adminmarketingestep6'),
				'name' => 'submitEmailingTest',
				'icon' => 'process-icon-envelope'
			)
		);

		$this->getFieldsValues();
		$output = parent::renderForm();

		// 2eme bloc : Apercu du HTML
		// --------------------------
		$this->fields_form = array(
			'legend' => array(
				'title' => $this->module->l('Preview of your mailing', 'adminmarketingestep6'),
				'icon' => 'icon-picture'
			),
			'input' => array(
				array(
					'type' => 'free',
					'name' => 'iframe_html',
					'align' => 'center',
					'lang' => false,
					'readonly' => 'readonly'
				)
			),
			'buttons' => array(
				array(
					'href' => 'index.php?controller=AdminMarketingEStep3&campaign_id='.
					$this->campaign_id.
					'&token='.Tools::getAdminTokenLite('AdminMarketingEStep3'), /* [VALIDATOR MAX 150 CAR] */
					'title' => $this->module->l('Update', 'adminmarketingestep6'),
					'icon' => 'process-icon-edit'
				),
				array(
					'href' => 'index.php?controller=AdminMarketingEStep7&campaign_id='.
					$this->campaign_id.
					'&token='.Tools::getAdminTokenLite('AdminMarketingEStep7'), /* [VALIDATOR MAX 150 CAR] */
					'title' => $this->module->l('Next', 'adminmarketingestep6'),
					'icon' => 'process-icon-next',
					'class' => 'pull-right'
				)
			)
		);

		$this->getFieldsValues();
		$output .= parent::renderForm();

		// Puis on renvoi la fusion des 2 blocs + le footer
		// ------------------------------------------------
		$footer = $this->getTemplatePath().'footer.tpl';
		$output .= $this->context->smarty->fetch($footer);

		return $output;
	}

	public function postProcess()
	{
		// ---------------------------------------------------
		// Nota : La session est vérifiée dans le constructeur
		// donc ici la session est déjà établie !
		// ---------------------------------------------------

		if (Tools::isSubmit('submitEmailingTest'))
		{
			// On vérifie l'adresse email saisie
			// ---------------------------------
			$campaign_last_tester = Tools::getValue('campaign_last_tester', Configuration::get('PS_SHOP_EMAIL'));

			if (!Validate::isEmail($campaign_last_tester))
			{
				$this->errors[] = Tools::displayError('Please verify your email address');
				return false;
			}

			// On mémorise le dernier email de test dans la bdd locale
			// -------------------------------------------------------
			Db::getInstance()->update('expressmailing_email',
				array(
					'campaign_last_tester' => $campaign_last_tester
				),
				'campaign_id = '.$this->campaign_id
			);

			// Puis on appele la fonction d'envoi de test
			// ------------------------------------------
			return $this->sendTest($campaign_last_tester);
		}
	}

	private function getFieldsValues()
	{
		// Campaign_id
		// -----------
		$this->fields_value['campaign_id'] = $this->campaign_id;

		// On retrouve l'aperçu html du mailing
		// ------------------------------------
		$sql = new DbQuery();
		$sql->select('*');
		$sql->from('expressmailing_email');
		$sql->where('campaign_id = '.$this->campaign_id);
		$result = Db::getInstance()->getRow($sql);

		$this->fields_value['iframe_html'] = $this->getHtmlPreview();
		$this->fields_value['preview_html'] = $result['campaign_html'];

		if (!empty($result['campaign_last_tester']))
			$this->fields_value['campaign_last_tester'] = $result['campaign_last_tester'];
		else
			$this->fields_value['campaign_last_tester'] = Configuration::get('PS_SHOP_EMAIL');

		return true;
	}

	private function getHtmlPreview()
	{
		// On retrouve le code HTML
		// ------------------------
		$sql = new DbQuery();
		$sql->select('campaign_html');
		$sql->from('expressmailing_email');
		$sql->where('campaign_id = '.$this->campaign_id);

		return Db::getInstance()->getValue($sql);
	}

	private function sendTest($recipient)
	{
		if (!empty($this->session_api->account_id) && ($this->session_api->account_id > 0))
		{
			$campaign_infos = $this->getCampaignInfos();
			$last_tester = new Customer();

			// 1 - On ajoute le destinataire du test dans la liste du mailing en cours
			// -----------------------------------------------------------------------
			if ($last_tester->getByEmail((string)$recipient))
			{
				$response_array = array();
				$parameters = array(
					'account_id' => $this->session_api->account_id,
					'list_id' => $campaign_infos['campaign_api_list_id'],
					'recipients' => array(
						array(
							'target' => $last_tester->email,
							'lastname' => $last_tester->lastname,
							'firstname' => $last_tester->firstname
						)
					)
				);

				$this->session_api->call('email', 'recipients', 'add', $parameters, $response_array);
			}

			// 2 - On envoi un test au destinataire
			/// -----------------------------------
			$response_array = array();
			$parameters = array(
				'account_id' => $this->session_api->account_id,
				'list_id' => $campaign_infos['campaign_api_list_id'],
				'message_id' => $campaign_infos['campaign_api_message_id'],
				'recipient' => $recipient
			);

			if ($this->session_api->call('email', 'campaign', 'send_test', $parameters, $response_array))
			{
				$this->confirmations[] = sprintf($this->module->l('An email as been sent to : %s'), $recipient);
				return true;
			}
		}

		$this->errors[] = sprintf(Tools::displayError('Error during communication with Express-Mailing API : %s'), $this->session_api->getError());
		return false;
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

}