(function ($, Drupal) {
  Drupal.behaviors.contactSection = {
    attach: function (context, settings) {
      if (settings.ss_location.contact) {
        var locationList = settings.ss_location.contact.locations;
        var icon = settings.ss_location.contact.icon;

        var mapSettings = {
          mapTypeControl: false,
          mapTypeId: google.maps.MapTypeId.ROADMAP,
          zoom: 5,
          zoomControlOptions: {style: google.maps.ZoomControlStyle.SMALL},
        };

        var GMap = new google.maps.Map(document.getElementById('contact-section-map'), mapSettings);

        // var GInfoBox = new InfoBox({
        //   content: '',
        //   boxClass: 'map-infobox',
        //   closeBoxMargin: '2px 2px 2px 2px',
        //   closeBoxURL: 'http://www.google.com/intl/en_us/mapfiles/close.gif',
        //   enableEventPropagation: true,
        //   pixelOffset: new google.maps.Size(35, -60),
        //   infoBoxClearance: new google.maps.Size(20, 50)
        // });
        var GOMS = new OverlappingMarkerSpiderfier(GMap, {
          markersWontMove: true,
          markersWontHide: true,
          keepSpiderfied: true,
          nearbyDistance: 20,
          legWeight: 8
        });

        // GOMS.addListener('click', function (Marker, Event) {
        //   GInfoBox.setContent(Marker.Info);
        //   GInfoBox.open(GMap, Marker);
        // });

        var GMapBounds = new google.maps.LatLngBounds();
        var locationsCount = 0;
        for (var key in locationList) {
          locationsCount++;
          var GMapMarker = new google.maps.Marker({
            map: GMap,
            position: new google.maps.LatLng(locationList[key].lat, locationList[key].lng),
            icon: icon
          });

          GOMS.addMarker(GMapMarker);

          GMapBounds.extend(new google.maps.LatLng(locationList[key].lat, locationList[key].lng));
        }

        if (locationsCount > 1) {
          GMap.fitBounds(GMapBounds);
        }
        else if (locationsCount == 1) {
          GMap.setCenter(GMapBounds.getCenter());
          GMap.setZoom(17);
        }

        google.maps.event.addDomListener(window, 'resize', function () {
          google.maps.event.trigger(GMap, 'resize');
          if (locationsCount > 1) {
            GMap.fitBounds(GMapBounds);
          }
          else if (locationsCount == 1) {
            GMap.setCenter(GMapBounds.getCenter());
            GMap.setZoom(17);
          }
        });
      }
      $('#contact .map-colapse-btn').click(function(e) {
        e.preventDefault();
        var $this = $(this, context);
        $this.closest('.map').toggleClass('full-width');
        setTimeout(function(){
          var Center = GMap.getCenter();
          google.maps.event.trigger(GMap, 'resize');
          GMap.setCenter(Center);
        }, 201);
        return false;
      });
    }
  };
})(jQuery, Drupal);
