<div class="grid_4 prefix_4">
<div class="box">
<h2>
<? echo $title ?>
</h2>
	<div id="login-forms" class="block">
		<?
        if($this->session->userdata('logged')){
            echo form_open('login/logout');
            echo form_fieldset("Logout",array('class'=>'login'));
            echo "<p>";
            echo form_submit(array('value'=>'Logout','name'=>'logout','class'=>"confirm button"));
            echo "</p>";
        }
        else{
            echo form_fieldset("Login",array('class'=>'login'));
            echo form_open('login');
            echo "<p>";
            echo form_label("Email","user");
            echo form_input("user");
            echo "</p>";
            echo "<p>";
            echo form_label('Password','pass'); 
            echo form_password('pass');
            echo "</p>";
            echo form_submit(array('value'=>'Login','name'=>'login','class'=>"confirm button"));
            if($err){
                echo "<p color='red'>Incorrect password/username</p>";
            }
            else if($logged_out){
                echo "<p>You have been logged out</p>";
            }
        }
        echo form_fieldset_close();
		echo form_close();
        ?>
	</div>
    <? echo anchor('login/request_reset','Reset Password');?>
</div>
</div>
