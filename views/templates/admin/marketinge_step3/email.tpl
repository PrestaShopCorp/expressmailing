{*
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
*}

<html>

<body class="margin: 0; padding: 0;">

<table style="background-color: #eeeeee; width: 100%;">
<tr>
	<td align="center" style="padding: 10px 50px">
	
		<table style="padding: 10px; background-color: #ffffff; width: 100%; min-width: 600px; max-width: 800px; font-family: Verdana, Helvetica, sans-serif; font-size: 11px;">
		<tr>
			<td>
				
				<table cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td align="left">
						<div id="header_logo">
							<a href="{$base_url}" title="{$shop_name|escape:'htmlall':'UTF-8'}"><img src="{$base_url}{$img_dir}{$logo_name}" alt="{$shop_name|escape:'htmlall':'UTF-8'}" {if $logo_width}width="{$logo_width}"{/if} {if $logo_height}height="{$logo_height}"{/if} /></a>
						</div>
					</td>
				</tr>
				<tr>
					<td align="left">&nbsp;</td>
				</tr>
				<tr>
					<td align="left"><span style="font-family: Verdana, Helvetica, sans-serif; font-size: 14pt;">Newsletter {$shop_name|escape:'htmlall':'UTF-8'}</span></td>
				</tr>
				<tr>
					<td align="left">&nbsp;</td>
				</tr>
				<tr>
					<td align="left">&nbsp;</td>
				</tr>
				<tr>
					<td align="left">&nbsp;</td>
				</tr>
				<tr>
					<td align="left">&nbsp;</td>
				</tr>
				<tr>
					<td align="left">&nbsp;</td>
				</tr>
				<tr>
					<td align="center"><font size="1">Vous recevez cet email car vous avez visit&eacute; ou command&eacute;<br>
					sur le site <a href="{$base_url}">{$domain_name|escape:'htmlall':'UTF-8'}</a>&nbsp; |&nbsp; <a href="##DESABONNEMENT##">Se d&eacute;sabonner</a>&nbsp; | <a href="##AMI##">Transf&eacute;rer &agrave; un ami</a></font></td>
				</tr>
				</table>
				
			</td>
		</tr>
		</table>
	
	</td>
</tr>
</table>

</body>

</html>