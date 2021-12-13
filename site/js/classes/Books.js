import { Record } from './Record.js';

export class Book extends Record {
	constructor(table, record) {
		super(table);
		this.title = record['title'];
		this.author = record['author'];
		this.year = record['year'];
		this.genre = record['genre'];
		this.ageGroup = record['age_group'];
		
		this.previousTitle_ = this.title;
		this.previousAuthor_ = this.author;
		this.previousYear_ = this.year;
		this.previousGenre_ = this.genre_;
		this.previousAgeGroup_ = this.ageGroup;
	}

	saveState() {
		this.previousTitle_ = this.title;
		this.previousAuthor_ = this.author;
		this.previousYear_ = this.year;
		this.previousGenre_ = this.genre_;
		this.previousAgeGroup_ = this.ageGroup;
	}

	revertChanges() {
		this.title = this.previousTitle_;
		this.author = this.previousAuthor_;
		this.year = this.previousYear_;
		this.genre_ = this.previousGenre_;
		this.ageGroup = this.previousAgeGroup_;
		this.state = 'view';
	}

	toDataObject() {
		/*object*/	var	data;

		data = {
			title:				this.title,
			author:				this.author,
			year:				this.year,
			genre:				this.genre,
			age_group:			this.ageGroup,
			primaryKey:			'title',
			primaryKeyValue:	this.previousTitle_
		};

		return (data);
	}
}