(function($){
    
    $.entwine('ss', function($){
        
        $('div.GoogleMapsComponent .geocode-field').entwine({
            
            onmatch: function() {
                
                this._super();
                
                var self = this;
                
                self.find('.geocode-spinner').hide();
                
            },
            
            'from .geocode-button': {
                
                onclick: function(e) {
                    
                    e.preventDefault();
                    
                    var self = this;
                    
                    self.find('.geocode-spinner').show();
                    
                    var address = self.siblings().find('textarea.geocode-address').val();
                    
                    if (address) {
                        
                        GMaps.geocode({
                            
                            address: address,
                            
                            callback: function(results, status) {
                                
                                if (status == 'OK') {
                                    
                                    var location = results[0].geometry.location;
                                    
                                    var lat = location.lat();
                                    var lng = location.lng();
                                    
                                    self.find('.geocode-lat').val(lat.toFixed(6));
                                    self.find('.geocode-lng').val(lng.toFixed(6));
                                    
                                } else {
                                    
                                    alert(
                                        ss.i18n._t(
                                        'GoogleMapsComponent.UNABLETOGEOCODE',
                                        'Unable to geocode address.'
                                        )
                                    );
                                    
                                }
                                
                                self.find('.geocode-spinner').hide();
                                
                            }
                        });
                        
                    } else {
                        
                        alert(ss.i18n._t('GoogleMapsComponent.ADDRESSEMPTY', 'Please enter an address.'));
                        
                        self.find('.geocode-spinner').hide();
                        
                    }
                    
                    return false;
                    
                }
                
            },
            
            'from .geocode-button-clear': {
                
                onclick: function(e) {
                    
                    e.preventDefault();
                    
                    var self = this;
                    
                    self.siblings().find('.geocode-address').val('');
                    
                    return false;
                    
                }
                
            }
            
        });
        
    });
    
}(jQuery));