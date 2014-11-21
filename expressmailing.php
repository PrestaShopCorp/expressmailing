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

if (!defined('_PS_VERSION_'))
	exit;

class ExpressMailing extends Module
{
	private $html_preview_folder = null;

	public function __construct()
	{
		$this->name = 'expressmailing';
		$this->displayName = 'Express-Mailing';
		$this->tab = 'emailing';
		$this->version = '1.0.0';
		$this->need_instance = 0;
		$this->bootstrap = true;
		$this->ps_versions_compliancy = array('min' => '1.5', 'max' => _PS_VERSION_);
		$this->author = 'Axalone France';
		$this->limited_countries = array('fr', 'pl');
		$this->description = /* [VALIDATOR MAX 150 CAR] */
		$this->l('Marketing Module from Express-Mailing, including emailing (100% free), sending faxes and sms at low price (mass or unitarily)');
		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
		$this->html_preview_folder = _PS_ROOT_DIR_.DIRECTORY_SEPARATOR.'expressmailing'.DIRECTORY_SEPARATOR;

		parent::__construct();

		// Custom des displayError
		// -----------------------
		$iso_code = $this->context->language->iso_code;
		include_once(_PS_TRANSLATIONS_DIR_.$iso_code.DIRECTORY_SEPARATOR.'errors.php');
		include(_PS_MODULE_DIR_.'expressmailing'.DIRECTORY_SEPARATOR.'translations'.DIRECTORY_SEPARATOR.$iso_code.DIRECTORY_SEPARATOR.'errors.php');
	}

	public function reset()
	{
		if (!$this->uninstall(false)) return false;
		if (!$this->install(false))   return false;

		return true;
	}

	public function install($alter_db = true)
	{
		if (Shop::isFeatureActive())
			Shop::setContext(Shop::CONTEXT_ALL);

		if (!parent::install())
			return false;

		if ((bool)$alter_db)
			if (!$this->installDB())
				return false;

		if (!$this->installAdminTab('AdminMarketing', true, $this->l('Marketing'), 0))
			return false;

		$id_marketing = Tab::getIdFromClassName('AdminMarketing');

		// On ajoute les 3 médias
		// ----------------------
		if (!$this->installAdminTab('AdminMarketingE', true, $this->l('Send an e-mailing'), $id_marketing))			return false;
		if (!$this->installAdminTab('AdminMarketingF', true, $this->l('Send a fax-Mailing'), $id_marketing))		return false;
		if (!$this->installAdminTab('AdminMarketingS', true, $this->l('Send a sms-Mailing'), $id_marketing))		return false;

		// On ajoute les onglets de consultation des statistiques
		// ------------------------------------------------------
		if (!$this->installAdminTab('AdminMarketingEStats', false, $this->l('My email statistics'), $id_marketing))	return false;
		if (!$this->installAdminTab('AdminMarketingFStats', false, $this->l('My fax statistics'), $id_marketing))	return false;
		if (!$this->installAdminTab('AdminMarketingSStats', false, $this->l('My sms statistics'), $id_marketing))	return false;

		// On ajoute les étapes nécessaires à l'emailing
		// ---------------------------------------------
		if (!$this->installAdminTab('AdminMarketingEStep1', false, $this->l('Send an e-mailing'), $id_marketing))	return false;
		if (!$this->installAdminTab('AdminMarketingEStep2', false, $this->l('Send an e-mailing'), $id_marketing))	return false;
		if (!$this->installAdminTab('AdminMarketingEStep3', false, $this->l('Send an e-mailing'), $id_marketing))	return false;
		if (!$this->installAdminTab('AdminMarketingEStep4', false, $this->l('Send an e-mailing'), $id_marketing))	return false;
		if (!$this->installAdminTab('AdminMarketingEStep5', false, $this->l('Send an e-mailing'), $id_marketing))	return false;
		if (!$this->installAdminTab('AdminMarketingEStep6', false, $this->l('Send an e-mailing'), $id_marketing))	return false;
		if (!$this->installAdminTab('AdminMarketingEStep7', false, $this->l('Send an e-mailing'), $id_marketing))	return false;

		if (!$this->registerHook('backOfficeHeader'))
			return false;

/*
		$lang = Configuration::get('PS_LANG_DEFAULT');
		$q = new QuickAccess();
		$q->link = 'http://www.google.com';
		$q->new_window = 1;
		$q->name[$lang] = $this->l('Send a mailing (email, fax, sms)');
		$q->add();
*/

		$this->html .= $this->displayConfirmation('Settings updated');

		return true;
	}

