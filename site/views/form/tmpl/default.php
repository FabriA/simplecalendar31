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

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select', null, array('disable_search_threshold' => 0));

// Create shortcut to parameters.
$params = $this->state->get('params');
?>

<script type="text/javascript">
	Joomla.submitbutton = function (task) {
		if (task == 'event.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			<?php echo $this->form->getField('description')->save(); ?>
			Joomla.submitform(task);
		}
	}
</script>
<div class="edit item-page<?php echo $this->pageclass_sfx; ?>">
	<?php if ($params->get('show_page_heading', 1)) : ?>
		<div class="page-header">
			<h1>
				<?php echo $this->escape($params->get('page_heading')); ?>
			</h1>
		</div>
	<?php endif; ?>
	<form action="<?php echo JRoute::_('index.php?option=com_simplecalendar&view=form&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate form-vertical">
		<div class="btn-toolbar">
			<div class="btn-group">
				<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('event.save')">
					<span class="icon-ok"></span>&#160;<?php echo JText::_('JSAVE') ?>
				</button>
			</div>
			<div class="btn-group">
				<button type="button" class="btn" onclick="Joomla.submitbutton('event.cancel')">
					<span class="icon-cancel"></span>&#160;<?php echo JText::_('JCANCEL') ?>
				</button>
			</div>
		</div>
		<div class="form-inline form-inline-header">
			<div class="control-group">
				<div class="control-label">
					<?php echo $this->form->getLabel('name'); ?>
				</div>
				<div class="controls">
					<?php echo $this->form->getInput('name'); ?>
				</div>
			</div>
		</div>

		<div class="form-horizontal">
			<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('COM_SIMPLECALENDAR_EVENT_TAB_DETAILS', true)); ?>
			<div class="row-fluid">
				<div class="span12">
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
									$this->form->getInput('end_dt') ;
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

				<!-- End Sidebar -->
			</div>

			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('COM_SIMPLECALENDAR_EVENT_TAB_PUBLISHING', true)); ?>
			<div class="row-fluid">
				<div class="span6">
					<div class="control-group">
						<div class="control-label">
							<?php echo $this->form->getLabel('id'); ?>
						</div>
						<div class="controls">
							<?php echo $this->form->getInput('id'); ?>
						</div>
					</div>
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
				</div>
				<div class="span6">
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
				<?php echo JHtml::_('bootstrap.endTab'); ?>
			</div>

			<input type="hidden" name="return" value="<?php echo $this->return_page; ?>" />
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
	<div class="sc-footer">
		<?php echo SCOutput::showFooter(); ?>
	</div>
</div>
