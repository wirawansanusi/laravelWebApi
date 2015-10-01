<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title">Add</h3>
	</div>
	<div class="panel-body">
		<form ng-submit="createCategory()">
			<div class="form-group">
				<label for="parent-category">Category</label>
				<select name="parent-category" class="form-control" ng-model="selectedNewCategory" ng-options="category.title for category in categories">
					<option value="">-- none --</option>
				</select>
			</div>
			<div class="form-group">
				<label for="category-title">Title</label>
				<input type="text" name="category-title" class="form-control" ng-model="selectedNewTitle" />
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary">Create</button>
			</div>
			<div class="alert alert-danger" role="alert" ng-show="selectedNewError" >@{{ selectedNewError }}</div>
		</form>
	</div>
</div>