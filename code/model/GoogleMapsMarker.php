<?php

/**
 * An extension of the data object class for a Google Maps marker.
 */
class GoogleMapsMarker extends DataObject
{
    private static $singular_name = "Marker";
    private static $plural_name   = "Markers";
    
    private static $db = array(
        'Title' => 'Varchar(128)',
        'Content' => 'HTMLText',
        'Latitude' => 'Decimal(9,6)',
        'Longitude' => 'Decimal(9,6)',
        'HideInfoWindow' => 'Boolean'
    );
    
    private static $defaults = array(
        'HideInfoWindow' => 0
    );
    
    private static $summary_fields = array(
        'Title' => 'Title',
        'Latitude' => 'Latitude',
        'Longitude' => 'Longitude'
    );
    
    /**
     * Answers a collection of field objects for the CMS interface.
     *
     * @todo Add fields for adding of custom markers to Google Maps components.
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Create Field Tab Set:
        
        $fields = FieldList::create(TabSet::create('Root'));
        
        // Create Field Objects:
        
        $fields->addFieldsToTab(
            'Root.Main',
            array(
                
            )
        );
        
        // Extend Field Objects:
        
        $this->extend('updateCMSFields', $fields);
        
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
                'Title',
                'Latitude',
                'Longitude'
            )
        );
    }
    
    /**
     * Converts the receiver to a map representation.
     *
     * @return array
     */
    public function toMap()
    {
        return array(
            'title' => $this->Title,
            'content' => $this->Content,
            'latitude' => $this->Latitude,
            'longitude' => $this->Longitude,
            'showInfoWindow' => $this->InfoWindowShown()
        );
    }
    
    /**
     * Answers true if the info window is to be shown.
     *
     * @return boolean
     */
    public function InfoWindowShown()
    {
        return ($this->Content && !$this->HideInfoWindow);
    }
}
