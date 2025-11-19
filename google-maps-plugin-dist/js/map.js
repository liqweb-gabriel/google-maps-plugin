/**
 * Initializes and displays the map.
 * This function is called by the Google Maps API loader script.
 */
function initMap() {
  // Data is passed from PHP via wp_localize_script
  const mode = map_data.mode;
  const locations = map_data.locations;

  if (mode === "geolocation") {
    // Future Geolocation Feature: For now, just shows a default map.
    const map = new google.maps.Map(document.getElementById("map"), {
      zoom: 12,
      center: { lat: 43.6532, lng: -79.3832 }, // Default center for geolocation mode
      fullscreenControl: false,
      streetViewControl: false,
    });

    // You can add geolocation logic here in the future.
    // For example, using navigator.geolocation.getCurrentPosition(...)
  } else {
    // Default mode: Show predefined locations
    const isMobile = window.innerWidth < 768;
    const iconSize = isMobile ? 32 : 64;
    const iconAnchor = iconSize / 2;
    const iconAnchorPoint = new google.maps.Point(iconAnchor, iconAnchor);

    const map = new google.maps.Map(document.getElementById("map"), {
      zoom: 15,
      center: { lat: 43.6532, lng: -79.3832 }, // Starting center
      fullscreenControl: false,
      streetViewControl: false,
      styles: [
        {
          featureType: "poi",
          stylers: [{ visibility: "off" }],
        },
      ],
    });

    const bounds = new google.maps.LatLngBounds();
    const infoWindow = new google.maps.InfoWindow();

    locations.forEach(function (location) {
      const icon_url = isMobile ? location.icon_mobile : location.icon_desktop;

      const customIcon = {
        url: icon_url,
        size: new google.maps.Size(iconSize, iconSize),
        scaledSize: new google.maps.Size(iconSize, iconSize),
        origin: new google.maps.Point(0, 0),
        anchor: iconAnchorPoint,
      };

      const marker = new google.maps.Marker({
        position: location.position,
        map: map,
        title: location.title,
        icon: customIcon,
      });

      bounds.extend(location.position);

      marker.addListener("click", function () {
        infoWindow.setContent(
          '<div class="text-gray-800">' +
            '<h3 class="font-bold text-lg">' +
            location.title +
            "</h3>" +
            "<p>" +
            location.address +
            "</p>" +
            "</div>"
        );
        infoWindow.open(map, marker);
      });
    });

    map.fitBounds(bounds);
  }
}

// Global function reference required by the Google Maps API loader
window.initMap = initMap;
