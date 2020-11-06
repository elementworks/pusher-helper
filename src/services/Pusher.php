<?php
/**
 * Pusher Helper plugin for Craft CMS 3.x
 *
 * Support functionality for Pusher
 *
 * @link      https://elementworks.co.uk
 * @copyright Copyright (c) 2020 Element Works Ltd
 */

namespace elementworks\pusherhelper\services;

use craft\base\Component;
use elementworks\pusherhelper\models\Settings;
use elementworks\pusherhelper\PusherHelper;
use Pusher\Pusher as PusherAPI;
use Pusher\PusherException;

/**
 * Pusher Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Element Works Ltd
 * @package   PusherHelper
 * @since     1.0.0
 *
 * @property-read mixed $userChannels
 * @property-read array $onlineUserIds
 */
class Pusher extends Component
{
    // Protected Properties
    // =========================================================================

    /**
     * @var PusherAPI
     */
    protected $_pusher;

    /**
     * @var Settings
     */
    protected $_settings;

    /**
     * PusherController constructor.
     *
     * @throws PusherException
     */
    public function __construct()
    {
        parent::__construct();

        $this->_settings = PusherHelper::$plugin->getSettings();

        if ($this->_settings->appKey && $this->_settings->appSecret && $this->_settings->appId) {
            $this->_pusher = new PusherAPI(
                $this->_settings->appKey,
                $this->_settings->appSecret,
                $this->_settings->appId,
                [
                    'cluster' => $this->_settings->appCluster,
                    'useTLS' => $this->_settings->useTLS
                ]
            );
        }
    }

    // Public Methods
    // =========================================================================

    /**
     * @param $user
     * @param $socketId
     * @param $channelName
     * @return string
     * @throws PusherException
     */
    public function authenticate($user, $socketId, $channelName)
    {
        $data = [];

        if ($this->_pusher) {
            return $this->_pusher->presence_auth($channelName,
                $socketId,
                $user->id,
                $data
            );
        }

        return '';
    }

    /**
     * @throws PusherException
     */
    public function getUserChannels()
    {
        if ($this->_pusher) {
            $userChannels = $this->_pusher->get_channels([
                'filter_by_prefix' => $this->_settings->globalChannel.'-'
            ]);
            return $userChannels->channels;
        }

        return [];
    }

    /**
     * @throws PusherException
     */
    public function getOnlineUserIds()
    {
        $userChannels = $this->getUserChannels();

        $userIds = [];

        foreach (array_keys($userChannels) as $channel) {
            $userIds[] = explode('-', $channel)[2];
        }

        return $userIds;
    }

    /**
     * @param $message
     * @return array|bool
     * @throws PusherException
     */
    public function sendMessageToGlobalChannel($message)
    {
        if ($this->_pusher) {
            return $this->_pusher->trigger($this->_settings->globalChannel, 'presence', $message);
        }

        return false;
    }
}
