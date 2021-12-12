import { RequestData } from './RequestData.js';
export class RequestHandler {
	constructor(requestData= null) {
		/** @private {!responseText}*/
		this.responseText_ = null;

		/** @private {!arrRequestData} */
		this.arrRequestData_ = [];
		if (requestData)
			this.initializeRequestData(requestData);
	}

	initializeRequestData(requestData) {
		var	i;

		i = 0;
		while(requestData[i]) {
			this.addNewRequestData(requestData[i][0], requestData[i][1]);
			i++;
		}
	}

	/*	____ACCESSOR METHODS____	*/
	/*		____GET____				*/

	//Used to find a requestData object by name
	getRequestN(requestName) {
		var i;

		i = 0;
		while(this.arrRequestData_[i]) {
			if (this.arrRequestData_[i].name === requestName)
				return(this.arrRequestData_[i]);
			i++;
		}

		return(false);
	}

	/*		____SET____				*/
	addNewRequestData(name, path) {
		var newRequestData = new RequestData(name, path);

		this.arrRequestData_.push(newRequestData);
	}

	setResponseText(responseText) {
		this.responseText_ = responseText;
	}

	/*	____STANDARD METHODS____		*/
	/*		____REQUEST METHODS___	*/
	//Send Request using the data provided in the requestData object
	//Returns the response text
	async fetch(requestData) {
		var	response	=null;
		var	data		=null;
		
		response = await fetch(requestData.path);
		data = await response.text();
		
		return (data);
	}

	async fetchN(requestName, setToCurrent=false) {
		var request;

		request = this.getRequestN(requestName);

		return(this.fetch(request));
	}
}