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

class AdminMarketingInscriptionController extends ModuleAdminController
{
	public $session_api = null;
	private $campaign_id = null;

	private $back_action = null;
	private $next_action = null;
	private $step_action = null;

	public function __construct()
	{
		$this->name = 'adminmarketinginscription';
		$this->bootstrap = true;
		$this->module = 'expressmailing';
		$this->context = Context::getContext();
		$this->lang = false;
		$this->default_form_language = $this->context->language->id;
		$this->next_controller = '';

		$this->campaign_id = (int)Tools::getValue('campaign_id');

		parent::__construct();

		switch ($this->controller_name)
		{
			case 'AdminMarketingEStep5': /* ---------------------------------------------------- */
				if (empty($this->campaign_id))
				{
					Tools::redirectAdmin('index.php?controller=AdminMarketingX&token='.Tools::getAdminTokenLite('AdminMarketingX'));
					exit;
				}
				$this->next_controller = $this->controller_name;
				$this->step_action = '5';
				$this->media = 'email';
				$this->back_action = 'index.php?controller=AdminMarketingEStep4&campaign_id='.
				$this->campaign_id.'&token='.Tools::getAdminTokenLite('AdminMarketingEStep4');

				$this->next_action = 'index.php?controller='.$this->controller_name.'&campaign_id='.
				$this->campaign_id.'&token='.Tools::getAdminTokenLite($this->controller_name);

				break;

			case 'AdminMarketingFStep5': /* ---------------------------------------------------- */
				if (empty($this->campaign_id))
				{
					Tools::redirectAdmin('index.php?controller=AdminMarketingX&token='.Tools::getAdminTokenLite('AdminMarketingX'));
					exit;
				}
				$this->next_controller = $this->controller_name;
				$this->step_action = '5';
				$this->media = 'fax';
				$this->back_action = 'index.php?controller=AdminMarketingFStep3&campaign_id='.
				$this->campaign_id.'&token='.Tools::getAdminTokenLite('AdminMarketingFStep3');

				$this->next_action = 'index.php?controller='.$this->controller_name.'&campaign_id='.
				$this->campaign_id.'&token='.Tools::getAdminTokenLite($this->controller_name);

				break;

			case 'AdminMarketingSStep4': /* ---------------------------------------------------- */
				if (empty($this->campaign_id))
				{
					Tools::redirectAdmin('index.php?controller=AdminMarketingX&token='.Tools::getAdminTokenLite('AdminMarketingX'));
					exit;
				}
				$this->next_controller = $this->controller_name;
				$this->step_action = '4';
				$this->media = 'sms';
				$this->back_action = 'index.php?controller=AdminMarketingSStep2&campaign_id='.
				$this->campaign_id.'&token='.Tools::getAdminTokenLite('AdminMarketingSStep2');

				$this->next_action = 'index.php?controller='.$this->controller_name.'&campaign_id='.
				$this->campaign_id.'&token='.Tools::getAdminTokenLite($this->controller_name);

				break;

			case 'AdminMarketingInscription':
				$this->next_controller = 'AdminMarketingX';
				$this->step_action = '1';
				$product = (string)Tools::getValue('product');
				$this->media = Tools::substr($product, 0, Tools::strpos($product, '-'));
				//$this->media = 'AdminMarketingX';
				$this->back_action = 'index.php?controller=AdminMarketingX&token='.Tools::getAdminTokenLite('AdminMarketingX');
				$this->next_action = 'index.php?controller=AdminMarketingX&token='.Tools::getAdminTokenLite('AdminMarketingX');
				break;

			default:
				Tools::redirectAdmin('index.php?controller=AdminMarketingX&token='.Tools::getAdminTokenLite('AdminMarketingX'));
				break;
		}

		// API initialization
		// ------------------
		include _PS_MODULE_DIR_.$this->module->name.'/controllers/admin/session_api.php';
		$this->session_api = new SessionApi();
	}

	public function initToolbarTitle()
	{
		parent::initToolbarTitle();
		$this->toolbar_title = $this->module->l('New subscriber', 'adminmarketinginscription');
	}

	public function renderList()
	{
		$this->getFieldsValues();

		if (!is_array($this->fields_form))
			$this->generateOpenForm();

		$output = parent::renderForm();

		$footer = $this->getTemplatePath().'footer.tpl';
		$output .= $this->context->smarty->fetch($footer);

		return $output;
	}

