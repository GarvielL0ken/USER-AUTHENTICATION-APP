export class Controls {
	constructor(table) {
		this.table = table;
	}

	createNewRecord() {
		/*Record*/	var	record;

		record = this.generateRecord();
		console.log(record);
		this.table.createNewRecord(record);
	}
}