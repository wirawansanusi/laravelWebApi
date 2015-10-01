<div class="panel panel-default" ng-show="isShownEditPostForm()">
	<div class="panel-heading">
		<div class="row">
			<h3 class="panel-title col-xs-6">Edit Post</h3> 
			<a href="#" class="col-xs-6 text-right text-muted" ng-click="showEditPostForm(false)">
				<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
			</a>
		</div>
	</div>
	<div class="panel-body">
		<div class="row" ng-if="images">
			<div class="col-xs-12">
				<label>Uploaded Thumbnail</label>
			</div>
			<div class="col-xs-4" ng-repeat="image in images">
				<div class="thumbnail">
					<img src="@{{ image['data']['encoded'] }}" />
					<div class="caption text-center">
						<button type="button" class="btn btn-danger" ng-click="deleteThumbnail(image['id'])">Delete</button>
					</div>
				</div>
			</div>
		</div>
		<form>
			<div class="form-group">
				<label for="post-title">Title</label>
				<input type="text" name="post-title" class="form-control" ng-model="selectedPost.title" />
			</div>
			<div class="form-group">
				<label for="post-body">Body</label>
				<textarea name="post-body" class="form-control" rows="5" ng-model="selectedPost.body"></textarea>
			</div>
		</form>
		<div class="form-group">
			<label for="post-thumbnail">Drag &amp; Drop / Click to Upload Thumbnail here..</label>
			<form class="thumbnail-form" dropzone="updatePostThumbnailConfig">
				<input type="hidden" name="_token" value="{!! csrf_token() !!}">
			</form> 
			<button type="button" class="btn btn-primary" ng-click="updateThumbnail(selectedPost)">Upload</button>
			<div class="alert alert-danger" role="alert" ng-show="selectedEditPostError" >@{{ selectedEditPostError }}</div>
		</div>
	</div>
</div>