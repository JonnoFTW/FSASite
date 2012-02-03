<div class="grid_4 prefix_4">
<div class="box">
<h2>
<? echo $title ?>
</h2>
	<div id="login-forms" class="block">
		<?
        if($this->session->userdata('logged')){
            echo "Please use the password reset form from the admin panel";
        }
        else{
            echo form_fieldset("Password Reset",array('class'=>'login'));
            echo form_open('login/request_reset');
            echo "<p>";
            echo form_label("Email","user");
            echo form_input("user");
            echo "</p>";
            echo form_submit(array('value'=>'Submit','name'=>'submit','class'=>"confirm button"));
        }
        echo form_fieldset_close();
		echo form_close();
        ?>
	</div>
</div>
</div>
