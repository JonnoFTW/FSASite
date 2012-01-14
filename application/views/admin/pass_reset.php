<div class="grid_9">
<div class="box">

<? echo heading("Password reset",2);
?>
<div class="block" id="login-forms">
<?
echo form_open("admin/pass_reset");
echo form_fieldset("Password Reset",array('class'=>'login'));
echo "<p>";
echo form_label("New Password","pass");
echo form_password("pass");
echo "</p>";
echo "<p>";
echo form_label("Confirm New Password","pass_confirm");
echo form_password("pass_confirm");
echo "</p>";
echo form_hidden(array("name"=>"reset","value"=>"true"));
if(isset($warning))
    echo $warning;
echo form_submit(array("value"=>"Save","name"=>"save","class"=>"confirm button"));
echo form_fieldset_close();
echo form_close();
?>
</div>
</div>
</div>
