<div class="grid_9">
<div class="box">
    <?
        echo heading("Rule Listing",2);
        echo "Click on the rule name to edit it<br/>";
        if(isset($warning)){
            echo $warning;
        }
        foreach($rules as $v){
            echo anchor('admin/rules/'.$v['id'],heading($v['title'],3));
            echo $v['brief'];
        }
    ?>
</div>
</div>