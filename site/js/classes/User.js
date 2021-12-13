import { Record } from "./Record.js";

export class User extends Record {
	constructor(table, record) {
		/*array*/	
		super(table);
		this.username = record['username'];
		this.roles = ['Member', 'Librarian'];
		this.intRole = record['role'];
		this.role = this.roles[this.intRole];
		

		this.previousRole_ = this.role;
		this.previousIntRole_ = this.intRole;
	}

	updateRecord() {
		this.role = this.roles[this.intRole];
		this.table_.updateTable(this);
	}

	saveState() {
		this.previousRole_ = this.role;
		this.previousIntRole_ = this.intRole;
	}

	revertChanges() {
		this.role = this.previousRole_;
		this.intRole = this.previousIntRole_;
		this.state = 'view';
	}

	toDataObject() {
		/*object*/	var	data;

		data = {
			username:			this.username,
			role:				this.intRole,
			primaryKey:			'username',
			primaryKeyValue:	this.username
		};

		return (data);
	}
}