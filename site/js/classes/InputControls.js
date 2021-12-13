export class Controls {
	constructor(table) {
		this.table = table;
	}

	createNewRecord() {
		/*Record*/	var	record;
		record = this.generateRecord();
		this.table.createNewRecord(record);
	}
}