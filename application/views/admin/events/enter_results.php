
<div class="grid_9">
<div class="box">
<script type="text/javascript">
// Some ajaxxy business
$(document).ready( function() {
    $("form").submit( function(event) {
        event.preventDefault();
        var results = {'event_id':<? echo $event_id ?>,'results':[]}
        $("input:text").each( function() {
            results['results'].push({
                'uid':$(this).attr("name"),
                'res':$(this).val()
            });
        });
        $.ajax({
            type: 'POST',
            url : "<? echo base_url(); ?>admin/results/add_event_result",
            data: {'data':results},
            success : function(data) {
                $("#results_error").html(data).hide().fadeIn(1000);
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
