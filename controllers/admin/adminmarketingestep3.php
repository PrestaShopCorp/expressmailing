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

include_once 'html_cleaner.php';

/**
 * Step 3 : Provide HTML content & Images upload
 */
class AdminMarketingEStep3Controller extends ModuleAdminController
{
	private $campaign_id = null;
	private $html_content = null;

	public function __construct()
	{
		$this->name = 'adminmarketingestep3';
		$this->bootstrap = true;
		$this->module = 'expressmailing';
		$this->context = Context::getContext();
		$this->lang = false;
		$this->default_form_language = $this->context->language->id;

		$this->campaign_id = (int)Tools::getValue('campaign_id');

		if (empty($this->campaign_id))
		{
			Tools::redirectAdmin('index.php?controller=AdminMarketing&token='.Tools::getAdminTokenLite('AdminMarketing'));
			exit;
		}

		parent::__construct();
	}

	public function initToolbarTitle()
	{
		parent::initToolbarTitle();
		$this->toolbar_title = Translate::getModuleTranslation('expressmailing', 'Send an e-mailing', 'adminmarketingestep1');
	}

	public function setMedia()
	{
		$this->addCSS(_PS_MODULE_DIR_.'expressmailing/css/expressmailing.css');
		$this->addJS(_PS_MODULE_DIR_.'expressmailing/js/tinymce.js');
		parent::setMedia();
	}

	public function renderList()
	{
		$this->getHTMLContentDB();

		$images_to_upload = array();
		$images_to_upload = $this->parseImagesToUpload($this->html_content);

		$output = '';

		if (Tools::isSubmit('ignoreImagesEmailingStep3') || (count($images_to_upload) == 0))
		{
			$output = $this->generateImportForm();
			$output .= $this->generateEditorForm();
		}

		if (count($images_to_upload) > 0)
			$output .= $this->generateImagesUploadForm($images_to_upload);

		$footer = $this->getTemplatePath().'footer.tpl';
		$output .= $this->context->smarty->fetch($footer);

		return $output;
	}

	public function postProcess()
	{
		if (Tools::isSubmit('importEmailingStep3'))
		{
			$this->html_file = isset($_FILES['html_file']) ? $_FILES['html_file'] : false;
			$this->html_url = (string)Tools::getValue('html_url');

			if (!empty($this->html_file) && !empty($this->html_file['tmp_name']))
			{
				if (!$this->importFile($_FILES['html_file']))
					$this->errors[] = $this->module->l('Unable to import this file', 'adminmarketingestep3');
			}
			elseif (!empty($this->html_url))
			{
				if (!$this->importURL($this->html_url))
					$this->errors[] = $this->module->l('Unable to import this URL', 'adminmarketingestep3');
			}
			else
				$this->errors[] = $this->module->l('Please verify the required fields', 'adminmarketingestep3');
		}

		if (Tools::isSubmit('uploadImagesEmailingStep3'))
		{
			if (isset($_FILES))
				$this->html_content = $this->copyImagesAndUpdateHTML($this->html_content, null, $_FILES);

			$this->saveHTML();
		}

		if (Tools::isSubmit('saveEmailingStep3') || Tools::isSubmit('nextEmailingStep3'))
		{
			$this->html_content = (string)Tools::getValue('campaign_html');

			if (!empty($this->html_content))
			{
				$this->html_content = $this->copyImagesAndUpdateHTML($this->html_content);
				$this->saveHTML();

				$images_to_upload = $this->parseImagesToUpload($this->html_content);

				if (Tools::isSubmit('nextEmailingStep3') && (count($images_to_upload) == 0))
				{
					Tools::redirectAdmin('index.php?controller=AdminMarketingEStep4&campaign_id='.
						$this->campaign_id.
						'&token='.Tools::getAdminTokenLite('AdminMarketingEStep4'));
					exit;
				}
			}
			else
				$this->errors[] = $this->module->l('Please verify the required fields', 'adminmarketingestep3');
		}
	}

