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

class AdminMarketingEStatsController extends ModuleAdminController
{
	private $session_api = null;

	public function __construct()
	{
		$this->name = 'adminmarketingestats';
		$this->bootstrap = true;
		$this->module = 'expressmailing';
		$this->context = Context::getContext();
		$this->lang = false;
		$this->default_form_language = $this->context->language->id;

		parent::__construct();

		// Initialisation de l'API
		// -----------------------
		include _PS_MODULE_DIR_.$this->module->name.'/controllers/admin/session_api.php';
		$this->session_api = new SessionApi();

		$this->table = 'expressmailing_email';

		$this->fields_list = array(
				'campaign_id' => array(
					'title' => 'ID',
					'align' => 'center',
					'width' => 25
				),
				'campaign_name' => array(
					'title' => 'Name',
					'width' => 'auto'
				)
		);
	}

	public function renderList()
	{
		// Vérification de la session
		// --------------------------
		if (!$this->session_api->connectFromCredentials('email'))
		{
			$this->errors[] = Tools::displayError('There are no Express Mailing account linked to this site Prestashop.');
			exit;
		}

		// Préparation du helper commun
		// ----------------------------
		$output = '';

		// Campagne en cours de création
		// -----------------------------
		if ($data = $this->getCampaigns('1'))
		{
			$helper = new HelperList();
			$helper->no_link = true;			// Lignes non cliquables
			$helper->shopLinkType = '';			// Faut le mettre
			$helper->simple_header = true;		// Retire l'entente de filtrage des données
			$helper->identifier = 'campaign_id';
			$helper->show_toolbar = true;
			$helper->table = 'expressmailing_email';

			$fields_list = array(
				'campaign_id' => array(
					'title' => 'ID',
					'width' => 140,
					'type' => 'text'
				),
				'campaign_date_update' => array(
					'title' => 'Last Update',
					'width' => 'auto',
					'type'=> 'text'
				),
				'campaign_name' => array(
					'title' => 'Subject',
					'width' => 'auto',
					'type' => 'text'
				)
			);

			$helper->actions = array('edit', 'delete', 'view');
			$helper->title = 'Campagnes en cours de cr&eacute;ation';
			$output .= $helper->generateList($data, $fields_list);
		}

		// Campagne en cours d'envoi
		// -------------------------
		if ($data = $this->getCampaigns('2'))
		{
			$helper = new HelperList();
			$helper->no_link = true;			// Lignes non cliquables
			$helper->shopLinkType = '';		// Faut le mettre
			$helper->simple_header = true;	// Retire l'entente de filtrage des données
			$helper->identifier = 'campaign_id';
			$helper->show_toolbar = true;
			$helper->table = 'expressmailing_email';

			$fields_list = array(
				'campaign_id' => array(
					'title' => 'ID',
					'width' => 140,
					'type' => 'text'
				),
				'campaign_date_send' => array(
					'title' => 'Date send',
					'width' => 'auto',
					'type'=> 'text'
				),
				'campaign_name' => array(
					'title' => 'Subject',
					'width' => 'auto',
					'type' => 'text'
				)
			);

			$helper->title = 'Campagnes en cours d\'envoi';
			$output .= $helper->generateList($data, $fields_list);
		}

		return $output;
	}

	private function getCampaigns($campaign_state)
	{
		$req = new DbQuery();
		$req->select('campaign_id, campaign_state, campaign_date_update, campaign_date_send, campaign_name');
		$req->from('expressmailing_email');
		$req->where('campaign_state = \''.(int)$campaign_state.'\'');
		$req->orderby('campaign_date_update DESC');
		$req->limit(5);

		$user_list = Db::getInstance()->executeS($req->build());
		return $user_list;
	}

}