	public function generateOpenForm()
	{
		$this->fields_form = array(
			'legend' => array(
				'title' => sprintf($this->module->l('Link your Prestashop to Express-Mailing API (Step %s)', 'adminmarketinginscription'),
									$this->step_action),
				'icon' => 'icon-random'
			),
			'description' =>
$this->module->l('Please fill this form to connect your Prestashop to the Express-Mailing API and send your mailing.', 'adminmarketinginscription'),
			'input' => array(
				array (
					'type' => 'hidden',
					'lang' => false,
					'label' => 'Ref :',
					'name' => 'campaign_id',
					'col' => 1,
					'readonly' => 'readonly'
				),
				array (
					'type' => 'hidden',
					'lang' => false,
					'label' => 'Media :',
					'name' => 'media',
					'col' => 1,
					'readonly' => 'readonly'
				),
				array (
					'type' => 'text',
					'lang' => false,
					'label' => Translate::getAdminTranslation('Shop name', 'AdminStores').' :',
					'prefix' => '<i class="icon-home"></i>',
					'col' => 4,
					'name' => 'company_name',
					'validation' => 'isGenericName',
					'required' => true
				),
				array (
					'type' => 'text',
					'lang' => false,
					'label' => Translate::getAdminTranslation('Shop email', 'AdminStores').' :',
					'prefix' => '<i class="icon-envelope-o"></i>',
					'col' => 4,
					'name' => 'company_email',
					'required' => true
				),
				array(
					'type' => 'text',
					'label' => Translate::getAdminTranslation('Shop address line 1', 'AdminStores').' :',
					'name' => 'company_address1',
					'col' => 4,
					'required' => true,
					'validation' => 'isAddress'
				),
				array(
					'type' => 'text',
					'label' => Translate::getAdminTranslation('Shop address line 2', 'AdminStores').' :',
					'col' => 4,
					'name' => 'company_address2',
					'validation' => 'isAddress'
				),
				array(
					'type' => 'text',
					'label' => Translate::getAdminTranslation('Zip/postal code', 'AdminStores').' :',
					'col' => 1,
					'validation' => 'isGenericName',
					'name' => 'company_zipcode',
					'required' => true
				),
				array(
					'type' => 'text',
					'label' => Translate::getAdminTranslation('City', 'AdminStores').' :',
					'validation' => 'isGenericName',
					'col' => 4,
					'name' => 'company_city',
					'required' => true
				),
				array(
					'type' => 'select',
					'label' => Translate::getAdminTranslation('Country', 'AdminStores').' :',
					'name' => 'country_id',
					'required' => true,
					'default_value' => (int)$this->context->country->id,
					'options' => array(
						'query' => Country::getCountries($this->context->language->id),
						'id' => 'id_country',
						'name' => 'name',
					)
				),
				array (
					'type' => 'text',
					'lang' => false,
					'label' => Translate::getAdminTranslation('Phone', 'AdminStores').' :',
					'validation' => 'isGenericName',
					'prefix' => '<i class="icon-phone"></i>',
					'col' => 4,
					'name' => 'company_phone',
					'required' => true
				),
				array (
					'type' => 'free',
					'lang' => false,
					'label' => $this->module->l('Choose your package :', 'adminmarketinginscription'),
					'name' => 'buy_package',
					'required' => true,
					'class' => 'alert-info'
				),
				array (
					'type' => 'free',
					'lang' => false,
					'label' => $this->module->l('Terms of Use :', 'adminmarketinginscription'),
					'name' => 'validate_cgu',
					'desc' => $this->module->l('A click on the button', 'adminmarketinginscription').' "'.
					$this->module->l('Link to Express-Mailing', 'adminmarketinginscription').'" '.
					$this->module->l('below, implies acceptance of the present rules.', 'adminmarketinginscription'),
					'required' => true
				)
			),
			'submit' => array(
				'title' => $this->module->l('Link to Express-Mailing', 'adminmarketinginscription'),
				'name' => 'submitInscription',
				'icon' => 'process-icon-next'
			),
			'buttons' => array(
				array (
					'href' => $this->back_action,
					'title' => $this->module->l('Back', 'adminmarketinginscription'),
					'icon' => 'process-icon-back'
				)
			)
		);

		// Get the Terms of Use (CGU)
		// --------------------------
		switch ($this->media)
		{
			case 'email':

				// Need to remove the block "Choose your package" !
				foreach ($this->fields_form['input'] as $key => $field)
				{
					if ($field['name'] == 'buy_package')
					{
						unset($this->fields_form['input'][$key]);
						break;
					}
				}

				$cgv_iframe = $this->module->l('http://www.express-mailing.com/actualites/cgv-email.php', 'adminmarketinginscription');
				break;

			case 'fax':
				$cgv_iframe = $this->module->l('http://www.express-mailing.com/actualites/cgv-fax.php', 'adminmarketinginscription');
				break;

			case 'sms':
				$cgv_iframe = $this->module->l('http://www.express-mailing.com/actualites/cgv-sms.php', 'adminmarketinginscription');
				break;
		}

		$this->fields_value['validate_cgu'] = '<iframe border="0" src="'.$cgv_iframe.
			'" width="100%" height="350"><a href="'.$cgv_iframe.'" target="_blank">'.
			$this->module->l('Download Terms of Use', 'adminmarketinginscription').
			'</a></iframe>';

		// Enfin, on récupère tous les tickets disponibles pour Prestashop
		// ---------------------------------------------------------------
		if ($this->media == 'fax' || $this->media == 'sms')
		{
			$response_array = array();
			$parameters = array('application_id' => $this->session_api->application_id);

			if ($this->session_api->callExternal('http://www.express-mailing.com/api/cart/ws.php',
												'common', 'account', 'enum_credits', $parameters, $response_array))
			{
				// Reuse translation in step0
				// --------------------------
				$this->context->smarty->assign(
					array(
						'fax_credits' => Translate::getModuleTranslation('expressmailing', 'fax credits', 'buy_step0'),
						'euro_symbol' => Translate::getModuleTranslation('expressmailing', '€', 'buy_step0'),
						'sms_credits' => Translate::getModuleTranslation('expressmailing', 'sms credits', 'buy_step0'),
						'fax_per_unit' => Translate::getModuleTranslation('expressmailing', '(Let %.3f € / page)', 'buy_step0'),
						'sms_per_unit' => Translate::getModuleTranslation('expressmailing', '(Let %.3f € / sms)', 'buy_step0')
					)
				);

				// Puis on affiche les tickets dans smarty
				// ---------------------------------------
				$buy = $this->getTemplatePath().'marketing_inscription/buy_package.tpl';
				$tools = new EMTools;
				$this->context->smarty->assign('tool_date', $tools);

				if (($this->media == 'fax') && isset($response_array['fax']))
				{
					$this->context->smarty->assign('smarty_fax_tickets', $response_array['fax']);
					$this->fields_value['buy_package'] = $this->context->smarty->fetch($buy);
				}
				elseif (($this->media == 'sms') && isset($response_array['sms']))
				{
					$this->context->smarty->assign('smarty_sms_tickets', $response_array['sms']);
					$this->fields_value['buy_package'] = $this->context->smarty->fetch($buy);
				}
			}
		}
	}

