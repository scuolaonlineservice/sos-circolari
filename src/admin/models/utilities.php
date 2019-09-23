<?php
function getAnnoScolastico() {
    $month = idate("m");
    $academic_year = idate("d");
    if ($academic_year < 31 && $month < 8) { return "" . (idate("Y") -1) . "/" . idate("Y"); } 
    else { return "" . idate("Y") . "/" . (idate("Y") + 1 ); }
}

function flat(array $elems, $key) {
    for ($i = 0; $i < count($elems); $i++) { $flat[$i] = $elems[$i][$key]; }
    return $flat;
}