import { RequestHandler } from './RequestHandler.js'

export class Record {
	constructor (table) {
		this.table_ = table;
		this.state = 'view';
		this.previousState_ = '';
	}

	changeState(newState) {
		this.previousState_ = this.state;
		this.state = newState;

		console.log(newState);
		if (this.state ==='edit') {
			this.saveState();
		} else if (this.state === 'confirmEdit') {
			this.updateRecord();
			this.state = 'view';
		} else if (this.state === 'cancelEdit') {
			this.revertChanges();
		} else if (this.state === 'confirmDelete') {
			this.deleteRecord();
			this.state = 'view';
		} else if (this.state === 'cancelDelete') {
			this.state = 'view';
		}
	}

	deleteRecord() {
		this.table_.deleteRecordFromTable(this);
	}

	updateRecord() {
		this.table_.updateTable(this);
	}

	stringify() {
		return(JSON.stringify(this.toDataObject()));
	}
}