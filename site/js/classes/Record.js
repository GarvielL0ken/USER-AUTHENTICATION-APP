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
	}

	updateRecord() {
		this.table_.updateTable(this);
	}

	stringify() {
		return(JSON.stringify(this.toDataObject()));
	}
}