<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\Google\Extensions
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-google
 */

namespace SilverWare\Google\Extensions;

use SilverStripe\Core\Convert;
use SilverStripe\Core\Extension;
use SilverStripe\SiteConfig\SiteConfig;
use SilverWare\Google\API\GoogleAPI;

/**
 * An extension which adds Google features to pages.
 *
 * @package SilverWare\Google\Extensions
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-google
 */
class PageExtension extends Extension
{
    /**
     * Define constants.
     */
    const VERIFICATION_TAG_NAME = 'google-site-verification';
    
    /**
     * Appends the additional Google tags to the provided meta tags.
     *
     * @param string $tags
     *
     * @return void
     */
    public function MetaTags(&$tags)
    {
        if ($code = GoogleAPI::singleton()->getVerificationCode()) {
            
            // Add New Line (if does not exist):
            
            if (!preg_match('/[\n]$/', $tags)) {
                $tags .= "\n";
            }
            
            // Add Verification Code Meta Tag:
            
            $tags .= sprintf(
                "<meta name=\"%s\" content=\"%s\" />\n",
                self::VERIFICATION_TAG_NAME,
                Convert::raw2att($code)
            );
            
        }
    }
}
