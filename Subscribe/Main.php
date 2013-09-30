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

    }

}
