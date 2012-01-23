<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>

<div class='grid_9'>
<div class='box'>
<script type="text/javascript">
$(document).ready(function() {
    var form = $('div.event').html();
    form = "<div class=\"event\">"+form+"</div>";
  //  console.log(form);
    $('input.date:text').live('focusin', function() {
        var $this = $(this);
        if(!$this.is('.hasDatepicker')) {
            $this.datepicker({dateFormat: 'yy-mm-dd'});
        }
    });
    $("#event_add").click( function() {
        console.log("Loading new event form");
        $(form).insertAfter('.event:last');
    });
    $("form").submit( function() {
        var events = [];
        $("div.event").each(function(index) {
            var buf = {};
            $.each($(this).find(':input').serializeArray(), function(i,value) {
                buf[value["name"]] = value["value"];
            });
            events.push(buf);
        });
      //  console.log(events);
        jQuery.ajax({
            url: "<? echo site_url("admin/events/add_events"); ?>",
            type: "POST",
            data: {"events":JSON.stringify(events)},
           // dataType: "json",
            success: function(data) {
                console.log(data);
                if(data.split(" ")[0] != "Success"){
                    // We have an error, display the message
                    console.log("Error");
                    $("#event_error").html("<p>An error: "+data+"</p>").hide().fadeIn(500);
                } else {
                    // Success, tell the user this
                    console.log("Success");
                    $('#event_input').html(data).hide().fadeIn(1500);
                }
            } 
        });
        return false;
    });
});
</script>
<?
echo heading("Add events",2);
echo "<div id=\"event_input\" class='block'>";
echo "<p>Only the Title field is optional. It will default to {Category} {Gender} {Weapon}. All other fields are mandatory.</p>";
//echo form_open('admin/add_events');
echo "<form action=\"javascript:alert('success')\">";
echo form_fieldset("Add events");

$loc = form_dropdown('type',array(
    'L'=>'Local',
  //  'E'=>'Events',
    'N'=>'National',
    'R'=>'Robyn Chaplin',
),'l');
$gender = form_dropdown('gender',$GENDERS,'m');
$weapon = form_dropdown('weapon',$WEAPONS,'f');
$categories = form_dropdown('category',$CATEGORIES,'o');

echo "<div class='event'>";
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
echo form_input("name");
echo form_label("Date (YYYY-MM-DD)");
echo form_input(array("name"=>"date","class"=>"date"));
echo form_label("Time (24 hrs HH:MM)");
echo form_input("time");
echo "</p>";
echo "<hr/>";
echo "</div>";
echo "<a id=\"event_add\">Add another event</a></br>";
echo form_submit(array("name"=>"submit","value"=>"Submit","class"=>"submit"));
echo "<div id=\"event_error\"></div>";
echo form_fieldset_close();
echo form_close();

?>
</div>
</div>

<?
/*
<div class="block">
echo heading("Manage existing events",2);

// load the current events
some more stuff down here
</div>
*/
?> 