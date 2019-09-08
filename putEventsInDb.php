<?php
include("config.php");
//$servername = "";
//$username = "";
//$password = "";

$db = new mysqli($servername, $username, $password, 'esperiadev');
date_default_timezone_set("Europe/Rome");
mb_internal_encoding("UTF-8");
$db->query('SET NAMES utf8');
set_time_limit(5000);

$searchStmt = $db->prepare("SELECT * FROM events WHERE id = ?");
$searchStmt->bind_param("i", $eventId);
$searchTimeStmt = $db->prepare("SELECT * FROM events_times WHERE id_event = ? AND start_time = ? AND end_time = ? AND date = ?");
$searchTimeStmt->bind_param("isss", $eventId, $timeStart, $timeEnd, $timeDate);
$eventStmt = $db->prepare("INSERT INTO events (id, title, place, phone, email, class, description, lat, lon) VALUES (?, ?, ?, '', '', ?, ?, ?, ?)");
$eventStmt->bind_param("issssss", $eventId, $eventTitle, $eventPlace, $eventClass, $eventDescription, $eventLat, $eventLon);
$timesStmt = $db->prepare("INSERT INTO events_times (id_event, start_time, end_time, all_day, date) VALUES (?, ?, ?, ?, ?)");
$timesStmt->bind_param("issss", $eventId, $timeStart, $timeEnd, $timeAllDay, $timeDate);


$json_comune = file_get_contents("https://cristianlivella.com/hackathon-2019/comune-bergamo-scrape.php");
$json_comune = json_decode($json_comune, true);
foreach ($json_comune['events'] AS $thisId => $thisEvent) {
    $eventId = $thisId;
    $searchStmt->execute();
    $result = $searchStmt->get_result();
    if ($result->num_rows>0) {
        continue;
    }
    $eventTitle = $thisEvent['title'];
    $eventPlace = $thisEvent['place'];
    $eventDescription = $thisEvent['description'];
    $eventClass = $thisEvent['class'];
    if (isset($thisEvent['latitude'])) {
        $eventLat = $thisEvent['latitude'];
    }
    else {
        $eventLat = "0";
    }
    if (isset($thisEvent['longitude'])) {
        $eventLon = $thisEvent['longitude'];
    }
    else {
        $eventLon = "0";
    }
    $eventStmt->execute();
}
foreach ($json_comune['occourrences'] AS $thisOccurrence) {
    $eventId = $thisOccurrence['eventId'];
    $dateTime = explode("T", $thisOccurrence['start']);
    $timeStart = $dateTime[1];
    $timeDate = $dateTime[0];
    $dateTime = explode("T", $thisOccurrence['end']);
    $timeEnd = "";
    if (isset($dateTime[1])) {
        $timeEnd = $dateTime[1];;
    }
    $timeAllDay = "";
    if (isset($thisOccurrence['allDay'])) {
        $timeAllDay = $thisOccurrence['allDay'];
    }
    $searchTimeStmt->execute();
    $result = $searchTimeStmt->get_result();
    if ($result->num_rows>0) {
        continue;
    }
    $timesStmt->execute();
}

$json_eventbrite = file_get_contents("https://cristianlivella.com/hackathon-2019/bergamo-eventbrite.php");
$json_eventbrite = json_decode($json_eventbrite, true);
foreach ($json_eventbrite['events'] AS $thisId => $thisEvent) {
    $eventId = $thisId/100;
    $searchStmt->execute();
    $result = $searchStmt->get_result();
    if ($result->num_rows>0) {
        continue;
    }
    $eventTitle = $thisEvent['title'];
    $eventPlace = $thisEvent['place'];
    $eventDescription = $thisEvent['description'];
    $eventClass = $thisEvent['class'];
    if (isset($thisEvent['latitude'])) {
        $eventLat = $thisEvent['latitude'];
    }
    else {
        $eventLat = "0";
    }
    if (isset($thisEvent['longitude'])) {
        $eventLon = $thisEvent['longitude'];
    }
    else {
        $eventLon = "0";
    }
    $eventStmt->execute();
}
foreach ($json_eventbrite['occourrences'] AS $thisOccurrence) {
    $eventId = $thisOccurrence['eventId'];
    $dateTime = explode("T", $thisOccurrence['start']);
    $timeStart = $dateTime[1];
    $timeDate = $dateTime[0];
    $dateTime = explode("T", $thisOccurrence['end']);
    $timeEnd = $dateTime[1];
    $timeAllDay = "";
    if (isset($thisOccurrence['allDay'])) {
        $timeAllDay = $thisOccurrence['allDay'];
    }
    $searchTimeStmt->execute();
    $result = $searchTimeStmt->get_result();
    if ($result->num_rows>0) {
        continue;
    }
    $timesStmt->execute();
}

//$stmt = $db->prepare("SELECT * FROM events");
//$stmt->execute();
//$result = $stmt->get_result();
//while ($resultArray = $result->fetch_assoc()) {
//    print_r($resultArray);
//}



?>
