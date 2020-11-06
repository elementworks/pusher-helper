<?php
/**
 * Pusher Helper plugin for Craft CMS 3.x
 *
 * Support functionality for Pusher
 *
 * @link      https://elementworks.co.uk
 * @copyright Copyright (c) 2020 Element Works Ltd
 */

namespace elementworks\pusherhelper\assetbundles\pusherhelper;

use Craft;
use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * PusherHelperAsset AssetBundle
 *
 * AssetBundle represents a collection of asset files, such as CSS, JS, images.
 *
 * Each asset bundle has a unique name that globally identifies it among all asset bundles used in an application.
 * The name is the [fully qualified class name](http://php.net/manual/en/language.namespaces.rules.php)
 * of the class representing it.
 *
 * An asset bundle can depend on other asset bundles. When registering an asset bundle
 * with a view, all its dependent asset bundles will be automatically registered.
 *
 * http://www.yiiframework.com/doc-2.0/guide-structure-assets.html
 *
 * @author    Element Works Ltd
 * @package   PusherHelper
 * @since     1.0.0
 */
class PusherHelperAsset extends AssetBundle
{
    // Public Methods
    // =========================================================================

    /**
     * Initializes the bundle.
     */
    public function init()
    {
        // define the path that your publishable resources live
        $this->sourcePath = "@elementworks/pusherhelper/assetbundles/pusherhelper/dist";

        // define the dependencies
        $this->depends = [
            CpAsset::class,
        ];

        // define the relative path to CSS/JS files that should be registered with the page
        // when this asset bundle is registered
        $this->js = [
            'js/PusherHelper.js',
        ];

        $this->css = [
            'css/PusherHelper.css',
        ];

        parent::init();
    }
}
