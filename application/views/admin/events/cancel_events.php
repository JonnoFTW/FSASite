<div class="grid_9">
<div class="box">
<?
echo heading("Please select an event to cancel",2);
if(isset($msg))
    echo $msg;
echo "</br>Cancelling an event will send an email to those persons who are currently entered";
echo $events;
?>
</div>
</div>
