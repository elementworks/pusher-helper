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
use craft\elements\User;
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
     * @var array
     */
    protected $_baseUserFields = ['id','firstName','lastName'];

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
     * @param array $params
     * @return array
     */
    public function getChannels($params = [])
    {
        if ($this->_pusher) {
            return $this->_pusher->get_channels($params)->channels;
        }

        return [];
    }

    /**
     * @throws PusherException
     */
    public function getOnlineUserIds()
    {
        $userChannels = $this->getChannels([
            'filter_by_prefix' => $this->_settings->globalChannel.'-'
        ]);

        $userIds = [];

        foreach (array_keys($userChannels) as $channel) {
            $userIds[] = explode('-', $channel)[2];
        }

        return $userIds;
    }

    /**
     * @throws PusherException
     */
    public function getChatUserIds()
    {
        $userChannels = $this->getChannels([
            'filter_by_prefix' => 'private-chat-'
        ]);

        $userIds = [];

        foreach (array_keys($userChannels) as $channel) {
            $channelNamePieces = explode('-', $channel);
            $userIds[] = $channelNamePieces[2];
            $userIds[] = $channelNamePieces[3];
        }

        return $userIds;
    }

    /**
     * @throws PusherException
     */
    public function getOnlineUserData()
    {
        $userIds = $this->getOnlineUserIds();

        $chatUserIds = $this->getChatUserIds();

        // Get user data
        if ($this->_settings->userFields) {
            $userQuery = User::find()
                ->id($userIds);

            $select = [];

            foreach ($this->_settings->userFields as $fieldHandle) {
                if (in_array($fieldHandle, $this->_baseUserFields, true)) {
                    $select[] = '{{%users}}.'.$fieldHandle;
                } else {
                    $select[] = 'field_'.$fieldHandle.' as '.$fieldHandle;
                }
            }

            if ($select) {
                $userQuery->select($select);
            }

            if ($this->_settings->orderBy) {
                $userQuery->orderBy($this->_settings->orderBy);
            }

            $userQuery->asArray(true);

            $userData = $userQuery->all();

            foreach ($userData as $key => $user) {
                if (in_array($user['id'], $chatUserIds, false)) {
                    $userData[$key]['inChat'] = true;
                } else {
                    $userData[$key]['inChat'] = false;
                }
            }
        } else {
            $userData = $userIds;
        }

        return $userData;
    }

    /**
     * @param $channel
     * @param $event
     * @param $message
     * @return array|bool
     */
    public function sendMessageToChannel($channel, $event, $message)
    {
        if ($this->_pusher) {
            return $this->_pusher->trigger($channel, $event, $message);
        }

        return false;
    }
}
