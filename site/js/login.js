var application = new Vue({
	el : "#application",
	data : {
		page : "sign-in"
	},
	methods : {
		changePage(page) {
			this.page = page;
		}
	}
})