	public function generateRescueForm()
	{
		$this->errors[] = sprintf($this->module->l('An account attached to your email address %s has already been registered', 'adminmarketinginscription'),
							Configuration::get('PS_SHOP_EMAIL'));

		$this->fields_form = array(
			'legend' => array(
				'title' => sprintf($this->module->l('Link your Prestashop to Express-Mailing API (Step %s)', 'adminmarketinginscription'),
									$this->step_action),
				'icon' => 'icon-warning-sign'
			),
			'description' => sprintf($this->module->l('We now resend your password to your Prestashop email address %s.', 'adminmarketinginscription'),
										Configuration::get('PS_SHOP_EMAIL')).'<br>'.
			$this->module->l('Simply copy-paste this password into the below text box.', 'adminmarketinginscription').'<br>'.
			$this->module->l('If you do not receive it, please call', 'adminmarketinginscription').
			' <b>'.$this->module->l('+33 169.313.961', 'adminmarketinginscription').'</b> '.
			$this->module->l('(Monday to Friday from 9am to 17pm)', 'adminmarketinginscription').' ...',
			'input' => array(
				array (
					'type' => _PS_MODE_DEV_ ? 'text' : 'hidden',
					'lang' => false,
					'label' => 'Ref :',
					'name' => 'campaign_id',
					'col' => 1,
					'readonly' => 'readonly'
				),
				array (
					'type' => _PS_MODE_DEV_ ? 'text' : 'hidden',
					'lang' => false,
					'label' => 'Media :',
					'name' => 'media',
					'col' => 1,
					'readonly' => 'readonly'
				),
				array (
					'type' => _PS_MODE_DEV_ ? 'text' : 'hidden',
					'lang' => false,
					'label' => 'Package :',
					'name' => 'product',
					'col' => 2,
					'readonly' => 'readonly'
				),
				array (
					'type' => 'text',
					'lang' => false,
					'label' => $this->module->l('Your Express-Mailing password', 'adminmarketinginscription'),
					'prefix' => '<i class="icon-key"></i>',
					'col' => 3,
					'name' => 'api_password',
					'required' => true
				)
			),
			'submit' => array(
				'title' => $this->module->l('Link to Express-Mailing', 'adminmarketinginscription'),
				'name' => 'submitRescue',
				'icon' => 'process-icon-next'
			),
			'buttons' => array(
				array (
					'href' => $this->back_action,
					'title' => $this->module->l('Back', 'adminmarketinginscription'),
					'icon' => 'process-icon-back'
				)
			)
		);
	}

