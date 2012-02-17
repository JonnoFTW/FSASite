<?php  

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>
<div class="grid_5">
<div class="box">
<?
if($user == null){
    echo heading("An error occurred!",2);
    echo "No such user exists or you do not have permission to edit that user's details!";
} else{
    if(!isset($new_user)){
        $new_user = false;
        $club_form = "<div id=\'club_form\'>".form_label('Short Name','short_name').form_input('short_name',$user['club']). form_label('Description','description').form_textarea(array('name'=>'description','value'=>$user['club_description'],'cols'=>39))."</div>"; 
    }
    else {
        $club_form = "<div id=\'club_form\'>".form_label('Short Name','short_name').form_input('short_name'). form_label('Description','description').form_textarea(array('name'=>'description','cols'=>39))."</div>";
    }
    ?>
<script type="text/javascript">
$(document).ready(function() {
    var form = '<? echo $club_form; ?>';
    $("select#types").change( function () {
        var v = $(this).val();
        if(v == 'club'){
            // Add new form elements
            $(form).appendTo($("p#exec"));
        } else {
            $("#club_form").remove();
        }
    });
    if($("select#types").val() == 'club')  $(form).appendTo($("p#exec"));
    $("form").submit( function() {
        jQuery.ajax({
            url: "<? if($new_user) echo site_url("admin/user/new_user"); else echo site_url("admin/user/update_user");?>",
            type: "POST",
            data: $(this).serialize() ,
            success: function(data) { 
                console.log(data);
                $("#input_error").html(data).hide().fadeIn(500);
            } 
        });
        return false;
    });
    $("#datepicker").datepicker({dateFormat: 'dd/mm/yy'});
});
</script>
    <?
    if($new_user) {
        $user = array();
        $user['isClub'] = false;
        $user['type'] = 2;
        $user['first_name'] = $user['club']  = $user['dob'] = 
        $user['last_name'] = $user['email'] = $user['phone'] = $user['address_1'] = $user['address_2'] = 
        $user['state'] = $user['post_code'] = $user['suburb'] =  $user['level'] = "";
        echo heading("New User",2);
    } else{
        echo heading(" {$user['first_name']} {$user['last_name']}",2);
    }
    echo "<div id='forms' class='block'>";
    echo "<form>";
  /*  if($new_user) 
        echo form_open('admin/new_user/');
    else
        echo form_open('admin/update_user/'.$user['uid']);
        */

    echo form_fieldset("Edit User");
    $fields = array(
        "First"=>"f_name"
    );
    $attrs = array(
        'First Name'=>array('value'=>$user['first_name'],'name'=>'first_name'),
        'Last Name'=> array('value'=>$user['last_name'],'name'=>'last_name'),
        'Email'=>     array('value'=>$user['email'],'name'=>'email','type'=>'email'),
        'Phone'=>     array('value'=>$user['phone'],'name'=>'phone','type'=>'tel'),
        'Address 1'=> array('value'=>$user['address_1'],'name'=>'addr1'),
        'Address 2'=> array('value'=>$user['address_2'],'name'=>'addr2'),
        'Suburb'=>    array('value'=>$user['suburb'],'name'=>'suburb'),
        'Post Code'=> array('value'=>$user['post_code'],'name'=>'post_code','type'=>'number'), 
        'State'=>     array('value'=>$user['state'],'name'=>'state'),
        'DOB (dd/mm/yyyy)'=>       array('value'=>$user['dob'],'name'=>'dob','type'=>'date','id'=>'datepicker')
    );
    if($user['isClub']) {
        unset($attrs['DOB (dd/mm/yyyy)']);
        echo "<p id='exec'>";
        echo $club_form;
        echo form_hidden("club",$user['uid']);
        echo form_hidden("type","club");
        echo "</p>";
    }
    if(!$new_user) {
        echo form_hidden('uid',$user['uid']);
    }
    echo "<p>";
    foreach($attrs as $k=>$v){
        echo form_label($k,$v['name']);
        echo form_input($v);
    } 
    if(!$user['isClub']) {
    echo form_label('Club','club');
    echo form_dropdown('club',$clubs,$user['club']);
    }
    echo "</p>";
    if($this->session->userdata('level') != 'club' && !$user['isClub']) {
      //  var_dump($user);
        
        echo "<p id='exec'>";
        echo form_label('Type','type');
        $types = array('executive'=>'Executive','other'=>'Other');
        if($user['level'] == null) {
            if($user['isClub']){
                $user['level'] = 'club';
            }
            else{
                $types['club'] = 'Club';
                $user['level'] = 'other';
            }
        }               
                        // change this in the db to be 
                        //  default, check if a user can have 
                        // more than one type. More types in fututure?
                        // probably not, and can just be another field
                        // in the users table.
        if(!$user['isClub']) {                                                    
            echo form_dropdown('type',$types,$user['level'],'id="types"');
            if($user['level'] == 'executive'){
                echo form_label('Note','note');
                echo form_input('note',$user['note']);
            }
        }
        echo "</p>";
    }
    echo "<p>";
    echo form_submit(array('class'=>'button','name'=>'save','value'=>'Save'));
    echo "</p>";

    echo form_fieldset_close();
    echo form_close();
    echo "</div>";
  ?>
        <div id="input_error"></div>
    <?
}
?>
</div>
</div>