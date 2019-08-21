//								EDIT FROM HERE
///////////////////////////////////////////////////////////////////////////////////

/**
		Script settings
**/

var settings = {
	
	'force_size':			0,         		// 	if set to 1 all banners will be resized to the width and height in the next to settings
	'img_width':			468,			//	width to resize all banners to, only takes effect if above is 1
	'img_height':			60, 			// 	height to resize all banners to, only takes effect if above is 1
	
	'refresh_time':			5000,			//	the seconds between refreshs of the banners - use 0 to disable
	'refresh_max':			2,				//	maximum number of refreshs on each page load
	
	'duplicate_banners':	0,				//	keep as 0 to make sure the same banner won't show on the same page. will only take effect
											//  if show_banners(); is used more than once. You must make sure you have enough banners to fill
											//  all the slots else the browser may freeze or give a stack overflow error
	
	'location_prefix': 		'adLocation-',	//	The prefix of the IDs of the <div> which wraps the banners - this div is generated dynamically.
											//  a number will be added on the end of this string. adLocation- was used by default before version 1.4.x
											
	'location_class':		'swb',			//  A class to add to all of the <div>s which wrap the banners, ideal to use for styling banners - use .swb img in your CSS	
	
	'window': 				'_blank',		//	Window to open links in, _self = current, _blank = new. Use _top if in a frame!		
	
	'default_ad_loc':		'default'		//	The default adLocation. This is assigned to any banners not given an adLocation in the below banner list
											//  There is no real reason to need to change this
}


/**
		Banners
**/
// banner list syntax: new banner(website_name, website_url, banner_url, show_until_date, adlocation),  DATE FORMAT: dd/mm/yyyy
// if you're not using adlocations just leave it empty like '' as in the last example here
// to make sure a banner is always rotating, just set the date far into the future, i.e. year 3000

