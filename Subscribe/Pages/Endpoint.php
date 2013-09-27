<?php

namespace IdnoPlugins\Subscribe\Pages {

    class Endpoint extends \Idno\Common\Page {

        function getContent() {

            echo "This is a subscribe endpoint (see http://www.marcus-povey.co.uk/2013/09/26/thoughts-simple-distributed-friendfollowsubscribe-scheme/). Use POST or DELETE methods to communicate with me";
        }

        function postContent() {
            
        }

        function deleteContent() {
            
        }

    }

}
