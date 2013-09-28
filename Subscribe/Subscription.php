<?php

namespace IdnoPlugins\Subscribe {

    class Subscription extends \Idno\Common\Entity {
        
        
        
        function saveDataFromInput() {
            
            // Create and update subscription object
            $this->subscriber = $this->getInput('subscriber');
            $this->subscription = $this->getInput('subscribe');
            
            // Now fetch MF2 of the subscription url
            $content = \Idno\Core\Webmention::getPageContent($this->subscription);
            $this->subscription_mf2 = \Idno\Core\Webmention::parseContent($content['content']);
            
            return $this->save();

        }
        
        
        
        
    }

}