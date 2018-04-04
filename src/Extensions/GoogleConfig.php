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

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverWare\Extensions\Config\ServicesConfig;
use SilverWare\Forms\FieldSection;
use SilverWare\Google\API\GoogleAPI;

/**
 * An extension of the services config class which adds Google settings to site configuration.
 *
 * @package SilverWare\Google\Extensions
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-google
 */
class GoogleConfig extends ServicesConfig
{
    /**
     * Maps field names to field types for this object.
     *
     * @var array
     * @config
     */
    private static $db = [
        'GoogleAPIKey' => 'Varchar(128)',
        'GoogleAPILanguage' => 'Varchar(8)',
        'GoogleVerificationCode' => 'Varchar(64)',
        'GoogleAnalyticsTrackingID' => 'Varchar(64)',
        'GoogleAnalyticsEnabled' => 'Boolean'
    ];
    
    /**
     * Updates the CMS fields of the extended object.
     *
     * @param FieldList $fields List of CMS fields from the extended object.
     *
     * @return void
     */
    public function updateCMSFields(FieldList $fields)
    {
        // Update Field Objects (from parent):
        
        parent::updateCMSFields($fields);
        
        // Create Google Tab:
        
        $fields->findOrMakeTab(
            'Root.SilverWare.Services.Google',
            $this->owner->fieldLabel('Google')
        );
        
        // Define Placeholders:
        
        $placeholderAuto = _t(__CLASS__ . '.DROPDOWNAUTOMATIC', 'Automatic');
        
        // Create Field Objects:
        
        $fields->addFieldsToTab(
            'Root.SilverWare.Services.Google',
            [
                FieldSection::create(
                    'GoogleAPIConfig',
                    $this->owner->fieldLabel('GoogleAPIConfig'),
                    [
                        TextField::create(
                            'GoogleAPIKey',
                            $this->owner->fieldLabel('GoogleAPIKey')
                        )->setRightTitle(
                            _t(
                                __CLASS__ . '.GOOGLEAPIKEYRIGHTTITLE',
                                'Create credentials using the Google API Manager and paste the API key here.'
                            )
                        ),
                        DropdownField::create(
                            'GoogleAPILanguage',
                            $this->owner->fieldLabel('GoogleAPILanguage'),
                            $this->owner->getGoogleLanguageOptions()
                        )->setEmptyString(' ')->setAttribute('data-placeholder', $placeholderAuto)
                    ]
                ),
                FieldSection::create(
                    'GoogleAnalyticsConfig',
                    $this->owner->fieldLabel('GoogleAnalyticsConfig'),
                    [
                        CheckboxField::create(
                            'GoogleAnalyticsEnabled',
                            $this->owner->fieldLabel('GoogleAnalyticsEnabled')
                        ),
                        TextField::create(
                            'GoogleAnalyticsTrackingID',
                            $this->owner->fieldLabel('GoogleAnalyticsTrackingID')
                        )->setAttribute('spellcheck', 'false')
                    ]
                ),
                FieldSection::create(
                    'GoogleVerificationConfig',
                    $this->owner->fieldLabel('GoogleVerificationConfig'),
                    [
                        TextField::create(
                            'GoogleVerificationCode',
                            $this->owner->fieldLabel('GoogleVerificationCode')
                        )->setAttribute('spellcheck', 'false')
                    ]
                )
            ]
        );
    }
    
    /**
     * Updates the field labels of the extended object.
     *
     * @param array $labels Array of field labels from the extended object.
     *
     * @return void
     */
    public function updateFieldLabels(&$labels)
    {
        // Update Field Labels (from parent):
        
        parent::updateFieldLabels($labels);
        
        // Update Field Labels:
        
        $labels['Google'] = _t(__CLASS__ . '.GOOGLE', 'Google');
        $labels['GoogleAPIKey'] = _t(__CLASS__ . '.GOOGLEAPIKEY', 'Google API Key');
        $labels['GoogleAPIConfig'] = _t(__CLASS__ . '.GOOGLEAPI', 'Google API');
        $labels['GoogleAPILanguage'] = _t(__CLASS__ . '.LANGUAGE', 'Language');
        $labels['GoogleAnalyticsConfig'] = _t(__CLASS__ . '.GOOGLEANALYTICS', 'Google Analytics');
        $labels['GoogleAnalyticsEnabled'] = _t(__CLASS__ . '.ENABLED', 'Enabled');
        $labels['GoogleAnalyticsTrackingID'] = _t(__CLASS__ . '.TRACKINGID', 'Tracking ID');
        $labels['GoogleVerificationConfig'] = _t(__CLASS__ . '.SITEVERIFICATION', 'Site Verification');
        $labels['GoogleVerificationCode'] = _t(__CLASS__ . '.VERIFICATIONCODE', 'Verification code');
    }
    
    /**
     * Event method called before the receiver is written to the database.
     *
     * @return void
     */
    public function onBeforeWrite()
    {
        $this->owner->GoogleVerificationCode    = $this->getVerificationCode($this->owner->GoogleVerificationCode);
        $this->owner->GoogleAnalyticsTrackingID = trim($this->owner->GoogleAnalyticsTrackingID);
    }
    
    /**
     * Answers the HTML tag attributes for the body as an array.
     *
     * @return array
     */
    public function getBodyAttributes()
    {
        $attributes = [];
        
        $api = GoogleAPI::singleton();
        
        if ($key = $api->getAPIKey()) {
            $attributes['data-google-api-key'] = $key;
        }
        
        if ($lang = $api->getAPILanguage()) {
            $attributes['data-google-api-lang'] = $lang;
        }
        
        if ($api->isAnalyticsEnabled()) {
            $attributes['data-google-tracking-id'] = $api->getAnalyticsTrackingID();
        }
        
        return $attributes;
    }
    
    /**
     * Answers an array of options for the language field.
     *
     * @return array
     */
    public function getGoogleLanguageOptions()
    {
        return $this->owner->config()->google_languages;
    }
    
    /**
     * Answers a status message array for the CMS interface.
     *
     * @return string
     */
    public function getGoogleStatusMessage()
    {
        if (!GoogleAPI::singleton()->hasAPIKey()) {
            
            return _t(
                __CLASS__ . '.GOOGLEAPIKEYMISSING',
                'Google API Key has not been entered into site configuration.'
            );
            
        }
    }
    
    /**
     * Removes any HTML present in the pasted value and answers the verification code.
     *
     * @param string $code
     *
     * @return string
     */
    protected function getVerificationCode($code)
    {
        // Trim Code:
        
        $code = trim($code);
        
        // Detect HTML:
        
        if (stripos($code, '<meta') === 0) {
            
            // Extract Verification Code:
            
            preg_match('/content="(.+)"/', $code, $matches);
            
            // Update or Nullify:
            
            $code = isset($matches[1]) ? $matches[1] : null;
            
        }
        
        // Answer Code:
        
        return $code;
    }
}
