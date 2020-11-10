<?php
/**
 * Pusher Helper plugin for Craft CMS 3.x
 *
 * Support functionality for Pusher
 *
 * @link      https://elementworks.co.uk
 * @copyright Copyright (c) 2020 Element Works Ltd
 */

namespace elementworks\pusherhelper\models;

use craft\base\Model;

/**
 * PusherHelper Settings Model
 *
 * This is a model used to define the plugin's settings.
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Element Works Ltd
 * @package   PusherHelper
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Pusher App ID
     *
     * @var string
     */
    public $appId = '';

    /**
     * Pusher App Key
     *
     * @var string
     */
    public $appKey = '';

    /**
     * Pusher App Secret
     *
     * @var string
     */
    public $appSecret = '';

    /**
     * Pusher App Cluster
     *
     * @var string
     */
    public $appCluster = 'eu';

    /**
     * Pusher use TLS
     *
     * @var bool
     */
    public $useTLS = false;

    /**
     * Pusher Global Channel
     *
     * Global channel is the name of the fake global presence channel
     * All private presence channels will be named using this as the base, followed by the userId of the user
     * See here for full details: https://support.pusher.com/hc/en-us/articles/360019620253-How-can-I-implement-large-presence-channels-on-Channels-
     *
     * @var string
     */
    public $globalChannel = 'private-presence';

    /**
     * User fields to include in global presence messages
     * This should a be an array of field handles
     *
     * @var array
     */
    public $userFields = ['id','firstName','lastName'];

    /**
     * orderBy parameter for User query when returning userData for who's online
     *
     * @var string
     */
    public $orderBy = 'firstName asc';

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['appId','appKey','appSecret','appCluster','globalChannel','orderBy'], 'string'],
            [['useTLS'], 'boolean'],
            ['useTLS', 'default', 'value' => false],
            ['globalChannel', 'default', 'value' => 'private-presence'],
            ['appCluster', 'default', 'value' => 'eu'],
            ['orderBy', 'default', 'value' => 'firstName asc'],
        ];
    }
}
