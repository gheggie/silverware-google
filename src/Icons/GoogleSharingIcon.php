<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\Google\Icons
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-google
 */

namespace SilverWare\Google\Icons;

use SilverWare\Google\Buttons\GoogleSharingButton;
use SilverWare\Social\Model\SharingIcon;

/**
 * An extension of the sharing icon class for a Google sharing icon.
 *
 * @package SilverWare\Google\Icons
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-google
 */
class GoogleSharingIcon extends SharingIcon
{
    /**
     * Human-readable singular name.
     *
     * @var string
     * @config
     */
    private static $singular_name = 'Google Sharing Icon';
    
    /**
     * Human-readable plural name.
     *
     * @var string
     * @config
     */
    private static $plural_name = 'Google Sharing Icons';
    
    /**
     * Description of this object.
     *
     * @var string
     * @config
     */
    private static $description = 'A sharing icon to share the current page via Google';
    
    /**
     * Defines the table name to use for this object.
     *
     * @var string
     * @config
     */
    private static $table_name = 'SilverWare_GoogleSharingIcon';
    
    /**
     * Defines an ancestor class to hide from the admin interface.
     *
     * @var string
     * @config
     */
    private static $hide_ancestor = SharingIcon::class;
    
    /**
     * Defines the default values for the fields of this object.
     *
     * @var array
     * @config
     */
    private static $defaults = [
        'FontIcon' => 'google-plus',
        'ColorBackgroundLink' => '#f34a38',
        'ColorForegroundLink' => '#ffffff'
    ];
    
    /**
     * Defines the class of sharing button to use for the popover.
     *
     * @var string
     * @config
     */
    private static $button_class = GoogleSharingButton::class;
}
