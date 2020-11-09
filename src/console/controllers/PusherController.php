<?php
/**
 * Pusher Helper plugin for Craft CMS 3.x
 *
 * Support functionality for Pusher
 *
 * @link      https://elementworks.co.uk
 * @copyright Copyright (c) 2020 Element Works Ltd
 */

namespace elementworks\pusherhelper\console\controllers;

use craft\elements\User;
use craft\helpers\Json;
use elementworks\pusherhelper\PusherHelper;
use Pusher\PusherException;
use yii\console\Controller;

/**
 * Pusher Command
 *
 * The first line of this class docblock is displayed as the description
 * of the Console Command in ./craft help
 *
 * Craft can be invoked via commandline console by using the `./craft` command
 * from the project root.
 *
 * Console Commands are just controllers that are invoked to handle console
 * actions. The segment routing is plugin-name/controller-name/action-name
 *
 * The actionIndex() method is what is executed if no sub-commands are supplied, e.g.:
 *
 * ./craft pusher-helper/pusher
 *
 * Actions must be in 'kebab-case' so actionDoSomething() maps to 'do-something',
 * and would be invoked via:
 *
 * ./craft pusher-helper/pusher/do-something
 *
 * @author    Element Works Ltd
 * @package   PusherHelper
 * @since     1.0.0
 */
class PusherController extends Controller
{
    /**
     * @var array
     */
    protected $_userFields;

    /**
     * @var array
     */
    protected $_baseUserFields = ['id','firstName','lastName'];

    const MESSAGE_CHUNK_SIZE = 9000;

    /**
     * PusherController init.
     */
    public function init()
    {
        parent::init();

        $this->_userFields = PusherHelper::$plugin->getSettings()->userFields;
    }

    // Public Methods
    // =========================================================================

    /**
     * Send presence message to global private channel
     *
     * Normally triggered by a cron job, this polls for all existing private channels matching private-presence-*
     * then sends a message to the global private channel private-presence with a list of all active private channels
     * This is a way to get around the 100 member limit for presence channels with Pusher
     * See https://support.pusher.com/hc/en-us/articles/360019620253-How-can-I-implement-large-presence-channels-on-Channels
     *
     * @return mixed
     * @throws PusherException
     */
    public function actionSendPresenceState()
    {
        $pusherAPI = PusherHelper::$plugin->pusher;

        $userData = $pusherAPI->getOnlineUserData();

        if ($userData) {
            $message = Json::encode($userData);

            $msgId = (string) time();
            for ($i = 0, $messageLength = strlen($message); $i * $this::MESSAGE_CHUNK_SIZE < $messageLength; $i++) {
                $pusherAPI->sendMessageToGlobalChannel([
                    'id' => $msgId,
                    'index' => $i,
                    'chunk' => substr($message, $i * $this::MESSAGE_CHUNK_SIZE, $this::MESSAGE_CHUNK_SIZE),
                    'final' => $this::MESSAGE_CHUNK_SIZE * ($i + 1) >= $messageLength
                ]);
            }

        }

        return true;
    }
}
