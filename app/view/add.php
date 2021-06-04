<?php defined('ABSPATH') || exit; ?>

<div class="wrap">
	<h2>
		<?php echo $page_title ?>
		<a href="<?php echo remove_query_arg(array('action', 'id', 'orderby', 'order')) ?>" class="page-title-action"><?php echo __('List data', 'lance') ?></a>
	</h2>
	<form method="post" action="<?php echo $form_actiion ?>" class="xyz-form-create">
		<table class="form-table">
			<tr>
				<th scope="row"><?php echo $Validate -> getLabel('field_one') ?></th>
				<td>
					<input name="field_one" type="text" class="f-text" value="<?php echo esc_attr($Validate -> getData('field_one')) ?>">
				</td>
			</tr>
			<tr>
				<th scope="row"><?php echo $Validate -> getLabel('field_two') ?></th>
				<td>
					<textarea name="field_two" class="f-textarea"><?php echo esc_attr($Validate -> getData('field_two')) ?></textarea>
				</td>
			</tr>
		</table>
		<?php submit_button(); ?>
	</form>
</div>