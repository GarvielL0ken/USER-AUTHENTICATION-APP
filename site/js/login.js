var application = new Vue({
	el : "#application",
	data : {
		page : "sign-in",
		errorMessage : ""
		//RequsetHandler : new RequestHandler();
	},
	methods : {
		changePage(page) {
			this.page = page;
		},
		/*Gets the value in a given parameter in the current url*/
		/*Retruns Null if no paramter is found in the url*/
		getURLParameter(parameter) {
			var	url;
			var	regex;
			var	value;

			url = window.location.href;
			regex = parameter + '=([^&]+)';
			regex = new RegExp(regex);
			
			/*returns: 
				[0: parameter=value, 1: value] hence
				value[1]
			*/
			value = regex.exec(url);
			if (value)
				return (value[1]);
			return ('');
		},
		sendRequest(requestName= "", callback) {
			this.RequestHandler.fetch(requestName).then((response) => callback(response));
		},
	},
	mounted() {
		var	result;

		result = this.getURLParameter("page");
		this.page = result ? result : "sign-in";

		this.errorMessage = this.getURLParameter("error-message");
		if (this.errorMessage)
			this.errorMessage = decodeURI(this.errorMessage);
	}
})