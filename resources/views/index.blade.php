@include('template.header.default')

{{-- Content Section --}}

<div class="container" ng-controller="CategoryListController as categoryListCtrl">
	<div class="row">
		<div class="col-xs-6">
			<div class="col-xs-6">
				@include('category.add')
			</div>
			<div class="col-xs-6" ng-show="isSelected">
				@include('category.edit')
			</div>
			<div class="col-xs-12">
				@include('category.index')
			</div>
		</div>
		<div class="col-xs-6" ng-show="isSelected">
			<div class="panel panel-default">
				<div class="panel-heading">
					<div class="row">
						<div class="col-xs-7">
							<h3 class="panel-title" ng-model="selectedTitle">@{{ selectedTitle }}</h3>
						</div>
						<div class="col-xs-5 text-right">
							<a href="#" ng-click="showNewPostForm(true)">
								<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>&nbsp; Add new Post
							</a>
						</div>
					</div>
				</div>
				<div class="panel-body">
					@include('post.add')
					@include('post.edit')
				</div>
				@include('post.index')
			</div>
		</div>
	</div>
</div>

@include('template.footer.default')