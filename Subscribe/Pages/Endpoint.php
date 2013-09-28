<?php

namespace IdnoPlugins\Subscribe\Pages {

    class Endpoint extends \Idno\Common\Page {

        function getContent() {

            echo "This is a subscribe endpoint (see http://www.marcus-povey.co.uk/2013/09/26/thoughts-simple-distributed-friendfollowsubscribe-scheme/). Use POST or DELETE methods to communicate with me";
        }

        function postContent() {
            
            try {
                // Subscription request
                if ((!empty($this->getInput('subscriber'))) && (!empty($this->getInput('subscribe')))) 
                {
                    // load subscribe , get owner object
                    if ($subscribing_to = \Idno\Entities\User::getByUUID($this->getInput('subscribe')))
                    {
                        $subscriber = new \IdnoPlugins\Subscribe\Subscriber();
                        if ($subscriber->saveDataFromInput()) 
                        {
                            $this->setResponse(202); // Accepted request
                            echo "OK";
                        }
                        else throw new \IdnoPlugins\Subscribe\SubscriptionException("Subscription not made, most likely your profile URL is not accessible.");
                        
                    }

                }
                // Post create / update ping
                else if (!empty($this->getInput('subscription')))
                {
                    $permalink = $this->getInput('subscription');
                    
                    // Check we have a domain
                    if ($subs_on_domain = \Idno\Core\site()->db()->getObjects('IdnoPlugins\Subscribe\Subscription', ['subscription_domain' =>  parse_url($permalink, PHP_URL_HOST)])) {
                        // Get MF2
                        if ($content = \Idno\Core\Webservice::get($permalink)) {
                        
                            if ($mf2 = \Idno\Core\Webmention::parseContent($content)) {
                                
                                // Get owner details
                                $owner = [];
                                
                                // A first pass for overall owner ...
                                foreach ($mf2['items'] as $item) {

                                    // Figure out what kind of Microformats 2 item we have
                                    if (!empty($item['type']) && is_array($item['type'])) {
                                        foreach ($item['type'] as $type) {

                                            switch($type) {
                                                case 'h-card':
                                                    if (!empty($item['properties'])) {
                                                        if (!empty($item['properties']['name'])) $owner['name'] = $item['properties']['name'][0];
                                                        if (!empty($item['properties']['url'])) $owner['url'] = $item['properties']['url'][0];
                                                        if (!empty($item['properties']['photo'])) $owner['photo'] = $item['properties']['photo'][0];
                                                    }
                                                    break;
                                            }
                                            if (!empty($owner)) {
                                                break;
                                            }

                                        }
                                    }

                                }

                                if (empty($owner))
                                    throw new \IdnoPlugins\Subscribe\SubscriptionException("Could not find owner in Microformats data, please visit indiewebcamp for help in marking up your page!");

                                // Check that author is a valid subscription
                                if ($subscriptons = \Idno\Core\site()->db()->getObjects('IdnoPlugins\Subscribe\Subscription', ['subscription' =>  $owner['url']]))
                                {
                                    foreach ($subscriptions as $subscription) {
                               
                                        // For each subscription to that author, notify interested party
                                        \Idno\Core\site()->triggerEvent('subscription/ping', [
                                            'permalink' => $permalink,
                                            'data' => $mf2,
                                            'subscription' => $subscription,
                                        ]);
                                
                                    }
                            
                                    $this->setResponse(202); // Accepted request
                                    echo "OK";
                                    
                                }
                                else 
                                    throw new \IdnoPlugins\Subscribe\SubscriptionException("Nobody seems to be following the author identified by {$owner['url']}");
                                
                            } else
                                throw new \IdnoPlugins\Subscribe\SubscriptionException("No valid MF2 content found on page, please visit indiewebcamp.com for help marking up your content.");
                        }
                        else
                            throw new \IdnoPlugins\Subscribe\SubscriptionException("Permalink $permalink could not be reached");
                    }
                    else {
                        throw new \IdnoPlugins\Subscribe\SubscriptionException("Permalink from unrecognised domain");
                    }
                }
            } catch (IdnoPlugins\Subscribe\SubscriptionException $e) {
                $this->setResponse(400);
                echo $e->getMessage();
            }
        }

        function deleteContent() {
            
            try {
                // Subscription removal
                if ((!empty($this->getInput('subscriber'))) && (!empty($this->getInput('subscribe')))) 
                {
                    // load subscribe , get owner object




                    //TODO: check authentication here
                }

                // Post removal
                else if (!empty($this->getInput('subscription')))
                {



                }
            } catch (IdnoPlugins\Subscribe\SubscriptionException $e) {
                $this->setResponse(400);
                echo $e->getMessage();
            }
        }

    }

}
