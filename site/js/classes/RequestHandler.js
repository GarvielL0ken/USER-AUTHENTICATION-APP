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
	addNewRequestData(name, path, method="GET", returnType="JSON") {
		var newRequestData = new RequestData(name, path, method, returnType);

		this.arrRequestData_.push(newRequestData);
	}

	setResponseText(responseText) {
		this.responseText_ = responseText;
	}

	/*	____STANDARD METHODS____		*/
	/*		____REQUEST METHODS___	*/
	//Send Request using the data provided in the requestData object
	//Returns the response text
	async fetch(requestData, data=null) {
		var	response	=null;
	

		if (requestData.method === "POST") {
			response = await fetch(requestData.path, {
				method: 'post',
				body: data
			})
		} else
			response = await fetch(requestData.path);
		if (requestData.returnType === "JSON")
			response = await response.json();
		else
			response= await response.text();
		return (response);
	}

	async fetchN(requestName, data=null) {
		var request;

		request = this.getRequestN(requestName);

		return(this.fetch(request, data));
	}
}