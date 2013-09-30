<?php

namespace IdnoPlugins\Subscribe {

    class Subscription extends \Idno\Common\Entity {
        
        function getActivityStreamsObjectType() {
            return 'false';
        }
        
        function saveDataFromInput() {
            
            // Create and update subscription object
            $this->subscriber = \Idno\Core\site()->currentPage()->getInput('subscriber');
            $this->subscription = \Idno\Core\site()->currentPage()->getInput('subscribe');
            
            // For reference, store the domain part so we can quickly see if it's a recognised domain before performing a MF2 parse
            $this->subscription_domain = parse_url($this->subscription, PHP_URL_HOST);
            
            // Now fetch MF2 of the subscription url
            $content = \Idno\Core\Webservice::get($this->subscription);
            $this->subscription_mf2 = \Idno\Core\Webmention::parseContent($content['content']);
            
            // Get the endpoint
             // Get subscriber endpoint
            if (preg_match('~<link href="([^"]+)" rel="http://mapkyc.me/1dM84ud" ?\/?>~', $content['content'], $match)) {
                $this->subscription_endpoint = $match[1];
            } else
                throw new SubscriptionException('No subscription endpoint found.');
            
            return $this->save();

        }
        
        /**
         * Subscribe and get pings
         */
        function subscribe() {
            
            if ($result = \Idno\Core\Webservice::post($this->subscription_endpoint, ['subscriber' => $this->subscriber, 'subscribe' => $this->subscription]))
            {
                if ($result['response']>=300) // handle poorly written endpoints, accept any 200 code
                    throw new SubscriptionException("Subscription attempt reported code {$result['response']}");
            }
            
        }
        
        /**
         * Unsubscribe
         * @throws SubscriptionException
         */
        function unsubscribe() {
            if ($result = \Idno\Core\Webservice::delete($this->subscription_endpoint, ['subscriber' => $this->subscriber, 'subscribe' => $this->subscription]))
            {
                if ($result['response']>=300) // handle poorly written endpoints, accept any 200 code
                    throw new SubscriptionException("Unsubscribe attempt reported code {$result['response']}");
            }
        }
        
    }

}