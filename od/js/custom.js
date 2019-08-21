$(document).ready(function() {

        if ($("#dataTableLines").length > 0) {
            $('#dataTableLines').DataTable({
                "order": [ 1, 'asc' ],
                "columnDefs": [{ "orderable": false, "targets": [4,5] }],

            });
        }

        if ($("#dataTableStations").length > 0) {
            $('#dataTableStations').DataTable({
                "order": [ 1, 'asc' ],
                "columnDefs": [{ "orderable": false, "targets": [2] }],
                "pageLength": 25

            });
        }

        if ($(".myDataTable").length > 0) {
            $('.myDataTable').DataTable({
            });
        }


        if ($("#dataTableStationsInterval").length > 0) {
            $('#dataTableStationsInterval').DataTable({
                "order": [ 1, 'asc' ],
                "pageLength": 10
            });
        }

        if ($("#dataTableStopPoints").length > 0) {
            $('#dataTableStopPoints').DataTable( {
                //"autoWidth": true,
                "processing": true,
                "serverSide": true,
                "pageLength": 25,

                "ajax": {
                    url: '/od/ajax/dt_stop_points.php',
                    type: 'GET',
                },
                "columns": [
                    { "data": "name" },
                    { "data": "type" },
                    { "data": "lines" },
                    { "data": "map" }
                ],
                "order": [ 1, 'asc' ],
                "columnDefs": [{ "orderable": false, "targets": [1,2,3] }],

            } );
        }

        if ($("#dataTableBikePoints").length > 0) {
            $('#dataTableBikePoints').DataTable({
                "order": [ 1, 'asc' ],
                "columnDefs": [{ "orderable": false, "targets": [2,3] }],

            });
        }

        if ($(".dataTableTimetable").length > 0) {
            $('.dataTableTimetable').DataTable({
                "pageLength": 5,
                "order": [ 2, 'asc' ],
            });
        }


        if ($("#dataTablePlaces").length > 0) {
            $('#dataTablePlaces').DataTable({
                "order": [ 1, 'asc' ],
                "columnDefs": [{ "orderable": false, "targets": [0,1] }],
                "pageLength": 25

            });
        }
        if ($("#dataTablePlacesGroup").length > 0) {
            $('#dataTablePlaces').DataTable({
                "order": [ 1, 'asc' ],
                "columnDefs": [{ "orderable": false, "targets": [2,3] }],
                "pageLength": 25

            });
        }
        if ($("#dataTableCarPark").length > 0) {
            $('#dataTableCarPark').DataTable({
                "order": [ 1, 'asc' ],
                "columnDefs": [{ "orderable": false, "targets": [5] }],
                "pageLength": 50

            });
        }


 });


    function PopupStationMap(id) {
        //    alert("click");
                $(".modal-title").html("Station map: <b>" + id  + "</b>");
            	$('.modal-body').load('/od/ajax/station_map.php?id=' + id,function(result){
        	    $('#myModal').modal({show:true});
        	});

    }

    function PopupStationLines(id) {
                $(".modal-title").html("Lines for this station/stop-point <b>" + id  + "</b>");
            	$('.modal-body').load('/od/ajax/station_lines.php?id=' + id,function(result){
        	    $('#myModal').modal({show:true});
        	});

    }

    function PopupStationLines2(id) {
        //    alert("click");
                $(".modal-title").html("Lines for this station/stop-point <b>" + id  + "</b>");
            	$('.modal-body').load('/od/ajax/stop_points.php?id=' + id,function(result){
        	    $('#myModal').modal({show:true});
        	});

    }


    function AdditionalPlaceData(id) {
        //    alert("click");
                $(".modal-title").html("Additional place data");
            	$('.modal-body').load('/od/ajax/place_data.php?id=' + id,function(result){
        	    $('#myModalPlace').modal({show:true});
        	});

    }


function initMap() {
    var myLatLng = {lat: 51.5287718, lng: -0.2760115};

    var map = new google.maps.Map( document.getElementById('map'), {
        zoom: 10, center: myLatLng
    });

/*
    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        title: 'Main Location',
        label: '',
        animation: google.maps.Animation.DROP,

    });
*/

    $.getJSON( "/od/ajax/popular_routes.php", function(result) {
        var json = result;
        console.log(json);

        $.each(result, function(i, obj){
            console.log(obj.id, parseFloat(obj.longitude),parseFloat(obj.latitude));
            var myLatLngMarker = {lng: parseFloat(obj.latitude), lat: parseFloat(obj.longitude)};
            var marker = new google.maps.Marker({
                position: myLatLngMarker,
                map: map,
                title: obj.title
            });

            var contentString = '<div class="marker_popup">Code: ' + obj.id  + '<br>' + '<b>' + obj.title + '</b><br>' +  obj.longitude + ' ' + obj.latitude + '<p><b>Lines:</b> ' + obj.lines + '</p></div>';
            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });

            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });

        });
    });

} // initMap


