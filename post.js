var ajaxurl = ajaxurl

jQuery(document).ready(function(){
	var twitbox = document.getElementById('twitbox');
	var twitboxLength = parseInt(twitbox.value.length);
	var twitboxData = jQuery('#twitbox').val();
	var dataTitle = jQuery('#title').val();
	
	twitbox.onkeyup = countKeys;
		
	jQuery('#title').focus(function(){
		dataTitle = jQuery('#title').val();
		twitboxData = jQuery('#twitbox').val();
		twitboxData = twitboxData.replace(dataTitle, '');
		dataTitle = null;
		jQuery('#title').on('keyup', function(){
			jQuery('#twitbox').val(function(){
				dataTitle = jQuery('#title').val();
				return dataTitle + ' ' + twitboxData;
				
			});
			countKeys();
			
		});
		
	});
	
	jQuery('#twitbox').focusout(function(){
		if(jQuery(this).val() == ''){
			jQuery(this).val(shorturl);
			countKeys();
		}
	});
	countKeys();
	linkValue(twitboxData);


}); //end ready
var addTickers = true;
var oldSymbols = [];
function searchSymbols(){ 
	content = jQuery('#content_ifr').contents().find('#tinymce').html();
		allSymbols = 		content.match(/\$((?:[0-9]+(?=[a-z])|(?![0-9\.\:\_\-]))(?:[a-z0-9]|[\_\.\-\:](?![\.\_\.\-\:]))*[a-z0-9]+)/gi);
		
		symbols = eliminateDuplicates(allSymbols);
		if(symbols != null){
			symbols = symbols.filter(function(val) {
  			return oldSymbols.indexOf(val) == -1;
			});
		}
	symbolSelector = document.getElementById('symbolSelector');
	symbolBox = document.getElementById('symbolBox');
	if(symbols[0] != null && addTickers == true){

		symbolSelector.className = 'active';
		symbolBox.className = 'active';
		
		symbolBox.innerHTML = 'Suggested Symbols: ';

		 if(symbols){	
			s = document.createTextNode(symbols.join(' '));
			p = document.createElement('P');
			p.setAttribute('class', 'symbol');
			p.appendChild(s);
			symbolBox.appendChild(p);
		}
		
		jQuery('#addTicker').click(function(){
		for(j = 0; symbols.length > j; j++){
			oldSymbols.push(symbols[j]);
			}
			
			symbolString = symbols.join(' ');
			symbolSelector.className = 'inactive';
			symbolBox.className = 'inactive';
			twitbox.value += ' ' + symbolString;
			symbolString = null;
			symbols = [];
			countKeys();
		});
		jQuery('#dismiss').click(function(){
		symbolSelector.className = 'inactive';
		symbolBox.className = 'inactive';
		addTickers = false;

		});
		
	} else{
		symbolSelector.className = 'inactive';
	}	
}

function eliminateDuplicates(arr) {
	if(arr == null){return;}
	 i, len=arr.length, out=[], obj={};

 	for (i=0;i<len;i++) {
 		obj[arr[i]]=0;
 	}
 	for (i in obj) {
 		out.push(i);
 	}
 	return out;
}

function initPage(){

 }

 function countKeys(){
	var counter = document.getElementById('counter');
	var error= document.getElementById('error');
	var sendPost = document.getElementById('sendPost');


	if(twitbox.value.length == 0){
		counter.innerHTML = 140;
	}
	
	var links = twitbox.value.match(/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/g);
	
		if(links){
			for(i=0; links.length > i; i++) {
			var linkSubString = links[i].substring(0, links[i].indexOf(' '));
			}
		var linksString = new String(linkSubString);
		
		counter.innerHTML = 140 - (links.length * 22) - (twitbox.value.length) + linksString.length;
		
		
	}else{
	counter.innerHTML = 140 - (twitbox.value.length);
	}
	if(counter.innerHTML < 0){
	
	error.innerHTML = "You message excceds the max characters and will not be sent to StockTwits";
	} else {
	error.innerHTML = '';
	}
	sendPost.value = counter.innerHTML;
}

function linkValue(text){
var textArray = text.split(" ");
for(i=0; i<textArray.length; i++){
	var linksSearch = textArray[i].match(/http/g);
			if(linksSearch == true){
				alert(linksSearch);
				for(j=0; j < linksSearch.length; j++){
				
			}
		}
	
	}

} 