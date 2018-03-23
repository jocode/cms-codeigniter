<h2>Login</h2>

<!-- <?= validation_errors('<span>','</span><br>'); ?> -->

<?= form_open('', ['id'=>'form_login', 'style'=>'width:300px; margin:0 auto;'], ['login'=>1]); ?>
<?= form_fieldset('Datos de usuario'); ?>
<div>
	<?= form_label($this->lang->line('cms_general_label_user'), 'user'); ?>
	<?= form_input(['id'=>'user', 'name'=>'user'], set_value('user')); ?>
	<?= form_error('user'); ?>
</div>
<div>
	<?= form_label($this->lang->line('cms_general_label_password'), 'user'); ?>
	<?= form_password(['id'=>'password', 'name'=>'password']); ?>
	<?= form_error('password'); ?>
</div>

<?= form_submit(['value'=>$this->lang->line('cms_general_label_button_access')]); ?>
<?= form_fieldset_close(); ?>
<?= form_close(); ?>