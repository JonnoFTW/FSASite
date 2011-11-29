<div class="grid_9">
<div class="box">
<style type="text/css">
.hovered {
    background-color:#CCC;
    cursor: hand;
    cursor: pointer;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    $('#users tr').click(function() {
        var href = $(this).find("a").attr("href");
        if(href) {
            window.location = href;
        }
    });
    $('tbody tr').hover(
        function() {
            $(this).find("td").addClass("hovered");
        },
        function() {
            $(this).find("td").removeClass("hovered");
        }
    );
});
</script>
<noscript>Please enable javascript to enable the search feature</noscript>
<?
echo "<h2>Current users are</h2>"; 
echo $user_table;
?>
</div>
<div class="grid_4">
<div class="box">
<h2>Add A New User</h2>
<div id='login-forms' class='block'>
<?
echo validation_errors();
echo form_open('admin/new_user');
echo form_fieldset('Details',array('class'=>'login'));
echo "<p>";
echo form_label('First Name','first_name');
echo form_input('first_name');
echo "</p>";
echo "<p>";
echo form_label('Last Name','last_name');
echo form_input('last_name');
echo "</p>";
echo "<p>";
echo form_label('Email','email');
echo form_input('email');
echo "</p>";
echo form_submit(array('class'=>'button','name'=>'submit','value'=>'Submit'));
//could possibly add in things to determine their position
//contactable,phone number
echo form_close();
?>
</div>
</div>
</div>
</div>