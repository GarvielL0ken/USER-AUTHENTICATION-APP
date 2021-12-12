import { Record } from './Record.js';

export class Author extends Record{
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

		super.changeState(newState);
		if (this.state ==='edit') {
			this.previousName_ = this.name;
			this.previousAge_ = this.previousAge_;
			this.previousGenres_ = this.genres;
		} else if (this.state === 'confirmEdit') {
			super.updateRecord();
			this.state = 'view'; 
		} else if (this.state === 'cancelEdit') {
			this.name = this.previousName_;
			this.age = this.previousAge_;
			this.genres = this.previousGenres_;
			this.state = 'view';
		} else if (this.state === 'confirmDelete') {
			super.deleteRecord();
			this.state = 'view';
		} else if (this.state === 'cancelDelete') {
			this.state = 'view';
		}
	}
}