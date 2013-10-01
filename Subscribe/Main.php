<?php

/**
 * @file
 * Subscription support for Idno.
 * 
 * This plugin allows subscription and updates. Currently has no UI, so use CURL.
 * 
 * @see http://www.marcus-povey.co.uk/2013/09/26/thoughts-simple-distributed-friendfollowsubscribe-scheme/
 */

namespace IdnoPlugins\Subscribe {

    class Main extends \Idno\Common\Plugin {

        function registerPages() {

            // Register endpoint
            \Idno\Core\site()->addPageHandler('/subscribe/?', '\IdnoPlugins\Subscribe\Pages\Endpoint');

            // Edit pages
            \Idno\Core\site()->addPageHandler('/subscription/edit/?', '\IdnoPlugins\Subscribe\Pages\Subscription\Edit');
            \Idno\Core\site()->addPageHandler('/subscription/edit/([A-Za-z0-9]+)/?', '\IdnoPlugins\Subscribe\Pages\Subscription\Edit');
            \Idno\Core\site()->addPageHandler('/subscriptions/?', '\IdnoPlugins\Subscribe\Pages\Subscription\ListAll');
            \Idno\Core\site()->addPageHandler('/subscribers/?', '\IdnoPlugins\Subscribe\Pages\Subscriber\ListAll');
            
            // Add header
            \Idno\Core\site()->template()->extendTemplate('shell/head', 'subscribe/header');
        }

        function registerEventHooks() {
            \Idno\Core\site()->addEventHook('save', function(\Idno\Core\Event $event) {
                        $object = $event->data()['object'];

                        // Check that this is an activity stream object, then notify subscriptions
                        if ($object->getActivityStreamsObjectType()) {

                            // Get subscriptions
                            if ($result = \Idno\Core\site()->db()->getObjects('IdnoPlugins\Subscribe\Subscriber', ['subscription' => \Idno\Core\site()->session()->currentUserUUID()])) {

                                foreach ($result as $subscriber) {
                                    $subscriber->notify($object->getUUID());
                                }
                            }
                        }
                    });


            // TODO: Send deletes
        }


        /**
         * Often we're dealing with profile urls not UUIDs, so this gets a user by their profile ID.
         * @param type $url
         */
        static function getUserByProfileURL($url) {
            if (preg_match("~".\Idno\Core\site()->config()->url . 'profile/([A-Za-z0-9]+)?~', $url, $matches))
                    return \Idno\Entities\User::getByHandle ($matches[1]);
            return false;
        }
        
        /**
         * Return author details from MF2.
         * @param type $mf2
         * @return type
         * @throws \IdnoPlugins\Subscribe\SubscriptionException
         */
        static function getAuthorFromMF2($mf2) {
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

            return $owner;
        }

    }

}
