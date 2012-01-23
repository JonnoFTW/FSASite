<div class="grid_9">
<div class="box">
<style type="text/css">
.hovered {
    background-color:#CCC !important;
    cursor: hand;
    cursor: pointer;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    $('#tables tr').click(function() {
        var href = $(this).find("a").attr("href");
        if(href) {
            window.location = href;
        }
    });
    $('tbody tr').hover(
        function() {
            $(this).find("td").addClass("hovered");
        },
        function() {
            $(this).find("td").removeClass("hovered");
        }
    );
});
</script>
<?
echo "<h2>{$type}</h2>";

foreach($tables as $v) {
echo '<div id="tables" class="block events">';
    echo $v;
echo '</div>';
}

?>
</div>
</div>

