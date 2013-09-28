<?php

/**
 * An update from a subscription
 */

namespace IdnoPlugins\Subscribe {

    class Update extends \Idno\Common\Entity {
         
        function saveDataFromInput() {
            
            $this->subscription = $this->getInput('subscription'); // UUID of the subscription
            $this->permalink = $this->getInput('permalink'); // UUID of the permalink
            
            // Now fetch MF2 of the permalink
            $content = \Idno\Core\Webmention::getPageContent($this->permalink);
            $this->permalink_mf2 = \Idno\Core\Webmention::parseContent($content['content']);
            
            return $this->save();

        }
    }
}