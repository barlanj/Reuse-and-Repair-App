var item_categories = {};
var org_categories = {};
var base_url = 'http://localhost:4000';

function addslashes( str ) {
    return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
}

/* ==================================================================
Creates a select list given the dictionary. 'Container' is the DOM 
element to attach this to.
=====================================================================*/
function create_selectlist_options (container, dictionary) {

	$.each(dictionary, function(key, value) {
		var option = "<option value='"+ key +"'>"+ value +"</option>";
		$(container).append(option);
	});
};


/* ==================================================================
Perform an ajax call to the API and retrieve item categories.
=====================================================================*/
function get_db_itemcategories () {

	if (Object.keys(item_categories).length < 1 ) {

		$.ajax({
				url: base_url + '/api/itemcategories',
				type: 'get',
				data: {},
				success: function (get_data) {

					$.each(get_data['data'], function(index, obj) {
						item_categories[obj['id']] = obj['description'];
					});
				}
			});
	} else {
		// do nothing if it's already been retrieved
	}
};


/* ==================================================================
Perform an ajax call to the API and retrieve organization categories.
=====================================================================*/
function get_db_orgcategories () {

	if (Object.keys(org_categories).length < 1 ) {

		$.ajax({
				url: base_url + '/api/organizationtypes',
				type: 'get',
				data: {},
				success: function (get_data) {

					$.each(get_data['data'], function(index, obj) {
						org_categories[obj['id']] = obj['description'];
					});
				}
			});
	} else {
		// do nothing if it's already been retrieved
	}
};

function get_key(get_value, dictionary) {
	var ret_key = undefined;

	$.each(dictionary, function(key, value) {
		if (String(get_value) == String(value)) {
			ret_key = key;
			return false;
		};
	});

	return ret_key;
};