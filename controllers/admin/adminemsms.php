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

class AdminEmsmsController extends ModuleAdminController
{
	/**
	 *
	 * @var SessionApi 
	 */
	private $session_api;
	private $account = null;

	public function __construct()
	{
		$this->name = 'adminemsms';
		$this->bootstrap = true;
		$this->module = 'expressmailing';
		$this->context = Context::getContext();
		$this->lang = false;
		$this->default_form_language = $this->context->language->id;

		parent::__construct();

		require_once _PS_MODULE_DIR_.$this->module->name.'/controllers/admin/session_api.php';
		require_once _PS_MODULE_DIR_.$this->module->name.'/controllers/admin/db_marketing.php';

		$this->session_api = new SessionApi();
		$this->session_api->connectFromCredentials('sms', $this->account);
		// TODO: if not connected Bouton crÃ©ation de compte
	}

	public function initToolbarTitle()
	{
		parent::initToolbarTitle();
		$this->toolbar_title = $this->module->l('Sms', 'adminemsms');
	}

	public function initPageHeaderToolbar()
	{
		$this->page_header_toolbar_btn['new_preset_sms'] = array(
			'href' => self::$currentIndex.'&addexpressmailing_sms_preset_messages&token='.$this->token,
			'desc' => $this->l('Add new preset sms'),
			'icon' => 'process-icon-new'
		);
		parent::initPageHeaderToolbar();
	}

