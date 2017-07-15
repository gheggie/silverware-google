<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\Google\Buttons
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-google
 */

namespace SilverWare\Google\Buttons;

use SilverStripe\Forms\DropdownField;
use SilverWare\Forms\FieldSection;
use SilverWare\Social\Model\SharingButton;

/**
 * An extension of the sharing button class for a Google sharing button.
 *
 * @package SilverWare\Google\Buttons
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2017 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-google
 */
class GoogleSharingButton extends SharingButton
{
    /**
     * Define annotation constants.
     */
    const ANNOTATION_NONE     = 'none';
    const ANNOTATION_BUBBLE   = 'bubble';
    const ANNOTATION_INLINE   = 'inline';
    const ANNOTATION_VERTICAL = 'vertical-bubble';
    
    /**
     * Define size constants.
     */
    const SIZE_SMALL  = 'small';
    const SIZE_MEDIUM = 'medium';
    const SIZE_LARGE  = 'large';
    
    /**
     * Human-readable singular name.
     *
     * @var string
     * @config
     */
    private static $singular_name = 'Google Sharing Button';
    
    /**
     * Human-readable plural name.
     *
     * @var string
     * @config
     */
    private static $plural_name = 'Google Sharing Buttons';
    
    /**
     * Description of this object.
     *
     * @var string
     * @config
     */
    private static $description = 'A sharing button to share the current page via Google';
    
    /**
     * Defines an ancestor class to hide from the admin interface.
     *
     * @var string
     * @config
     */
    private static $hide_ancestor = SharingButton::class;
    
    /**
     * Maps field names to field types for this object.
     *
     * @var array
     * @config
     */
    private static $db = [
        'ButtonSize' => 'Varchar(16)',
        'ButtonAnnotation' => 'Varchar(32)'
    ];
    
    /**
     * Defines the default values for the fields of this object.
     *
     * @var array
     * @config
     */
    private static $defaults = [
        'ButtonSize' => 'medium',
        'ButtonAnnotation' => 'bubble'
    ];
    
    /**
     * Maps field and method names to the class names of casting objects.
     *
     * @var array
     * @config
     */
    private static $casting = [
        'ButtonAttributesHTML' => 'HTMLFragment'
    ];
    
    /**
     * Answers a list of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Create Style Fields:
        
        $fields->addFieldToTab(
            'Root.Style',
            FieldSection::create(
                'ButtonStyle',
                $this->fieldLabel('ButtonStyle'),
                [
                    DropdownField::create(
                        'ButtonSize',
                        $this->fieldLabel('ButtonSize'),
                        $this->getButtonSizeOptions()
                    ),
                    DropdownField::create(
                        'ButtonAnnotation',
                        $this->fieldLabel('ButtonAnnotation'),
                        $this->getButtonAnnotationOptions()
                    )
                ]
            )
        );
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers the labels for the fields of the receiver.
     *
     * @param boolean $includerelations Include labels for relations.
     *
     * @return array
     */
    public function fieldLabels($includerelations = true)
    {
        // Obtain Field Labels (from parent):
        
        $labels = parent::fieldLabels($includerelations);
        
        // Define Field Labels:
        
        $labels['ButtonSize'] = _t(__CLASS__ . '.BUTTONSIZE', 'Button size');
        $labels['ButtonAnnotation'] = _t(__CLASS__ . '.BUTTONANNOTATION', 'Button annotation');
        $labels['ButtonStyle'] = _t(__CLASS__ . '.BUTTON', 'Button');
        
        // Answer Field Labels:
        
        return $labels;
    }
    
    /**
     * Answers an array of HTML tag attributes for the button.
     *
     * @return array
     */
    public function getButtonAttributes()
    {
        $attributes = [
            'class' => $this->ButtonClass,
            'data-action' => 'share',
            'data-annotation' => $this->ButtonAnnotation
        ];
        
        if ($this->ButtonAnnotation != self::ANNOTATION_VERTICAL) {
            $attributes['data-height'] = $this->ButtonHeight;
        }
        
        $this->extend('updateButtonAttributes', $attributes);
        
        return $attributes;
    }
    
    /**
     * Answers the HTML tag attributes for the button as a string.
     *
     * @return string
     */
    public function getButtonAttributesHTML()
    {
        return $this->getAttributesHTML($this->getButtonAttributes());
    }
    
    /**
     * Answers an array of button class names for the HTML template.
     *
     * @return array
     */
    public function getButtonClassNames()
    {
        $classes = ['g-plus'];
        
        $this->extend('updateButtonClassNames', $classes);
        
        return $classes;
    }
    
    /**
     * Answers the height of the button according to the size setting.
     *
     * @return integer
     */
    public function getButtonHeight()
    {
        switch ($this->ButtonSize) {
            case self::SIZE_SMALL:
                return 15;
            case self::SIZE_MEDIUM:
                return 20;
            case self::SIZE_LARGE:
                return 24;
        }
    }
    
    /**
     * Answers an array of options for the button size field.
     *
     * @return array
     */
    public function getButtonSizeOptions()
    {
        return [
            self::SIZE_SMALL  => _t(__CLASS__ . '.SMALL', 'Small'),
            self::SIZE_MEDIUM => _t(__CLASS__ . '.MEDIUM', 'Medium'),
            self::SIZE_LARGE  => _t(__CLASS__ . '.LARGE', 'Large')
        ];
    }
    
    /**
     * Answers an array of options for the button annotation field.
     *
     * @return array
     */
    public function getButtonAnnotationOptions()
    {
        return [
            self::ANNOTATION_NONE     => _t(__CLASS__ . '.NONE', 'None'),
            self::ANNOTATION_BUBBLE   => _t(__CLASS__ . '.BUBBLE', 'Bubble'),
            self::ANNOTATION_INLINE   => _t(__CLASS__ . '.INLINE', 'Inline'),
            self::ANNOTATION_VERTICAL => _t(__CLASS__ . '.VERTICALBUBBLE', 'Vertical Bubble')
        ];
    }
}
