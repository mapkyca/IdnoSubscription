<?php

namespace IdnoPlugins\Subscribe\Pages {

    class Endpoint extends \Idno\Common\Page {

        function getContent() {

            echo "This is a subscribe endpoint (see http://www.marcus-povey.co.uk/2013/09/26/thoughts-simple-distributed-friendfollowsubscribe-scheme/). Use POST or DELETE methods to communicate with me";
        }

        function postContent() {
            
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
                    else {
                        $this->setResponse(400);
                        echo "Subscription not made, most likely your profile URL is not accessible.";
                    }
                }
                
            }
            // Post create / update
            else if (!empty($this->getInput('subscription')))
            {
                
                
            
            }
        }

        function deleteContent() {
            
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
        }

    }

}
