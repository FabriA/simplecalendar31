<?php
// use GCore\Extensions\Chronoforums\Helpers\Elements;

/**
 *    com_simplecalendar - a simple calendar component for Joomla
 *  Copyright (C) 2008-2016 Fabrizio Albonico
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
 * displays the information panel for SimpleCalendar
 *
 * @package     com_simplecalendar
 * @subpackage  settings
 * @since       3.0
 */
class JFormFieldInfo extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since   3.0
	 */
	protected $type = 'info';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string    The field input markup.
	 * @since   3.0
	 */
	protected function getInput()
	{
		if (!defined('DS'))
		{
			define('DS', DIRECTORY_SEPARATOR);
		}
		require_once JPATH_SITE . DS . 'components' . DS . 'com_simplecalendar' . DS . 'helpers' . DS . 'output.class.php';

		$html = array();

		$html[] = '<h2>' . SCOutput::getComponentName() . ' ' . SCOutput::getComponentVersion() .
			'&nbsp;<small>' . SCOutput::getComponentDate() . '</small></h2>';
		if (SCOutput::isDevelopment())
		{
			$html[] = '<h3>Development version - Please do not use in a productive environment!</h3>';
			$html[] = '<p></p>';
		}
		$html[] = '<p><a href="http://software.albonico.ch/" target="_blank">http://software.albonico.ch/</a></p>';
		$html[] = '<p><a href="http://www.facebook.com/Simplecalendar" target="_blank">' .
			JHTML::_('image', 'components/com_simplecalendar/assets/images/like_fb.png', JText::_('COM_SIMPLECALENDAR_PLEASE_LIKE_US'), array('title' => JText::_('COM_SIMPLECALENDAR_PLEASE_LIKE_US')))
			. '</a>';
		$uri    = JFactory::getUri();
		preg_match('/http(s)*:\/\/(.*?)\//i', $uri->root(), $matches);
		$domainWithoutPort = explode(':', $matches[2]);
		if (!SCOutput::isValidated())
		{
			echo SCOutput::validateInstallText();
			$html[] = '<p>Validation key <a href="http://software.albonico.ch/shop/product/view/1/2.html">available here</a>.' .
				'</p>';
		}
		else
		{
			$html[] = '<p>This installation is valid - thank you!</p>';
		}

		// Check schema version
//		$db = JFactory::getDbo();
//
//		$query = $db->getQuery(true);
//		$query
//			->select($db->quoteName(array('s.version_id', 's.extension_id')))
//			->from($db->quoteName('#__schemas', 's'))
//			->join('INNER', $db->quoteName('#__extensions', 'e') . ' ON (' . $db->quoteName('e.extension_id') . ' = ' . $db->quoteName('s.extension_id') . ')')
//			->where($db->quoteName('e.element') . ' = ' . $db->quote('com_simplecalendar'));
//
//		// Reset the query using our newly populated query object.
//		$db->setQuery($query, 0 , 1); // LIMIT1
//
//		// Load the results as a list of stdClass objects (see later for more options on retrieving data).
//		$resultEx = $db->loadAssoc();
//
//		$html[] = '<h4>Debug information</h4>';
//		$html[] = '<ul>';
//		$html[] = '<li>Schema value: ' . $resultEx['version_id'] . '</li>';
//		$html[] = '<li>ExtensionID: ' . $resultEx['extension_id'] . '</li>';
//
//		$update = JTable::getInstance('update');
//		$extension = JTable::getInstance('extension');
//		$uid = $update
//			->find(
//				array(
//					'element' => 'com_simplecalendar',
//					'type' => 'component',
//					'client_id' => '1',
//					'folder' => ''
//				)
//			);
//
//		$eid = $extension
//			->find(
//				array(
//					'element' => 'com_simplecalendar',
//					'type' => 'component',
//					'client_id' => '1',
//					'folder' => ''
//				)
//			);
//		$update->load($uid);
//
//		$html[] = '<li>Uid: ' . $uid . '</li>';
//		$html[] = '<li>Eid: ' . $eid . '</li>';
//		$html[] = '<li>' . SCOutput::getComponentVersion() . ' < ' . $update->version . '</li>';
//
//		//$html[] =  var_dump(JUpdater::getInstance()->findUpdates($resultEx['extension_id'], 0, 1));
//		$html[] = '</ul>';

		return implode($html);
	}
}