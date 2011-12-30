<div class="grid_5">
<div class="box">
<?
if($user == null){
    echo heading("An error occurred!",2);
    echo "No such user exists or you do not have permission to edit that user's details!";
} else{
    if(!isset($new_user)){
        $new_user = false;
    }
    if($new_user) {
        $user = array();
        $user['type'] = 2;
        $user['first_name'] = $user['club'] = 
        $user['last_name'] = $user['email'] = $user['phone'] = $user['address_1'] = $user['address_2'] = 
        $user['state'] = $user['post_code'] = $user['suburb'] =  $user['level'] = "";
        echo heading("New User",2);
    } else{
        echo heading(" {$user['first_name']} {$user['last_name']}",2);
    }
    echo "<div id='forms' class='block'>";
    if($new_user) 
        echo form_open('admin/new_user/');
    else
        echo form_open('admin/update_user/'.$user['uid']);
    echo form_fieldset("Edit User");
    $fields = array(
        "First"=>"f_name"
    );
    $attrs = array(
        'Last Name'=> array('value'=>$user['last_name'],'name'=>'last_name'),
        'First Name'=>array('value'=>$user['first_name'],'name'=>'first_name'),
        'Email'=>     array('value'=>$user['email'],'name'=>'email','type'=>'email'),
        'Phone'=>     array('value'=>$user['phone'],'name'=>'phone','type'=>'number'),
        'Address 1'=> array('value'=>$user['address_1'],'name'=>'addr1'),
        'Address 2'=> array('value'=>$user['address_2'],'name'=>'addr2'),
        'Suburb'=>    array('value'=>$user['suburb'],'name'=>'suburb'),
        'Post Code'=> array('value'=>$user['post_code'],'name'=>'post_code','type'=>'number'), 
        'State'=>     array('value'=>$user['state'],'name'=>'state'),
    );

    echo "<p>";
    foreach($attrs as $k=>$v){
        echo form_label($k,$v['name']);
        echo form_input($v);
    } 
    echo form_label('Club','club');
    echo form_dropdown('club',$clubs,$user['club']);
    echo "</p>";
 
    if($this->session->userdata('level') != 'club') {
      //  var_dump($user);
        echo "<p>";
        echo form_label('Type','type');
        $types = array('club'=>'Club',
                       'executive'=>'Executive',
                       'other'=>'Other');
        if($user['level'] == null) $user['level'] = 'other'; // change this in the db to be 
                                                            //  default, check if a user can have 
                                                            // more than one type. More types in fututure?
                                                            // probably not, and can just be another field
                                                            // in the users table.
                                                            
        echo form_dropdown('type',$types,$user['level']);
        if($user['level'] == 'executive'){
            echo form_label('Note','note');
            echo form_input('note');
        }
        echo "</p>";
    }
    // Should be something about saving passwords in here too
    echo "<p>";
    echo form_submit(array('class'=>'button','name'=>'save','value'=>'Save'));
    echo "</p>";

    echo form_fieldset_close();
    echo form_close();
    echo "</div>";
}
//remove user?
?>
</table>
</div>
</div>