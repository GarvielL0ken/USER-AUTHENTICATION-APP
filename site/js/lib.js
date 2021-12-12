export function getURLParameter(url, parameter) {
	var	url;
	var	regex;
	var	value;

	regex = parameter + '=([^&]+)';
	regex = new RegExp(regex);
	
	/*returns: 
		[0: parameter=value, 1: value] hence
		value[1]
	*/
	value = regex.exec(url);
	if (value)
		return (value[1]);
	return ('');
}