<table class="table table-hover">
	<tr>
		<th>ID</th>
		<th>Title</th>
	</tr>
	<tr ng-repeat="post in posts">
		<td>@{{ post.id }}</td>
		<td>
			<div class="row">
				<div class="col-xs-12">@{{ post.title }}</div>
				<div class="col-xs-6">
					<a href="#" ng-click="editPost(post)">
						<span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>&nbsp; Show
					</a>
				</div>
				<div class="col-xs-6 text-right">
					<a href="#" class="text-danger" ng-click="deletePost(post)">
						<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>&nbsp; Delete
					</a>
				</div>

			</div>
		</td>
	</tr>
</table>