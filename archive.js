
/* enhancements

  Support for MRT station direction

*/
// Creates a service with Google Maps
const express = require('express');
const hbs = require('hbs');
const request = require('request');
const JSONP = require('node-jsonp');
const fs = require('fs');
const intersect = require('intersect');
var fullTextSearch = require('full-text-search');
var search = new fullTextSearch();

const port = process.env.PORT || 3000;

var app = express();


app.set('view engine', 'hbs');

app.use(express.static(__dirname + '/index'));

 var googleMapsClient = require('@google/maps').createClient({
   key: 'AIzaSyDrhFF24KoHXe-joRulwqQ26Edr8JizKa8'
 });

// Create a raw Google Map Directions request via query strings
 app.use('/', (req, res, next) => {
   app.locals.origin = req.param("origin");
   app.locals.destination = req.param("destination");

   googleMapsClient.directions({
        origin: app.locals.origin,
        destination: app.locals.destination,
        mode: 'transit',
        alternatives: true,
        /*
        transit_mode: 'bus',
        */
      }, function(err, response) {
    if (!err) {

      function fuck() {
        app.locals.alpha = response.json.routes;

        app.locals.beta = app.locals.alpha;

        // Filter directions by MRT breakdown

        /* The Google Maps Directions API information hierarchy

        0 (loop through)->legs (no loop through)->steps (loop through)->steps (no loop through, if transit mode is walking)
        0 (loop through)->legs (no loop through)->steps (loop through)->transit_details->line->vehicle->type


        */

        var raw = app.locals.alpha;


        for (var i = 0; i < raw.length; i++){
          var singleRaw = raw[i];

          var legs = singleRaw['legs'][0];
          var stepsArray = legs['steps'];

          for (var i = 0; i < stepsArray.length; i++) {
            var steps = stepsArray[i];

            if (typeof steps['transit_details'] !== 'undefined') {

              var transitDetails = steps['transit_details'];

              if (typeof transitDetails['line']['name'] !== 'undefined') {

                var mrtLineName = transitDetails['line']['name'];

                var departureStop = transitDetails['departure_stop']['name'];
                var arrivalStop = transitDetails['arrival_stop']['name'];

                // Import MRT breakdown information

                request('http://mrt-breakdown-api.000webhostapp.com/alpha/', function (error, response, body) {
                  if (!error ) {


                    var breakdownInfo = JSON.parse(body);

                    var breakdownInfo = breakdownInfo;

                    if (breakdownInfo[mrtLineName]['affected_stations'] !== null) {


                      var affectedStations = breakdownInfo[mrtLineName]['affected_stations'];


                     // Gets JSON data of MRT stations

                      request('http://mrt-breakdown-api.000webhostapp.com/alpha/mrtstations.json', function (err, res, bod) {
                        if (!err ) {
                          var mrtStations = JSON.parse(bod);
                          var fullStationsArray = mrtStations[mrtLineName];
                          var stationsToCheck = fullStationsArray.slice(fullStationsArray.indexOf(departureStop), fullStationsArray.indexOf(arrivalStop));
                          stationsToCheck.push(arrivalStop);

                          // Check if affected stations array contains stations involved in the user's route

                          if (intersect(stationsToCheck, affectedStations) !== false) {
                        app.locals.alpha = "it works"

                          }



                        }
                      });


                    }


                  }
                });



              }

            }

          }

        }



      }

      fuck();


    };//meme
  });

next();
 });



 app.use('/', (req, res, next) => {

   function function2() {

     if (app.locals.alpha === 'it works') {

       console.log('shit');

         googleMapsClient.directions({
              origin: app.locals.origin,
              destination: app.locals.destination,
              mode: 'transit',
              transit_mode: 'bus',
              alternatives: true,
            }, function(err, res) {
          if (!err) {

            app.locals.great = res.json.routes


}
})
     } else {

       app.locals.great = app.locals.beta;

       var flatten = require('@flatten/array')
       var helptaxi = flatten(app.locals.great);
       console.log(helptaxi);


       /*
       note to self: MRT stations close around 11pm, so directions would have no MRT
       stuff
       */
       console.log('help cher my app is not working')

     }

     res.json(app.locals.great);
     res.end;

   };

   setTimeout(function2, 3500);




 })




app.listen(3000, function () {
  console.log(`Server is up to port ${port}`);
});


// Connect to MRT breakdown API, filter by MRT breakdown then adjust walking pace. Clearly need to redo the array of MRT stations in Javascript. Should have done everything in NodeJS...
