<?php

/**
 * Singleton class acting as a wrapper for the Google API platform.
 */
class GoogleAPI
{
    /**
     * @config
     * @var string
     */
    private static $api_key;
    
    /**
     * @config
     * @var string
     */
    private static $maps_api_endpoint = "//maps.googleapis.com/maps/api/js";
    
    /**
     * @config
     * @var array
     */
    private static $maps_libraries = array();
    
    /**
     * Answers the key for the Google API from either site config or YAML config.
     *
     * @return string
     */
    public static function get_api_key()
    {
        $key = trim(SiteConfig::current_site_config()->GoogleAPIKey);
        
        if (!$key) {
            $key = trim(Config::inst()->get(__CLASS__, 'api_key', Config::FIRST_SET));
        }
        
        return $key;
    }
    
    /**
     * Loads the Google Maps API.
     *
     * @param array $libraries
     */
    public static function load_maps_api($libraries = array())
    {
        // Obtain Libraries Args:
        
        if (!is_array($libraries) || func_num_args() > 1) {
            $libraries = func_get_args();
        }
        
        // Merge Config Libraries:
        
        $maps_libraries = array_unique(
            array_merge(
                $libraries,
                Config::inst()->get(__CLASS__, 'maps_libraries')
            )
        );
        
        // Insert API Script Tag:
        
        if ($URL = self::get_maps_api_url($maps_libraries)) {
            
            Requirements::insertHeadTags('<script src="' . $URL . '"></script>', 'GoogleMapsAPI');
            
        }
    }
    
    /**
     * Informs the Google Maps API to load the library with the specified name.
     *
     * @param string $name
     */
    public static function use_map_library($name)
    {
        $libraries = Config::inst()->get(__CLASS__, 'maps_libraries');
        
        if (!in_array($name, $libraries)) {
            $libraries[] = $name;
        }
        
        Config::inst()->update(__CLASS__, 'maps_libraries', $libraries);
    }
    
    /**
     * Answers the URL for the Google Maps API including the API key.
     *
     * @param array $libraries
     * @return string
     */
    public static function get_maps_api_url($libraries = array())
    {
        if ($key = self::get_api_key()) {
            
            $endpoint = Config::inst()->get('GoogleAPI', 'maps_api_endpoint');
            
            return $endpoint . "?" . http_build_query(self::get_maps_api_params($libraries));
            
        }
    }
    
    /**
     * Answers the parameters for the Google Maps API.
     *
     * @param array $libraries
     * @return array
     */
    public static function get_maps_api_params($libraries = array())
    {
        $params = array(
            'key' => self::get_api_key()
        );
        
        if ($libraries) {
            $params['libraries'] = implode(',', $libraries);
        }
        
        return $params;
    }
}
