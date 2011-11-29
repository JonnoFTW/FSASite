<div class="grid_4">
<div class="box">
<h2>Fencing SA Executive Members</h2>
<div class="block" id="tables">
		<table>
			<colgroup>
				<col class="colA" />
				<col class="colB" />
				<col class="colC" />
			</colgroup>
			<thead>
				<tr>
					<th>Position</th>
					<th>Email</th>
					<th>Name</th>
				</tr>
			</thead>
			<tbody>
<? 

foreach($mails as $i){
	echo "<tr class=\"odd\"><th>{$i['note']}</th><td>".safe_mailto($i['email'],$i['email'])."</td><td>{$i['first_name']} {$i['last_name']}</td>";
}
 ?>			
			</tbody>
		</table>

 
 </div>
 </div>
 </div>
 <div class="grid_8">
 <div class="box">
	<h2>
		<a href="#" id="toggle-login-forms">Send us an email enquiry</a>

	</h2>
	<div class="block" id="login-forms">
    <?
echo validation_errors();
if($mailed){
echo "Your message was successfully sent!";
}else{
echo form_open('contact');
echo form_fieldset('Email',array('class'=>'login'));
echo "<p>";
echo form_label('Name','name');
echo form_input('name');
echo "</p>";
echo "<p>";
echo form_label('Email','email');
echo form_input('email');
echo "</p>";
echo "<p>";
echo form_label('Message','message');
echo form_textarea('message');
echo "</p>";
echo form_submit(array('class'=>'button','name'=>'submit','value'=>'Send'));
echo form_close();
}
?>
	</div>
</div>
</div>


<div class="grid_12">
<div class="box">
 <h2>Websight</h2>
<div class="block">
Please contact <? echo safe_mailto('webmaster@fencingsa.org.au');?> with any details regarding the site. Please report all bugs and error messages to the above address.
</div>
</div>
</div>

