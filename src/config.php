<?php
/**
 * Pusher Helper config.php
 *
 * This file exists only as a template for the Pusher Helper settings.
 * It does nothing on its own.
 *
 * Don't edit this file, instead copy it to 'craft/config' as 'pusher-helper.php'
 * and make your changes there to override default settings.
 *
 * Once copied to 'craft/config', this file will be multi-environment aware as
 * well, so you can have different settings groups for each environment, just as
 * you do for 'general.php'
 */

return [
    // Global settings
    '*' => [
        // Pusher configuration, set using environment variables
        'appId' => getenv('PUSHER_APP_ID'),
        'appKey' => getenv('PUSHER_KEY'),
        'appSecret' => getenv('PUSHER_SECRET'),
        'appCluster' => getenv('PUSHER_CLUSTER'),
        'useTLS' => getenv('ENVIRONMENT') !== 'dev',

        // Global channel is the name of the fake global presence channel
        // All private presence channels will be named using this as the base, followed by the userId of the user
        // See here for full details: https://support.pusher.com/hc/en-us/articles/360019620253-How-can-I-implement-large-presence-channels-on-Channels-
        'globalChannel' => 'private-channel-name'
    ],
];
