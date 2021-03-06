<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>{% block title %} {%endblock%}</title>

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" type="text/css" href="{{ AssetsURL }}/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="{{ AssetsURL }}/css/web_content.css">
		<link rel="stylesheet" type="text/css" href="{{ AssetsURL }}/css/bootstrap-tokenfield.min.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="{{ AssetsURL }}/css/animate.css">
		{% block other_css %}{% endblock %}

	</head>
	<body>
		{% block body_content %}{% endblock %}

		<!-- jQuery -->
		<script src="{{ AssetsURL }}/js/jquery.min.js"></script>
		<!-- Bootstrap JavaScript -->
		<script src="{{ AssetsURL }}/js/bootstrap.min.js"></script>
		<script src="{{ AssetsURL }}/js/jquery-ui.min.js"></script>
		<script src="{{ AssetsURL }}/js/bootstrap-tokenfield.min.js"></script>


		<script src="{{ AssetsURL }}/js/custom_functions.js"></script>
		<script src="{{ AssetsURL }}/js/custom_organizations.js"></script>
		<script src="{{ AssetsURL }}/js/custom_items.js"></script>
		<script src="{{ AssetsURL }}/js/custom_categories.js"></script>


		{% block other_js %}{% endblock %}
	</body>
</html>
