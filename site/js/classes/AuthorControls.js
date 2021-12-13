import { Author } from "./Author.js";
import { Controls } from "./InputControls.js";

export class AuthorControls extends Controls{
	constructor (table) {
		super(table);
		this.name = "Test";
		this.age = 42;
		this.genres = "Genres";
	}

	generateRecord() {
		/*array*/	var record;
		/*object*/	var	author;
		
		record = [];
		record['name'] = this.name;
		record['age'] = this.age;
		record['genres'] = this.genres;
		author = new Author(this.table, record);

		return (author);
	}
}