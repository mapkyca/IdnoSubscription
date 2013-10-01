<?php

    if (!empty($vars['objects'])) {
        foreach($vars['objects'] as $entry) { 
            echo $this->__(['object' => $entry])->draw('entity/Subscriber');
        }
    }

?>