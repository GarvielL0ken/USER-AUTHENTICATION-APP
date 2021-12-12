import { getURLParameter } from './lib.js';

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
		}
	},
	mounted() {
		var	result;

		result = getURLParameter(window.location.href, "page");
		this.page = result ? result : "sign-in";

		this.errorMessage = getURLParameter("error-message");
		if (this.errorMessage)
			this.errorMessage = decodeURI(this.errorMessage);
	}
})