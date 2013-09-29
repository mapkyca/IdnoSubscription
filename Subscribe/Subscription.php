<?php

namespace IdnoPlugins\Subscribe {

    class Subscription extends \Idno\Common\Entity {
        
        
        
        function saveDataFromInput() {
            
            // Create and update subscription object
            $this->subscriber = $this->getInput('subscriber');
            $this->subscription = $this->getInput('subscribe');
            
            // For reference, store the domain part so we can quickly see if it's a recognised domain before performing a MF2 parse
            $this->subscription_domain = parse_url($this->subscription, PHP_URL_HOST);
            
            // Now fetch MF2 of the subscription url
            $content = \Idno\Core\Webservice::get($this->subscription);
            $this->subscription_mf2 = \Idno\Core\Webmention::parseContent($content['content']);
            
            // Get the endpoint
             // Get subscriber endpoint
            if (preg_match('/<link href="([^"]+)" rel="http://mapkyc.me/1dM84ud" ?\/?>/i', $content, $match)) {
                $this->subscription_endpoint = $match[1];
            } else
                throw new SubscriptionException('No subscription endpoint found.');
            
            return $this->save();

        }
        
        
        // TODO: Method to notify creation and deletion
        
    }

}