	public function installDB()
	{
		/* Pour le stockage des identifiants à l'api */

		if (!Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'expressmailing` (
				`api_media` ENUM(\'all\',\'email\',\'fax\',\'sms\') NOT NULL DEFAULT \'all\',
				`api_login` VARCHAR(255) NULL DEFAULT NULL,
				`api_password` VARCHAR(255) NULL DEFAULT NULL,
				PRIMARY KEY (`api_login`)
			) DEFAULT CHARSET=utf8')) return false;

		/* Pour le stockage des campagnes d'emailing */
		/* Week_limite : L=Lundi M=Mardi C=Mercredi J=Jeudi V=Vendredi S=Samedi D=Dimanche */

		if (!Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'expressmailing_email` (
				`campaign_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`campaign_state` INT UNSIGNED NOT NULL DEFAULT 1,
				`campaign_date_create` DATETIME NOT NULL,
				`campaign_date_update` DATETIME NULL DEFAULT NULL,
				`campaign_date_send` DATETIME NULL DEFAULT NULL,
				`campaign_name` VARCHAR(255) NULL DEFAULT NULL,
				`campaign_tracking` ENUM(\'1\',\'0\') NOT NULL DEFAULT \'1\',
				`campaign_linking` ENUM(\'1\',\'0\') NOT NULL DEFAULT \'1\',
				`campaign_redlist` ENUM(\'1\',\'0\') NOT NULL DEFAULT \'1\',
				`campaign_day_limit` INT(11) UNSIGNED NOT NULL DEFAULT 0,
				`campaign_max_limit` INT(11) UNSIGNED NOT NULL DEFAULT 0,
				`campaign_week_limit` VARCHAR(7) NULL DEFAULT NULL,
				`campaign_lang` VARCHAR(2) NOT NULL DEFAULT \'fr\',
				`campaign_html` LONGTEXT NULL,
				`campaign_sender_email` VARCHAR(255) NULL DEFAULT NULL,
				`campaign_sender_name` VARCHAR(255) NULL DEFAULT NULL,
				`campaign_optin` ENUM(\'1\',\'0\') NOT NULL DEFAULT \'1\',
			    `campaign_newsletter` ENUM(\'1\',\'0\') NOT NULL DEFAULT \'1\',
			    `campaign_active` ENUM(\'1\',\'0\') NOT NULL DEFAULT \'1\',
			    `campaign_api_list_id` INT UNSIGNED NULL DEFAULT NULL,
			    `campaign_api_message_id` INT UNSIGNED NULL DEFAULT NULL,
			    `campaign_last_tester` VARCHAR(255) NULL DEFAULT NULL,
				PRIMARY KEY (`campaign_id`),
				INDEX `index_state` (`campaign_state`)
			) DEFAULT CHARSET=utf8')) return false;

		if (!Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'expressmailing_email_groups` (
			    `campaign_id` INT UNSIGNED NOT NULL,
			    `group_id` INT UNSIGNED NOT NULL,
				PRIMARY KEY (`campaign_id`, `group_id`)
			) DEFAULT CHARSET=utf8')) return false;

		if (!Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'expressmailing_email_langs` (
			    `campaign_id` INT UNSIGNED NOT NULL,
			    `lang_id` INT UNSIGNED NOT NULL,
				PRIMARY KEY (`campaign_id`, `lang_id`)
			) DEFAULT CHARSET=utf8')) return false;

		/* Pour le stockage des campagnes de fax-mailing */

		/* Pour le stockage des campagnes de sms-mailing */

		return true;
	}

	public function uninstall($alter_db = true)
	{
		Configuration::deleteByName('session_id_api');

		$this->unregisterHook('backOfficeHeader');

		$this->uninstallAdminTab('AdminMarketingEStep1');
		$this->uninstallAdminTab('AdminMarketingEStep2');
		$this->uninstallAdminTab('AdminMarketingEStep3');
		$this->uninstallAdminTab('AdminMarketingEStep4');
		$this->uninstallAdminTab('AdminMarketingEStep5');
		$this->uninstallAdminTab('AdminMarketingEStep6');
		$this->uninstallAdminTab('AdminMarketingEStep7');

		$this->uninstallAdminTab('AdminMarketingEStats');
		$this->uninstallAdminTab('AdminMarketingFStats');
		$this->uninstallAdminTab('AdminMarketingSStats');

		$this->uninstallAdminTab('AdminMarketingE');
		$this->uninstallAdminTab('AdminMarketingF');
		$this->uninstallAdminTab('AdminMarketingS');
		$this->uninstallAdminTab('AdminMarketing');

		if ((bool)$alter_db)
		{
			if (!$this->uninstallDB())
				return false;

			// On retire le répertoire de stockage des images des emailing
			// -----------------------------------------------------------
			if (Tools::file_exists_no_cache($this->getPreviewFolder()))
				Tools::deleteDirectory($this->getPreviewFolder());
		}

		if (!parent::uninstall())
			return false;

		return true;
	}

	public function uninstallDB()
	{
		if (!Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'expressmailing_email_groups`'))
			return false;

		if (!Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'expressmailing_email_langs`'))
			return false;

		if (!Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'expressmailing_email`'))
			return false;

