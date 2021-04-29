<?php

/**
 * An extension of the SilverWare icon link class for Google+ icon links.
 */
class GooglePlusIconLink extends SilverWareIconLink
{
    private static $singular_name = "Google+ Icon";
    private static $plural_name   = "Google+ Icons";
    
    private static $defaults = array(
        'Name' => 'Google+',
        'FontIcon' => 'fa-google-plus',
        'IconColor' => 'ffffff',
        'IconBackgroundColor' => 'db4437',
        'OpenInNewTab' => 1,
        'LinkURL' => 'https://plus.google.com/'
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
        
        // Modify Field Objects:
        
        $fields->removeByName('LinkPageID');
        
        // Answer Field Objects:
        
        return $fields;
    }
}
