(function(){
	angular.module('dropzone', []).directive('dropzone', function () {
	  return function (scope, element, attrs) {
	    var config, dropzone;

	    config = scope[attrs.dropzone];

	    // create a Dropzone for the element with the given options
	    dropzone = new Dropzone(element[0], config.options);

	    // bind the given event handlers
	    angular.forEach(config.eventHandlers, function (handler, event) {
	      dropzone.on(event, handler);
	    });
	  };
	});
	angular.module('mlmfoto', ['dropzone', 'angular-loading-bar'])
		.controller('CategoryListController', function($scope, $http){
			$http.get('./api/category')
				.success(function(data){
					$scope.categories = data;
				});
			$scope.clearSelected = function(){ $scope.isSelected = false }
			$scope.createCategory = function(){
				var category = { parent_id: '0' };
				if($scope.selectedNewCategory != null){
					category.parent_id = $scope.selectedNewCategory.id;
					category.temp_id = $scope.selectedNewCategory.temp_id;
				}
				if($scope.selectedNewTitle != null){
					category.title = $scope.selectedNewTitle;
				}
				$http.post('./api/category', category)
					.success(function(data){
						$http.get('./api/category')
							.success(function(data){
								$scope.categories = data;
							});
					}).error(function(error, status){
						$scope.selectedNewError = error.title[0];
					});
			}
			$scope.editCategory = function(category, index){
				if(category.child_id == undefined){
					$scope.selectedCategoryId = category.id;
				}else{
					$scope.selectedCategoryId = category.child_id;
				}
				$http.get('./api/category/' + $scope.selectedCategoryId)
					.success(function(data){
						if(data.child == null){
							if(category.parentTemp_id != null && data.parent_id != null){
								$scope.selected = $scope.categories[category.parentTemp_id].child[index];
								$scope.selectedCategory = $scope.categories[category.parentTemp_id];
							}else{
								$scope.selected = $scope.categories[index];
								$scope.selectedCategory = $scope.categories[data.parent_id-1];
							}
							if(data.temp_id != undefined){
								$scope.selected.temp_id = data.temp_id;
							}
							$scope.selectedTitle = data.title;
							$scope.selectedChildIndex = index;
							$scope.selectedParentIndex = data.parent_id;
							$http.get('./api/category/' + $scope.selectedCategoryId + '/post')
								.success(function(data){
									$scope.posts = data;
									$scope.isSelected = true;
								});
						} else {
							alert('Please remove subcategory first!');
							$scope.isSelected = false;
						}
					});
			}
			$scope.updateCategory = function(selected){
				var category = { parent_id: '0', temp_id: '0' };
				if($scope.selectedCategory != null){
					category.parent_id = $scope.selectedCategory.id;
					category.parentTemp_id = $scope.selectedCategory.temp_id;
				}
				if($scope.selectedTitle != null){
					category.title = $scope.selectedTitle;
				}
				if(selected.child_id != null){
					category.id = selected.child_id;
				}else{
					category.id = selected.id;
				}
				if(selected.parent_id){
					category.temp_id = selected.temp_id;
				}
				$http.put('./api/category/' + category.id, category)
					.success(function(data){
						$http.get('./api/category')
							.success(function(data){
								$scope.categories = data;
								$scope.isSelected = false;
							});
					}).error(function(error, status){
						$scope.selectedError = error.title[0];
					});
			}
			$scope.deleteCategory = function(selected){
				var category = { parent_id: '0', temp_id: '0' };
				if($scope.selectedCategory != null){
					category.parent_id = $scope.selectedCategory.id;
					category.parentTemp_id = $scope.selectedCategory.temp_id;
				}
				if($scope.selectedTitle != null){
					category.title = $scope.selectedTitle;
				}
				if(selected.child_id != null){
					category.id = selected.child_id;
				}else{
					category.id = selected.id;
				}
				if(selected.parent_id){
					category.temp_id = selected.temp_id;
				}
				$http.delete('./api/category/' + category.id)
					.success(function(data){
						$http.get('./api/category')
							.success(function(data){
								$scope.categories = data;
								$scope.isSelected = false;
							});
					}).error(function(error, status){
						$scope.selectedError = error.title[0];
					});
			}
			$scope.newPostForm = false;
			$scope.editPostForm = false;
			$scope.showNewPostForm = function(bool){
				$scope.newPostForm = bool;
			}
			$scope.showEditPostForm = function(bool){
				$scope.editPostForm = bool;
			}
			$scope.isShownNewPostForm = function(){
				return $scope.newPostForm;
			}
			$scope.isShownEditPostForm = function(){
				return $scope.editPostForm;
			}
			/* $scope.createPost = function(post){
				$http.post('./api/category/' + $scope.selected.id + '/post', post)
					.success(function(data){
						$http.get('./api/category/' + $scope.selected.id + '/post')
							.success(function(data){
								$scope.posts = data;
							});
					}).error(function(error, status){
						$.each(error, function(key, value){
							$scope.selectedNewPostError = value[0];
						})
					});
			} */
			$scope.editPost = function(post){
				$http.get('./api/category/' + post.category_id + '/post/' + post.id)
					.success(function(data){
						$scope.selectedPost = data;
						$scope.editPostForm = true;
						$http.get('./api/category/' + post.category_id + '/post/' + post.id + '/thumbnail')
					        .success(function(data){
					          $scope.images = data;
					        }).error(function(error, status){
					          console.log(error);
					        });
					}).error(function(error, status){
						$scope.selectedEditPostError = error.title[0];
					});
			}
			/* $scope.updatePost = function(post){
				$http.put('./api/category/' + post.category_id + '/post/' + post.id, post)
					.success(function(){
						$scope.editPostForm = false;
						$http.get('./api/category/' + post.category_id + '/post')
							.success(function(data){
								$scope.posts = data;
							});
					}).error(function(error, status){
						$.each(error, function(key, value){
							$scope.selectedEditPostError = value[0];
						})
					});
			} */
			$scope.deletePost = function(post){
				$http.delete('./api/category/' + post.category_id + '/post/' + post.id + '/thumbnail')
					.success(function(){
						console.log(post.category_id);
						$http.get('./api/category/' + post.category_id + '/post')
							.success(function(data){
								$scope.posts = data;
							});
					}).error(function(error, status){
						console(error);
					});
			}
	      	$scope.triggerCreateThumbnail = function(){
	        	$scope.uploadWithThumbnail = true;
	      	}
	      	$scope.showUploadButton = function(){
	        	return $scope.uploadButton;
	      	}
	      	$scope.createThumbnail = function(post){
				$http.post('./api/category/' + $scope.selectedCategoryId + '/post', post)
					.success(function(data){
						$scope.post = {};
			        	$scope.selectedPost = data;
			        	if($scope.createWithThumbnail == true){
			          		$scope.createPostThumbnail.processQueue();
			        	}
						$http.get('./api/category/' + $scope.selectedCategoryId + '/post')
							.success(function(data){
								$scope.posts = data;
							});
					}).error(function(error, status){
						$.each(error, function(key, value){
							$scope.selectedNewPostError = value[0];
						})
					});
	      	}
	      	$scope.updateThumbnail = function(post){
				$http.put('./api/category/' + post.category_id + '/post/' + post.id, post)
					.success(function(){
			        	if($scope.updateWithThumbnail == true){
			          		$scope.updatePostThumbnail.processQueue();
			        	}
						$http.get('./api/category/' + post.category_id + '/post')
							.success(function(data){
								$scope.posts = data;
							});
					}).error(function(error, status){
						$.each(error, function(key, value){
							$scope.selectedEditPostError = value[0];
						})
					});
	      	}
	      	$scope.deleteThumbnail = function(id){
	        	$http.delete('./api/category/' + $scope.selectedCategoryId + '/post/' + $scope.selectedPost.id + '/thumbnail/' + id)
	          		.success(function(data){
	            		$http.get('./api/category/' + $scope.selectedCategoryId + '/post/' + $scope.selectedPost.id + '/thumbnail')
	              			.success(function(data){
	                			$scope.images = data;
	              			}).error(function(error, status){
	                			console.log(error);
	              			});
	          		}).error(function(error, status){
	            		console.log(error);
	          		});
	      	}
	      	$scope.createPostThumbnailConfig = {
	        	'options': {
	        		'url': '.api/category',
	          		'autoProcessQueue': false,
	          		'uploadMultiple': true,
	          		'parallelUploads': 20,
	          		'init': function(){ 
	          			$scope.createPostThumbnail = this;
	          			$scope.createWithThumbnail = false;
	          			this.on("addedfile", function(file) {
							file.previewElement.addEventListener("click", function() { $scope.createPostThumbnail.removeFile(file); });
						});
	          			this.on('processing', function(file) {
					    	this.options.url = './api/category/' + $scope.selected.id + '/post/' + $scope.selectedPost.id + '/thumbnail';
					    });
					    this.on('complete', function(file) {
					    	this.removeFile(file);
					    });
	          		}
	        	},
	        	'eventHandlers': {
	          		'success': function (file, response) { console.log(response); },
	          		'error': function (error, message) { console.log(message); },
	          		'addedfile': function (file) { $scope.createWithThumbnail = true; },
				    'sending': function(file, xhr, formData) { formData.append('_token', $('[name=_token').val()); }
	        	}
	      	};
	      	$scope.updatePostThumbnailConfig = {
	        	'options': {
	        		'url': '.api/category',
	          		'autoProcessQueue': false,
	          		'uploadMultiple': true,
	          		'parallelUploads': 20,
	          		'init': function(){ 
	          			$scope.updatePostThumbnail = this;
	          			$scope.updateWithThumbnail = false;
	          			this.on("addedfile", function(file) {
							file.previewElement.addEventListener("click", function() { $scope.updatePostThumbnail.removeFile(file); });
						});
	          			this.on('processing', function(file) {
					    	this.options.url = './api/category/' + $scope.selected.id + '/post/' + $scope.selectedPost.id + '/thumbnail';
					    });
					    this.on('complete', function(file) {
					    	this.removeFile(file);
					    });
	          		}
	        	},
	        	'eventHandlers': {
	          		'success': function (file, response) { console.log(response); $scope.editPostForm = false; },
	          		'error': function (error, message) { console.log(message); },
	          		'addedfile': function (file) { $scope.updateWithThumbnail = true; },
				    'sending': function(file, xhr, formData) { formData.append('_token', $('[name=_token').val()); }
	        	}
	      	};
		});
})();