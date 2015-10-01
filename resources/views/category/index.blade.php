<table class="table table-hover table-bordered">
	<tr>
		<th>ID</th>
		<th>Title</th>
	</tr>
	<tr ng-repeat="category in categories">
		<td>@{{ category.id }}</td>
		<td>
			@{{ category.title }} <button ng-click="editCategory(category, $index)">edit</button>
			<ul ng-show="category.child">
				<li ng-repeat="subCategory in category.child">
					@{{ subCategory.title }}
					<button ng-click="editCategory(subCategory, $index)">edit</button>
				</li>
			</ul>
		</td>
	</tr>
</table>