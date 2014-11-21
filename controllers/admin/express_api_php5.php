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

class ExpressApi
{
	private $base_url = 'http://ep-0.axalone.com/Services/API/V1.0/ws.ashx';

	public function __construct($url = '')
	{
		if (!empty($url)) $this->base_url = $url;
	}

	public function call($module, $section, $method, $parameters, &$response_array, $session_id = null, &$error = null)
	{
		// Si parameters est vide on en crée un
		// ------------------------------------
		if (!is_array($parameters))
			$parameters = array();

		// Génération du xml à envoyer
		// ---------------------------
		$xml = $this->serialize($parameters);
		$xml = utf8_encode($xml);
		$length = mb_strlen($xml);
		$url = parse_url($this->base_url);

		// Contruction et envoi de la requête HTTP
		// ---------------------------------------
		$request =	'POST '.$url['path'].'?format=xml&method='.$module.'.'.$section.'.'.$method.
					(!empty($session_id) ? '&idsession='.$session_id : '').
					'&async=false HTTP/1.0'."\r\n";
		$request .= 'Host: '.$url['host']."\r\n";
		$request .= 'Connection: Close'."\r\n";
		$request .= 'Content-type: application/xml'."\r\n";
		$request .= 'Content-Length: '.$length."\r\n";
		$request .= "\r\n";
		$request .= $xml;

		$fp = fsockopen($url['host'], 80, $errno, $errstr, 10);

		if ($fp === false)
		{
			$error = -2;
			return false;
		}

		fwrite($fp, $request);

		$response = '';

		while (!feof($fp)) $response .= fgets($fp, 128);
		fclose($fp);

		$response = trim($response);

		// On transforme la reponse HTTP en tableau associatif
		// ---------------------------------------------------
		if ($this->parseResponse($response, $response_array))
		{
			if (isset($response_array['response']['result']))
			{
				$response_array = $response_array['response']['result'];
				return true;
			}
			elseif (isset($response_array['response']['error_code']))
			{
				$error = $response_array['response']['error_code'];
				return false;
			}
		}
		else
		{
			$error = -2;
			return false;
		}
	}

	private function parseResponse($response, &$return = null)
	{
		$match = array();
		$pos_crlf = strpos((string)$response, "\r\n\r\n");

		$headers = Tools::substr($response, 0, $pos_crlf);

		if (preg_match('/HTTP\/... ([0-9]*)/i', $headers, $match))
		{
			$http_code = $match[1];
			if ($http_code < 400 && $http_code >= 200)
			{
				$data = Tools::substr((string)$response, $pos_crlf + 4, false);
				$return = $this->deserialize($data);
				return true;
			}
		}

		return false;
	}

	private function deserialize($xml)
	{
		$response_array = array();
		$iter = new SimpleXmlIterator((string)$xml, null);
		$response_array[Tools::strtolower($iter->getName())] = $this->recursiveDeserialize($iter);
		return $response_array;
	}

	private function recursiveDeserialize(SimpleXmlIterator $xml_iterator)
	{
		$i = 0;
		$array = array();

		for ($xml_iterator->rewind(); $xml_iterator->valid(); $xml_iterator->next())
		{
			if ($xml_iterator->hasChildren())
			{
				if ($xml_iterator->current()->getName() == 'item')
					$array[$i++] = $this->recursiveDeserialize($xml_iterator->current());
				else
					$array[Tools::strtolower($xml_iterator->key())] = $this->recursiveDeserialize($xml_iterator->current());
			}
			else
			{
				if ($this->getAttributesValue('type', $xml_iterator->current()->attributes()) == 'binary')
					$array[Tools::strtolower($xml_iterator->key())] = mb_convert_encoding((string)$xml_iterator->current(), 'UTF-8', 'BASE64');
				else
					$array[Tools::strtolower($xml_iterator->key())] = (string)$xml_iterator->current();
			}
		}

		return $array;
	}

	private function getAttributesValue($research, SimpleXMLIterator $attributes)
	{
		foreach ($attributes as $key => $value)
			if ((string)$key == $research)
				return $value;

		return false;
	}

	private function serialize($parameters)
	{
		$document = new DOMDocument('1.0', 'utf-8');
		$root = $document->createElement('parametres');
		$root->setAttribute('primitive', 'complex');
		$root = $document->appendChild($root);
		$this->arrayOrValueSerialize($document, $root, $parameters);
		$xml = $document->saveXML();
		return $xml;
	}

