<?php

namespace IdnoPlugins\Subscribe\Pages\Subscription {

    class ListAll extends \Idno\Common\Page {

        function getContent() {
            
            $this->gatekeeper();    // This functionality is for logged-in users only

            $t = \Idno\Core\site()->template();
            $body = $t->__(array(
                'objects' => \Idno\Core\site()->db()->getObjects('IdnoPlugins\Subscribe\Subscription', ['owner' => \Idno\Core\site()->session()->currentUserUUID()])
            ))->draw('entity/Subscription/listall');

                $title = 'Your friends';

            if (!empty($this->xhr)) {
                echo $body;
            } else {
                $t->__(array('body' => $body, 'title' => $title))->drawPage();
            }
        }
        

    }

}
