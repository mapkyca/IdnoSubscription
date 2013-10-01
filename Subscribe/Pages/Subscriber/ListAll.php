<?php

namespace IdnoPlugins\Subscribe\Pages\Subscriber {

    class ListAll extends \Idno\Common\Page {

        function getContent() {
            $this->gatekeeper();    // This functionality is for logged-in users only

            $t = \Idno\Core\site()->template();
            $body = $t->__(array(
                'objects' => \Idno\Core\site()->db()->getObjects('IdnoPlugins\Subscribe\Subscriber', ['subscription' => \Idno\Core\site()->session()->currentUser()->getUrl()])
            ))->draw('entity/Subscriber/listall');

                $title = 'Your subscribers';

            if (!empty($this->xhr)) {
                echo $body;
            } else {
                $t->__(array('body' => $body, 'title' => $title))->drawPage();
            }
        }
        

    }

}
