<?php

/**
 * An extension of the LeftAndMain extension class to load the Google Maps API for the CMS.
 */
class GoogleLeftAndMainExtension extends LeftAndMainExtension
{
    /**
     * Extension init() method, called by LeftAndMain init() method.
     */
    public function init()
    {
        // Load Google Maps API:
        
        GoogleAPI::load_maps_api();
    }
}
