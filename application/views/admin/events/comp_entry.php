<script type="text/javascript">$(document).ready( function () {
    $("form").submit( function (event) {
        event.preventDefault();
        var entrants = {'event_id':<? echo $event_id; ?>,'fencers':[]};
        $("form table tr:not(:first)").each( function () {
            var fencer = {}; 
            fencer['name'] = $(this).text();
            fencer['uid'] = $(this).find("td > input:hidden").val();
            fencer['entered'] = Boolean($(this).find(":input:checked").first().val());
            entrants['fencers'].push(fencer);
        });
        console.log(entrants);
        $.ajax( {
            url: '<?  echo base_url().'admin/events/add_entrants';?>',
            type: "POST",
            data: {'entrants': JSON.stringify(entrants)},
            success : function (data) {
                $("#entry_report").html(data).hide().fadeIn(1000);
            }
        });
    });
});
</script> 
<div class="grid_9">
<div class="box">
<?
echo heading("Adding competitors for ".$event['name'].' '.$event['date'],2);
?>
<div class="block">
Note, if fencers are hidden and you have ticked the box, they will still be entered
<?
// form to show elligible users for a comp
echo form_open();
echo form_fieldset('Add Fencers');
echo $fencers;
echo form_submit('submit','Submit');
echo form_fieldset_close();
echo form_close();
?>

</div>
<div id="entry_report"></div>
</div>
</div>
