<div class="grid_9">
<div class="box">
<script src="http://js.nicedit.com/nicEdit-latest.js" type="text/javascript"></script>
<script type="text/javascript">bkLib.onDomLoaded(nicEditors.allTextAreas);</script>
						
<script type="text/javascript">
// Ajax in the form
$(document).ready(function() {
    $("form").submit( function(event) {
        console.log("form submitted");
        nicEditors.findEditor('description').saveContent();
        event.preventDefault();
        jQuery.ajax({
            url: "<? echo site_url("admin/page/update"); ?>",
            type: "POST",
            data: {'data': $("textarea#description").val()},
            success: function(response) {
                //do something
                if(response ==  "Success") {
                    $('#rule_update').html("Page successfully updated").hide().fadeIn(1000);
                } else {
                    $('#rule_update_error').html(response);
                }
            }
            });
        });
}); 
</script>
<?
echo heading("Updating Home",2);
?>
<div id="rule_update" class="block">
<?

echo "<form>";
echo form_fieldset("Updating Home Page Message");
echo "<p>";
echo form_label("Full Message",'description');
echo form_textarea(array("name"=>"message","value"=>$page['message'],"cols"=>78,"id"=>"description")); // should be 78 cols wide
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