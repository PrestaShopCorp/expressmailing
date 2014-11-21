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

class AdminMarketingEStep4Controller extends ModuleAdminController
{
	private $campaign_id = null;
	private $list_total = 0;
	private $checked_groups = array();
	private $checked_langs = array();
	private $checked_campaign_optin = null;
	private $checked_campaign_newsletter = null;
	private $checked_campaign_active = null;

	public function __construct()
	{
		$this->name = 'adminmarketingestep4';
		$this->bootstrap = true;
		$this->module = 'expressmailing';
		$this->context = Context::getContext();
		$this->lang = false;
		$this->default_form_language = $this->context->language->id;
		$this->table = 'customer';
		$this->simple_header = true;

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
		$this->addJqueryUI('ui.slider');
	}

	public function renderList()
	{
		$this->getFieldsValues();
		$this->setSmartyVars();

		// 1er panel : Critères de sélection (2 blocs)
		// -------------------------------------------
		$output = '';
		$display = $this->getTemplatePath().'marketinge_step4/display.tpl';
		$output .= $this->context->smarty->fetch($display);

		// 2ème panel : Aperçu des destinataires
		// -------------------------------------
		$this->fieldImageSettings = array(
			'name' => 'id_lang',
			'dir' => 'l'
		);

		$this->imageType = 'jpg';
		$this->image_dir = $this->fieldImageSettings['dir'];

		$fields_list = array(
			'id_customer' => array(
				'title' => '#',
				'width' => 140,
				'type' => 'text'
			),
			'iso_code' => array(
				'title' => $this->module->l('Lang', 'adminmarketingestep4'),
				'align' => 'center',
				'width' => 30,
				'type' => 'text'
			),
			'firstname' => array(
				'title' => $this->module->l('First name', 'adminmarketingestep4'),
				'width' => 140,
				'type' => 'text'
			),
			'lastname' => array(
				'title' => $this->module->l('Last name', 'adminmarketingestep4'),
				'width' => 140,
				'type' => 'text'
			),
			'email' => array(
				'title' => $this->module->l('Email', 'adminmarketingestep4'),
				'width' => 140,
				'type' => 'text'
			),
			'ip_address' => array(
				'title' => $this->module->l('Cart IP address', 'adminmarketingestep4'),
				'width' => 140,
				'type' => 'text'
			)
		);

		$customers = $this->getCustomers();  // Le comptage sera stocké dans $this->list_total

		$helper_list = new HelperList();

		$helper_list->no_link = true;			// Lignes non cliquables
		$helper_list->shopLinkType = '';		// Faut le mettre
		$helper_list->simple_header = true;		// Retire l'entente de filtrage des données
		$helper_list->identifier = 'id_customer';
		$helper_list->show_toolbar = false;
		$helper_list->table = 'customer';
		$helper_list->imageType = 'jpg';

		$html_list = $helper_list->generateList($customers, $fields_list);
		if (!preg_match('/<table.*<\/table>/iUs', $html_list, $array_table))
			$output .= $html_list;

		// 3ème panel : Boutons Prev/Next
		// ------------------------------
		$this->fields_form = array(
			'legend' => array(
				'title' => $this->module->l('Recipients preview', 'adminmarketingestep4').'<span class="badge">'.$this->list_total.'</span>',
				'icon' => 'icon-envelope'
			),
			'input' => array(
				array(
					'type' => 'free',
					'name' => 'html_list'
				)
			),
			'buttons' => array(
				array(
					'href' => 'index.php?controller=AdminMarketingEStep5&campaign_id='.$this->campaign_id.
					'&token='.Tools::getAdminTokenLite('AdminMarketingEStep5'), /* [VALIDATOR MAX 150 CAR] */
					'title' => $this->module->l('Validate this selection', 'adminmarketingestep4'),
					'icon' => 'process-icon-next',
					'class' => 'pull-right'
				),
				array(
					'href' => 'index.php?controller=AdminMarketingEStep3&campaign_id='.
					$this->campaign_id.
					'&token='.Tools::getAdminTokenLite('AdminMarketingEStep3'), /* [VALIDATOR MAX 150 CAR] */
					'title' => $this->module->l('Back', 'adminmarketingestep4'),
					'icon' => 'process-icon-back',
					'class' => 'pull-left'
				)
			)
		);

		$html_boutons = parent::renderForm();

		// On imbrique la liste et les boutons
		// -----------------------------------
		if (count($array_table) > 0)
			$output .= str_replace('<div class="form-group">', '<div class="form-group">'.$array_table[0], $html_boutons);
		else
			$output .= $html_boutons;

		// Puis on affiche la fusion des 3 blocs + le footer
		// -------------------------------------------------
		$footer = $this->getTemplatePath().'footer.tpl';
		$output .= $this->context->smarty->fetch($footer);

		return $output;
	}

