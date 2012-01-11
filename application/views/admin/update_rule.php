<div class="grid_9">
<div class="box">
<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>
						
<script type="text/javascript">
// Ajax in the form
$(document).ready(function() {
    $("form").submit( function(event) {
        event.preventDefault();
        console.log("form submitted");
        var rule = $(this).serializeArray();
        jQuery.ajax({
            url: "<? echo site_url("admin/update_rule"); ?>",
            type: "POST",
            data: {'rule':rule},
            success: function(response) {
                //do something
                if(response == "success") {
                    $('#rule_update').html("Rule successfully updated").hide().fadeIn(1000);
                } else {
                    $('#rule_update_error').html(response);
                }
            }
            });
        });
}); 
</script>
<?
echo heading("Updating Rule",2);
?>
<div id="rule_update" class="block">
<?
$rule = $rules[$rule];
echo "<form action=\"javascript:alert('Success!')\">";
echo form_fieldset("Updating Rule");
echo form_hidden('id',$rule['id']);
echo "<p>";
echo form_label("Rule Title");
echo form_input("title",$rule['title']);

echo form_label("Brief description");
echo form_input("brief",$rule['brief']);

echo form_label("Full description");
echo form_textarea("description",$rule['description']);
echo "</p>";
echo form_submit("submit","Submit");
?>
<div id="rule_update_error"></div>
<?
echo form_fieldset_close();
echo form_close();
?>
</div>

</div>
</div>