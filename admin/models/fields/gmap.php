<?php
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
 * Supports the Google Maps location selector widget
 *
 * @package     com_simplecalendar
 * @subpackage  settings
 * @since       3.0
 */
class JFormFieldGmap extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since   3.0
	 */
	protected $type = 'gmap';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string    The field input markup.
	 * @since   3.0
	 */
	protected function getLabel()
	{
		$html   = array();
		$html[] = JText::_('COM_SIMPLECALENDAR_ORGANIZER_FIELD_ADDRESS_LABEL');

		return implode($html);
	}

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

		$document = JFactory::getDocument();
		$params   = JComponentHelper::getParams('com_simplecalendar');

// 		Initialize to a valid value
		if ($this->value == null || $this->value == '')
		{
			$center = $params->get('gmap_std_latlon', '46,9');
		}
		else
		{
			$center = $this->value;
		}

		// Set the autocomplete input field
		if (!isset($this->element['searchfield']) || $this->element['searchfield'] == '')
		{
			$input = "document.getElementById('map_search')";
		}
		else
		{
			$input = "document.getElementById('jform_" . $this->element['searchfield'] . "')";
		}

		// Add the necessary API key
		$apikey = $params->get('gmap_api_key', '') != '' ? $params->get('gmap_api_key') : 'AIzaSyBITX-gc9VZTAJHmfJaRgQKA3pHTOA5yWE';


		$css = "#scmap-canvas label { width: auto; display:inline; }
				#scmap-canvas img { max-height: none; max-width: none; }
				#scmap-canvas { width:auto; height:200px; display: block; }";
		$document->addStyleDeclaration($css);

		$html = array();
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$attr .= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';

		$html[] = '&nbsp;';

		$html[] = '<div id="maparea">';
		if (!isset($this->element['searchfield']) || $this->element['searchfield'] == '')
		{
			$html[] = '<input type="text" ' . $attr . ' name="map_search" id="map_search" value="" style="margin-bottom: 10px;" />';
		}
		$html[] = '<div id="scmap-canvas"></div>';
		$html[] = '</div>';

		$script = "
		var geocoder;
		var map;
		var marker;
		var updateaddressfield = 'jform_address';
		var autocomplete;
		var input;

		function initMap() {
			var mapCenter = new google.maps.LatLng(" . $center . ");
			var mapOptions = {
		            center: mapCenter,
					zoom: 10,
					scrollwheel: true,
					zoomControl: true,
			        streetViewControl: false,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
			map = new google.maps.Map(document.getElementById(\"scmap-canvas\"), mapOptions);
			geocoder = new google.maps.Geocoder();
			marker = new google.maps.Marker({
			    map: map,
			    draggable: true,
			    position: mapCenter
			});
			input = " . $input . ";
		    autocomplete = new google.maps.places.Autocomplete(input);
			autocomplete.bindTo('bounds', map);
			google.maps.event.addListener(autocomplete, 'place_changed', function() {
		          marker.setVisible(false);
		          input.className = '';
		          var place = autocomplete.getPlace();
		          if (!place.geometry) {
		            // Inform the user that the place was not found and return.
		            input.className = 'notfound';
		            return;
		          }

		          // If the place has a geometry, then present it on a map.
		          if (place.geometry.viewport) {
		            map.fitBounds(place.geometry.viewport);
		          } else {
		            map.setCenter(place.geometry.location);
		            map.setZoom(17);  // Why 17? Because it looks good.
		          }
		          marker.setPosition(place.geometry.location);
				  geocodePosition(place.geometry.location);
		          marker.setVisible(true);
		        });
			google.maps.event.addListener(marker, 'dragend', function(evt) {
				document.getElementById('" . $this->id . "').value = evt.latLng.lat().toFixed(5) + ',' + evt.latLng.lng().toFixed(5);
				geocodePosition(marker.getPosition());
			});
		}

		//google.maps.event.addDomListener(window, 'load', initMap);

		function codeAddress() {
		    var address = input.value;
		    geocoder.geocode( { 'address': address}, function(results, status) {
		          if (status == google.maps.GeocoderStatus.OK) {
		            map.setCenter(results[0].geometry.location);
		            marker.setPosition(results[0].geometry.location);
					document.getElementById('" . $this->id . "').value = results[0].geometry.location.lat().toFixed(5) + ',' + results[0].geometry.location.lng().toFixed(5);
				} else {
					alert('Geocode was not successful for the following reason: ' + status);
				}
			});
		}

		function geocodePosition(pos) {
			geocoder.geocode({
				latLng: pos
			}, function(responses) {
			if (responses && responses.length > 0) {
				document.getElementById(updateaddressfield).value = responses[0].formatted_address;
				document.getElementById('" . $this->id . "').value = responses[0].geometry.location.lat().toFixed(5) + ',' + responses[0].geometry.location.lng().toFixed(5);
			} else {
				document.getElementById(updateaddressfield).value = '*****';
				alert('Cannot determine address at this location.');
			}
			});
		}";

		$script .= "function removeMapData() {
				document.getElementById('jform_" . $this->element['searchfield'] . "').value='';
				document.getElementById('" . $this->id . "').value='';
			}";

		$html[] = "\r\n" . '<script type="text/javascript">' . $script . '</script>';
		$html[] = '<script src="https://maps.googleapis.com/maps/api/js?libraries=places&callback=initMap&key=' . $apikey . '" type="text/javascript"></script>';
		$html[] = '<input type="hidden" ' . $attr . ' name="' . $this->name . '" id="' . $this->id . '" value="' . $this->value . '" style="margin-bottom: 10px;" />';
		$html[] = '<input type="button" class="btn btn-small" onclick="removeMapData();" id="btn_removemapdata" value="[' . JText::_('COM_SIMPLECALENDAR_SETTINGS_REMOVE_MAP_DATA') . ']" />';

		if (!SCOutput::isValidated())
		{
			if (rand(0, 1))
			{
				echo SCOutput::validateInstallText();
			}
		}

		return implode($html);
	}
}