var banners = [
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/hungary-budapest_airport-budapest',           '/baner/banner01.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/hungary-budapest-budapest_airport',           '/baner/banner02.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-sighetul_marmatiei-budapest_airport', '/baner/banner03.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-satu_mare-budapest_airport',          '/baner/banner04.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-carei-budapest_airport',              '/baner/banner05.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-bistrita-budapest_airport',           '/baner/banner06.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-oradea-budapest_airport',             '/baner/banner07.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-cluj-budapest_airport',               '/baner/banner08.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-cluj-budapest_airport',               '/baner/banner09.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-timisoara-deva',                      '/baner/banner10.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-timisoara-novi_sad',                  '/baner/banner11.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-timisoara-belgrade',                  '/baner/banner12.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/hungary-budapest_airport-siofok',             '/baner/banner13.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-timisoara-budapest_airport',          '/baner/banner14.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-timisoara-budapest_airport',          '/baner/banner15.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-timisoara-szeged',                    '/baner/banner16.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-timisoara-arad',                      '/baner/banner17.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-timisoara-timisoara_airport',         '/baner/banner18.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/uk-glasgow_airport-glasgow',                  '/baner/banner19.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/germany-munich_airport-munich',               '/baner/banner20.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/germany-munich_airport-munich',               '/baner/banner21.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/germany-munich_airport-munich',               '/baner/banner22.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/france-paris_charles_de_gaulle-paris',        '/baner/banner23.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/france-paris_charles_de_gaulle-paris',        '/baner/banner24.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/czechrepublic-prague_airport-prague',         '/baner/banner25.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/czechrepublic-prague_airport-prague',         '/baner/banner26.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/czechrepublic-prague_airport-prague',         '/baner/banner27.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-cluj_airport-cluj',                   '/baner/banner28.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-cluj_airport-cluj',                   '/baner/banner29.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-timisoara_airport-arad',              '/baner/banner30.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-timisoara_airport-timisoara',         '/baner/banner31.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-timisoara_airport-timisoara',         '/baner/banner32.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-timisoara_airport-timisoara',         '/baner/banner33.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/croatia-dubrovnik_airport-dubrovnik',   '/baner/banner34.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/croatia-dubrovnik_airport-dubrovnik',   '/baner/banner35.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/croatia-dubrovnik_airport-dubrovnik',   '/baner/banner36.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/croatia-dubrovnik_airport-dubrovnik',   '/baner/banner37.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/croatia-dubrovnik_airport-dubrovnik',   '/baner/banner38.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/croatia-dubrovnik_airport-dubrovnik',   '/baner/banner39.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-otopeni_airport-bucharest',     '/baner/banner40.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-otopeni_airport-bucharest',     '/baner/banner41.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-otopeni_airport-bucharest',     '/baner/banner42.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-bucharest-bran_castle',         '/baner/banner43.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-bucharest-bran_castle',         '/baner/banner44.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-bucharest-peles_castle',        '/baner/banner45.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-bucharest-sibiu',               '/baner/banner46.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-bucharest-brasov',              '/baner/banner47.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-bucharest-bran_castle',         '/baner/banner48.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-bucharest-constanta',           '/baner/banner49.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-bucharest-sofia',               '/baner/banner50.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-bucharest-varna',               '/baner/banner51.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-bucharest-stara_zagora',        '/baner/banner52.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/romania-bucharest-burgas',              '/baner/banner53.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/christian-transfers-austria',           '/baner/banner54.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/austria-vienna_airport-vienna',         '/baner/banner55.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/austria-vienna-budapest',               '/baner/banner56.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/austria-graz-budapest',                 '/baner/banner57.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/belgium-brussels_airport-brussels',     '/baner/banner58.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/bosniaherzegovina-sarajevo-belgrade',   '/baner/banner59.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/bulgaria-sofia-belgrade',               '/baner/banner60.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/bulgaria-sofia-bucharest',              '/baner/banner61.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/bulgaria-sofia-russe',                  '/baner/banner62.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/bulgaria-sofia-otopeni_airport',        '/baner/banner63.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/bulgaria-burgas-bucharest_airport',     '/baner/banner64.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/bulgaria-varna-bucharest_airport',      '/baner/banner65.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/bulgaria-russe-bucharest_airport',      '/baner/banner66.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/croatia-rijeka-rijeka_airport',         '/baner/banner67.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/croatia-rijeka-zagreb',                 '/baner/banner68.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/croatia-rijeka-venezia',                '/baner/banner69.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/croatia-rijeka-trieste',                '/baner/banner70.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/croatia-rijeka-ljubljana',              '/baner/banner71.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/croatia-zagreb-zagreb_airport',         '/baner/banner72.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/croatia-zagreb-budapest',               '/baner/banner73.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/hungary-budapest_airport-balatonalmadi','/baner/banner74.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/hungary-budapest_airport-budapest',     '/baner/banner75.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/christian-transfers-hungary',           '/baner/banner76.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/hungary-budapest-vienna',               '/baner/banner77.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/hungary-budapest-bratislava',           '/baner/banner78.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/hungary-budapest-zagreb',               '/baner/banner79.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/hungary-budapest-belgrade',             '/baner/banner80.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/hungary-budapest-timisoara',            '/baner/banner81.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/hungary-budapest-arad',                 '/baner/banner82.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/hungary-budapest-oradea',               '/baner/banner83.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/hungary-budapest-cluj',                 '/baner/banner84.jpg', '30/04/2029',	'468'),
	new banner('Christian Transfers','https://www.christiantransfers.eu/airport_transfer/hungary-budapest-graz',                 '/baner/banner85.jpg', '30/04/2029',	'468')
]

//         				There is no need to edit below here
///////////////////////////////////////////////////////////////////////////////////

/*****
"global" vars
*****/
var used				= 0;
var location_counter	= 0;
var refresh_counter 	= 1;
var map 				= new Array();


/*************
	function banner()
	creates a banner object
*************/
function banner(name, url, image, date, loc)
{
	this.name	= name;
	this.url	= url;
	this.image	= image;
	this.date	= date;
	this.active = 1;
	this.oid = 0;
	
	// if no adlocation is given use the default a adlocation setting
	// this is used if adlocations aren't being used or using pre-1.4.x code
	if(loc != '')
	{
		this.loc = loc;
	}
	else
	{
		this.loc = settings.default_ad_loc;
	}
}


