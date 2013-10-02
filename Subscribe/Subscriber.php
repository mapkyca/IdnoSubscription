<?php

namespace IdnoPlugins\Subscribe {

    class Subscriber extends \Idno\Common\Entity {
        
        
        
        function saveDataFromInput() {
            
            // Create and update subscription object
            $this->subscriber = \Idno\Core\site()->currentPage()->getInput('subscriber');
            $this->subscription = \Idno\Core\site()->currentPage()->getInput('subscribe');
            
            // Check duplicates
            if ($result = \Idno\Core\site()->db()->getObjects('IdnoPlugins\Subscribe\Subscriber', ['subscriber' =>  $this->subscriber, 'subscription' => $this->subscription]))
                    throw new SubscriptionException("{$this->subscriber} already subscribed to updates from {$this->subscription}");

            if ($content = \Idno\Core\Webservice::get($this->subscriber)) {
                    
                // Now fetch MF2 of the subscriber url
                
                $this->subscriber_mf2 = \Idno\Core\Webmention::parseContent($content['content']);

                // Get subscriber endpoint
                /*if (preg_match('/<link href="([^"]+)" rel="http://mapkyc.me/1dM84ud" ?\/?>/i', $content['content'], $match)) {*/
                if (preg_match('~<link href="([^"]+)" rel="http://mapkyc.me/1dM84ud" ?\/?>~', $content['content'], $match)) {
                    $this->subscriber_endpoint = $match[1];
                } else
                    throw new SubscriptionException('No subscriber endpoint found.');
            }
            else
                throw new SubscriptionException("Page {$this->subscription} could not be reached.");
            
            return $this->save();

        }
        
        
        /**
         * Inform people who are subscribed to the currently logged in user that an update has taken place
         * @param type $permalink
         * @param string method CREATE_UPDATE, or DELETE
         */
        function notify($permalink, $method = 'CREATE_UPDATE' ) {

            switch ($method)
            {
                case 'DELETE': // TODO
                    break;
                case 'CREATE_UPDATE' :
                default:
                    // POST update
                    $endpoint = $this->subscriber_endpoint;  
                    \Idno\Core\Webservice::post($endpoint, [
                        'subscription' => $permalink
                    ]);
            }
            
            
        }
        
        
    }

}