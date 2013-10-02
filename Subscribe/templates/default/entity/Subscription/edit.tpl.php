<?php


?>
<form action="/subscription/edit" method="post">

    <div class="row">

        <div class="span10 offset1">
            <p>Enter the URL of your friend's profile</p>

            <p>
                <label>
                    Profile Url
                <input type="url" name="subscribe" value="<?=$vars['subscribe'];?>" />
                </label>
            </p>
            <p>
                <?= \Idno\Core\site()->actions()->signForm('/subscription/edit') ?>
                <input type="submit" class="btn btn-primary" value="Subscribe"/>
                <input type="button" class="btn" value="Cancel" onclick="hideContentCreateForm();"/>
                
            </p>
        </div>

    </div>
</form>

<div class="row">
    <div class="span10 offset1">
            <p><a href="/subscriptions/">View my subscriptions...</a></p>
    </div>
</div>

<div class="row">
    <div class="span10 offset1">
            <p><a href="/subscribers/">View my subscribers...</a></p>
    </div>
</div>