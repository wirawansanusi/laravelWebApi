<div class="panel panel-default" ng-show="isShownNewPostForm()">
	<div class="panel-heading">
		<div class="row">
			<h3 class="panel-title col-xs-6">Create Post</h3> 
			<a href="#" class="col-xs-6 text-right text-muted" ng-click="showNewPostForm(false)">
				<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
			</a>
		</div>
	</div>
	<div class="panel-body">
		<form>
			<div class="form-group">
				<label for="post-title">Title</label>
				<input type="text" name="post-title" class="form-control" ng-model="post.title" />
			</div>
			<div class="form-group">
				<label for="post-body">Body</label>
				<textarea name="post-body" class="form-control" rows="5" ng-model="post.body"></textarea>
			</div>
		</form>
		<div class="form-group">
			<label for="post-thumbnail">Drag &amp; Drop / Click to Upload Thumbnail here..</label>
			<form class="thumbnail-form" dropzone="createPostThumbnailConfig">
				<input type="hidden" name="_token" value="{!! csrf_token() !!}">
			</form>
			<button type="button" class="btn btn-primary" ng-click="createThumbnail(post)">Upload</button>
			<div class="alert alert-danger" role="alert" ng-show="selectedNewPostError" >@{{ selectedNewPostError }}</div>
		</div>
	</div>
</div>