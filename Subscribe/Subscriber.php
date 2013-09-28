<?php

namespace IdnoPlugins\Subscribe {

    class Subscriber extends \Idno\Common\Entity {
        
        
        
        function saveDataFromInput() {
            
            // Create and update subscription object
            $this->subscriber = $this->getInput('subscriber');
            $this->subscription = $this->getInput('subscribe');
            
            // Now fetch MF2 of the subscriber url
            $content = \Idno\Core\Webmention::getPageContent($this->subscriber);
            $this->subscriber_mf2 = \Idno\Core\Webmention::parseContent($content['content']);
            
            return $this->save();

        }
        
        
        /**
         * Inform people who are subscribed to the currently logged in user that an update has taken place
         * @param type $permalink
         * @param string method CREATE_UPDATE, or DELETE
         */
        function notify($permalink, $method = 'CREATE_UPDATE' ) {


        }
        
        
    }

}