	private function renderFreeFilters()
	{
		// 1er bloc : Les critères de sélection
		// ------------------------------------
		$sql = 'SELECT `'._DB_PREFIX_.'customer_group`.id_group, `'._DB_PREFIX_.'group_lang`.name, `'._DB_PREFIX_.'customer_group`.id_group as val
					FROM `'._DB_PREFIX_.'customer_group`
					LEFT JOIN `'._DB_PREFIX_.'group_lang` ON `'._DB_PREFIX_.'customer_group`.id_group = `'._DB_PREFIX_.'group_lang`.id_group
					GROUP BY `'._DB_PREFIX_.'customer_group`.id_group';
		$field_groups = db::getInstance()->executeS($sql);

		$sql = 'SELECT `'._DB_PREFIX_.'lang`.id_lang, `'._DB_PREFIX_.'lang`.name, `'._DB_PREFIX_.'lang`.id_lang as val
					FROM `'._DB_PREFIX_.'customer`
					LEFT JOIN `'._DB_PREFIX_.'lang` ON `'._DB_PREFIX_.'customer`.id_lang = `'._DB_PREFIX_.'lang`.id_lang
					GROUP BY `'._DB_PREFIX_.'lang`.id_lang';
		$field_langs = db::getInstance()->executeS($sql);

		$field_subscriptions = array(
			array(
				'id' => 'campaign_optin',
				'val' => '1',
				'name' => $this->module->l('Filter Optin Customer', 'adminmarketingestep4')
			),
			array(
				'id' => 'campaign_newsletter',
				'val' => '1',
				'name' => $this->module->l('Filter Newsletter Customer', 'adminmarketingestep4')
			),
			array(
				'id' => 'campaign_active',
				'val' => '1',
				'name' => $this->module->l('Filter Active Customer', 'adminmarketingestep4')
			)
		);

		$this->fields_form = array(
			'legend' => array(
				'title' => $this->module->l('Recipients configuration (4)', 'adminmarketingestep4'),
				'icon' => 'icon-shopping-cart'
			),
			'description' => $this->module->l('Free filters work only on customers that have made a shopping cart !', 'adminmarketingestep4'),
			'input' => array(
				array(
					'type' => 'checkbox',
					'label' => $this->module->l('Groups', 'adminmarketingestep4'),
					'class' => 'checkbox-inline',
					'name' => 'groups[]',
					'prefix' => 'test prefixe',
					'values' => array(
						'query' => $field_groups,
						'id' => 'id_group',
						'class' => 'checkbox-inline',
						'name' => 'name' /* Label */
					)
				),
				array(
					'type' => 'checkbox',
					'label' => $this->module->l('Purchase language', 'adminmarketingestep4'),
					'class' => 'checkbox-inline',
					'name' => 'langs[]',
					'values' => array(
						'query' => $field_langs,
						'id' => 'id_lang',
						'name' => 'name'
					)
				),
				array(
					'type' => 'checkbox',
					'label' => $this->module->l('Subscription filters', 'adminmarketingestep4'),
					'class' => 'checkbox-inline',
					'name' => 'subscriptions',
					'values' => array(
						'query' => $field_subscriptions,
						'id' => 'id',
						'name' => 'name'
					)
				)
			),
			'submit' => array(
				'title' => $this->module->l('Apply settings', 'adminmarketingestep4'),
				'name' => 'refreshEmailingStep4',
				'icon' => 'process-icon-refresh'
			)
		);

		$output = parent::renderForm();
		return $this->getFormWrapperElement($output);
	}

	private function renderPayingFilters($extended = false)
	{
		if ($extended)
		{
			$template_path = $this->getTemplatePath().'marketinge_step4/filters_payed.tpl';
			return $this->context->smarty->fetch($template_path);
		}
		else
		{
			$template_path = $this->getTemplatePath().'marketinge_step4/filters_buy.tpl';
			return $this->context->smarty->fetch($template_path);
		}
	}

	public function postProcess()
	{
		if (Tools::isSubmit('refreshEmailingStep4'))
		{
			// On efface l'ancienne sélection Langs + Groups
			// ---------------------------------------------
			DB::getInstance()->delete('expressmailing_email_groups', 'campaign_id = '.$this->campaign_id);
			DB::getInstance()->delete('expressmailing_email_langs', 'campaign_id = '.$this->campaign_id);

			// On mémorise la sélection des Groupes
			// ------------------------------------
			if ($groups = Tools::getValue('groups'))
			{
				$db_entries = array();
				foreach ($groups as $group)
				{
					$this->checked_groups[] = (int)$group;
					$db_entries[] = array(
						'campaign_id' => (int)$this->campaign_id,
						'group_id' => (int)$group
					);
				}
				DB::getInstance()->insert('expressmailing_email_groups', $db_entries);
			}

			// On mémorise la sélection des Langues
			// ------------------------------------
			if ($langs = Tools::getValue('langs'))
			{
				$db_entries = array();
				foreach ($langs as $lang)
				{
					$this->checked_langs[] = (int)$lang;
					$db_entries[] = array(
						'campaign_id' => (int)$this->campaign_id,
						'lang_id' => (int)$lang
					);
				}
				DB::getInstance()->insert('expressmailing_email_langs', $db_entries);
			}

			// On mémorise les paramètes Optin/ Newsletter/ Active
			// ---------------------------------------------------
			$this->checked_campaign_optin = Tools::getValue('subscriptions_campaign_optin', '0');
			$this->checked_campaign_newsletter = Tools::getValue('subscriptions_campaign_newsletter', '0');
			$this->checked_campaign_active = Tools::getValue('subscriptions_campaign_active', '0');

			Db::getInstance()->update('expressmailing_email',
				array(
				'campaign_optin' => $this->checked_campaign_optin,
				'campaign_newsletter' => $this->checked_campaign_newsletter,
				'campaign_active' => $this->checked_campaign_active
				), 'campaign_id = '.$this->campaign_id
			);
		}
	}