	/**
	 * Generate the import form
	 * @return string The HTML string containing the form
	 */
	private function generateImportForm()
	{
		$this->fields_form = array(
			'legend' => array(
				'title' => $this->module->l('Import HTML from file or URI (3)', 'adminmarketingestep3'),
				'icon' => 'icon-beaker'
			),
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
					'type' => 'file',
					'label' => $this->module->l('Import HTML page from your hard drive :', 'adminmarketingestep3'),
					'name' => 'html_file',
					'required' => false
				),
				array (
					'type' => 'text',
					'label' => $this->module->l('Import from web page :', 'adminmarketingestep3'),
					'name' => 'html_url',
					'prefix' => 'http://',
					'col' => 6,
					'required' => false
				)
			),
			'submit' => array(
				'title' => $this->module->l('Start analysis ...', 'adminmarketingestep3'),
				'name' => 'importEmailingStep3',
				'icon' => 'process-icon-cogs'
			)
		);
		return parent::renderForm();
	}

	/**
	 * Generate the images upload form
	 * @param Array $images_to_upload An array containing files informations
	 * @return string The HTML string containing the form
	 */
	private function generateImagesUploadForm(Array $images_to_upload)
	{
		$desc = $this->module->l('Your uploaded file contains %d picture(s) that refer to your hard drive', 'adminmarketingestep3');
		$desc = sprintf($desc, count($images_to_upload));
		$desc .= $this->module->l(', you need to upload them on your Prestashop newsletter storage directory.', 'adminmarketingestep3');

		$this->fields_form = array(
			'legend' => array(
				'title' => $this->module->l('Images upload', 'adminmarketingestep3'),
				'icon' => 'icon-beaker'
			),
			'description' => $desc,
			'input' => array(
				array (
					'type' => _PS_MODE_DEV_ ? 'text' : 'hidden',
					'lang' => false,
					'label' => 'Ref :',
					'name' => 'campaign_id',
					'col' => 1,
					'readonly' => 'readonly'
				)
			),
			'submit' => array(
				'title' => sprintf($this->module->l('Upload this %d image%s', 'adminmarketingestep3'),
					count($images_to_upload), (count($images_to_upload) > 1) ? 's' : ''),
				'name' => 'uploadImagesEmailingStep3',
				'icon' => 'process-icon-configure'
			),
			'buttons' => array(
				array (
					'type' => 'submit',
					'title' => $this->module->l('Ignore', 'adminmarketingestep3'),
					'name' => 'ignoreImagesEmailingStep3',
					'icon' => 'process-icon-back'
				)
			)
		);

		sort($images_to_upload);
		foreach ($images_to_upload as $key => $unavailable_image)
		{
			$this->fields_form['input'][] = array(
				'type' => 'file',
				'label' => basename($unavailable_image),
				'name' => 'image_upload_'.$key,
				'required' => false
			);
		}

		return parent::renderForm();
	}

	/**
	 * Generate the editor form
	 * @return string The HTML string containing the form
	 */
	private function generateEditorForm()
	{
		$this->fields_form = array(
			'legend' => array(
				'title' => $this->module->l('HTML editor', 'adminmarketingestep3'),
				'icon' => 'icon-edit'
			),
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
					'type' => 'textarea',
					'label' => $this->module->l('HTML body of your emailing :', 'adminmarketingestep3'),
					'name' => 'campaign_html',
					'lang' => false,
					'autoload_rte' => true,
					'required' => true
				)
			),
			'submit' => array(
				'title' => $this->module->l('Validate', 'adminmarketingestep3'),
				'name' => 'nextEmailingStep3',
				'icon' => 'process-icon-next'
			),
			'buttons' => array(
				array (
					'type' => 'submit',
					'title' => $this->module->l('Save only', 'adminmarketingestep3'),
					'name' => 'saveEmailingStep3',
					'class' => 'pull-right',
					'icon' => 'process-icon-save'
				),
				array (
					'href' => 'index.php?controller=AdminMarketingEStep2&campaign_id='.
					$this->campaign_id.'&token='.
					Tools::getAdminTokenLite('AdminMarketingEStep2'),
					'title' => $this->module->l('Back', 'adminmarketingestep3'),
					'icon' => 'process-icon-back'
				)
			)
		);

		$this->fields_value['campaign_id'] = $this->campaign_id;
		$this->fields_value['campaign_html'] = $this->html_content;

		return parent::renderForm();
	}

	/**
	 * Import an html file into the current campaign and save to DB
	 * @param Array $file The array containing infos about the file to upload
	 * @return boolean true if success, false otherwise
	 */
	private function importFile(Array $file)
	{
		$error = $file['error'];
		$type = $file['type'];

		if (($error === 0) && ($type == 'text/html'))
		{
			$file_path = $file['tmp_name'];
			$content_html = Tools::file_get_contents($file_path);

			if (!empty($content_html))
			{
				$this->html_content = $content_html;
				$this->saveHTML();
				return true;
			}
		}

		return false;
	}

	/**
	 * Import an html file from an url into the current campaign and save to DB
	 * @param string $url The URL to import
	 * @return boolean true if success, false otherwise
	 */
	private function importURL($url)
	{
		if ((strpos($url, '://') === false))
			$url = 'http://'.$url;

		$html_content = Tools::file_get_contents($url);
		$html_content = mb_convert_encoding($html_content, 'UTF-8', 'ASCII');
		$html_content = htmlspecialchars_decode(htmlentities($html_content, ENT_SUBSTITUTE, 'UTF-8', false));

		if (!empty($html_content))
		{
			$html_content = $this->copyImagesAndUpdateHTML($html_content, $url);
			$html_content = $this->updateRelativeLinks($html_content, $url);
			$this->html_content = $html_content;
			$this->saveHTML();
			return true;
		}
		else
			return false;
	}

	/**
	 * Get the html content of the current campaign from database
	 * If HTML is empty, we use views/templates/admin/marketinge_step3/marketinge_template.tpl
	 * @return string The HTML content of the current campaign or marketinge_template.tpl
	 */
	private function getHTMLContentDB()
	{
		$sql = new DbQuery();
		$sql->select('campaign_html');
		$sql->from('expressmailing_email');
		$sql->where('campaign_id = '.$this->campaign_id);
		$result = Db::getInstance()->getRow($sql);

		if (empty($result['campaign_html']))
		{
			$domain_name = Tools::getShopDomain(false, true);
			if (empty($domain_name))
				$domain_name = Tools::getHttpHost(false, true, true);
			if ($pos = strpos($domain_name, ':'))
				$domain_name = Tools::substr($domain_name, 0, $pos);

			$this->context->smarty->assign('base_url', Tools::getShopDomainSsl(true, true));
			$this->context->smarty->assign('domain_name', $domain_name);
			$this->context->smarty->assign('logo_name', Configuration::get('PS_LOGO'));
			$this->context->smarty->assign('logo_width', Configuration::get('SHOP_LOGO_WIDTH'));
			$this->context->smarty->assign('logo_height', Configuration::get('SHOP_LOGO_HEIGHT'));

			$template = $this->getTemplatePath().'marketinge_step3/marketinge_template.tpl';
			Tools::clearCache();
			$this->html_content = $this->context->smarty->fetch($template);
			return true;
		}
		else
		{
			$this->html_content = $result['campaign_html'];
			return true;
		}
	}

	/**
	 * Parse the $html_content and return an array containing images that need to be uploaded
	 * @param string $html_content
	 * @return Array
	 */
	private function parseImagesToUpload($html_content)
	{
		$images = $this->getImgTags($html_content);
		$images_src = $images[2];

		$images_to_upload = array();
		foreach ($images_src as $image_src)
		{
			$parsed_url = parse_url($image_src);
			if (isset($parsed_url['scheme']) === false)
				$images_to_upload[] = $image_src;
		}

		return array_unique($images_to_upload);
	}

	/**
	 * Parse $html_content and updates all relative links to absolute links
	 * @param string $html_content
	 * @param string $imported_url
	 * @return string
	 */
	private function updateRelativeLinks($html_content, $imported_url)
	{
		$links = $this->getLinkTags($html_content);
		$hrefs = $links[2];
		foreach ($hrefs as $key => $href)
		{
			$absolute_link = $this->getAbsoluteFromRelativeURL($href, $imported_url);
			$html_content = str_replace($links[0][$key], $links[1][$key].$absolute_link.$links[3][$key], $html_content);
		}

		return $html_content;
	}

	/**
	 * Get all link tags from $html_content
	 * @param string $html_content
	 * @return Array
	 */
	private function getLinkTags($html_content)
	{
		$links = null;
		preg_match_all('/(<[^>]*href=[\'\"])((?<=href=\")[^\"]*|(?<=href=\')[^\']*)([\'\"][^>]*>)/i', $html_content, $links);
		return $links;
	}

	/**
	 * Get all image tags from $html_content
	 * @param string $html_content
	 * @return Array
	 */
	private function getImgTags($html_content)
	{
		$images = null;
		preg_match_all('/(<img[^>]*src=[\'\"])((?<=src=\")[^\"]*|(?<=src=\')[^\']*)([\'\"][^>]*>)/i', $html_content, $images);
		return $images;
	}

	/**
	 * Copy images included in html_content to local campaign storage and update html with new links
	 * @param string $html_content
	 * @param string $imported_url
	 * @param Array $files
	 * @return string
	 */
	private function copyImagesAndUpdateHTML($html_content, $imported_url = null, Array $files = null)
	{
		$images = $this->getImgTags($html_content);

		$complete_tags = $images[0];
		$before_src = $images[1];
		$srcs = $images[2];
		$after_src = $images[3];

		foreach ($srcs as $key => $src)
		{
			$filename = null;
			if (is_array($files))
			{
				foreach ($files as $file)
				{
					if (basename($src) == $file['name'])
					{
						$filename = $file['name'];
						$image_url = $file['tmp_name'];
						break;
					}
				}
				if (!isset($filename) || !isset($image_url))
					continue;
			}
			else
			{
				$image_url = '';
				if (strpos($src, '://') > -1)
					$image_url = $src;
				elseif ($imported_url)
					$image_url = $this->getAbsoluteFromRelativeURL($src, $imported_url);
				elseif (strpos($src, '/') === 0)
				{
					$image_url = _PS_BASE_URL_.__PS_BASE_URI__;
					if (Tools::substr($image_url, -1) == '/')
						$image_url .= Tools::substr($src, 1);
					else
						$image_url .= $src;
				}
			}

			if (!empty($image_url) && $this->copyFileToStorage($image_url, $filename))
			{
				$final_img_url = _PS_BASE_URL_.__PS_BASE_URI__.'expressmailing/'.$this->campaign_id.'/';
				if ($filename)
					$final_img_url .= $filename;
				else
					$final_img_url .= basename($image_url);
				$final_tag = $before_src[$key].$final_img_url.$after_src[$key];
				$html_content = str_replace($complete_tags[$key], $final_tag, $html_content);
			}
		}

		return $html_content;
	}

	/**
	 * Transform a relative link to an absolute link
	 * @param string $relative The relative link
	 * @param string $base_url The base url of the link
	 * @return string The absolute link
	 */
	private function getAbsoluteFromRelativeURL($relative, $base_url)
	{
		if (strpos($relative, '//') === 0)
			return 'http:'.$relative;

		if (parse_url($relative, PHP_URL_SCHEME) != '')
			return $relative;

		if ($relative[0] == '#' || $relative[0] == '?')
			return $base_url.$relative;

		$parsed_url = parse_url($base_url);
		$scheme = $parsed_url['scheme'];
		$host = $parsed_url['host'];
		$path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
		$path = preg_replace('#/[^/]*$#', '', $path);

		if ($relative[0] == '/')
			$path = '';

		$absolute = $host.$path.'/'.$relative;

		$re = array('#(/.?/)#', '#/(?!..)[^/]+/../#');
		$n = 1;
		while ($n > 0)
			$absolute = preg_replace($re, '/', $absolute, -1, $n);

		return $scheme.'://'.$absolute;
	}

	/**
	 * Copy a remote or local file to the local campaign storage
	 * @param string $url
	 * @param string $filename The name of the resulting file
	 * @return mixed Null if fail or a string containing the path of the resulting image.
	 */
	private function copyFileToStorage($url, $filename = null)
	{
		$dest = $this->module->getPreviewFolder();
		$dest .= $this->campaign_id.DIRECTORY_SEPARATOR;
		if (!Tools::file_exists_no_cache($dest))
			mkdir($dest, 0777, true);
		if ($filename)
			$dest .= $filename;
		else
			$dest .= basename($url);
		if (Tools::copy($url, $dest))
			return $dest;
		else
			return null;
	}

	private function cleanHTML()
	{
		$html_cleaner = new HtmlCleaner();
		$this->html_content = $html_cleaner->cleanHTML($this->html_content);
	}

	/**
	 * Saves the html_content into DB
	 */
	private function saveHTML()
	{
		$this->cleanHTML();

		Db::getInstance()->update('expressmailing_email', array(
			'campaign_html' => pSQL($this->html_content, true)
			), 'campaign_id = '.pSQL($this->campaign_id)
		);
	}

}
