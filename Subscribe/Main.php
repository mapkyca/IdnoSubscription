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

                    // Add header
                    \Idno\Core\site()->template()->extendTemplate('shell/head','subscribe/header');
				
            }
            
            /**
             * Subscribe the logged in user to a given profile UUID 
             * @param type $profile_uuid
             */
            function subscribe($profile_uuid) {
                // Subscribe to a user
                
                // If remote request successful, then create a local Subscription object
            }
        }
    }
