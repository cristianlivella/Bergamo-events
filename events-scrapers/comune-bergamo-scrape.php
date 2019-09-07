<?php
$lastUpdate = file_get_contents("comune-bergamo-scrape.last_update.txt");
if ($lastUpdate+3600>time()) {
    echo file_get_contents("comune-bergamo-scrape.cache.txt");
    die;
}

$events = array();
$occurrences = array();

$html = file_get_contents("https://www.comune.bergamo.it/eventi-calendario");
$html = explode('<script type="application/json" data-drupal-selector="drupal-settings-json">', $html);
$html = explode('</script>', $html[1]);
$data = json_decode($html[0], true);
$data = $data['simple_recurring_calendar'];

//print_r($data);

foreach ($data AS $thisData) {
    $eventId = $thisData['id'];
    if (isset($events[$eventId])) {
        $occurrence = array("eventId" => $eventId, "start" => $thisData['start']);
        if (strtotime($thisData['start'])<time()-60*60*24 OR strtotime($thisData['start'])>time()+60*60*24*30*12) {
            continue;
        }
        if (isset($thisData['end'])) {
            $occurrence['end'] = $thisData['end'];
        }
        if (isset($thisData['allDay']) AND $thisData['allDay']==1) {
            $occurrence['allDay'] = $thisData['allDay'];
        }
        else {
            $occurrence['allDay'] = 0;
        }
        $occurrences[] = $occurrence;
    }
    else {
        $occurrence = array("eventId" => $eventId, "start" => $thisData['start']);
        if (strtotime($thisData['start'])<time()-60*60*24 OR strtotime($thisData['start'])>time()+60*60*24*30*12) {
            continue;
        }
        if (isset($thisData['end'])) {
            $occurrence['end'] = $thisData['end'];
        }
        if (isset($thisData['allDay']) AND $thisData['allDay']==1) {
            $occurrence['allDay'] = $thisData['allDay'];
        }
        else {
            $occurrence['allDay'] = 0;
        }
        $events[$eventId]['title'] = $thisData['title'];
        $events[$eventId]['description'] = "";
        $events[$eventId]['place'] = "";
        $events[$eventId]['class'] = $thisData['className'];
        $occurrences[] = $occurrence;
    }
}

foreach($events AS $thisId => $thisEvent) {
    $html = file_get_contents("https://www.comune.bergamo.it/node/" . $thisId);
    $descrizione = "";
    $luogo = "";
    try {
        $temp = explode('<div class="clearfix text-formatted field field--name-body field--type-text-with-summary field--label-hidden field__item Prose">', $html);
        $temp = explode("</div>", $temp[1])[0];
        $temp = explode("<blockquote>", $temp);
        $tempString = $temp[0];
        if (isset($temp[1])) {
            $temp = explode("</blockquote>", $temp[1]);
            $tempString .= $temp[1];
        }
        $descrizione = $tempString;
    }
    catch (exception $e) {};
    try {
        $temp = explode('<div class="field field--name-field-stu-indirizzo field--type-string field--label-hidden field__item">', $html);
        $luogo .= explode("</div>", $temp[1])[0];
    }
    catch (exception $e) {};
    try {
        $temp = explode('<div class="field field--name-field-stu-comune field--type-string field--label-hidden field__item">', $html);
        if (strlen($luogo)>0) {
            $luogo .= " - ";
        }
        $luogo .= explode("</div>", $temp[1])[0];
    }
    catch (exception $e) {};
    $events[$thisId]['description'] = str_replace("&#039;", "'", trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($descrizione)))));
    $events[$thisId]['place'] = str_replace("&#039;", "'", html_entity_decode(strip_tags($luogo)));
}

$json = array("events" => $events, "occourrences" => $occurrences);

//print_r($json);
echo json_encode($json);
file_put_contents("comune-bergamo-scrape.cache.txt", json_encode($json));
file_put_contents("comune-bergamo-scrape.last_update.txt", time());
