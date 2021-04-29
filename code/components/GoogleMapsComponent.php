<?php

/**
 * An extension of the base component class for a Google Maps component.
 */
class GoogleMapsComponent extends BaseComponent
{
    private static $singular_name = "Map Component";
    private static $plural_name   = "Map Components";
    
    private static $description = "A component for showing a map from Google Maps";
    
    private static $icon = "silverware-google/images/icons/GoogleMapsComponent.png";
    
    private static $hide_ancestor = "BaseComponent";
    
    private static $allowed_children = "none";
    
    private static $db = array(
        'Zoom' => 'Int',
        'MapType' => 'Varchar(16)',
        'Address' => 'Text',
        'Latitude' => 'Decimal(9,6)',
        'Longitude' => 'Decimal(9,6)',
        'UseLocationMarker' => 'Boolean',
        'MarkerTitle' => 'Varchar(128)',
        'MarkerContent' => 'HTMLText',
        'MarkerHideInfoWindow' => 'Boolean'
    );
    
    private static $defaults = array(
        'Zoom' => 8,
        'MapType' => 'roadmap',
        'UseLocationMarker' => 0,
        'MarkerHideInfoWindow' => 0
    );
    
    private static $required_js = array(
        'silverware-google/thirdparty/gmaps/gmaps.min.js',
        'silverware-google/javascript/gmaps/gmaps.init.js'
    );
    
    private static $required_css = array(
        'silverware-google/css/google-maps-component.css'
    );
    
    /**
     * Answers a collection of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Add Status Message (if defined):
        
        $fields->addStatusMessage(GoogleConfig::get_status_message());
        
        // Create Main Fields:
        
        $fields->addFieldsToTab(
            'Root.Main',
            array(
                TextareaField::create(
                    'Address',
                    _t('GoogleMapsComponent.ADDRESS', 'Address')
                )->addExtraClass('geocode-address'),
                FieldGroup::create(
                    TextField::create(
                        'Latitude',
                        ''
                    )->addExtraClass('geocode-lat')->setAttribute(
                        'placeholder',
                        _t('GoogleMapsComponent.LATITUDE', 'Latitude')
                    ),
                    TextField::create(
                        'Longitude',
                        ''
                    )->addExtraClass('geocode-lng')->setAttribute(
                        'placeholder',
                        _t('GoogleMapsComponent.LONGITUDE', 'Longitude')
                    ),
                    FormAction::create(
                        'doGeocode',
                        ''
                    )->addExtraClass('geocode-button')->setUseButtonTag(true)->setButtonContent(
                        _t('GoogleMapsComponent.LOOKUP', 'Lookup')
                    ),
                    FormAction::create(
                        'doGeocodeClear',
                        ''
                    )->addExtraClass('geocode-button-clear')->setUseButtonTag(true)->setButtonContent(
                        _t('GoogleMapsComponent.CLEAR', 'Clear')
                    ),
                    LiteralField::create(
                        'GeocodeSpinner',
                        '<div class="geocode-spinner"><i class="fa fa-spinner fa-pulse"></i></div>'
                    )
                )->setTitle('Coordinates')->addExtraClass('geocode-field'),
                ToggleCompositeField::create(
                    'LocationMarkerToggle',
                    _t('GoogleMapsComponent.LOCATIONMARKER', 'Location Marker'),
                    array(
                        CheckboxField::create(
                            'UseLocationMarker',
                            _t('GoogleMapsComponent.ADDMARKERFORLOCATIOn', 'Add marker for location')
                        )->addExtraClass('toggle')->setAttribute('data-toggle', 'marker'),
                        $marker_title = TextField::create(
                            'MarkerTitle',
                            _t('GoogleMapsComponent.MARKERTITLE', 'Marker title')
                        )->addExtraClass('marker')->setRightTitle(
                            _t(
                                'GoogleMapsComponent.MARKERTITLERIGHTTITLE',
                                'Optional. If empty, the marker will use the component title.'
                            )
                        ),
                        $marker_content = HtmlEditorField::create(
                            'MarkerContent',
                            _t('GoogleMapsComponent.MARKERCONTENT', 'Marker content')
                        )->addExtraClass('marker')->setRows(20)->setRightTitle(
                            _t(
                                'GoogleMapsComponent.MARKERCONTENTRIGHTTITLE',
                                'Optional. If empty, the marker will use the address above.'
                            )
                        ),
                        CheckboxField::create(
                            'MarkerHideInfoWindow',
                            _t('GoogleMapsComponent.HIDEINFOWINDOWFORMARKER', 'Hide info window for marker')
                        )
                    )
                )
            )
        );
        
        // Update Field Objects:
        
        if (!$this->UseLocationMarker) {
            $marker_title->addExtraClass('hidden');
            $marker_content->addExtraClass('hidden');
        }
        
        // Create Options Fields:
        
        $fields->addFieldToTab(
            'Root.Options',
            ToggleCompositeField::create(
                'GoogleMapsComponentOptions',
                $this->i18n_singular_name(),
                array(
                    DropdownField::create(
                        'MapType',
                        _t('GoogleMapsComponent.MAPTYPE', 'Map type'),
                        $this->config()->map_types
                    ),
                    DropdownField::create(
                        'Zoom',
                        _t('GoogleMapsComponent.ZOOM', 'Zoom'),
                        array_combine(range(1, 20), range(1, 20))
                    )
                )
            )
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers a validator for the CMS interface.
     *
     * @return RequiredFields
     */
    public function getCMSValidator()
    {
        return RequiredFields::create(
            array(
                'Latitude',
                'Longitude'
            )
        );
    }
    
