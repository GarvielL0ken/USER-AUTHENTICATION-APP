import { Author } from './Author.js';
import { RequestData } from './RequestData.js';
import { RequestHandler } from './RequestHandler.js'

import { getURLParameter } from '../lib.js';

export class Table {
	constructor(name, fields, displayMode='view') {
		/** @private {!name}*/
		this.name_ = name;
		
		/** @private {!fields} */
		this.fields_ = fields;

		/** @private {!displayMode} */
		this.displayMode_ = displayMode;

		/** @private {!records}*/
		this.records_ = [];
		this.RequestHandler = new RequestHandler();
		this.RequestHandler.addNewRequestData("getTable", "get_table.php?table="+name, "GET");
		this.RequestHandler.addNewRequestData("CREATE_RECORD", "create_record.php?table="+name);
		this.RequestHandler.addNewRequestData("UPDATE_RECORD", "edit.php?table="+name+"&action=UPDATE", "POST", "text");
		this.RequestHandler.addNewRequestData("DELETE_RECORD", "delete_record.php?table="+name);

		/** @private {!updatedRecord} */
		this.updatedRecord = '';
	}

	//=========================================================================
	//ACCESSOR METHODS
	//	SET METHODS
	//=========================================================================
	getTableFromDatabase() {
		this.RequestHandler.fetchN("getTable")
			.then((response) =>this.getTableFromDatabaseCallback(response));
	}

	/** 
	 * @param	{string}	response
	 */
	getTableFromDatabaseCallback(response) {
		/*int*/		var	index;
	
		index = 0;
		if (response) {
			while (response[index]) {
				if (this.name_ === 'authors')
					this.records_.push(new Author(this, response[index]));
				index++;
			}
		}
	}

	//=========================================================================
	//GET METHODS
	//=========================================================================
	getRecords() {
		return (this.records_);
	}

	/**
	 * @param {string}	identifier
	 * @param {bool}	isPlural
	 * @returns 
	 */
	parseIdentifier(identifier, isPlural) {
		identifier = identifier[0].toUpperCase() + identifier.slice(1);
		identifier = identifier.replace(/_/g, " ");
		if (isPlural)
			identifier = identifier.slice(0, -1);
		return (identifier);
	}

	//=========================================================================
	//CRUD METHODS
	//=========================================================================

	updateTable(record) {
		this.updatedRecord = record;
		this.RequestHandler.fetchN("UPDATE_RECORD", record.stringify())
			.then((response) => this.updateTableCallback(response));
	}

	updateTableCallback(response) {
		if (getURLParameter(response, 'response') != 'SUCCESS')
			this.updatedRecord.revertChanges();

	}

	//=========================================================================
	//PRINT METHODS
	//=========================================================================
	print() {
		/*string*/	var	html;

		html = "";
		html = this.printHeader();
		html += this.printNewRecordControls();
		return (html);
	}

	printHeader() {
		/*int*/		var	index;
		
		/*string*/	var	field;
		/*string*/	var html;
		
		index = 0;

		html = "<h1>";
		html += this.parseIdentifier(this.name_);
		html +="</h1>";
		if (this.fields_) {
			html += "<div class='row-container'>"
			while (this.fields_[index]) {
				field = this.fields_[index];
				html += "<div class='header box'>" + this.parseIdentifier(field) + "</div>";
				index++;
			}
			html += "</div>"
		}
		return (html);
	}

	printNewRecordControls() {
		/*int*/		var	index;
		
		/*string*/	var	field;
		/*string*/	var html;
		/*string*/	var htmlAction;

		index = 0;
		if (this.fields_) {
			htmlAction = "../config/edit.php?page=" + this.name_ + "&action=CREATE";
			html = "<form action='" + htmlAction + "' method='POST'>"
			html += "<div class='row-container'>";
			while (this.fields_[index]) {
				field = this.fields_[index];
				html += "<div class='record box'>";
				html += "<input type='text' name='" + field + "'>"
				html += "</div>"
				index++;
			}
			html += "<div class='record box'>" + 
					"<button>Add New " + this.parseIdentifier(this.name_, true) + "</button>" + 
				"</div>"
			html += "</div></form>"
		}
		return(html);
	}
}