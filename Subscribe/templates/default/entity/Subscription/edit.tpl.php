<?php


?>
<form action="/subscription/edit" method="post">

    <div class="row">

        <div class="span10 offset1">
            <p>Enter the URL of your friend's profile</p>

            <p>
                <label>
                    Profile Url
                <input type="url" name="subscription" value="<?=$vars['subscription'];?>" />
                </label>
            </p>
            <p>
                <?= \Idno\Core\site()->actions()->signForm('/subscription/edit') ?>
                <input type="submit" class="btn btn-primary" value="Save"/>
                <input type="button" class="btn" value="Cancel" onclick="hideContentCreateForm();"/>
            </p>
        </div>

    </div>
</form>