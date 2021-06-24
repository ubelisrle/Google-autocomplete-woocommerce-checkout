<?php 

// BILLING ADDRESS AUTOCOMPLETE

add_action( 'wp_enqueue_scripts', 'my_enqueue_scripts');

function my_enqueue_scripts() {
     //load script only on checkout
    if(function_exists('is_checkout') && is_checkout()){
        $api_key = 'YOUR-GOOGLE-API-KEY';
        $url = "https://maps.googleapis.com/maps/api/js?key=$api_key&libraries=places";
        wp_enqueue_script( 'google-places-js', $url );
    }

}


// BILLING ADDRESS AUTOCOMPLETE JS

function autocomplete_footer_js_checkout() {
     //load script only on checkout
     if (function_exists('is_checkout') && is_checkout()) { ?>
     <script>
     jQuery(function($) {     

          //Attach the autocomplete to the DOM element
          var billing_autocomplete = new google.maps.places.Autocomplete($('#billing_address_1')[0], 
          {
          //Define what information we want back from the API          
          componentRestrictions: { country: "rs" },
          fields: ["address_components", "geometry"],
          types: ["address"],
          strictBounds: true,        
          });          

          //Define a handler which fires when an address is chosen from the autocomplete
          billing_autocomplete.addListener('place_changed', function() {

          var place = billing_autocomplete.getPlace();
          var street_number = '';
          var street_name = '';
          var city = '';
          var suburb = '';
          for (var component of place.address_components) {
               var componentType = component.types[0];

               switch (componentType) {
                    case "street_number": {
                    street_number = component.long_name;
                    break;
                    }
                    case "route": {
                    street_name = component.long_name;
                    break;
                    }
                    case "locality": {
                    city = component.long_name;
                    break;
                    }
                    case "sublocality_level_1": {                    
                    suburb = component.long_name;
                    break;
                    }
               }
          }
          
          console.log(place.address_components);

          $('#billing_address_1').val(street_name + ' ' + street_number);
          $('#billing_city').val(city + ', ' + suburb);
          });
     });
     </script>
     <?php
     }
}
add_action( 'wp_footer', 'autocomplete_footer_js_checkout');