	public function postProcess()
	{
		// On construit un login pour le compte
		// ------------------------------------
		// Si PS_SHOP_EMAIL = info@axalone.com
		// Alors login      = ps-info-axalone
		//   1/ On ajoute 'ps-' devant l'email
		//   2/ On retire l'extention .com à la fin
		//   3/ On remplace toutes les lettres accentuées par leurs équivalents sans accent
		//   4/ On remplace tous les sigles par des tirets
		//   5/ Enfin on remplace les doubles/triples tirets par des simples
		// --------------------------------------------------------------------------------
		$company_login = 'ps-'.Configuration::get('PS_SHOP_EMAIL');
		$company_login = Tools::substr($company_login, 0, strrpos($company_login, '.'));
		$company_login = $this->removeAccents($company_login);
		$company_login = Tools::strtolower($company_login);
		$company_login = preg_replace('/[^a-z0-9-]/', '-', $company_login);
		$company_login = preg_replace('/-{2,}/', '-', $company_login);

		$cart_product = (string)Tools::getValue('product', '');

		// Initialisation de l'API
		// -----------------------
		if (Tools::isSubmit('submitInscription'))
		{
			// On prépare l'ouverture du compte
			// --------------------------------
			$company_name = (string)Tools::getValue('company_name');
			$company_email = (string)Tools::getValue('company_email');
			$company_phone = (string)Tools::getValue('company_phone');
			$company_address1 = (string)Tools::getValue('company_address1');
			$company_address2 = (string)Tools::getValue('company_address2');
			$company_zipcode = (string)Tools::getValue('company_zipcode');
			$company_city = (string)Tools::getValue('company_city');
			$country_id = (int)Tools::getValue('country_id');

			$country = new Country($country_id);

			if (!is_object($country) || empty($country->id))
				$this->errors[] = Tools::displayError('Country is invalid');
			else
				$company_country = Country::getNameById($this->context->language->id, $country_id);

			if (!ValidateCore::isGenericName($company_name))
				$this->errors[] = sprintf(Tools::displayError('The %s field is required.'), '« '.
					Translate::getAdminTranslation('Shop name', 'AdminStores').' »');

			if (!ValidateCore::isEmail($company_email))
				$this->errors[] = sprintf(Tools::displayError('The %s field is required.'), '« '.
					Translate::getAdminTranslation('Shop email', 'AdminStores').' »');

			if (!ValidateCore::isPhoneNumber($company_phone))
				$this->errors[] = sprintf(Tools::displayError('The %s field is required.'), '« '.
					Translate::getAdminTranslation('Phone', 'AdminStores').' »');

			if (!ValidateCore::isAddress($company_address1))
				$this->errors[] = sprintf(Tools::displayError('The %s field is required.'), '« '.
					Translate::getAdminTranslation('Shop address line 1', 'AdminStores').' »');

			if ($country->zip_code_format && !$country->checkZipCode($company_zipcode))
				$this->errors[] = Tools::displayError('Your Zip/postal code is incorrect.').'<br />'.
				Tools::displayError('It must be entered as follows:').' '.
				str_replace('C', $country->iso_code, str_replace('N', '0', str_replace('L', 'A', $country->zip_code_format)));
			elseif (empty($company_zipcode) && $country->need_zip_code)
				$this->errors[] = Tools::displayError('A Zip/postal code is required.');
			elseif ($company_zipcode && !Validate::isPostCode($company_zipcode))
				$this->errors[] = Tools::displayError('The Zip/postal code is invalid.');

			if (!ValidateCore::isGenericName($company_city))
				$this->errors[] = sprintf(Tools::displayError('The %s field is required.'), '« '.
					Translate::getAdminTranslation('City', 'AdminStores').' »');

			// We save these informations in the database
			// ------------------------------------------
			Db::getInstance()->insert('expressmailing_order_address', array(
				'id_address' => 1,
				'company_name' => pSQL($company_name),
				'company_email' => pSQL($company_email),
				'company_address1' => pSQL($company_address1),
				'company_address2' => pSQL($company_address2),
				'company_zipcode' => pSQL($company_zipcode),
				'company_city' => pSQL($company_city),
				'country_id' => $country_id,
				'company_country' => pSQL($company_country),
				'company_phone' => pSQL($company_phone),
				'product' => pSQL($cart_product)
				), false, false, Db::REPLACE
			);

			// If form contains 1 or more errors, we stop the process
			// ------------------------------------------------------
			if (is_array($this->errors) && count($this->errors))
				return false;

			// Open a session on Express-Mailing API
			// -------------------------------------
			if ($this->session_api->openSession())
			{
				// We create the account
				// ---------------------
				$response_array = array();
				$parameters = array(
					'login' => $company_login,
					'info_company' => $company_name,
					'info_email' => $company_email,
					'info_phone' => $company_phone,
					'info_address' => $company_address1."\r\n".$company_address2,
					'info_country' => $company_country,
					'info_zipcode' => $company_zipcode,
					'info_city' => $company_city,
					'info_phone' => $company_phone,
					'info_contact_firstname' => $this->context->employee->firstname,
					'info_contact_lastname' => $this->context->employee->lastname,
					'email_report' => $this->context->employee->email
				);

				if ($this->session_api->createAccount($parameters, $response_array))
				{
					// If the form include the buying process (field 'product')
					// We initiate a new cart with the product selected
					// --------------------------------------------------------
					if ($cart_product)
					{
						Tools::redirectAdmin('index.php?controller=AdminMarketingBuy&submitCheckout&campaign_id='.
								$this->campaign_id.'&media='.$this->next_controller.'&product='.$cart_product.
								'&token='.Tools::getAdminTokenLite('AdminMarketingBuy'));
						exit;
					}

					// Else we back to the mailing process
					// -----------------------------------
					Tools::redirectAdmin($this->next_action);
					exit;
				}

				if ($this->session_api->error == 11)
				{
					// Account already existe, we print the rescue form (with password input)
					// ----------------------------------------------------------------------
					$response_array = array();
					$parameters = array('login' => $company_login);
					$this->session_api->resendPassword($parameters, $response_array);
					$this->generateRescueForm();
					return;
				}
				else
				{
					// Other error
					// -----------
					$this->errors[] = sprintf($this->module->l('Unable to create an account : %s', 'adminmarketinginscription'),
										$this->session_api->getError());
					return false;
				}
			}
			else
			{
				$this->errors[] = sprintf($this->module->l('Error during communication with Express-Mailing API : %s', 'adminmarketinginscription'),
									$this->session_api->getError());
				return false;
			}
		}
		elseif (Tools::isSubmit('submitRescue'))
		{
			// Rescue form : ask for existing password
			// ---------------------------------------
			if ($this->session_api->openSession())
			{
				$response_array = array();
				$password = trim((string)Tools::getValue('api_password'));
				$parameters = array('login' => $company_login, 'password' => $password);

				if ($this->session_api->connectUser($parameters, $response_array))
				{
					Db::getInstance()->insert('expressmailing', array(
						'api_login' => pSQL($company_login),
						'api_password' => pSQL($password)
						), false, false, Db::REPLACE
					);

					// If the form include the buying process (field 'product')
					// We initiate a new cart with the product selected
					// --------------------------------------------------------
					if ($cart_product)
					{
						Tools::redirectAdmin('index.php?controller=AdminMarketingBuy&submitCheckout&campaign_id='.
								$this->campaign_id.'&media='.$this->next_controller.'&product='.$cart_product.
								'&token='.Tools::getAdminTokenLite('AdminMarketingBuy'));
						exit;
					}

					// Else we back to the mailing process
					// -----------------------------------
					Tools::redirectAdmin($this->next_action);
					exit;
				}
			}

			$this->errors[] = sprintf($this->module->l('Error during communication with Express-Mailing API : %s', 'adminmarketinginscription'),
								$this->session_api->getError());
			return false;
		}
	}

