<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class='grid_9'>
<div class='box'>
<script type="text/javascript">
$(document).ready(function() {
    $('input.date').datepicker({dateFormat:'yy-mm-dd'});
    $("#event_add").click( function() {
        $('#event').clone().insertAfter('#event');
        $('input.date').removeClass('hasDatepicker').datepicker({dateFormat:'yy-mm-dd'});
    });
});
</script>
<?
echo heading("Add events",2);
echo "<div class='block'>";

echo form_open('admin/add_events');
echo form_fieldset("Add events");

$loc = form_dropdown('location',array(
    'l'=>'Local',
    'e'=>'Events',
    'n'=>'National',
    'r'=>'Robyn Chaplin',
),'l');
$gender = form_dropdown('gender',array(
    'm'=>'Mens',
    'w'=>'Womens',
    'o'=>'Mixed'
),'m');
$weapon = form_dropdown('weapon',array(
    'f'=>'Foil',
    'e'=>'Epee',
    's'=>'Sabre'
),'f');
$categories = form_dropdown('category',array(
    'u11'=>'U11',
    'u13'=>'U13',
    'u15'=>'U15',
    'u17'=>'U17',
    'u20'=>'U20',
    'n'=>'Novice',
    'i'=>'Intermediate',
    'o'=>'Open',
    'v'=>'Veteran'
),'o');

echo "<div id='event'>";
echo "<p>";
/*
$table = array(
    array("Location","Weapon","Gender","Category","Title","Date","Time"),
    array($loc,$weapon,$gender,$categories,form_input("title"),form_input("date"),form_input("date"))
);
echo $this->table->generate($table);
*/
echo form_label("Location","location");
echo $loc;
echo form_label("Weapon");
echo $weapon;
echo form_label("Gender");
echo $gender;
echo form_label("Category");
echo $categories;
echo form_label("Title");
echo form_input("Title");
echo form_label("Date (YYYY-MM-DD)");
echo form_input(array("name"=>"Date","class"=>"date"));
echo form_label("Time (HH:MM)");
echo form_input("Time");
echo "</p>";
echo "<hr/>";
echo "</div>";
echo "<a id=\"event_add\">Add another event</a></br>";
echo form_submit("Submit","Submit");
echo form_fieldset_close();
echo form_close();

?>
</div>

<?
echo heading("Manage existing events",2);

// load the current events
?> 
</div>
</div>