	private function getFieldsValues()
	{
		// Campaign_id
		// -----------
		$this->fields_value['campaign_id'] = $this->campaign_id;

		// On retrouve les 3 valeurs Optin/ Newsletter /Active
		// ---------------------------------------------------
		$sql = new DbQuery();
		$sql->select('*');
		$sql->from('expressmailing_email');
		$sql->where('campaign_id = '.$this->campaign_id);
		$result = Db::getInstance()->getRow($sql);

		$this->checked_campaign_optin = $result['campaign_optin'];
		$this->checked_campaign_newsletter = $result['campaign_newsletter'];
		$this->checked_campaign_active = $result['campaign_active'];

		$this->fields_value['subscriptions_campaign_optin'] = $this->checked_campaign_optin;
		$this->fields_value['subscriptions_campaign_newsletter'] = $this->checked_campaign_newsletter;
		$this->fields_value['subscriptions_campaign_active'] = $this->checked_campaign_active;

		// On retrouve les groupes sélectionnnés
		// -------------------------------------
		$sql = new DbQuery();
		$sql->select('group_id');
		$sql->from('expressmailing_email_groups');
		$sql->where('campaign_id = '.$this->campaign_id);
		if ($result = Db::getInstance()->ExecuteS($sql))
			foreach ($result as $row)
			{
				$this->checked_groups[] = $row['group_id'];
				$this->fields_value['groups[]_'.$row['group_id']] = '1';
			}

		// On retrouve les langues sélectionnnées
		// --------------------------------------
		$sql = new DbQuery();
		$sql->select('lang_id');
		$sql->from('expressmailing_email_langs');
		$sql->where('campaign_id = '.$this->campaign_id);
		if ($result = Db::getInstance()->ExecuteS($sql))
			foreach ($result as $row)
			{
				$this->checked_langs[] = $row['lang_id'];
				$this->fields_value['langs[]_'.$row['lang_id']] = '1';
			}

		return true;
	}

	private function getCustomers($extended = false)
	{
		$req = new DbQuery();
		$req->select('SQL_CALC_FOUND_ROWS	customer.id_customer, customer.id_lang,
											customer.firstname, customer.lastname, customer.email,
											connections.ip_address, country.iso_code');
		$req->from('customer', 'customer');
		$req->leftJoin('customer_group', 'customer_group', 'customer_group.id_customer = customer.id_customer');
		$req->leftJoin('guest', 'guest', 'guest.id_customer = customer.id_customer');
		$req->leftJoin('connections', 'connections', 'connections.id_guest = guest.id_guest');
		$req->leftJoin('address', 'address', 'address.id_customer = customer.id_customer');
		$req->leftJoin('country', 'country', 'address.id_country = country.id_country');

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
		if (!$extended)
			$where[] = 'connections.ip_address IS NOT NULL';

		$req->where(implode(' AND ', $where));
		$req->orderby('customer.id_customer');
		$req->groupby('customer.id_customer');
		$req->limit(20);

		$user_list = Db::getInstance()->executeS($req->build());
		$this->list_total = Db::getInstance()->getValue('SELECT FOUND_ROWS()');

		$formated_user_list = array();
		foreach ($user_list as $user)
		{
			if ($user['ip_address'])
				$user['ip_address'] = long2ip($user['ip_address']);
			$formated_user_list[] = $user;
		}

		return $formated_user_list;
	}

	private function setSmartyVars()
	{
		$this->context->smarty->assign(array(
			'campaign_id' => $this->campaign_id,
			'free_filter_inputs' => $this->renderFreeFilters(),
			'paying_filter_inputs' => $this->renderPayingFilters()
		));
	}

	private function getFormWrapperElement($html)
	{
		$dom_original = new DOMDocument();
		$dom_output = new DOMDocument();

		$html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
		$dom_original->loadHTML($html);
		$xpath = new DOMXpath($dom_original);

		if (strpos($html, 'class="alert alert-info"'))
		{
			// S'il y a une balise 'alert-info', on la conserve
			// ------------------------------------------------
			$result = $xpath->query('//div[@class="alert alert-info"]');
			$output_div = $result->item(0);
			$dom_output->appendChild($dom_output->importNode($output_div, true));
		}

		// Puis on extrait le 'form-wrapper'
		// ---------------------------------
		$result = $xpath->query('//div[@class="form-wrapper"]');
		$output_div = $result->item(0);
		$dom_output->appendChild($dom_output->importNode($output_div, true));

		return $dom_output->saveHTML();
	}

}