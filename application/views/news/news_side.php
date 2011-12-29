<div class="grid_3">
	<div class="box">
			<h2>
				<a href="#" id="toggle-tables">Search</a>
			</h2>
            <div class="block">
View news from year:
<script type="text/javascript">
$(document).ready(function() {
    $('option').click( function() {
        window.location = "<? echo base_url() ?>news/year/"+ $(this).text();
    });
});    
</script>
<? 
$y = array();
foreach($years as $i) {
    $y[$i['year']] = $i['year'];
}
echo form_dropdown('Years',array_values($y));

//var_dump($years);
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