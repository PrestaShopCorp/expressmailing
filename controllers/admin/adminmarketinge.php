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

class AdminMarketingEController extends ModuleAdminController
{
	private $campaign_id = null;

	public function __construct()
	{
		$this->name = 'adminmarketinge';
		$this->bootstrap = true;
		$this->context = Context::getContext();
		$this->module = 'expressmailing';

		parent::__construct();
	}

	public function renderList()
	{
		$step0 = $this->getTemplatePath().'step0.tpl';
		$output = $this->context->smarty->fetch($step0);

		$footer = $this->getTemplatePath().'footer.tpl';
		$output .= $this->context->smarty->fetch($footer);

		return $output;
	}

}