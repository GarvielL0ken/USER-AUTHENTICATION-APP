import { Record } from './Record.js';

export class Author extends Record {
	constructor(table, record) {
		super(table);
		this.name = record['name'];
		this.age = record['age'];
		this.genres = record['genres'];

		this.previousName_ = this.name;
		this.previousAge_ = this.age;
		this.previousGenres_ = this.previousGenres_;
	}

	changeState(newState) {
		this.previousState_ = this.state;
		this.state = newState;

		//super.changeState(newState);
		console.log(newState);
		if (this.state ==='edit') {
			this.saveState();
		} else if (this.state === 'confirmEdit') {
			super.updateRecord();
			this.state = 'view';
		} else if (this.state === 'cancelEdit') {
			this.revertChanges();
		} else if (this.state === 'confirmDelete') {
			super.deleteRecord();
			this.state = 'view';
		} else if (this.state === 'cancelDelete') {
			this.state = 'view';
		}
	}

	saveState() {
		this.previousName_ = this.name;
		this.previousAge_ = this.previousAge_;
		this.previousGenres_ = this.genres;
	}

	revertChanges() {
		this.name = this.previousName_;
		this.age = this.previousAge_;
		this.genres = this.previousGenres_;
		this.state = 'view';
	}

	toDataObject() {
		/*object*/	var	data;

		data = {
			name:				this.name,
			age:				this.age,
			genres:				this.genres,
			primaryKey:			'name',
			primaryKeyValue:	this.previousName_
		};

		return (data);
	}
}