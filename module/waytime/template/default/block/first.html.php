<?php
    
?>

<h1>{phrase var='waytime.what_is_the_w_time_capsule'}</h1>

<p>{phrase var='waytime.all_we_want_to_write_here'}</p>

<div class="js_box_close" style="display: block;">
    <span class="box_controll_left">
        <a type="button" class="button" onclick="js_box_remove(this);$Core.waytime.goNext(1);">{phrase var='waytime.start'}</a>
    </span>
    <span class="box_controll_right">
        <a type="button" class="button" onclick="return $Core.waytime.close(this);">{phrase var='waytime.close_remember'}</a>
    </span>
</div>