<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\Google\API
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-google
 */

namespace SilverWare\Google\API;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\SiteConfig\SiteConfig;

/**
 * An object to encapsulate Google API data and methods.
 *
 * @package SilverWare\Google\API
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-google
 */
class GoogleAPI
{
    use Injectable;
    use Configurable;
    
    /**
     * Answers the API key from site or YAML configuration.
     *
     * @return string
     */
    public function getAPIKey()
    {
        $key = SiteConfig::current_site_config()->GoogleAPIKey;
        
        if (!$key) {
            $key = self::config()->api_key;
        }
        
        return $key;
    }
    
    /**
     * Answers true if the receiver has an API key.
     *
     * @return boolean
     */
    public function hasAPIKey()
    {
        return (boolean) $this->getAPIKey();
    }
    
    /**
     * Answers the API language from site or YAML configuration.
     *
     * @return string
     */
    public function getAPILanguage()
    {
        $lang = SiteConfig::current_site_config()->GoogleAPILanguage;
        
        if (!$lang) {
            $lang = self::config()->api_language;
        }
        
        return $lang;
    }
    
    /**
     * Answers the analytics tracking ID from site or YAML configuration.
     *
     * @return string
     */
    public function getAnalyticsTrackingID()
    {
        $id = SiteConfig::current_site_config()->GoogleAnalyticsTrackingID;
        
        if (!$id) {
            $id = self::config()->analytics_tracking_id;
        }
        
        return $id;
    }
    
    /**
     * Answers the site verification code from site or YAML configuration.
     *
     * @return string
     */
    public function getVerificationCode()
    {
        $code = SiteConfig::current_site_config()->GoogleVerificationCode;
        
        if (!$code) {
            $code = self::config()->verification_code;
        }
        
        return $code;
    }
    
    /**
     * Answers true if analytics is enabled for the site.
     *
     * @return boolean
     */
    public function isAnalyticsEnabled()
    {
        $enabled = SiteConfig::current_site_config()->GoogleAnalyticsEnabled;
        
        if (!$enabled) {
            $enabled = self::config()->analytics_enabled;
        }
        
        return (boolean) $enabled;
    }
}
