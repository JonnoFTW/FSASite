<div class="grid_9">
<div class="box">
<style type="text/css">
.hovered {
    background-color:#CCC;
    cursor: hand;
    cursor: pointer;
}
</style>
<script type="text/javascript">
$(document).ready(function() {
    $('#users tr').click(function() {
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
<noscript>Please enable javascript to enable the search feature</noscript>
<h2>Search Users</h2>
<div class="block">
Insert a search form here, to filter out users
</div>
<div class="block">
<?
echo "<h2>Current users are</h2>"; 
echo $user_table;
?>
</div>
</div>
</div>