	private function getFieldsValues()
	{
		$company_name = '';
		$company_email = '';
		$company_phone = '';
		$company_address1 = '';
		$company_address2 = '';
		$company_zipcode = '';
		$company_city = '';
		$company_country = 0;

		$cart_product = (string)Tools::getValue('product', '');

		$sql = new DbQuery();
		$sql->select('*');
		$sql->from('expressmailing_order_address');
		$sql->where('id_address = 1');

		if ($result = Db::getInstance()->getRow($sql))
		{
			$company_name = (string)$result['company_name'];
			$company_email = (string)$result['company_email'];
			$company_phone = (string)$result['company_phone'];
			$company_address1 = (string)$result['company_address1'];
			$company_address2 = (string)$result['company_address2'];
			$company_zipcode = (string)$result['company_zipcode'];
			$company_city = (string)$result['company_city'];
			$company_country = (int)$result['company_country'];
		}

		// Default value for company_name
		// ------------------------------
		if (empty($company_name))
			$company_name = Configuration::get('BLOCKCONTACTINFOS_COMPANY');
		if (empty($company_name))
			$company_name = Configuration::get('CHEQUE_NAME');
		if (empty($company_name))
			$company_name = Configuration::get('BANK_WIRE_OWNER');
		if (empty($company_name))
			$company_name = Configuration::get('PS_SHOP_NAME');

		// Default value for company_email
		// -------------------------------
		if (empty($company_email))
			$company_email = Configuration::get('PS_SHOP_EMAIL');

		// Default value for company_phone
		// -------------------------------
		if (empty($company_phone))
			$company_phone = Configuration::get('PS_SHOP_PHONE');
		if (empty($company_phone))
			$company_phone = Configuration::get('BLOCKCONTACT_TELNUMBER');
		if (empty($company_phone))
			$company_phone = Configuration::get('BLOCKCONTACTINFOS_PHONE');
		if (empty($company_phone))
			$this->module->l('+33 ', 'adminmarketinginscription');

		// Default value for company_country
		// ---------------------------------
		if (empty($company_country))
			$company_country = Configuration::get('PS_SHOP_COUNTRY_ID');
		if (empty($company_country))
			$company_country = (int)$this->context->country->id;

		// Other default values
		// --------------------
		if (empty($company_address1))
			$company_address1 = Configuration::get('PS_SHOP_ADDR1');
		if (empty($company_address2))
			$company_address2 = Configuration::get('PS_SHOP_ADDR2');
		if (empty($company_zipcode))
			$company_zipcode = Configuration::get('PS_SHOP_CODE');
		if (empty($company_city))
			$company_city = Configuration::get('PS_SHOP_CITY');

		// Initialize the smarty variables
		// -------------------------------
		$this->fields_value['media'] = $this->media;
		$this->fields_value['campaign_id'] = $this->campaign_id;
		$this->fields_value['company_name'] = $company_name;
		$this->fields_value['company_email'] = $company_email;
		$this->fields_value['company_phone'] = $company_phone;
		$this->fields_value['company_address1'] = $company_address1;
		$this->fields_value['company_address2'] = $company_address2;
		$this->fields_value['company_zipcode'] = $company_zipcode;
		$this->fields_value['company_city'] = $company_city;
		$this->fields_value['company_country'] = $company_country;

		$this->fields_value['cart_product'] = $cart_product;
		$this->context->smarty->assign('cart_product', $cart_product);

		return true;
	}
	
