<?php
/**
 *	com_simplecalendar - a simple calendar component for Joomla
 *  Copyright (C) 2008-2016 Fabrizio Albonico
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

defined('JPATH_BASE') or die;

/**
 * Hits Field class for the Joomla Framework.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 * @since       1.6
 */
class JFormFieldCurrency extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   1.6
	 */
	protected $type = 'currency';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string	The field input markup.
	 * @since   1.6
	 */
	protected function getInput()
	{
		$params = JComponentHelper::getParams('com_simplecalendar');
		$currency = $center = $params->get('currency', '');

		$html  = '<div class="input-append">';
		$html .= '<input type="text" class="inputbox input-small form-control" value="' . $this->value . '" />';
		$html .= '<button id="btnCurrency" class="btn" type="button"><span>' . $currency . '</span></button>';
		$html .= '</div>';

		return $html;
	}
}