    /**
     * Answers a unique map ID for the HTML template.
     *
     * @return string
     */
    public function getMapID()
    {
        return $this->getHTMLID() . "_Map";
    }
    
    /**
     * Answers a string of class names for the map element.
     *
     * @return string
     */
    public function getMapClass()
    {
        return implode(' ', $this->getMapClassNames());
    }
    
    /**
     * Answers a string of class names for the wrapper element.
     *
     * @return string
     */
    public function getWrapperClass()
    {
        return implode(' ', $this->getWrapperClassNames());
    }
    
    /**
     * Answers an array of class names for the wrapper element.
     *
     * @return array
     */
    public function getWrapperClassNames()
    {
        $classes = array('google-map-wrapper');
        
        return $classes;
    }
    
    /**
     * Answers an array of class names for the map element.
     *
     * @return array
     */
    public function getMapClassNames()
    {
        $classes = array('google-map');
        
        return $classes;
    }
    
    /**
     * Returns the data attributes HTML for the map element.
     *
     * @return string
     */
    public function getDataAttributesHTML()
    {
        // Create Data Array:
        
        $data = array();
        
        // Define Data Array:
        
        $data['zoom'] = $this->Zoom;
        $data['map-type'] = $this->MapType;
        $data['latitude'] = $this->Latitude;
        $data['longitude'] = $this->Longitude;
        $data['markers'] = $this->MarkersJSON();
        
        // Answer Data Array as HTML Attributes:
        
        return $this->getAttributesHTML($data, 'data');
    }
    
    /**
     * Loads the CSS and scripts required by the receiver.
     */
    public function getRequirements()
    {
        // Load Parent Requirements:
        
        parent::getRequirements();
        
        // Load Google Maps API:
        
        GoogleAPI::load_maps_api();
    }
    
    /**
     * Answers the title for the location marker.
     *
     * @return string
     */
    public function getLocationMarkerTitle()
    {
        return $this->MarkerTitle ? $this->MarkerTitle : $this->Title;
    }
    
    /**
     * Answers the content for the location marker.
     *
     * @return string
     */
    public function getLocationMarkerContent()
    {
        if ($this->MarkerContent) {
            return $this->MarkerContent;
        } else {
            return nl2br($this->Address);
        }
    }
    
    /**
     * Answers the markers to display on the map.
     *
     * @return ArrayList
     */
    public function Markers()
    {
        $markers = ArrayList::create();
        
        if ($this->UseLocationMarker) {
            
            $marker = GoogleMapsMarker::create();
            
            $marker->Title     = $this->getLocationMarkerTitle();
            $marker->Content   = $this->getLocationMarkerContent();
            
            $marker->Latitude  = $this->Latitude;
            $marker->Longitude = $this->Longitude;
            
            $marker->HideInfoWindow = $this->MarkerHideInfoWindow;
            
            $markers->push($marker);
            
        }
        
        return $markers;
    }
    
    /**
     * Answers the markers as a JSON-encoded string.
     *
     * @return string
     */
    public function MarkersJSON()
    {
        $markers = array();
        
        foreach ($this->Markers() as $marker) {
            $markers[] = $marker->toMap();
        }
        
        return Convert::array2json($markers);
    }
    
    /**
     * Answers true if a Google API key has not been defined.
     *
     * @todo Better way of checking for "null" lat and long?
     * @return boolean
     */
    public function Disabled()
    {
        if (!GoogleAPI::get_api_key()) {
            return true;
        }
        
        if ($this->Latitude === '0.000000' && $this->Longitude === '0.000000') {
            return true;
        }
        
        return parent::Disabled();
    }
}

/**
 * An extension of the base component controller class for a Google Maps component.
 */
class GoogleMapsComponent_Controller extends BaseComponent_Controller
{
    /**
     * Defines the URLs handled by this controller.
     */
    private static $url_handlers = array(
        
    );
    
    /**
     * Defines the allowed actions for this controller.
     */
    private static $allowed_actions = array(
        
    );
    
    /**
     * Performs initialisation before any action is called on the receiver.
     */
    public function init()
    {
        // Initialise Parent:
        
        parent::init();
    }
}
