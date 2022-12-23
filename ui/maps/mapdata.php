<?php // document requested by page rendered to generate map data 
require './../../utils.php';?>
var simplemaps_worldmap_mapdata={
main_settings: {
//General settings
width: "responsive", //or 'responsive'
background_color: "#FFFFFF",
background_transparent: "yes",
popups: "detect",

//State defaults
state_description: "State description",
state_color: "#88A4BC",
state_hover_color: "#3B729F",
state_url: "https://jaumelopez.dev",
border_size: 1.5,
border_color: "#ffffff",
all_states_inactive: "no",
all_states_zoomable: "no",

//Location defaults
location_description: "Location description",
location_color: "#FF0067",
location_opacity: 0.8,
location_hover_opacity: 1,
location_url: "",
location_size: 25,
location_type: "square",
location_border_color: "#FFFFFF",
location_border: 2,
location_hover_border: 2.5,
all_locations_inactive: "no",
all_locations_hidden: "no",

//Label defaults
label_color: "#ffffff",
label_hover_color: "#ffffff",
label_size: 22,
label_font: "Arial",
hide_labels: "no",

//Zoom settings
manual_zoom: "yes",
back_image: "no",
arrow_box: "no",
navigation_size: "40",
navigation_color: "#f7f7f7",
navigation_border_color: "#636363",
initial_back: "no",
initial_zoom: -1,
initial_zoom_solo: "no",
region_opacity: 1,
region_hover_opacity: 0.6,
zoom_out_incrementally: "yes",
zoom_percentage: 0.99,
zoom_time: 0.5,

//Popup settings
popup_color: "white",
popup_opacity: 0.9,
popup_shadow: 1,
popup_corners: 5,
popup_font: "12px/1.5 Verdana, Arial, Helvetica, sans-serif",
popup_nocss: "no",

//Advanced settings
div: "map",
auto_load: "yes",
rotate: "0",
url_new_tab: "yes",
images_directory: "default",
import_labels: "no",
fade_time: 0.1,
link_text: "Buy country"
},
state_specific: {
<?php
$acronyms = ["AF", "AO", "AL", "AE", "AR", "AM", "AU", "AT", "AZ", "BI", "BE", "BJ", "BF", "BD", "BG", "BH", "BA", "BY", "BZ", "BO", "BR", "BN", "BT", "BW", "CF", "CA", "CH", "CL", "CN", "CI", "CM", "CD", "CG", "CO", "CR", "CU", "CZ", "DE", "DJ", "DK", "DO", "DZ", "EC", "EG", "ER", "EE", "ET", "FI", "FJ", "GA", "GB", "GE", "GH", "GN", "GM", "GW", "GQ", "GR", "GL", "GT", "GY", "HN", "HR", "HT", "HU", "ID", "IN", "IE", "IR", "IQ", "IS", "IL", "IT", "JM", "JO", "JP", "KZ", "KE", "KG", "KH", "KR", "XK", "KW", "LA", "LB", "LR", "LY", "LK", "LS", "LT", "LU", "LV", "MA", "MD", "MG", "MX", "MK", "ML", "MM", "ME", "MN", "MZ", "MR", "MW", "MY", "NA", "NE", "NG", "NI", "NL", "NO", "NP", "NZ", "OM", "PK", "PA", "PE", "PH", "PG", "PL", "KP", "PT", "PY", "PS", "QA", "RO", "RU", "RW", "EH", "SA", "SD", "SS", "SN", "SL", "SV", "RS", "SR", "SK", "SI", "SE", "SZ", "SY", "TD", "TG", "TH", "TJ", "TM", "TL", "TN", "TR", "TW", "TZ", "UG", "UA", "UY", "US", "UZ", "VE", "VN", "VU", "YE", "ZA", "ZM", "ZW", "SO", "GF", "FR", "ES", "AW", "AI", "AD", "AG", "BS", "BM", "BB", "KM", "CV", "KY", "DM", "FK", "FO", "GD", "HK", "KN", "LC", "LI", "MF", "MV", "MT", "MS", "MU", "NC", "NR", "PN", "PR", "PF", "SG", "SB", "ST", "SX", "SC", "TC", "TO", "TT", "VC", "VG", "VI", "CY", "RE", "YT", "MQ", "GP", "CW", "IC"];

/**
 * Representing this structure
 * IC: {
 * name: "Canary Islands (Spain)",
 * description: "",
 * color: "",
 * hover_color: "",
 * url: ""
 * },
 */
foreach ($acronyms as $acronym) { ?>
  <?php echo $acronym; ?>: {
    name: "<?php $cName = getCountryName($acronym); echo $cName; ?>",
    description: "<?php
                $result = isCountryOwned($acronym);
                $price = getCountryBasePrice($acronym);
                if ($result['bool']) {
                  echo "Owned by: " . getUserName($result['data']['ownerId']);
                } else {
                  echo "For sale.<br>Base Price: $".number_format($price, 0, ".",",");
                }
                echo "<br>Generates $".number_format(($price/1000),0, ".",",")."/daily";
                
                ?>",
    color: "<?php if ($result['bool']) {
            $color = getUserColor($result['data']['ownerId']);
            echo "#".$color ;
          } else {
            echo 'default';
          } ?>",
    hover_color: "<?php if ($result['bool']) {
    echo adjustBrightness($color, -0.4);
  } else {
    echo 'default';
  } ?>",
    url: "<?php if (!$result['bool']) {
    if (isset($_SERVER['HTTPS'])) {
      $extraS = "s";
    } else {
      $extraS = "";
    }
    // echo "http" . $extraS . "://" . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_FILENAME']), "", "/buyCountry.php?c=".$acronym);
    echo "javascript:confirmBuy('".$acronym."', '". $cName. "', ".$price.");";
  } ?>"},
<?php
}
?>
},
locations: {},
labels: {}
};
