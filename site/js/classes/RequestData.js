export class RequestData {
	constructor(name, path, method, returnType) {
		this.name = name;
		this.path = "../config/fetch_points/" + path;
		this.method = method;
		this.returnType = returnType;
	}
}