		if (!Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'expressmailing`'))
			return false;

		return true;
	}

	private function installAdminTab($tab_class, $tab_active, $tab_name, $tab_parent)
	{
		if (!Tab::getIdFromClassName($tab_class))
		{
			// Si l'onglet n'existe pas déjà on l'ajoute
			// -----------------------------------------
			$tab = new Tab();
			$tab->name = array();

			foreach (Language::getLanguages() as $language)
				$tab->name[$language['id_lang']] = (string)$tab_name;

			$tab->class_name = (string)$tab_class;
			$tab->module = $this->name;
			$tab->id_parent = (int)$tab_parent;
			$tab->active = (bool)$tab_active ? 1 : 0;

			if (!$tab->save())
				return false;

			// On change sa position
			// Voir https://github.com/pal/prestashop/blob/master/classes/Tab.php
			// ------------------------------------------------------------------
			if ((int)$tab_parent == 0)
			{
				$tab_promo = Tab::getTab($this->context->language->id, Tab::getIdFromClassName('AdminPriceRule'));
				$tab_marketing = Tab::getTab($this->context->language->id, Tab::getIdFromClassName($tab_class));

				$position_promo = $tab_promo['position'] + 1;
				$position_marketing = $tab_marketing['position'];

				for ($i = $position_marketing; $i > $position_promo; $i--)
					$tab->move('l');
			}

			// Puis on valide l'ajout de l'onglet
			// ----------------------------------
				return true;
		}

		return false;
	}

	private function uninstallAdminTab($tab_class)
	{
		$id_tab = Tab::getIdFromClassName((string)$tab_class);

		if ($id_tab != 0)
		{
			// On retire le menu
			// -----------------
			$tab = new Tab($id_tab);
			$tab->delete();

			return true;
		}

		return false;
	}

	public function hookBackOfficeHeader()
	{
		$this->context->controller->addCSS($this->_path.'css/icon-marketing.css', 'all');
	}

	public function getContent()
	{
		$output = $this->display(__FILE__, 'views/templates/admin/step0.tpl');
		$output .= $this->display(__FILE__, 'views/templates/admin/footer.tpl');

		return $output;
	}

	public function getPreviewFolder()
	{
		return $this->html_preview_folder;
	}

}