let map;
let currentPosition;

function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: 48.58, lng: 7.75 },
    zoom: 11,
    disableDefaultUI: true,
  });

  marker = new google.maps.Marker({
    map,
  });

  map.addListener('click', (e) => {
    currentPosition = e.latLng.toJSON();
    marker.setPosition(e.latLng);
  });
}

document.getElementById('search').addEventListener("click", () => {
    if(currentPosition != null) {
        document.cookie = (`userLongitude=${currentPosition.lng}`);
        document.cookie = (`userLatitude=${currentPosition.lat}`);
        location.href = "/";
    } else {
        console.log("test");
    }
});