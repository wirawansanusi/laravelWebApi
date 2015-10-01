<div class="panel panel-default">
	<div class="panel-heading">
		<div class="row">
			<h3 class="panel-title col-xs-6">Edit</h3> 
			<a href="#" class="col-xs-6 text-right text-muted" ng-click="clearSelected()">
				<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
			</a>
		</div>
	</div>
	<div class="panel-body">
		<form>
			<div class="form-group">
				<label for="parent-category">Category</label>
				<select name="parent-category" class="form-control" ng-model="selectedCategory" ng-options="category.title for category in categories">
					<option value="">-- none --</option>
				</select>
			</div>
			<div class="form-group">
				<label for="category-title">title</label>
				<input type="text" name="category-title" class="form-control" ng-model="selectedTitle"/>
			</div>
			<div class="form-group">
				<div class="row">
					<div class="col-xs-5">
						<button class="btn btn-warning" type="button" ng-click="updateCategory(selected)">Update</button>
					</div>
					<div class="col-xs-5 col-xs-offset-2 text-right">
						<button class="btn btn-danger" type="button" ng-click="deleteCategory(selected)">Delete</button>
					</div>
				</div>
			</div>
			<div class="alert alert-danger" role="alert" ng-show="selectedError" >@{{ selectedError }}</div>
		</form>
	</div>
</div>