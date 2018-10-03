window.addEvent('domready', function() {
	// You can skip the following two lines of code. We need them to make sure demos
	// are runnable on MooTools demos web page.
	if (!window.demo_path) window.demo_path = '';
	var demo_path = window.demo_path;
	// --
		
	//We can use one Request object many times.
	var req = new Request.HTML({
		url:'http://127.0.0.1/exchange/', 
		onSuccess: function(html) {
		 update: 'result';
			//Clear the text currently inside the results div.
			$('result').set('text', '');
			//Inject the new DOM elements into the results div.
			$('result').adopt(html);
		},
		//Our request will most likely succeed, but just in case, we'll add an
		//onFailure method which will let the user know what happened.
		onFailure: function() {
			$('result').set('text', 'The request failed.');
		}
	});
	
	$('makeRequest').addEvent('click', function() {
		req.send();
	});

});
