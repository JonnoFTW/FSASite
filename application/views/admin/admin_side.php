<div class="grid_3">
<div class="box">
<?
    echo heading('Manage',2);
    echo "<div id=\"list-items\" class=\"block\">";
    echo heading(anchor('admin/user','Users'),5);
    $items = array(
        anchor('admin/user/filter/all','All'),
        anchor('admin/user/filter/licensed','Licensed'),
        anchor('admin/user/filter/unlicensed','Unlicensed'),
        anchor('admin/user/filter/clubs','Clubs')
    );
    if($this->session->userdata('level') == 'executive')
        $items[] = anchor('admin/licenses','Manage licenses');
    echo ul($items,array('class'=>'menu'));
    $items = array();
    if($this->session->userdata('level') == 'executive') {
        echo heading('Pages',5);
        $pages = array(
            anchor('admin/page/home','Home'),
            anchor('admin/page/news','News'),
            anchor('admin/page/forms','Forms and Resources'),
            anchor('admin/rules/','Rules')
        );
        echo ul($pages,array('class'=>'menu'));
        $items[] = anchor('admin/result_entry','Results Entry');
        $items[] = anchor('admin/page/calendar','Add events');
        $items[] = anchor('calendar','Update events');
    }
    
    $items[] = anchor('admin/comp_entry','Competition Entry');
    echo heading("Events",5);
    echo ul($items,array('class'=>'menu'));
    
    echo heading("Your Account",5);
    $items = array(anchor('admin/pass_reset',"Reset Password"));
    echo ul($items,array('class'=>'menu'));
    echo "</div>";
     ?>
</div>
</div>