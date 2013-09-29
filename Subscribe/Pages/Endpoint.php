<?php

namespace IdnoPlugins\Subscribe\Pages {

    class Endpoint extends \Idno\Common\Page {

        function getContent() {

            echo "This is a subscribe endpoint (see http://www.marcus-povey.co.uk/2013/09/26/thoughts-simple-distributed-friendfollowsubscribe-scheme/). Use POST or DELETE methods to communicate with me";
        }

        function post() {
            
            $subscriber = $this->getInput('subscriber');
            $subscribe = $this->getInput('subscribe');

            $permalink = $this->getInput('subscription');

            try {
                
                // Subscription request    
                if ((!empty($subscriber)) && (!empty($subscribe))) 
                {
                    // load subscribe , get owner object
                    if (($subscribing_to = \Idno\Entities\User::getByUUID($subscribe)) || ($subscribing_to = \IdnoPlugins\Subscribe\Main::getUserByProfileURL($subscribe)))
                    {
                        // Create subscription
                        $subscriber = new \IdnoPlugins\Subscribe\Subscriber();
                        if ($subscriber->saveDataFromInput()) 
                        {
                            $this->setResponse(202); // Accepted request
                            echo "OK";
                        }
                        else throw new \IdnoPlugins\Subscribe\SubscriptionException("Subscription not made, most likely your profile URL is not accessible.");
                        
                    }
                    else
                        throw new \IdnoPlugins\Subscribe\SubscriptionException("Could not get user identified by $subscribe");

                }
                // Post create / update ping
                else if (!empty($permalink))
                {
                    // Check we have a domain
                    if ($subs_on_domain = \Idno\Core\site()->db()->getObjects('IdnoPlugins\Subscribe\Subscription', ['subscription_domain' =>  parse_url($permalink, PHP_URL_HOST)])) {
                        // Get MF2
                        if ($content = \Idno\Core\Webservice::get($permalink)) {
                        
                            if ($mf2 = \Idno\Core\Webmention::parseContent($content['content'])) {
                                
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
                                        \Idno\Core\site()->triggerEvent('subscription/post/update', [
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
                else {
                    throw new \IdnoPlugins\Subscribe\ubscriptionException("Unknown method");
                }
 
            } catch (\IdnoPlugins\Subscribe\SubscriptionException $e) {
                $this->setResponse(400);
                echo $e->getMessage(); echo "\n";
            }
        }

        function deleteContent() {
            
            $subscriber = $this->getInput('subscriber');
            $subscribe = $this->getInput('subscribe');

            $permalink = $this->getInput('subscription');
            
            try {
                // Subscription removal
                if ((!empty($subscriber)) && (!empty($subscribe))) 
                {
                    // load subscribe , get owner object




                    //TODO: check authentication here
                }

                // Post removal
                else if (!empty($permalink))
                {

                    // Get subscriptions
                    
                    // Set tombstones


                }
            } catch (\IdnoPlugins\Subscribe\SubscriptionException $e) {
                $this->setResponse(400);
                echo $e->getMessage();
            }
        }

    }

}
