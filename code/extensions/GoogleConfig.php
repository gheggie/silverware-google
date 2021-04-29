<?php

/**
 * An extension of the SilverWare services config class to add Google settings to site config.
 */
class GoogleConfig extends SilverWareServicesConfig
{
    private static $db = array(
        'GoogleAPIKey' => 'Varchar(128)',
        'GoogleAnalyticsEnabled' => 'Boolean',
        'GoogleAnalyticsTrackingID' => 'Varchar(64)',
        'GoogleSiteVerificationCode' => 'Varchar(64)'
    );
    
    /**
     * Answers an status message to display in the CMS interface.
     *
     * @return array
     */
    public static function get_status_message()
    {
        if (!GoogleAPI::get_api_key()) {
            
            return array(
                'text' => _t(
                    'GoogleConfig.APIKEYMISSINGMESSAGE',
                    'Google API key has not been entered into site configuration'
                ),
                'type' => 'warning',
                'icon' => 'fa-warning'
            );
            
        }
    }
    
    /**
     * Updates the CMS fields of the extended object.
     *
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        // Update Field Objects (from parent):
        
        parent::updateCMSFields($fields);
        
        // Create Google Tab:
        
        $fields->findOrMakeTab(
            'Root.SilverWare.Services.Google',
            _t('GoogleConfig.GOOGLE', 'Google')
        );
        
        // Create Field Objects:
        
        $fields->addFieldsToTab(
            'Root.SilverWare.Services.Google',
            array(
                ToggleCompositeField::create(
                    'GoogleAPIToggle',
                    _t('GoogleConfig.API', 'API'),
                    array(
                        TextField::create(
                            'GoogleAPIKey',
                            _t('GoogleConfig.GOOGLEAPIKEY', 'Google API Key')
                        )->setRightTitle(
                            _t(
                                'GoogleConfig.GOOGLEAPIKEYRIGHTTITLE',
                                'Create credentials using the Google API Manager and paste the API key here.'
                            )
                        )
                    )
                ),
                ToggleCompositeField::create(
                    'GoogleAnalyticsToggle',
                    _t('GoogleConfig.ANALYTICS', 'Analytics'),
                    array(
                        CheckboxField::create(
                            'GoogleAnalyticsEnabled',
                            _t('GoogleConfig.ENABLEGOOGLEANALYTICS', 'Enable Google Analytics')
                        ),
                        TextField::create(
                            'GoogleAnalyticsTrackingID',
                            _t('GoogleConfig.GOOGLEANALYTICSTRACKINGID', 'Google Analytics Tracking ID')
                        )
                    )
                ),
                ToggleCompositeField::create(
                    'GoogleSiteVerificationToggle',
                    _t('GoogleConfig.SITEVERIFICATION', 'Site Verification'),
                    array(
                        TextField::create(
                            'GoogleSiteVerificationCode',
                            _t('GoogleConfig.GOOGLESITEVERIFICATIONCODE', 'Google Site Verification Code')
                        )
                    )
                )
            )
        );
    }
}