/*************
	function show_banners()
	writes banner <div> HTML and maps ad locations to <div> ID tags
*************/
function show_banners(banner_location)
{
	// increase the counter ready for further calls
	location_counter = location_counter + 1;

	// this part maps the adlocation name supplied by the user to the adlocation
	// ID used by the script
	if(banner_location != '' && banner_location != undefined)
	{
		map[location_counter] = banner_location;
	}
	else
	{
		map[location_counter] = settings.default_ad_loc;
	}

	// writes banner html
	var html = '<div id="' + settings.location_prefix + location_counter + '" class="' + settings.location_class + '"></div>';
	document.write(html);
	// calls the display banners script to fill this ad location
	display_banners(location_counter);
	
}



/*************
	function display_banners()
	displays banners for a given location number
*************/
function display_banners(location)
{
	// used in this function to hold tempoary copy of banners array
	var location_banners	= new Array();
	
	// if no location is given, do nothing
	if(location == '' || !location || location < 0)
	{
		return;
	}
	
	// get total banners
	var am	= banners.length;
	
	// all banners have been displayed in this pass and the user doesnt
	// want to have duplicate banners showing
	if((am == used) && settings.duplicate_banners == 0) {
		return;
	}

	// new for 1.4.x, this takes the list of banners and creates a tempoary list
	// with only the banners for the current adlocation in
	for(i = 0; i < (banners.length); i++)
	{
		banners[i].oid = i;
		if((banners[i].loc == map[location]) && (banners[i].active == 1))
		{
			location_banners.push(banners[i]);
		}
	}

	// same as 1.2.x - finds the banner randomly
	var rand	= Math.floor(Math.random()*location_banners.length);	
	var bn 		= location_banners[rand];
	
	// creates html
	var image_size 	= (settings.force_size == 1) ? ' width="' + settings.img_width + '" height="' + settings.img_height + '"' : '';
	var html 		= '<a href="' + bn.url + '" title="' + bn.name + '" target="' + settings.window + '"><img border="0" src="' + bn.image + '"' + image_size + ' alt="' + bn.name + '" /></a>';
	
	// calculates the date from inputted string, expected formate is DD/MM/YYYY
	var now		= new Date(); 
	var input	= bn.date;
	input		= input.split('/', 3);
	
	// creates a date object with info
	var end_date	= new Date();
	end_date.setFullYear(parseInt(input[2]), parseInt(input[1]) - 1, parseInt(input[0]));
	
	// compares curent date with banner end date
	if((now < end_date) && bn.active == 1) 
	{
		// attempt to find adlocation div
		var location_element = document.getElementById(settings.location_prefix + location);
		
		// couldn't find it, if this message shows there is a problem with show_banners
		if(location_element == null)
		{
			alert('spyka Webmaster banner rotator\nError: adLocation doesn\'t exist!');
		}
		// output banner HTML
		else
		{
			location_element.innerHTML = html;
			
			// if the user doesn't want the same banner to show again deactive it and increase
			// the users banners counter
			if(settings.duplicate_banners == 0)
			{
				banners[bn.oid].active = 0;
				used++;
			}
			return;
		}
	}
	else
	{
		// inactive banner, find another
		// if no banners fit this adlocation you'll have an endless loop !
		display_banners(location);
	}
	return;
}



/*************
	function refresh_banners()
	resets counters and active settings
*************/
function refresh_banners()
{
	if((refresh_counter == settings.refresh_max) || settings.refresh_time < 1)
	{
		clearInterval(banner_refresh);  
	}
	used = 0;
	for(j = 0; j < (banners.length); j++)
	{
		banners[j].active = 1;
	}

	for(j = 1; j < (location_counter+1); j++)
	{
		display_banners(j);
	}
	refresh_counter++;
}



// set timeout
var banner_refresh = window.setInterval(refresh_banners, settings.refresh_time);