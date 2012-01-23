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
foreach($users as $v) {
    echo $v['first_name'];
}
echo form_submit('submit','Submit');
echo form_fieldset_close();
echo form_close();
?>

</div>
</div>
</div>
