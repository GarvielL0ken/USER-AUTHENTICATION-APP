import { Book } from "./Books.js";
import { Controls } from "./InputControls.js";

export class BooksControls extends Controls {
	constructor(table) {
		super(table);
		this.title = "test";
		this.author = "Joe";
		this.genre = "genre";
		this.year = 1984;
		this.ageGroup = "Teens";
	}

	generateRecord() {
		/*array*/	var record;
		/*object*/	var	book;
		
		record = [];
		record['title'] = this.title;
		record['author'] = this.author;
		record['genre'] = this.genre;
		record['year'] = this.year;
		record['age_group'] = this.ageGroup;
		book = new Book(this, record);

		return (book);
	}
}