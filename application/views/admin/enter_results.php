
<div class="grid_9">
<div class="box">
<script type="text/javascript">
// Some ajaxxy business
$(document).ready( function() {
    $("form").submit( function(event) {
        event.preventDefault();
        $.ajax({
            url : "admin/results/add_event_result",
            data: {'data':$(this).serialize()},
            success : function(data) {
                $("#results_error").html(data).hide.fadeIn(1000);
            }
        });
    });
});
</script>
<?
echo heading("Results for: ".$name,2);
?>
<div id="results" class="block">
<?
echo form_open();
echo form_fieldset("Results Entry");
echo form_hidden(array("name"=>"event_id","value"=>$event_id));

echo $entrants;

echo form_submit("submit","Submit");
echo form_fieldset_close();
echo form_close();
?> 
<div id="results_error"></div>
<? echo anchor('admin/events/entry/'.$event_id,'Add more entrants to this event'); ?>
</div>
</div>
</div>
