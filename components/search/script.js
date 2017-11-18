$(document).ready(function(){
	
	var config = {
		siteURL		: 'Enteris',	// Change this to your site
		searchSite	: true,
		type		: 'web',
		append		: false,
		perPage		: 8,			// A maximum of 8 is allowed by Google
		page		: 1				// The start page
	}
	
	var UNKNOWN = 0;
	var TUPPLE = 1;
	var TEXT = 2;
	var AUDIO = 3;
	var VIDEO = 4;
	var DESKTOP_RECORDING = 5;
	var CAMERA_IMAGE = 6;
	var RICHTEXT = 7;
	var DESKTOP_IMAGE = 8;
	var FILE = 9;
  
	// The small arrow that marks the active search icon:
	var arrow = $('<span>',{className:'arrow'}).appendTo('ul.icons');
	
	$('ul.icons li').click(function(){
		var el = $(this);
		
		if(el.hasClass('active')){
			// The icon is already active, exit
			return false;
		}
		
		el.siblings().removeClass('active');
		el.addClass('active');
		
		// Move the arrow below this icon
		arrow.stop().animate({
			left		: el.position().left,
			marginLeft	: (el.width()/2)-4
		});
		
		// Set the search type
		config.type = el.attr('data-searchType');
		$('#more').fadeOut();
	});
	
	// Adding the site domain as a label for the first radio button:
	$('#siteNameLabel').append(' '+config.siteURL);
	
	// Marking the Search tutorialzine.com radio as active:
	$('#searchSite').click();	
	
	// Marking the web search icon as active:
	$('li.web').click();
	
	// Focusing the input text box:
	$('#s').focus();

	$('#searchForm').submit(function(){
		config.page = 1;
		enterisSearch(false);
		return false;
	});
	
	$('#searchSite,#searchWeb').change(function(){
		// Listening for a click on one of the radio buttons.
		// config.searchSite is either true or false.
		
		config.searchSite = this.id == 'searchSite';
	});
	
	function getContentTypeName(type){
	
		switch(type){
			case UNKNOWN:
				return "Unknown Type";
			case TUPPLE:
				return "Key / Value";
			case TEXT:
				return "Text Note";
			case AUDIO:
				return "Audio Recording";
			case VIDEO:
				return "Video Recording";
			case DESKTOP_RECORDING:
				return "Desktop Recording";
			case CAMERA_IMAGE:
				return "Camera Snapshot";
			case RICHTEXT:
				return "Rich Article";
			case DESKTOP_IMAGE:
				return "Desktop Capture";
			case FILE:
				return "File";
		}
		return "";
	}
	window.nextPage = function(){
		config.page++;
		enterisSearch(true);
	}
	function enterisSearch(append){
		
		if(append === false)
			$("#resultsDiv").html("<h3 class='loading'>Loading results ... </h3>");
		
		var pattern 	= $('#s').val();
		var isFile 		= false;
		var isMedia 	= false;
		var isArticle 	= false;
		var isDeep 		= $("#deepSearch").attr('checked');
		var page 		= config.page;
		var count 		= config.perPage;
		
		switch(config.type){
			case "all":
				isFile 		= true;
				isMedia 	= true;
				isArticle 	= true;
			break;
			case "files":
				isFile 		= true;
			break;
			case "articles":
				isArticle 	= true;
			break;
			case "media":
				isMedia 	= true;
			break;
		}
		
		$.get( window.THIS_SERVICE_BASE_URL+"/api/search/contents.php?pattern="+pattern+"&isFile="+isFile+"&isMedia="+isMedia+"&isArticle="+isArticle+"&isDeep="+isDeep+"&page="+page+"&count="+count,
			function( data ) {
				var results = JSON.parse(data);
				var docs = results.response.docs;
				var highlightings = results.highlighting;
				var resultsDiv = $("#resultsDiv");
				var html = "<table width='100%'>";
				
				/*UNKNOWN,TUPPLE,TEXT,AUDIO,VIDEO,DESKTOP_RECORDING,CAMERA_IMAGE,RICHTEXT,DESKTOP_IMAGE,FILE*/
				
				if((docs.length === 0) && (append === false))
					html+="<tr>" +
							  "     <td style='border-top: 2px solid #000000; padding-bottom: 15px'>" +
							  "			<h3 class='loading'>0 results found </h3>" +
							  "		</td>" +
							  "</tr>";
							  
				for(var i=0; i<docs.length; i++){
					
					var content = docs[i];
					var type = content.posttype;
					var guid = content.guid;
					var highlighting = highlightings[content.id];
					var title = ((typeof highlighting.title) !== "undefined")?highlighting.title:content.title;
					var typeName = getContentTypeName(type);
					var body = "";
					var tags = ((typeof highlighting.tags) !== "undefined")?highlighting.tags:content.tags;
					var url = window.THIS_SERVICE_BASE_URL + "/api/search/result.php?guid=" + guid;
					
					if((typeof highlighting.postbody) !== "undefined")
						body = highlighting.postbody;
					else if((typeof highlighting.description) !== "undefined")
						body = highlighting.description;
					else
						body = content.description;
					
					html+="<tr>" +
						  "     <td style='border-top: 2px solid #000000; padding-bottom: 15px'>" +
						  "            <table width='100%'>" +
						  "     			<tr>" +
						  "						<td><div class='typeName'>" + typeName + "</div><a href='"+url+"' target='_blank'><div class='title'>" + title + "</div></a></td>" + 
						  "						<td></td>" + 
						  "					</tr>" + 
						  "					<tr>" +
						  "						<td class='contentBody'>" + body + "</td>" + 
						  "					</tr>" + 
						  "					<tr>" +
						  "						<td><div class='tags'><strong>Tags:</strong> " + tags + "</div></td>" +
						  "					</tr>" + 
						  "			   </table>" +
						  "		</td>" +
						  "</tr>";
							
				}
				html+="</table>";
				
				if(append === false)
					resultsDiv.html(html);
				else
					resultsDiv.append(html);
				
				$("#loadMoreBtn").remove();
				
				if(docs.length > 0)
					resultsDiv.append("<div width='100%' id='loadMoreBtn' align='center'><input type='button' value='Load More' onclick='window.nextPage()' class='loadMoreClass' /></div>");
				
			},
			function(error){
				
				console.log("Error: ");
				console.log(error);
			}
		);
	}
	
	function googleSearch(settings){
		
		// If no parameters are supplied to the function,
		// it takes its defaults from the config object above:
		
		settings = $.extend({},config,settings);
		settings.term = settings.term || $('#s').val();
		
		if(settings.searchSite){
			// Using the Google site:example.com to limit the search to a
			// specific domain:
			settings.term = 'site:'+settings.siteURL+' '+settings.term;
		}
		
		// URL of Google's AJAX search API
		var apiURL = 'http://ajax.googleapis.com/ajax/services/search/'+settings.type+'?v=1.0&callback=?';
		var resultsDiv = $('#resultsDiv');
		
		$.getJSON(apiURL,{q:settings.term,rsz:settings.perPage,start:settings.page*settings.perPage},function(r){
			
			var results = r.responseData.results;
			$('#more').remove();
			
			if(results.length){
				
				// If results were returned, add them to a pageContainer div,
				// after which append them to the #resultsDiv:
				
				var pageContainer = $('<div>',{className:'pageContainer'});
				
				for(var i=0;i<results.length;i++){
					// Creating a new result object and firing its toString method:
					pageContainer.append(new result(results[i]) + '');
				}
				
				if(!settings.append){
					// This is executed when running a new search, 
					// instead of clicking on the More button:
					resultsDiv.empty();
				}
				
				pageContainer.append('<div class="clear"></div>')
							 .hide().appendTo(resultsDiv)
							 .fadeIn('slow');
				
				var cursor = r.responseData.cursor;
				
				// Checking if there are more pages with results, 
				// and deciding whether to show the More button:
				
				if( +cursor.estimatedResultCount > (settings.page+1)*settings.perPage){
					$('<div>',{id:'more'}).appendTo(resultsDiv).click(function(){
						googleSearch({append:true,page:settings.page+1});
						$(this).fadeOut();
					});
				}
			}
			else {
				
				// No results were found for this search.
				
				resultsDiv.empty();
				$('<p>',{className:'notFound',html:'No Results Were Found!'}).hide().appendTo(resultsDiv).fadeIn();
			}
		});
	}
	
	function result(r){
		
		// This is class definition. Object of this class are created for
		// each result. The markup is generated by the .toString() method.
		
		var arr = [];
		
		// GsearchResultClass is passed by the google API
		switch(r.GsearchResultClass){

			case 'GwebSearch':
				arr = [
					'<div class="webResult">',
					'<h2><a href="',r.unescapedUrl,'" target="_blank">',r.title,'</a></h2>',
					'<p>',r.content,'</p>',
					'<a href="',r.unescapedUrl,'" target="_blank">',r.visibleUrl,'</a>',
					'</div>'
				];
			break;
			case 'GimageSearch':
				arr = [
					'<div class="imageResult">',
					'<a target="_blank" href="',r.unescapedUrl,'" title="',r.titleNoFormatting,'" class="pic" style="width:',r.tbWidth,'px;height:',r.tbHeight,'px;">',
					'<img src="',r.tbUrl,'" width="',r.tbWidth,'" height="',r.tbHeight,'" /></a>',
					'<div class="clear"></div>','<a href="',r.originalContextUrl,'" target="_blank">',r.visibleUrl,'</a>',
					'</div>'
				];
			break;
			case 'GvideoSearch':
				arr = [
					'<div class="imageResult">',
					'<a target="_blank" href="',r.url,'" title="',r.titleNoFormatting,'" class="pic" style="width:150px;height:auto;">',
					'<img src="',r.tbUrl,'" width="100%" /></a>',
					'<div class="clear"></div>','<a href="',r.originalContextUrl,'" target="_blank">',r.publisher,'</a>',
					'</div>'
				];
			break;
			case 'GnewsSearch':
				arr = [
					'<div class="webResult">',
					'<h2><a href="',r.unescapedUrl,'" target="_blank">',r.title,'</a></h2>',
					'<p>',r.content,'</p>',
					'<a href="',r.unescapedUrl,'" target="_blank">',r.publisher,'</a>',
					'</div>'
				];
			break;
		}
		
		// The toString method.
		this.toString = function(){
			return arr.join('');
		}
	}
	
	
});
