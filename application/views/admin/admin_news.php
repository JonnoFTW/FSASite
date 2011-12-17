<?php  

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>

<div class="grid_9">
<div class="box">
<?
echo heading('Add a news item',2);
echo "<div id=\"forms\" class=\"block\">";
echo form_open('admin/add_news');
echo form_fieldset('Add news');
if(isset($err)){
    echo "Be sure to include both a title and a message";
}
echo "<p>";
echo form_label('Title','title');
echo form_input('title');
echo "</p>";
echo "<p>";
echo form_label('Message','message');
echo form_textarea('Message');
echo "</p>";
echo form_submit('news','Submit');
echo form_fieldset_close();
echo form_close();

?>

</div>
</div>
</div>