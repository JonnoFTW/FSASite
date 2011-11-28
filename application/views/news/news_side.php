<div class="grid_3">
	<div class="box">
			<h2>
				<a href="#" id="toggle-tables">Search</a>
			</h2>
            <div class="block">
View news from year:
<?
echo form_dropdown('Years',$years[0]);
?>
</div>
<div class="block" id="login-forms">
<?
    $attrs = array('class'=>'block','id'=>'forms');
    echo form_open('news/search',$attrs);
?>
<fieldset class="login">
<legend>Search</legend>
<?
    foreach(array('Author','Description') as $i){
        echo "<p>";
        echo form_label($i);
        echo form_input($i);
        echo "</p>";
    }
    ?>
    <p>
    <label>Date from:</label>
    <script>
    $(function() {
            $( "#from" ).datepicker();
    });
    </script>
    <input id="from" name="from" type="text">
    </p>
    <p>
    <label>Date to:</label>
    <script>
    $(function() {
        $( "#to" ).datepicker();
    });
    </script>
    <input id="to" name="to" type="text">
    <p>
                <?
    echo form_submit(array('class'=>'button','name'=>'search'),'Search');
    echo "</p>";
    echo form_close();
?>
</fieldset>
</div>

<a href="rss"><? echo img("assets/images/rss.png") ?></a>
</div>
</div>