	public function renderList()
	{
		if (Tools::isSubmit('updateexpressmailing_sms_preset_messages'))
			return $this->renderForm();
		elseif (Tools::isSubmit('addexpressmailing_sms_preset_messages'))
			return $this->renderForm();
		elseif (Tools::isSubmit('deleteexpressmailing_sms_preset_messages'))
			$this->deleteRow(Tools::getValue('id'));

		$fields_list = array(
			'id' => array(
				'title' => $this->l('ID'),
				'align' => 'center',
				'width' => 2
			),
			'name' => array(
				'title' => $this->l('Name'),
				'width' => 'auto'
			),
			'content' => array(
				'title' => $this->l('Content'),
				'width' => 'auto'
			)
		);

		$preset_messages = DBMarketing::getSMSPresetMessages();

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = true;
		$helper->actions = array('edit', 'delete');
		$helper->identifier = 'id';
		$helper->show_toolbar = true;
		$helper->title = "Preset SMS<span class='badge'>".count($preset_messages).'</span>';
		$helper->table = 'expressmailing_sms_preset_messages';
		$helper->token = Tools::getAdminTokenLite('AdminEmsms');
		$helper->currentIndex = AdminController::$currentIndex;

		$page = $helper->generateList($preset_messages, $fields_list);

		$orig = Tools::getValue('orig');
		if (!empty($orig))
		{
			$orig = Tools::jsonDecode(Tools::getValue('orig'));
			$ctrl_token = Tools::getAdminTokenLite($orig->controller);
			$page .= "
				<script type=\"text/javascript\">
					var after = \"<div class='panel-footer'><a href='index.php?token=$ctrl_token";
			foreach ($orig as $key => $value)
				$page .= "&$key=$value";
			$page .= '";';
			$page .= "
					after += \"' class='btn btn-default'><i class='process-icon-back'></i> back</a></div>\";
					$(\"#form-expressmailing_sms_preset_messages .table-responsive-row\").after(after);
				</script>
			";
		}

		return $page;
	}

	public function renderForm()
	{
		$this->fields_form = array(
			'legend' => array(
				'title' => $this->l('Edit preset sms')
			),
			'input' => array(
				array(
					'type' => 'hidden',
					'label' => $this->l('id'),
					'name' => 'id',
					'readonly' => 'readonly'
				),
				array(
					'type' => 'text',
					'label' => $this->l('Name'),
					'name' => 'name',
					'size' => 33,
					'required' => true,
					'desc' => $this->l('Name of the sms'),
				),
				array(
					'type' => 'text',
					'label' => $this->l('Content'),
					'name' => 'sms_content',
					'size' => 33,
					'required' => true,
					'desc' => $this->l('Content of the sms'),
				)
			),
			'submit' => array(
				'title' => $this->l('Save'),
				'name' => 'submit_presetsms',
				'icon' => 'process-icon-save'
			),
			'buttons' => array(
				array (
					'href' => 'index.php?controller=AdminEmsms&token='.Tools::getAdminTokenLite('AdminEmsms'),
					'title' => $this->module->l('Cancel', 'AdminEmsms'),
					'icon' => 'process-icon-back'
				)
			)
		);

		$id_preset_message = Tools::getValue('id', null);
		if (!Tools::isEmpty($id_preset_message))
		{
			$preset_message = DBMarketing::getSMSPresetMessages($id_preset_message);
			$this->fields_value['id'] = $preset_message[0]['id'];
			$this->fields_value['name'] = $preset_message[0]['name'];
			$this->fields_value['sms_content'] = $preset_message[0]['content'];
		}

		return parent::renderForm();
	}

	public function postProcess()
	{
		if (Tools::isSubmit('submit_presetsms'))
		{
			$id = Tools::getValue('id', null);
			$name = Tools::getValue('name');
			$content = Tools::getValue('sms_content');

			if (!Tools::isEmpty($id))
			{
				$id = (int)$id;
				$ret = Db::getInstance()->update('expressmailing_sms_preset_messages',
					array(
						'name' => pSQL($name),
						'content' => pSQL($content)
					), 'id = '.$id
				);
				if ($ret)
					$this->confirmations[] = $this->l('Preset SMS updated');
				else
					$this->errors[] = 'Unable to update the preset SMS';

			}
			else
			{
				$ret = Db::getInstance()->insert('expressmailing_sms_preset_messages', array(
					'name' => pSQL($name),
					'content' => pSQL($content)
				));
				if ($ret)
					$this->confirmations[] = $this->l('Preset SMS added');
				else
					$this->errors[] = 'Unable to add the preset SMS';
			}
		}
	}

	public function displayAjax()
	{
		$method = Tools::getValue('method', 'default');
		switch ($method)
		{
			case 'getAvailableCredits':
				echo Tools::jsonEncode($this->getAvailableCredits());
				break;

			case 'listMobileNumberFromOrder':
				$order_id = (int)Tools::getValue('order_id', null);
				echo Tools::jsonEncode($this->listMobileNumberFromOrder($order_id));
				break;

			case 'listCustomerMobileNumber':
				$id_customer = (int)Tools::getValue('id_customer', null);
				echo Tools::jsonEncode($this->listCustomerMobileNumber($id_customer));
				break;

			case 'listPresetMessages':
				echo Tools::jsonEncode($this->listPresetMessages());
				break;

			case 'listSentMessages':
				$target = Tools::getValue('target');
				echo Tools::jsonEncode($this->listSentMessages($target));
				break;

			case 'sendSMS':
				$recipient = Tools::getValue('recipient');
				$content = Tools::getValue('content');
				echo Tools::jsonEncode($this->sendSMS($recipient, $content));
				break;

			default:
				break;
		}
	}

	private function deleteRow($id)
	{
		$id = (int)$id;
		$ret = Db::getInstance()->delete('expressmailing_sms_preset_messages', "id = $id");
		if ($ret)
			$this->confirmations[] = $this->l('Preset SMS deleted');
		else
			$this->errors[] = 'Unable to delete the preset SMS';
	}

	private function getAvailableCredits()
	{
		$parameters = array('account_id' => $this->session_api->account_id);
		$response_array = array();
		$this->session_api->call('sms', 'account', 'enum_credit_balances', $parameters, $response_array);

		if (empty($response_array))
			return 0;

		$credits = '';
		foreach ($response_array as $credit)
			$credits .= $credit['balance'].' ('.$credit['credit_name'].') ';

		if (empty($credits))
			$credits = 0;

		return $credits;
	}

	private function listMobileNumberFromOrder($id_order)
	{
		$order = new Order($id_order);
		return $this->listCustomerMobileNumber($order->id_customer);
	}

	private function listCustomerMobileNumber($id_customer)
	{
		$customer = new Customer($id_customer);

		$addresses = $customer->getAddresses($this->context->language->id);

		$mobile_numbers = array();
		foreach ($addresses as $address)
		{
			if (!empty($address['phone_mobile']))
			{
				$mobile_numbers[] = array(
					'id_address' => $address['id_address'],
					'alias' =>  $address['alias'],
					'phone_mobile' => $address['phone_mobile']
				);
			}
		}

		return $mobile_numbers;
	}

	private function listPresetMessages()
	{
		/*
			CREATE TABLE `prestashop`.`ps_expressmailing_sms_preset_messages` (
			`id` INT NOT NULL AUTO_INCREMENT,
			`name` VARCHAR(100) NOT NULL,
			`content` TEXT NOT NULL,
			PRIMARY KEY (`id`));
		*/
		$res = DBMarketing::getSMSPresetMessages();
		return $res;
	}

	private function listSentMessages($target)
	{
		$target = preg_replace('/^0/', '+33 ', $target);
		$response = null;
		$parameters = array(
			'account_id' => $this->account['account_id'],
			'target' => $target
		);
		$this->session_api->call('sms', 'conversation', 'enum_conversationentries', $parameters, $response);
		return array_reverse($response);
	}

	private function sendSMS($recipient, $content)
	{
		$response = null;
		$parameters = array();
		$this->session_api->call('sms', 'on_demand', 'init_recipients_from_file', $parameters, $response);
		$guid = $response;

		$response = null;
		$parameters = array(
			'operation' => $guid,
			'recipients' => array(array('target' => $recipient))
		);
		$this->session_api->call('sms', 'on_demand', 'sendpart_recipients_from_file', $parameters, $response);
		$guid = $response;

		$response = null;
		$parameters = array(
			'account_id' => $this->account['account_id'],
			'document' => $content,
			'group'	=> 'Express-Mailing Prestashop',
			'operation'	=> $guid,
			'send_date' => null
		);
		$this->session_api->call('sms', 'on_demand', 'send_smss', $parameters, $response);

		return $response;
	}
}