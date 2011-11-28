<div class="grid_5">
<div class="box">
<?
//var_dump($user);
echo "<h2>User: {$user['first_name']} {$user['last_name']}</h2>"; 
echo "<div id='login-forms' class='block'>";
//edit details: password, phone, isContact
echo form_open('admin/update_user/'.$user['uid']);
echo form_fieldset("User Control",array('class'=>'login'));
echo "<p>";
echo form_label('Phone','phone');
echo form_input('phone',$user['phone']);
echo "</p>";
echo "<p>";
echo form_label('Email','email');
echo form_input('email',$user['email']);
echo "</p>";
echo "<p>";
echo form_label('Position','position');
echo form_input('position',$user['position']);
echo "</p>";
echo "<p>";
echo form_label('On Contact list','contact');
echo form_checkbox('contact','contact',$user['contact']);
echo "</p>";
echo form_submit(array('class'=>'button','name'=>'save','value'=>'Save'));

echo form_close();
//remove user?
?>
</table>
</div>
</div>
</div>