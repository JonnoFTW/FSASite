<div class="grid_5">
<div class="box">
<?
if($user == null){
    echo "No such user exists!";
} else{
    echo "<h2>User: {$user['first_name']} {$user['last_name']}</h2>"; 
    echo "<div id='login-forms' class='block'>";
    echo form_open('admin/update_user/'.$user['uid']);
    echo form_fieldset("User Control",array('class'=>'login'));
    $fields = array(
        "First"=>"f_name"
    );
    foreach($user as $k=>$v){
        echo "<p>";
        echo form_label($k,$k);
        echo form_input($k,$v);
        echo "</p>";
    }
    echo form_submit(array('class'=>'button','name'=>'save','value'=>'Save'));

    echo form_close();
    echo form_fieldset_close();
    echo "</div>";
}
//remove user?
?>
</table>
</div>
</div>