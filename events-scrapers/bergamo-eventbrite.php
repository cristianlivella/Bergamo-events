<?php
$events = array();
$occurrences = array();

$categories_english = array();
$categories_italian = array();

$token = "6FXUQ4HJMNDIHDDICDRG";
// get your token here: https://www.eventbrite.com/platform/api-keys

$json = file_get_contents("https://www.eventbriteapi.com/v3/events/search/?q=Bergamo&expand=venue,category&token=".$token);
$json = json_decode($json, true);
foreach ($json['events'] AS $thisEvent) {
    $events[$thisEvent['id']]['title'] = $thisEvent['name']['text'];
    $events[$thisEvent['id']]['description'] = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($thisEvent['summary']))));
    $events[$thisEvent['id']]['place'] = $thisEvent['venue']['address']['address_1'];
    if (!empty($thisEvent['venue']['address']['address_2'])) {
        $events[$thisEvent['id']]['place'] .= " " . $thisEvent['venue']['address']['address_2'];
    }
    $events[$thisEvent['id']]['place'] .= " - " . $thisEvent['venue']['address']['city'];
    $events[$thisEvent['id']]['class'] = "";
    if (isset($thisEvent['category']['name_localized'])) {
        $events[$thisEvent['id']]['class'] = $thisEvent['category']['name_localized'];
    }
    $events[$thisEvent['id']]['latitude'] = $thisEvent['venue']['address']['latitude'];
    $events[$thisEvent['id']]['longitude'] = $thisEvent['venue']['address']['longitude'];
    $occurrence = array("eventId" => $thisEvent['id'], "start" => $thisEvent['start']['local'], "end" => $thisEvent['end']['local'], "allDay" => 0);
    $occurrences[] = $occurrence;
}

$json = array("events" => $events, "occourrences" => $occurrences);
echo json_encode($json);
