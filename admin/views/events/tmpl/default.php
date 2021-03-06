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

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', 'com_simplecalendar.category');
$archived	= $this->state->get('filter.state') == 2 ? true : false;
$trashed	= $this->state->get('filter.state') == -2 ? true : false;
$params		= (isset($this->state->params)) ? $this->state->params : new JObject;

// $saveOrder	= $listOrder == 'ordering';
// if ($saveOrder)
// {
	// $saveOrderingUrl = 'index.php?option=com_simplecalendar&task=simplecalendar.saveOrderAjax&tmpl=component';
	// JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
// }
$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>')
		{
			dirn = 'asc';
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>
<?php echo SCOutput::validateInstallText(); ?>
<form action="<?php echo JRoute::_('index.php?option=com_simplecalendar&view=events'); ?>" method="post" name="adminForm" id="adminForm">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
			<?php
			// Search tools bar
			echo  JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
			?>
			<table class="table table-striped" id="articleList">
			<thead>
				<tr>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					</th>
					<th width="10%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
					</th>
					<th width="30%">
						<?php echo JHtml::_('searchtools.sort', 'COM_SIMPLECALENDAR_HEADING_EVENT_NAME', 'a.name', $listDirn, $listOrder); ?>
					</th>
					<th>
						<?php echo JHtml::_('searchtools.sort', 'COM_SIMPLECALENDAR_HEADING_DATE', 'a.start_dt', $listDirn, $listOrder); ?>
					</th>
					<th class="nowrap">
						<?php echo JHtml::_('searchtools.sort', 'COM_SIMPLECALENDAR_HEADING_EVENT_VENUE', 'a.venue', $listDirn, $listOrder); ?>
					</th>
					<th class="nowrap hidden-phone">
						<?php echo JHtml::_('searchtools.sort', 'COM_SIMPLECALENDAR_HEADING_CATEGORY', 'category_title', $listDirn, $listOrder); ?>
					</th>
					<th width="1%" class="nowrap center hidden-phone">
						<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="8" style="text-align: center;">
						<?php echo $this->pagination->getListFooter(); ?>
						<small><?php echo SCOutput::showFooter(); ?></small>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php foreach ($this->items as $i => $item) :
				$ordering  = ($listOrder == 'ordering');
				$item->cat_link = JRoute::_('index.php?option=com_categories&extension=com_simplecalendar&task=edit&type=other&cid[]='. $item->catid);
				$canCreate  = $user->authorise('core.create',     'com_simplecalendar.category.' . $item->catid);
				$canEdit    = $user->authorise('core.edit',       'com_simplecalendar.category.' . $item->catid);
				$canChange  = $user->authorise('core.edit.state', 'com_simplecalendar.category.' . $item->catid) /* && $canCheckin */;
			?>
				<tr class="row<?php echo $i % 2; ?>" sortable-group-id="<?php echo $item->catid?>">
					<td class="center hidden-phone">
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					</td>
					<td class="center hidden-phone">
						<?php echo JHtml::_('jgrid.published', $item->state, $i, 'events.', $canChange/* , 'cb', $item->publish_up, $item->publish_down*/);  ?>
						<?php echo JHtml::_('simplecalendaradministrator.featured', $item->featured, $i, $canChange); ?> 
					</td>
					<td class="nowrap has-context">
						<div class="pull-left">
							<?php //if ($item->checked_out) : ?>
								<?php // echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'banners.', $canCheckin); ?>
							<?php //endif; ?>
							<?php if ($canEdit) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_simplecalendar&task=event.edit&id='.(int) $item->id); ?>">
									<?php echo $this->escape($item->name); ?></a>
							<?php else : ?>
								<?php echo $this->escape($item->name); ?>
							<?php endif; ?>
							<div class="small">
								<?php echo $this->escape($item->category_title); ?>
							</div>
						</div>
						<div class="pull-left">
							<?php
								// Create dropdown items
								JHtml::_('dropdown.edit', $item->id, 'event.');
								JHtml::_('dropdown.divider');
								if ($item->state) :
									JHtml::_('dropdown.unpublish', 'cb' . $i, 'events.');
								else :
									JHtml::_('dropdown.publish', 'cb' . $i, 'events.');
								endif;

								JHtml::_('dropdown.divider');

								if ($archived) :
									JHtml::_('dropdown.unarchive', 'cb' . $i, 'events.');
								else :
									JHtml::_('dropdown.archive', 'cb' . $i, 'events.');
								endif;

								if ($trashed) :
									JHtml::_('dropdown.untrash', 'cb' . $i, 'events.');
								else :
									JHtml::_('dropdown.trash', 'cb' . $i, 'events.');
								endif;
								
								JHtml::_('dropdown.divider');
								
								if ( $item->featured ) :
									JHtml::_('dropdown.unfeatured', 'cb' . $i, 'events.');
								else :
									JHtml::_('dropdown.featured', 'cb' . $i, 'events.');
								endif;
								
								// render dropdown list
								echo JHtml::_('dropdown.render');
								?>
						</div>
					</td>
					<td>
						<?php echo JHtml::_('date', $item->start_dt, $params->get('backend_list_date_format')); ?>
					</td>
					<td>
						<?php echo $item->venue; ?>
					</td>
					<td class="hidden-phone">
						<?php echo $this->escape($item->category_title); ?>
					</td>
					<td class="hidden-phone">
						<?php echo $item->id; ?><br />
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php //Load the batch processing form. ?>
		<?php //echo $this->loadTemplate('batch'); ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<!--<input type="hidden" name="filter_order" value="<?php /*echo $listOrder; */?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php /*echo $listDirn; */?>" />-->
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
