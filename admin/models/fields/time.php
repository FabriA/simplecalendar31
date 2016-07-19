<?php
/**
 *    com_simplecalendar - a simple calendar component for Joomla
 *  Copyright (C) 2008-2013 Fabrizio Albonico
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
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
 * Supports a date and time combo box
 *
 * @package     com_simplecalendar
 * @subpackage  settings
 * @since       3.0
 */
class JFormFieldTime extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since   3.0
	 */
	protected $type = 'time';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string    The field input markup.
	 * @since   3.0
	 */
	protected function getInput()
	{
		JHtml::_('behavior.calendar');
		JHtml::_('behavior.tooltip');

		//$document = JFactory::getDocument();
		$html   = array();
		$params = JComponentHelper::getParams('com_simplecalendar');

		$ampm = 'am';
		$isAM = true;

		// Prepare the hours drop-down. Read 12/24-hour setting from main params
		$timeOptions = array();
		$minuteSplit = (int) $params->get('minute_split', '4') == '0' ? 4 : (int) $params->get('minute_split', 4);

		for ($i = 0; $i < 24; $i++)
		{
			if ($params->get('use_24h', '1') == '0')
			{
				if ($i > 12)
				{
					$i    = $i - 12;
					$isAM = false;
				}
			}

			$hourValue = (string) $i;
			if (strlen($i) < 2)
			{
				$hourValue = '0' . $i;
			}

			for ($j = 0; $j < $minuteSplit; $j++)
			{
				$minuteValue = (string) ((60 / $minuteSplit) * $j);

				if (strlen($minuteValue) < 2)
				{
					$value = $hourValue . ':' . '0' . $minuteValue;
				}
				else
				{
					$value = $hourValue . ':' . $minuteValue;
				}

				if ($params->get('use_24h', '1') == '0')
				{
					if ($isAM == true)
					{
						$value .= ' ' . JText::_('COM_SIMPLECALENDAR_SETTINGS_AM');
					}
					else
					{
						$value .= ' ' . JText::_('COM_SIMPLECALENDAR_SETTINGS_PM');
					}

				}

				$timeOptions[] = JHTML::_('select.option', $value, $value);
			}
		}

		array_unshift($timeOptions, JHTML::_('select.option', '', '--'));

		// If it's 12-hours format, prepare the AM/PM drop-down
		if ($params->get('use_24h') == '0')
		{
			$ampmOptions   = array();
			$ampmOptions[] = JHTML::_('select.option', 'am', JText::_('COM_SIMPLECALENDAR_SETTINGS_AM'));
			$ampmOptions[] = JHTML::_('select.option', 'pm', JText::_('COM_SIMPLECALENDAR_SETTINGS_PM'));
		}

		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';

		// Set initial value
		if ($this->value != null && $this->value != '50:00:00')
		{
			$hours   = date('H', strtotime($this->value));
			$minutes = date('i', strtotime($this->value));
		}
		else
		{
			$hours   = null;
			$minutes = null;
		}

		if ($hours != null)
		{
			if ($params->get('use_24h') == '0')
			{
				if ((int) $hours < 12)
				{
					$ampm = 'am';
				}
				else
				{
					$ampm = 'pm';
				}
				if ((int) $hours > 12)
				{
					$hours = (int) ((int) $hours - 12);
				}
			}
			if (strlen($hours) < 2)
			{
				$hours = '0' . $hours;
			}
		}

		$time = $hours != null || $minutes || null ? $hours . ':' . $minutes : null;

		// Output the select box
		$html[] = JHTML::_('select.genericlist', $timeOptions, $this->id, 'class="inputbox input-small"', 'value', 'text', $time);

		return implode($html);
	}
}