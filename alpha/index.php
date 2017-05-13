<?php



/*

FEATURES 

Breakdown information with official delay time, affected stations (including specific stations to be bypassed), line direction and security incident capturing for both SMRT and SBS lines


Improvements to be made

- LRT integration
- Live testing
- Track work and maintenance support
- Natural language processing from tweets to determine actual delay time

Tests
    - Tested on Tues Apr 18, May 03 and fully functional
    
Known vulnerabilities
- Will not work if SBS Transit changes phrasing from NE03 to NE3 or NE 3 or NE 03
- DT04 Hume is a ghost MRT station that is not in use but will still be included

*/




header('Content-Type: application/json');

include 'interpretation.php';

print_r(json_encode($completetable, JSON_PRETTY_PRINT));

?>
