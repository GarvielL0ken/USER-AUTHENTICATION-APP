import { Table } from './classes/Table.js';
import { getURLParameter } from './lib.js';

var vueModel = new Vue({
	el : "#vueModel",
	data : {
		errorMessage : "",
		table : "",
		tables : {
			authors : new Table('authors', ['name', 'age', 'genres']),
			books : new Table('books', ['title', 'author', 'genre', 'year', 'age_group']),
			genres : new Table('genres', ['name']),
			users : new Table('users', ['username', 'role'])
		},
		test : "<h1>Users<h1>"
	},
	methods : {
		/*
		 *@param	{string}	table;
		 */
		changeTable(table) {
			this.table = table;
		},
		log() {
			console.log("success");
		}
	},
	mounted() {
		var	result;
		
		//this.tables.authors.getTableFromDatabase();
		//this.tables.books.getTableFromDatabase();
		this.tables.users.getTableFromDatabase();
		
		result = getURLParameter(window.location.href, "page");
		this.table = result ? result : "authors";

		this.errorMessage = getURLParameter("response");
		if (this.errorMessage)
			this.errorMessage = decodeURI(this.errorMessage);
	}
})