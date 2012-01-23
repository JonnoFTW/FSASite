<?php  

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>

<div class="grid_9">
<div class="box">
<?
echo heading('Add a news item',2);
echo "<div id=\"forms\" class=\"block\">";
echo form_open('admin/news/add');
echo form_fieldset('Add news');
if(isset($err)){
    echo "Be sure to include both a title and a message";
}
echo "Any URLs you place inside the message will be made into clickable links on the news page";
echo "<p>";
echo form_label('Title','title');
echo form_input('title');
echo "</p>";
echo "<p>";
echo form_label('Message','message');
echo form_textarea(array("name"=>'Message',"cols"=>78));
echo "</p>";
echo form_submit('news','Submit');
echo form_fieldset_close();
echo form_close();

?>

</div>
</div>
</div>