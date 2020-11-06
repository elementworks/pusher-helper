# Pusher Helper plugin for Craft CMS 3.x

Support functionality for Pusher

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require elementworks/pusher-helper

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Pusher Helper.

## Pusher Helper Overview

This plugin provides boilerplate backend support for Pusher to CraftCMS, including:
 
* The ability to authenticate to private channels.
* Support for large presence implementations using a pull-based approach as described here: https://support.pusher.com/hc/en-us/articles/360019620253-How-can-I-implement-large-presence-channels-on-Channels-
* Support for message chunking to allow for messages greater than 10kb to be sent to 'global' presence channel using technique outlined here: https://github.com/pusher/pusher-channels-chunking-example

There is no front-end code provided by this plugin. You will need to write your own front-end implementation to suit your own requirements.

## Configuring Pusher Helper

Copy the `config.php` file in the `src` directory of the plugin into the `config` directory of your project, rename it `pusher-helper.php` and add the settings you need for your project. You are strongly advised to use environment variables for the Pusher settings.

Add the following environment variables to your `.env` file in all environments:

* `PUSHER_APP_ID`
* `PUSHER_KEY`
* `PUSHER_SECRET`
* `PUSHER_CLUSTER`

`PUSHER_CLUSTER` defaults to `eu` if not otherwise specified. The other Pusher API parameters are required for the plugin to work. 

The `globalChannel` config setting is the name of the global presence channel as described here: https://support.pusher.com/hc/en-us/articles/360019620253-How-can-I-implement-large-presence-channels-on-Channels- and must start with `private-`.

The `userFields` config setting determines what fields on a user object will be included in the presence messages sent out on the global presence channel. These can include any valid fields defined on users in your project. They can be used to populate a who's online list, so think about what information you might want to include on there, remembering it is best to keep this as brief as possible to limit the number and size of messages sent. 

Pusher imposes a 10kb limit on messages, so the messages sent on the global presence channel will be chunked if they are above this.

## Using Pusher Helper

Install and configure the plugin as described above.

You are responsible for providing the following to make this work:

* All front-end javascript using the `pusher.js` library to authenticate users and receive and process messages from the global presence channel. The plugin provides no code for the front-end of your site and will not function without it.
* Set up a cron job on your server to ping `./craft pusher-helper/pusher/send-presence-state` at 1 minute intervals to update the presence state on the global presence channel.

## Pusher Helper Roadmap

Some things to do, and ideas for potential features:

* Release it

Brought to you by [Element Works Ltd](https://elementworks.co.uk)
