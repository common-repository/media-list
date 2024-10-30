//Medialist Features
//Medialist Version: 1.4.1
var $medialistjq = jQuery.noConflict();
$medialistjq (document).ready(function($){
	var mediaconstruct = $medialistjq("div[mediajqref='medialist-construct']");//target medialist elements only and cache it.
	$medialistjq(mediaconstruct).each(function(index){
		//page data tag defines
        var medialistinstance = $medialistjq(this).data('instance');
		var medialistmltoken = $medialistjq(this).data('token');
		var medialistpaginate = $medialistjq(this).data('paging');
		var medialistmlstyle = $medialistjq (this).attr('class').split(' ')[1];
        setmlpage(medialistinstance,medialistmltoken,medialistpaginate,medialistmlstyle);
	});
	function setmlpage(medialistinstance,medialistmltoken,medialistpaginate,medialistmlstyle){
	    var medialistcache = $medialistjq ("#mlid-" + medialistinstance)
		$medialistjq(medialistcache).each(function () {//do the below for each medialist instance on the page.
			var foo = $medialistjq (this);
			//adjust offset
			var passmaxitems = medialistmltoken-1;
			var items = passmaxitems+1; //items to display adjusted to account for offset, make items to display true to setting.
			var pages = 1; //used for checking against paginate setting
			//calculate pages
			var totalitems = $medialistjq("#mlid-"+medialistinstance+" ul li").size(); //how many items available in total per instance.
			var addpage = 0; //'items' value are added to 'addpage' via loop.
			var maxpages = 0; //incremented once each loop cycle to give us the total pages minus any remainder
			var nextpage = 1;//used for the current page of items being displayed
			var remainder = (totalitems)%(items);//calculate any remaining items.
//Build pagination
			if ((medialistpaginate >= pages) && (totalitems > passmaxitems) && (totalitems !=1)){
				var vpages = passtojq.vpages;
				var voffsep = passtojq.voffsep;
				var vprev = passtojq.vprev;
				var vnext = passtojq.vnext;
				var pageappend = $medialistjq (this).append('<div class="mlpagination-'+medialistmlstyle+'"><div style="float:left; padding-top:12px;" class="medialist-page-meta"><p class="medialist-page-data">'+vpages+'</p> <div class="medialist-page-data mlpageoff-'+medialistinstance+'">1</div> <p class="medialist-page-data">'+voffsep+'</p> <div class="medialist-page-data mlmaxpage-'+medialistinstance+'"></div></div><div style="width:309px;" class="medialist-buttons"><a class="prev">'+vprev+'</a><a class="next">'+vnext+'</a></div></div>');
				var itemhide = $medialistjq (this).find(".ml-ul-"+medialistmlstyle+" li:gt(" +passmaxitems+ ")").hide();
				var mlhide = $medialistjq("#mlid-"+medialistinstance+" ul li").filter(":hidden").size(); //count hidden items
				$medialistjq (itemhide,pageappend);//hide items beyond set items to display and append pagination divs.
				while (addpage <= mlhide){//checking total hidden items is less than or equal to addpage
					addpage += items;//'items' added to 'addpage'. 
					maxpages++;//increments our page total each loop cycle until condition met.
				}
				if (remainder > 0){//if any remaining items found increment our page total to give the true page total.
					maxpages++;
				}
				$medialistjq('div.mlmaxpage-'+medialistinstance).text(maxpages);//write our max pages to unique class in html.
//Page buttons
				//set next button
				var medianext = $medialistjq (this).find('.next')
				var mediapageoff = $medialistjq('div.mlpageoff-'+medialistinstance)
				$medialistjq (medianext).click(function () {
					if (checknext()) {
					var last = $medialistjq ('.ml-ul-'+medialistmlstyle+'',foo).children('li:visible:last');
					last.nextAll(":lt(" +items+ ")").show();
					last.next().prevAll().hide();
					nextpage++;//increment page on-click
					$medialistjq(mediapageoff).text(nextpage);
					}
				});
				//set previous button
				var mediaprev = $medialistjq (this).find('.prev')
				$medialistjq (mediaprev).click(function () {
					if (checkprev()) {
					var first = $medialistjq ('.ml-ul-'+medialistmlstyle+'',foo).children('li:visible:first');
						first.prevAll(":lt(" +items+ ")").show();
						first.prev().nextAll().hide();
						nextpage--;	//decrement page on-click
						$medialistjq(mediapageoff).text(nextpage);
					}
				});
			} else if (medialistpaginate < pages){
				$medialistjq (this).find(".ml-ul-"+medialistmlstyle+" li:gt(" +passmaxitems+ ")").hide();
			}
			function checknext(){if (nextpage == maxpages){return false;}return true;}//checks if maximum page is equal to current page, if it is prevent click.
			function checkprev(){if (nextpage == 1){return false;}return true;}//checks if current page is first page, if it is prevent click.
//Search Functions
			$medialistjq.expr[":"].contains = $medialistjq.expr.createPseudo(function(arg) { //Allow contains to be case-insensitive.
				return function( elem ) {return $medialistjq(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;};
			});
			//cache re-used elements.
			var findhide = $medialistjq("#mlid-"+medialistinstance+" ul li");
			var uniqueinstance = $medialistjq ("#mlid-" +medialistinstance);
			$medialistjq (this).find('.medialist-gosearch').click(function () {
				//On click hide all li items in medialistinstance, search for match using modified 'contains' and display.
			    var medialistsearch = $medialistjq('.ml-search-'+medialistinstance).val();
				if($medialistjq('.ml-search-'+medialistinstance).val() == ''){return false;}
				$medialistjq(findhide.addClass('medialist-hidden'));
				$medialistjq (uniqueinstance).find('.mlpagination-'+medialistmlstyle).addClass('medialist-hidden');
				$medialistjq("#mlid-"+medialistinstance+" ul li:contains('"+medialistsearch+"')").addClass('medialist-active');
			});
			var medialistkeyup = $medialistjq('.ml-search-'+medialistinstance)
			$medialistjq(medialistkeyup).keyup(function(){//Restores the medialist when field emptied.
				if($medialistjq('.ml-search-'+medialistinstance).val() == ''){
					$medialistjq (uniqueinstance).find('.mlpagination-'+medialistmlstyle).removeClass('medialist-hidden');
					$medialistjq(findhide).removeClass('medialist-hidden medialist-active');
				}
			});
		//end of Search Functions
        });//end of #mlid each function
	}//close setmlpage function 
});//end document.ready


































    
	
	
	
	

