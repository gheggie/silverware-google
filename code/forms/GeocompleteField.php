<?php

/**
 * An extension of the text field class for a Google Maps geocomplete field.
 */
class GeocompleteField extends TextField
{
    /**
     * @config
     * @var array
     */
    private static $default_config = array(
        'detailsAttribute' => 'name'
    );
    
    /**
     * @var array
     */
    protected $config;
    
    /**
     * @var array
     */
    protected $eventHandlers;
    
    /**
     * Answers the type of the receiver.
     *
     * @return string
     */
    public function Type()
    {
        return "geocomplete text";
    }
    
    /**
     * Constructs the object upon instantiation.
     *
     * @param string $name
     * @param string $title
     * @param string $value
     */
    public function __construct($name, $title = null, $value = null)
    {
        // Construct Parent:
        
        parent::__construct($name, $title, $value);
        
        // Construct Object:
        
        $this->config = $this->config()->default_config;
        $this->eventHandlers = array();
    }
    
    /**
     * Renders the field as HTML.
     * 
     * @param array $properties
     * @return HTMLText
     */
    public function Field($properties = array())
    {
        // Load Requirements:
        
        GoogleAPI::load_maps_api('places');
        
        Requirements::javascript(SILVERWARE_GOOGLE_DIR . '/thirdparty/geocomplete/geocomplete.min.js');
        
        Requirements::customScript($this->getCustomScript(), $this->id());
        
        // Show Error Message (if defined):
        
        if ($message = GoogleConfig::get_status_message()) {
            
            $this->setError(
                sprintf(
                    "<i class=\"fa fa-fw %s\"></i> %s",
                    $message['icon'],
                    $message['text']
                ),
                $message['type']
            );
            
        }
        
        // Render Field:
        
        return parent::Field($properties);
    }
    
    /**
     * Adds an event handler for the specified event.
     *
     * @return GeocompleteField
     */
    public function addEventHandler($event, $handler)
    {
        if (!isset($this->eventHandlers[$event])) {
            $this->eventHandlers[$event] = array();
        }
        
        $this->eventHandlers[$event][] = $handler;
        
        return $this;
    }
    
    /**
     * Defines a configuration setting with the given name and value.
     * 
     * @param string $name
     * @param mixed $val
     * @return GeocompleteField
     */
    public function setConfig($name, $val)
    {
        $this->config[$name] = $val;
        
        return $this;
    }
    
    /**
     * Answers either the configuation setting with the given name, or the entire configuration array.
     * 
     * @param string $name (optional)
     * @return mixed|array
     */
    public function getConfig($name = null)
    {
        if ($name) {
            return isset($this->config[$name]) ? $this->config[$name] : null;
        } else {
            return $this->config;
        }
    }
    
    /**
     * Answers the configuration JSON for the field.
     *
     * @return string
     */
    public function getConfigJSON()
    {
        return Convert::array2json($this->getConfig());
    }
    
    /**
     * Answers the custom script for the field.
     *
     * @return string
     */
    protected function getCustomScript()
    {
        $file = SILVERWARE_GOOGLE_DIR . '/javascript/geocomplete/geocomplete.init.js';
        
        $script = file_get_contents(Director::getAbsFile($file));
        
        $script = str_replace('$ID', $this->id(), $script);
        $script = str_replace('$Config', $this->getConfigJSON(), $script);
        $script = str_replace('$EventHandlers', $this->getEventHandlerScript(), $script);
        
        return $script;
    }
    
    /**
     * Answers the event handler script for the field.
     *
     * @return string
     */
    protected function getEventHandlerScript()
    {
        $script = null;
        
        foreach ($this->eventHandlers as $event => $handlers) {
            
            foreach ($handlers as $handler) {
                $script .= "\n.on('$event', $handler)";
            }
            
        }
        
        return $script;
    }
}
