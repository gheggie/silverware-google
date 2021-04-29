<?php

/**
 * An extension of the extension class which allows controllers to make use of Google features.
 */
class GoogleControllerExtension extends Extension
{
    /**
     * Event handler method triggered after the controller has initialised.
     */
    public function onAfterInit()
    {
        // Initialise Google API Platform:
        
        Requirements::javascript(SILVERWARE_GOOGLE_DIR . '/javascript/api.init.js');
        
        // Obtain Site Configuration:
        
        if ($Config = SiteConfig::current_site_config()) {
            
            // Initialise Google Analytics Tracking:
            
            if ($Config->GoogleAnalyticsEnabled && $Config->GoogleAnalyticsTrackingID) {
                
                // Define Data Array:
                
                $data = ArrayData::create(array('TrackingID' => $Config->GoogleAnalyticsTrackingID));
                
                // Render Template:
                
                Requirements::customScript($data->renderWith('GoogleTracker'));
                
            }
            
        }
    }
}
