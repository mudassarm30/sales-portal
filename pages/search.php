<?php include_once __DIR__ . "/../api/common/common.php"; ?>
<?php if(checkFeatureElement(FE_Search_Capability)){ ?>
<?php
	include_once __DIR__ . "/../api/common/config.php";
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../components/search/styles.css?id=7" />
</head>

<body>
<div id="page">    
    <form id="searchForm" method="post">
		<fieldset>
        
           	<input id="s" type="text" />
            
            <input type="submit" value="Submit" id="submitButton" />
            
            <div id="searchInContainer">
                <input type="radio" name="check" value="site" id="searchSite" checked />
                <label for="searchSite" id="siteNameLabel">Search</label>
                
                <input type="radio" name="check" value="web" id="searchWeb" disabled />
                <label for="searchWeb">Search The Web</label>
				
				<input type="checkbox" id="deepSearch" />
                <label for="deepSearch">Deep Search</label>
			</div>
             
            <ul class="icons">
                <li class="web" title="Search everything" data-searchType="all">All</li>
                <li class="images" title="Search files" data-searchType="files">Files</li>
                <li class="news" title="Search key/values, text notes and articles" data-searchType="articles">Articles</li>
                <li class="videos" title="Search audio and desktop recordings" data-searchType="media">Media</li>
            </ul>
            
        </fieldset>
    </form>

	<style>
		.typeName{
			text-align: center;
			background-color: #808080;
			color: #ffffff;
			font-weight: bold;
			font-size: 14px;
			width:200px;
		}
		.title{
			padding-top: 10px;
			padding-left: 10px;
			text-align: left;
			font-weight: bold;
			color: #000000;
			font-size: 13px;
		}
		.contentBody{
			padding-top: 10px;
			padding-left: 10px;
			text-align: left;
			color: #000000;
			font-size: 12px;
		}
		.tags{
			padding-top: 10px;
			padding-left: 10px;
			text-align: left;
			color: #000000;
			font-size: 12px;
		}
		#resultsDiv{
			overflow-x: hidden;
			overflow-y: auto;
			height: 370px;
		}
		.loading{
			text-align: center;
			padding-top: 100px;
			font-weight: bold;
			color: #000000;
		}
		.loadMoreClass{
			width:100px;
			height: 30px;
			font-weight: bold;
			color: #ffffff;
			background-color: #0066ff;
		}
	</style>
	
    <div id="resultsDiv"></div>
    
</div>
    
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script src="../components/search/script.js?id=30"></script>
<script language="javascript">
	window.THIS_SERVICE_BASE_URL = "<?=THIS_SERVICE_BASE_URL?>";
</script>
</body>
</html>
<?php } else {?>
<h3><?=NO_ACCESS_MESSAGE?></h3>
<?php } ?>