<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Entity Generator</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<style>
		#content {
			margin-top: 20px;
		}

		.col.actions a {
			margin-top: 35px;
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
		<div class="container">
			<a class="navbar-brand" href="{{ route('entity-generator::index') }}">Entity Generator</a>
		</div>
	</nav>
	<div class="container" id="content">
		<form action="{{ route('entity-generator::create') }}" method="post">
			@csrf
			@if (isset($errors) && count($errors) == 1)
				<div class="alert alert-danger">
					@foreach ($errors->all() as $error)
						{!! $error !!}
					@endforeach
				</div>
			@elseif(isset($errors) && count($errors) > 1)
				<div class="alert alert-danger">
					<p style="margin: 0;">
						@foreach ($errors->all() as $error)
							{!! $error !!}<br>
						@endforeach
					</p>
				</div>
			@endif
			@if(session('alert'))
				<div class="alert alert-{{ session('alert') }}">
					{!! session('message') !!}
				</div>
			@endif
			<div class="row">
				<div class="col">
					<h3>Basic Info</h3>
					<hr>
					<div class="form-group">
						<label for="entity_name">Entity Name</label>
						<input type="text" class="form-control" id="entity_name" name="entity_name" value="{{ old('entity_name') }}">
						<small id="entity_nameHelp" class="form-text text-muted">Example: Post Entity</small>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<h3>Configuration</h3>
					<hr>
					<div class="form-group">
						<label for="table_name">Table Name</label>
						<input type="text" class="form-control" id="table_name" name="table_name" value="{{ old('table_name') }}">
						<small id="table_nameHelp" class="form-text text-muted">Example: posts</small>
					</div>
					<div class="form-group">
						<label for="model_name">Model Name</label>
						<input type="text" class="form-control" id="model_name" name="model_name" value="{{ old('model_name') }}">
						<small id="model_nameHelp" class="form-text text-muted">Example: Post</small>
					</div>
					<div class="form-group">
						<label for="controller_name">Controller Name</label>
						<input type="text" class="form-control" id="controller_name" name="controller_name" value="{{ old('controller_name') }}">
						<small id="controller_nameHelp" class="form-text text-muted">Example: PostController</small>
					</div>
					<div class="form-group">
						<label for="route_prefix">Route Prefix</label>
						<input type="text" class="form-control" id="route_prefix" name="route_prefix" value="{{ old('route_prefix') }}">
						<small id="route_prefixHelp" class="form-text text-muted">Example: posts</small>
					</div>
					<div class="form-group">
						<label for="views_dir">Views Directory</label>
						<input type="text" class="form-control" id="views_dir" name="views_dir" value="{{ old('views_dir') }}">
						<small id="views_dirHelp" class="form-text text-muted">Example: posts</small>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col">
					<h3>Table Fields</h3>
					<hr>
					<div id="table-fields-container">
						<div class="row" style="display: none">
							<div class="col">
								<div class="form-group">
									<label for="table_field_name">Name</label>
									<input type="text" class="form-control" id="table_field_name">
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="table_field_display">Display</label>
									<input type="text" class="form-control" id="table_field_display">
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<label for="table_field_type">Type</label>
									<select id="table_field_type" class="form-control">
										<option value="integer">integer</option>
										<option value="string">string</option>
										<option value="longText">longText</option>
									</select>
								</div>
							</div>
							<div class="col actions">
								<div class="form-group">
									<a href="javascript:" class="btn btn-sm btn-danger btn-delete-field">Delete</a>
								</div>
							</div>
						</div>
						@if(old('table_field_name'))
							@foreach(old('table_field_name') as $key => $value)
								@if($value)
									<div class="row">
										<div class="col">
											<div class="form-group">
												<label for="table_field_name">Name</label>
												<input type="text" class="form-control" id="table_field_name" name="table_field_name[]" value="{{ old('table_field_name')[$key] }}">
											</div>
										</div>
										<div class="col">
											<div class="form-group">
												<label for="table_field_display">Display</label>
												<input type="text" class="form-control" id="table_field_display" name="table_field_display[]" value="{{ old('table_field_display')[$key] }}">
											</div>
										</div>
										<div class="col">
											<div class="form-group">
												<label for="table_field_type">Type</label>
												<select id="table_field_type" name="table_field_type[]" class="form-control">
													<option value="integer" @if(old('table_field_type')[$key] === 'integer') selected @endif>integer</option>
													<option value="string" @if(old('table_field_type')[$key] === 'string') selected @endif>string</option>
													<option value="longText" @if(old('table_field_type')[$key] === 'longText') selected @endif>longText</option>
												</select>
											</div>
										</div>
										<div class="col actions">
											<div class="form-group">
												<a href="javascript:" class="btn btn-sm btn-danger btn-delete-field">Delete</a>
											</div>
										</div>
									</div>
								@endif
							@endforeach
						@endif
					</div>
					<a href="javascript:" class="btn btn-success" onclick="addNewField()">Add New</a>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col">
					<button type="submit" class="btn btn-primary">Create Entity</button>
				</div>
			</div>
		</form>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script>
        let fieldsCount = 0;

        function addNewField() {
            let el = $('#table-fields-container .row').clone().show();
            el.find('label[for="table_field_name"]').attr('for', 'table_field_name_' + fieldsCount);
            el.find('#table_field_name').attr('id', 'table_field_name_' + fieldsCount).attr('name', 'table_field_name[]');
            el.find('label[for="table_field_display"]').attr('for', 'table_field_display_' + fieldsCount);
            el.find('#table_field_display').attr('id', 'table_field_display_' + fieldsCount).attr('name', 'table_field_display[]');
            el.find('label[for="table_field_type"]').attr('for', 'table_field_type_' + fieldsCount);
            el.find('#table_field_type').attr('id', 'table_field_type_' + fieldsCount).attr('name', 'table_field_type[]');
            $('#table-fields-container').append(el[0]);
            fieldsCount++;
        }

        $('#table-fields-container').on('click', '.btn-delete-field', function () {
            $(this).parent().parent().parent().remove();
        });
	</script>
</body>
</html>