$(function(){
   
    // Initialise Variables:
    
    var gmaps = [];
    
    // Process Map Components within the page:
    
    $('div.google-map').each(function() {
        
        // Create Map Object:
        
        var map = new GMaps({
            el: '#' + this.id,
            lat: $(this).data('latitude'),
            lng: $(this).data('longitude'),
            zoom: parseInt($(this).data('zoom')),
            mapType: $(this).data('map-type'),
            idle: function() {
                this.lastCenter = this.getCenter();
            },
            resize: function() {
                this.setCenter(this.lastCenter);
                this.lastCenter = this.getCenter();
            }
        });
        
        // Add Map Markers:
        
        $.each($(this).data('markers'), function(key, value) {
            
            var marker = {
                lat: value.latitude,
                lng: value.longitude,
                title: value.title
            };
            
            if (value.showInfoWindow) {
                marker.infoWindow = {
                    content: value.content
                };
            }
            
            map.addMarker(marker);
            
        });
        
        // Store Map in Array:
        
        gmaps.push(map);
        
    });
    
    // Refresh Maps on Browser Resize:
    
    var resizingTimeOut;
    
    $(window).resize(function() {
      clearTimeout(resizingTimeOut);
      resizingTimeOut = setTimeout(function() {
        $(gmaps).each(function() {
          this.refresh();
        });
      }, 200);
    });
    
});
