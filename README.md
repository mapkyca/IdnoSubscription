Subscription support for Idno
=============================

This plugin represents a first stab at distributed friend/follow/update notification support for idno, based on the idea sketched out on this blog post: http://www.marcus-povey.co.uk/2013/09/26/thoughts-simple-distributed-friendfollowsubscribe-scheme/

Once installed, this plugin provides the ability to follow other indieweb/idno sites that implement this protocol. 

* Important: This is highly experimental at the moment, and the protocol may be changed and replaced without warning *

Installation
------------

* Install into your IdnoPlugins directory and activate it in the plugins setting panel.

Note, currently this plugin requires my /dev branch of idno as it requires the Webservice library. Hopefully, this will shortly be merged into upstream.

Testing
-------

The util/ directory contains some scripts that give an example how to discover endpoints, subscribe and send notification messages from the command line.

Todo
----
* [X] Subscription support
* [X] Notify API support
* [ ] Implement a UI for the above

Licence
-------

Released under the Apache 2.0 licence: http://www.apache.org/licenses/LICENSE-2.0.html

See
---
 * Author: Marcus Povey <http://www.marcus-povey.co.uk> 
 * Related post: http://www.marcus-povey.co.uk/2013/09/26/thoughts-simple-distributed-friendfollowsubscribe-scheme/
