<?php

include_once ('../base/Restaurant.php');
$r = new Restaurant();

$r->nom = "testnom";
$r->description = "testdescription";
$r->adresse = "testadresse";
$r->contact = "testcontact";
$r->id_theme = 1;

$r->insert();
echo "$r->id <br/>";

$liste = Restaurant::findAll();

foreach($liste as $key => $value) {
    echo "" . $value->nom . " ; " . $value->description  . " <br>";
}

$rr = Restaurant::findById($r->id);
var_dump($rr);

$rr->description = "une autre description";
$rr->update();
ver_dump($rr);

$rr->delete();
$liste2 = Restaurant::findAll();

foreach($liste2 as $key => $value) {
    echo "" . $value->nom . " ; " . $value->description  . " <br>";
}