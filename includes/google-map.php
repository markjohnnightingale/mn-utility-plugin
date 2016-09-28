<?php
/** 
 * Google Maps plugin
 */

/** 
 * mn_init_google_map
 * @param  array  $args array of arguments for constructing Google Map
 * @return [type]       [description]
 */
function mn_init_google_map( $args = array() ) {
    $defaults = array(
            'container_id'      => '',
            'style'             => '',
            'api_key'           => '',
            'map_center_lat'    => '48.5840',
            'map_center_lng'   => '7.74696',
            'zoom'              => '15',
            'marker'            => array(),
        );
    $params = array_replace_recursive($defaults, $args);
    $required_args = array(
        'container_id',
        'api_key',
    );
    foreach ($required_args as $arg) {
        if (empty($params[$arg])) {
            echo "Warning - missing argument ". $arg ." for mn_init_google_map(). ";
            return false;
        }
    }
    ?>
    
    <script>
        var map;

        var mapStyle = <?php echo (!empty($params['style'])) ? $params['style'] : '[]' ;?>;
        function initMap() {
            var mapCenter = {lat: <?php echo $params['map_center_lat'];?>, lng: <?php echo $params['map_center_lng'];?>}
            map = new google.maps.Map(document.getElementById('<?php echo $params['container_id'];?>'), {
              center: mapCenter,
              zoom: <?php echo $params['zoom'];?>,
              styles: mapStyle,
              disableDefaultUI: true,
              scrollwheel: false
            });
            <?php if (!empty($params['marker'])) : ?>
                var marketLatLng
                var marker = new google.maps.Marker({
                    position: {lat: <?php echo $params['marker']['lat']; ?>, lng: <?php echo $params['marker']['lng']; ?>},
                    map: map,
                    title: '<?php echo $params['marker']['title']; ?>'
                  });
                <?php if (!empty($params['marker']['content'])) : ?>
                    var infoWindow = new google.maps.InfoWindow({
                        content: '<?php echo $params['marker']['content']; ?>'
                    });

                    marker.addListener('click', function() {
                        infoWindow.open(map, marker);
                    });
                <?php endif;?>

           <?php endif; ?>
        }
        /**
         * [{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"landscape.man_made","elementType":"geometry.fill","stylers":[{"color":"#e8edec"}]},{"featureType":"landscape.man_made","elementType":"geometry.stroke","stylers":[{"visibility":"on"}]},{"featureType":"landscape.man_made","elementType":"labels.text.fill","stylers":[{"visibility":"on"}]},{"featureType":"landscape.man_made","elementType":"labels.text.stroke","stylers":[{"visibility":"on"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.attraction","elementType":"geometry.fill","stylers":[{"visibility":"on"}]},{"featureType":"poi.attraction","elementType":"geometry.stroke","stylers":[{"visibility":"on"}]},{"featureType":"poi.attraction","elementType":"labels.text.fill","stylers":[{"visibility":"on"}]},{"featureType":"poi.attraction","elementType":"labels.text.stroke","stylers":[{"visibility":"on"}]},{"featureType":"poi.attraction","elementType":"labels.icon","stylers":[{"visibility":"on"}]},{"featureType":"poi.business","elementType":"geometry.fill","stylers":[{"color":"#f3fff9"},{"visibility":"on"}]},{"featureType":"poi.business","elementType":"geometry.stroke","stylers":[{"visibility":"on"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"transit.line","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#4a9d8c"}]},{"featureType":"transit.station.rail","elementType":"labels.icon","stylers":[{"visibility":"on"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#7dc4b6"},{"visibility":"on"}]}]
         */
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $params['api_key'];?>&callback=initMap&libraries=places" async defer></script> <?php 
} ?>