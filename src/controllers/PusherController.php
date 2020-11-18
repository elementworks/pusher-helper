<?php
/**
 * Pusher Helper plugin for Craft CMS 3.x
 *
 * Support functionality for Pusher
 *
 * @link      https://elementworks.co.uk
 * @copyright Copyright (c) 2020 Element Works Ltd
 */

namespace elementworks\pusherhelper\controllers;

use Craft;
use craft\web\Controller;
use elementworks\pusherhelper\PusherHelper;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;

/**
 * Pusher Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your plugin’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    Element Works Ltd
 * @package   PusherHelper
 * @since     1.0.0
 */
class PusherController extends Controller
{
    // Public Methods
    // =========================================================================

    /**
     * Authenticate Pusher user
     *
     * @return string
     * @throws HttpException
     * @throws BadRequestHttpException
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionAuth()
    {
        $this->requirePostRequest();

        // Cache Craft components
        $request = Craft::$app->getRequest();

        $socketId = $request->getRequiredParam('socket_id');
        $channelName = $request->getRequiredParam('channel_name');

        $currentUser = Craft::$app->getUser()->getIdentity();

        return PusherHelper::$plugin->pusher->authenticate($currentUser, $socketId, $channelName);
    }

    public function actionSendMessage()
    {
        $this->requirePostRequest();
        $this->requireAcceptsJson();

        // Cache Craft components
        $request = Craft::$app->getRequest();

        $message = $request->getRequiredParam('message');
        $event = $request->getRequiredParam('event');
        $channelName = $request->getRequiredParam('channel-name');

        $currentUser = Craft::$app->getUser()->getIdentity();

        return PusherHelper::$plugin->pusher->sendMessageToChannel(
            $channelName,
            $event,
            [
                'sender' => $currentUser->id,
                'message' => $message
            ]
        );
    }
}
