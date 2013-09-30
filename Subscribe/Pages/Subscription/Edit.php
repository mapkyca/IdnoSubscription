<?php

namespace IdnoPlugins\Subscribe\Pages\Subscription {

    class Edit extends \Idno\Common\Page {

        function getContent() {
            $this->gatekeeper();    // This functionality is for logged-in users only

            // Are we loading an entity?
            if (!empty($this->arguments)) {
                $object = \IdnoPlugins\Subscribe\Subscription::getByID($this->arguments[0]);
            } else {
                $object = new \IdnoPlugins\Subscribe\Subscription();
            }

            $t = \Idno\Core\site()->template();
            $body = $t->__(array(
                'object' => $object,
                'url' => $this->getInput('url'),
            ))->draw('entity/Subscription/edit');

                $title = 'Your friends';

            if (!empty($this->xhr)) {
                echo $body;
            } else {
                $t->__(array('body' => $body, 'title' => $title))->drawPage();
            }
        }

        function postContent() {
            $this->gatekeeper();
            
            try {
                
                $this->setInput('subscriber', \Idno\Core\site()->session()->currentUser()->getUrl());
            
                $subscription = new \IdnoPlugins\Subscribe\Subscription();
                if (!$subscription->saveDataFromInput())
                    throw new \IdnoPlugins\Subscribe\SubscriptionException("Sorry, your new subscription could not be saved.");
                
            
            } catch (\IdnoPlugins\Subscribe\SubscriptionException $e) {
                \Idno\Core\site()->session()->addMessage($e->getMessage());
                $this->forward();
            }
            
            $this->forward('/subscriptions/');
        }
        

    }

}