	private function arrayOrValueSerialize(&$document, &$root, $parameters)
	{
		// Fonction récursive permettant de générer le xml quelque soit le type de données
		// Une donnée texte ou un array. Utilisée dans serialize()

		foreach ($parameters as $key => $value)
		{
			$value_type = gettype($value);

			if (is_numeric($key))
			{
				$element = $document->createElement('item');
				if (($value_type == 'array') || ($value_type == 'object'))
					$element->setAttribute('primitive', 'complex');
				else
					$element->setAttribute('primitive', gettype($value));
			}
			else
			{
				$element = $document->createElement($key);
				$element->setAttribute('primitive', gettype($value));
			}

			if (is_array($value))
				$this->arrayOrValueSerialize($document, $element, $value);
			else
			{
				if (is_null($value))
					$element->setAttribute('isnull', 'true');
				else
				{
					$value = mb_convert_encoding($value, 'HTML-ENTITIES', 'UTF-8');
					$content = $document->createTextNode($value);
					$element->appendChild($content);
				}
			}
			$root->appendChild($element);
		}
	}

	private $error_codes = array(
		1 => 'INF_AuthentificationRequise',
		2 => 'INF_FormatIncorrect',
		3 => 'INF_DonneesIncorectes',
		4 => 'INF_TypeNonParsable',
		5 => 'INF_AutentificationIncorrect',
		6 => 'INF_DroitsInsufisants',
		7 => 'INF_CompteInexistant',
		8 => 'INF_MarqueInexistante',
		9 => 'INF_SessionRequise',
		10 => 'INF_CreationCompteIncorrect_AttentePremiereConnection',
		11 => 'INF_CreationCompteIncorrect_CompteExistant',
		12 => 'INF_CreationCompteIncorrect',
		10001 => 'FAX_ImpossibleDeCrerCampagne',
		10002 => 'FAX_CampagneInexistante',
		10003 => 'FAX_ModificationDeCampagneImpossible',
		10004 => 'FAX_ImpossibleDeGenereLeTestDeLaCampagne',
		11000 => 'FAX_ImpossibleEcrireDocument',
		10012 => 'FAX_ParametreIncorrect',
		10015 => 'FAX_ActionImpossible',
		10016 => 'FAX_DocumentInvalide',
		10017 => 'FAX_TestInexistant',
		20001 => 'SMS_ImpossibleDeCrerCampagne',
		20002 => 'SMS_CampagneInexistante',
		20003 => 'SMS_ModificationDeCampagneImpossible',
		20012 => 'SMS_ParametreIncorrect',
		20015 => 'SMS_ActionImpossible',
		30001 => 'AUD_ImpossibleDeCrerCampagne',
		30002 => 'AUD_CampagneInexistante',
		30003 => 'AUD_ModificationDeCampagneImpossible',
		30004 => 'AUD_ImpossibleDeGenereLeTestDeLaCampagne',
		30010 => 'AUD_FichierNonTrouve',
		30011 => 'AUD_AjoutDuContactImpossible',
		30012 => 'AUD_ParametreIncorrect',
		30013 => 'AUD_LimiteTailleAtteinte',
		30014 => 'AUD_FormatRetourNonValide',
		30015 => 'AUD_ActionImpossible',
		40001 => 'F2M_ImpossibleDeTrouverLeFax',
		40002 => 'F2M_ErreurCreationImage',
		40003 => 'F2M_ErreurListeFax',
		40004 => 'F2M_LargeurHorsLimite',
		40005 => 'F2M_FormatNonReconnu',
		40006 => 'F2M_PageVide',
		40007 => 'F2M_ErreurRecuperationEmail',
		40008 => 'F2M_FichierNonTrouve',
		40009 => 'F2M_MessageNonTrouve',
		400010 => 'F2M_OperationInterdite',
		40015 => 'F2M_ActionImpossible',
		50001 => 'OUT_ConvertionImpossible',
		-2 => 'GEN_NonImplemente',
		-1 => 'GEN_Generique',
		0 => 'OK'
	);

	public function getError($code)
	{
		if (isset($this->error_codes[$code]))
			return $this->error_codes[$code];
		else
			return $this->error_codes[-1];
	}

}