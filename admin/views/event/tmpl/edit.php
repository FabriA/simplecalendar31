<?php
/**
 *  com_simplecalendar - a simple calendar component for Joomla
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

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidator');
JHtml::_('formbehavior.chosen', 'select', null, array('disable_search_threshold' => 0));

// Add the necessary Javascript
$document = JFactory::getDocument();
$document->addScript(JUri::base() . 'components/com_simplecalendar/views/event/tmpl/edit.js');
?>
<script type="text/javascript">
	Joomla.submitbutton = function (task) {
		// Check for correct start and end dates
		var start_dt = document.getElementById('jform_start_dt');
		var end_dt = document.getElementById('jform_end_dt');

		var n = start_dt.value.indexOf('.');
		//alert(n);
		if (n == -1 || isNaN(n)) {
			var dateSplit1 = start_dt.value.split('-');
			var dateSplit2 = end_dt.value.split('-');
		} else {
			var dateSplit1 = start_dt.value.split('.');
			var dateSplit2 = end_dt.value.split('.');
		}
		if (dateSplit1[0].length == 4) {
			var d1 = new Date(dateSplit1[0], dateSplit1[1] - 1, dateSplit1[2]);
			var d2 = new Date(dateSplit2[0], dateSplit2[1] - 1, dateSplit2[2]);
		} else {
			var d1 = new Date(dateSplit1[2], dateSplit1[1] - 1, dateSplit1[0]);
			var d2 = new Date(dateSplit2[2], dateSplit2[1] - 1, dateSplit2[0]);
		}
		//alert(d1+" "+d2);
		if (task != 'event.cancel' && end_dt.value != '' && d1 > d2) {

			alert('<?php echo JText::_('COM_SIMPLECALENDAR_EVENT_WARNING_END_DATE_PRIOR_TO_START_DATE'); ?>');
			document.getElementById('jform_end_dt').setAttribute('class', 'input-small invalid');
			document.getElementById('jform_end_dt').focus();
			return false;
		}
		else {
			if (task == 'event.cancel' || document.formvalidator.isValid(document.id('simplecalendar-form'))) {
				<?php echo $this->form->getField('description')->save(); ?>
				Joomla.submitform(task, document.getElementById('simplecalendar-form'));
			}
		}
	}
</script>
<?php //DEBUG echo '<code>' . str_replace('<', '&lt;', $this->form->getInput('end_dt')) . '</code>'; ?>
<form action="<?php echo JRoute::_('index.php?option=com_simplecalendar&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="simplecalendar-form" class="form-validate">
	<div class="form-inline form-inline-header">
		<div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('name'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('name'); ?>
			</div>
		</div>
		<div class="control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('id'); ?>
			</div>
			<div class="controls">
				<?php echo $this->form->getInput('id'); ?>
			</div>
		</div>
	</div>

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('COM_SIMPLECALENDAR_EVENT_TAB_DETAILS', true)); ?>
		<div class="row-fluid">
			<div class="span9">
				<div class="row-fluid form-horizontal-desktop">
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('start_dt'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('start_dt') . '&nbsp;' .
								$this->form->getInput('start_time') . '&nbsp' .
								JText::_('COM_SIMPLECALENDAR_EVENT_DATE_UNTIL') . '&nbsp;' .
								$this->form->getInput('end_time') . '&nbsp;' .
								$this->form->getInput('end_dt');
							?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('reserve_dt'); ?>
						</div>
						<div class="controls">
							<span id="span_reserve_dt"><?php echo $this->form->getInput('reserve_dt'); ?></span>
						</div>
					</div>
				</div>
				<button type="button" id="btnShowRecurrence" class="btn" onclick="javascript:showRecurrence();">
					<span class="icon-loop"> </span>&nbsp;<?php echo JText::_('COM_SIMPLECALENDAR_EVENT_FIELD_RECURRING_EVENTS_BUTTON_DESC'); ?>
				</button>
				<div class="row-fluid form-horizontal" style="display:none;" id="divRecurrence">
					<div class="span6">
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('recur_every'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('recur_every'); ?>
							</div>
						</div>
					</div>
					<div class="span6">
						<div class="control-label">
							<?php echo $this->form->getLabel('recur_end_after'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('recur_end_after'); ?>
						</div>
					</div>
				</div>
				<hr />
				<div class="row-fluid form-horizontal">
					<div class="span6">
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('venue'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('venue'); ?>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('address'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('address'); ?>
							</div>
						</div>
					</div>
					<div class="span6">
						<div class="control-group">
							<div class="form-group">
								<?php echo $this->form->getInput('latlon'); ?>
							</div>
						</div>
					</div>
				</div>
				<hr />
				<div class="row-fluid form-horizontal">
					<div class="span6">
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('organizer_id'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('organizer_id'); ?><br />
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('contact_name'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('contact_name'); ?><br />
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('contact_telephone'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('contact_telephone'); ?><br />
							</div>
						</div>
					</div>
					<div class="span6">
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('contact_email'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('contact_email'); ?><br />
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">
								<?php echo $this->form->getLabel('contact_website'); ?>
							</div>
							<div class="controls">
								<?php echo $this->form->getInput('contact_website'); ?><br />
							</div>
						</div>

					</div>
				</div>
				<?php foreach ($this->form->getFieldset('image') as $field) : ?>
					<div id="image">
						<div class="control-group">
							<div class="control-label">
								<?php echo $field->label; ?>
							</div>
							<div class="controls">
								<?php echo $field->input; ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
				<hr />
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getLabel('description'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('description'); ?>
					</div>
				</div>
				<hr />
				<?php foreach ($this->form->getFieldset('extended') as $field) : ?>
					<div class="control-group">
						<div class="control-label">
							<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<!-- Begin Sidebar -->
			<div class="span3">
				<fieldset class="form-vertical">
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('state'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('state'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('catid'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('catid'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('alias'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('alias'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('statusid'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('statusid'); ?>
						</div>
					</div>
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('access'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('access'); ?>
						</div>
					</div>
					<?php echo $this->form->getControlGroups('metadata'); ?>
				</fieldset>
			</div>
			<!-- End Sidebar -->
		</div>

		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('COM_SIMPLECALENDAR_EVENT_TAB_PUBLISHING', true)); ?>
		<?php foreach ($this->form->getFieldset('publish') as $field) : ?>
			<div class="control-group">
				<div class="control-label">
					<?php echo $field->label; ?>
				</div>
				<div class="controls">
					<?php echo $field->input; ?>
				</div>
			</div>
		<?php endforeach; ?>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php if ($this->canDo->get('core.admin')) : ?>
			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'permissions', JText::_('COM_SIMPLECALENDAR_EVENT_TAB_PERMISSIONS', true)); ?>
			<fieldset>
				<?php echo $this->form->getInput('rules'); ?>
			</fieldset>
			<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php endif; ?>

		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
