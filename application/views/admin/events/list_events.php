<script type="text/javascript">
$(document).ready(function() {
    $('tr').click(function() {
        var href = $(this).find("a").attr("href");
        if(href) {
            window.location = href;
        }
    });
});
</script>
<div class="grid_9">
<div class="box">
<?
echo heading("Please select an event",2);
if(isset($warning))
    echo $warning;
    
echo $events;
?>
</div>
</div>