	private function removeAccents($string)
	{
		if (!preg_match('/[\x80-\xff]/', (string)$string))
			return $string;

		$a = array(/* a */ chr(192), chr(193), chr(194), chr(195), chr(196), chr(197), chr(198),
			/* c */ chr(199), chr(231),
			/* e */ chr(200), chr(201), chr(202), chr(203),
			/* i */ chr(204), chr(205), chr(206), chr(207),
			/* d */ chr(208),
			/* n */ chr(209),
			/* o */ chr(210), chr(211), chr(212), chr(213), chr(214), chr(215), chr(216),
			/* u */ chr(217), chr(218), chr(219), chr(220),
			/* y */ chr(221),
			/* th */ chr(222), chr(254), chr(223),
			/* a */ chr(224), chr(225), chr(226), chr(227), chr(228), chr(229), chr(230),
			/* e */ chr(232), chr(233), chr(234), chr(235),
			/* i */ chr(236), chr(237), chr(238), chr(239),
			/* th */ chr(240), chr(241),
			/* o */ chr(242), chr(243), chr(244), chr(245), chr(246), chr(247), chr(248),
			/* u */ chr(249), chr(250), chr(251), chr(252),
			/* y */ chr(253), chr(255),
			/* a */ chr(256), chr(257), chr(258), chr(259), chr(260), chr(261),
			/* c */ chr(262), chr(263), chr(264), chr(265), chr(266), chr(267), chr(268), chr(269),
			/* d */ chr(270), chr(271), chr(272), chr(273),
			/* e */ chr(274), chr(275), chr(276), chr(277), chr(278), chr(279), chr(280), chr(281), chr(282), chr(283),
			/* g */ chr(284), chr(285), chr(286), chr(287), chr(288), chr(289), chr(290), chr(291),
			/* h */ chr(292), chr(293), chr(294), chr(295),
			/* i */ chr(296), chr(297), chr(298), chr(299), chr(300), chr(301), chr(302), chr(303), chr(304), chr(305),
			/* ij */ chr(306), chr(307), chr(308), chr(309),
			/* k */ chr(310), chr(311),
			/* l */ chr(313), chr(314), chr(315), chr(316), chr(317), chr(318), chr(319), chr(320), chr(321), chr(322),
			/* n */ chr(323), chr(324), chr(325), chr(326), chr(327), chr(328), chr(329),
			/* o */ chr(332), chr(333), chr(334), chr(335), chr(336), chr(337), chr(338), chr(339),
			/* r */ chr(340), chr(341), chr(342), chr(343), chr(344), chr(345),
			/* s */ chr(346), chr(347), chr(348), chr(349), chr(350), chr(351), chr(352), chr(353),
			/* t */ chr(354), chr(355), chr(356), chr(357), chr(358), chr(359),
			/* u */ chr(360), chr(361), chr(362), chr(363), chr(364), chr(365), chr(366), chr(367), chr(368), chr(369), chr(370), chr(371),
			/* w */ chr(372), chr(373),
			/* y */ chr(374), chr(375), chr(376),
			/* z */ chr(377), chr(378), chr(379), chr(380), chr(381), chr(382),
			/* ss */ chr(383),
			/* b */ chr(384), chr(385), chr(386), chr(387), chr(388), chr(389),
			/* c */ chr(390), chr(391), chr(392),
			/* d */ chr(393), chr(394), chr(395), chr(396), chr(397),
			/* e */ chr(398), chr(399), chr(400),
			/* f */ chr(401), chr(402),
			/* g */ chr(403), chr(404),
			/* hw */ chr(405),
			/* i */ chr(406), chr(407),
			/* k */ chr(408), chr(409),
			/* l */ chr(410), chr(411),
			/* m */ chr(412),
			/* n */ chr(413), chr(414),
			/* o */ chr(415), chr(416), chr(417), chr(418), chr(419)
		);

		$b = array('a', 'a', 'a', 'a', 'a', 'a', 'ae',
			'c', 'c',
			'e', 'e', 'e', 'e',
			'i', 'i', 'i', 'i',
			'd',
			'n',
			'o', 'o', 'o', 'o', 'o', 'x', 'o',
			'u', 'u', 'u', 'u',
			'y',
			'th', 'th', 'ss',
			'a', 'a', 'a', 'a', 'a', 'a', 'ae',
			'e', 'e', 'e', 'e',
			'i', 'i', 'i', 'i',
			'th', 'n',
			'o', 'o', 'o', 'o', 'o', '-', 'o',
			'u', 'u', 'u', 'u',
			'y', 'y',
			'a', 'a', 'a', 'a', 'a', 'a',
			'c', 'c', 'c', 'c', 'c', 'c', 'c', 'c',
			'd', 'd', 'd', 'd',
			'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'oe', 'oe',
			'g', 'g', 'g', 'g', 'g', 'g', 'g', 'g',
			'h', 'h', 'h', 'h',
			'i', 'i', 'i', 'i', 'i', 'i', 'i', 'i', 'i', 'i',
			'ij', 'ij', 'j', 'j',
			'k', 'k',
			'l', 'l', 'l', 'l', 'l', 'l', 'l', 'l', 'l', 'l',
			'n', 'n', 'n', 'n', 'n', 'n', 'n',
			'o', 'o', 'o', 'o', 'o', 'o', 'oe', 'oe',
			'r', 'r', 'r', 'r', 'r', 'r',
			's', 's', 's', 's', 's', 's', 's', 's',
			't', 't', 't', 't', 't', 't',
			'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u',
			'w', 'w',
			'y', 'y', 'y',
			'z', 'z', 'z', 'z', 'z', 'z',
			'ss',
			'b', 'b', 'b', 'b', 'b', 'b',
			'c', 'c', 'c',
			'd', 'd', 'd', 'd', 'd',
			'e', 'e', 'e',
			'f', 'f',
			'g', 'g',
			'hw',
			'i', 'i',
			'k', 'k',
			'l', 'dl',
			'm',
			'n', 'n',
			'o', 'o', 'o', 'o', 'o'
		);

		$string = str_replace($a, $b, (string)$string);

		$chars = array(
			// Table Latin-1
			chr(195).chr(128) => 'a', chr(195).chr(129) => 'a',
			chr(195).chr(130) => 'a', chr(195).chr(131) => 'a',
			chr(195).chr(132) => 'a', chr(195).chr(133) => 'a',
			chr(195).chr(135) => 'c', chr(195).chr(136) => 'e',
			chr(195).chr(137) => 'e', chr(195).chr(138) => 'e',
			chr(195).chr(139) => 'e', chr(195).chr(140) => 'i',
			chr(195).chr(141) => 'i', chr(195).chr(142) => 'i',
			chr(195).chr(143) => 'i', chr(195).chr(145) => 'n',
			chr(195).chr(146) => 'o', chr(195).chr(147) => 'o',
			chr(195).chr(148) => 'o', chr(195).chr(149) => 'o',
			chr(195).chr(150) => 'o', chr(195).chr(153) => 'u',
			chr(195).chr(154) => 'u', chr(195).chr(155) => 'u',
			chr(195).chr(156) => 'u', chr(195).chr(157) => 'y',
			chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
			chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
			chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
			chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
			chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
			chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
			chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
			chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
			chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
			chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
			chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
			chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
			chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
			chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
			chr(195).chr(191) => 'y',
			// Table Latin Extendue
			chr(196).chr(128) => 'a', chr(196).chr(129) => 'a',
			chr(196).chr(130) => 'a', chr(196).chr(131) => 'a',
			chr(196).chr(132) => 'a', chr(196).chr(133) => 'a',
			chr(196).chr(134) => 'c', chr(196).chr(135) => 'c',
			chr(196).chr(136) => 'c', chr(196).chr(137) => 'c',
			chr(196).chr(138) => 'c', chr(196).chr(139) => 'c',
			chr(196).chr(140) => 'c', chr(196).chr(141) => 'c',
			chr(196).chr(142) => 'd', chr(196).chr(143) => 'd',
			chr(196).chr(144) => 'd', chr(196).chr(145) => 'd',
			chr(196).chr(146) => 'e', chr(196).chr(147) => 'e',
			chr(196).chr(148) => 'e', chr(196).chr(149) => 'e',
			chr(196).chr(150) => 'e', chr(196).chr(151) => 'e',
			chr(196).chr(152) => 'e', chr(196).chr(153) => 'e',
			chr(196).chr(154) => 'e', chr(196).chr(155) => 'e',
			chr(196).chr(156) => 'g', chr(196).chr(157) => 'g',
			chr(196).chr(158) => 'g', chr(196).chr(159) => 'g',
			chr(196).chr(160) => 'g', chr(196).chr(161) => 'g',
			chr(196).chr(162) => 'g', chr(196).chr(163) => 'g',
			chr(196).chr(164) => 'h', chr(196).chr(165) => 'h',
			chr(196).chr(166) => 'h', chr(196).chr(167) => 'h',
			chr(196).chr(168) => 'i', chr(196).chr(169) => 'i',
			chr(196).chr(170) => 'i', chr(196).chr(171) => 'i',
			chr(196).chr(172) => 'i', chr(196).chr(173) => 'i',
			chr(196).chr(174) => 'i', chr(196).chr(175) => 'i',
			chr(196).chr(176) => 'i', chr(196).chr(177) => 'i',
			chr(196).chr(178) => 'ij', chr(196).chr(179) => 'ij',
			chr(196).chr(180) => 'j', chr(196).chr(181) => 'j',
			chr(196).chr(182) => 'k', chr(196).chr(183) => 'k',
			chr(196).chr(184) => 'k', chr(196).chr(185) => 'l',
			chr(196).chr(186) => 'l', chr(196).chr(187) => 'l',
			chr(196).chr(188) => 'l', chr(196).chr(189) => 'l',
			chr(196).chr(190) => 'l', chr(196).chr(191) => 'l',
			chr(197).chr(128) => 'l', chr(197).chr(129) => 'l',
			chr(197).chr(130) => 'l', chr(197).chr(131) => 'n',
			chr(197).chr(132) => 'n', chr(197).chr(133) => 'n',
			chr(197).chr(134) => 'n', chr(197).chr(135) => 'n',
			chr(197).chr(136) => 'n', chr(197).chr(137) => 'n',
			chr(197).chr(138) => 'n', chr(197).chr(139) => 'n',
			chr(197).chr(140) => 'o', chr(197).chr(141) => 'o',
			chr(197).chr(142) => 'o', chr(197).chr(143) => 'o',
			chr(197).chr(144) => 'o', chr(197).chr(145) => 'o',
			chr(197).chr(146) => 'oe', chr(197).chr(147) => 'oe',
			chr(197).chr(148) => 'r', chr(197).chr(149) => 'r',
			chr(197).chr(150) => 'r', chr(197).chr(151) => 'r',
			chr(197).chr(152) => 'r', chr(197).chr(153) => 'r',
			chr(197).chr(154) => 's', chr(197).chr(155) => 's',
			chr(197).chr(156) => 's', chr(197).chr(157) => 's',
			chr(197).chr(158) => 's', chr(197).chr(159) => 's',
			chr(197).chr(160) => 's', chr(197).chr(161) => 's',
			chr(197).chr(162) => 't', chr(197).chr(163) => 't',
			chr(197).chr(164) => 't', chr(197).chr(165) => 't',
			chr(197).chr(166) => 't', chr(197).chr(167) => 't',
			chr(197).chr(168) => 'u', chr(197).chr(169) => 'u',
			chr(197).chr(170) => 'u', chr(197).chr(171) => 'u',
			chr(197).chr(172) => 'u', chr(197).chr(173) => 'u',
			chr(197).chr(174) => 'u', chr(197).chr(175) => 'u',
			chr(197).chr(176) => 'u', chr(197).chr(177) => 'u',
			chr(197).chr(178) => 'u', chr(197).chr(179) => 'u',
			chr(197).chr(180) => 'w', chr(197).chr(181) => 'w',
			chr(197).chr(182) => 'y', chr(197).chr(183) => 'y',
			chr(197).chr(184) => 'y', chr(197).chr(185) => 'z',
			chr(197).chr(186) => 'z', chr(197).chr(187) => 'z',
			chr(197).chr(188) => 'z', chr(197).chr(189) => 'z',
			chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
		);

		return strtr($string, $chars);
	}

}
