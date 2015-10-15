<!DOCTYPE html>
<html lang="en">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>MLMFoto Products List</title>
<link href="{!! asset('css/app.css') !!}" media="all" rel="stylesheet" type="text/css" />
<link href="{!! asset('css/all.css') !!}" media="all" rel="stylesheet" type="text/css" />
<link href="{!! asset('css/loading-bar.css') !!}" media="all" rel="stylesheet" type="text/css" />
<link href="{!! asset('css/dropzone.css') !!}" media="all" rel="stylesheet" type="text/css" />
<body ng-app="mlmfoto">

	{{-- Header Section --}}
	<header class="navbar navbar-inverse navbar-fixed-top" ng-controller="VersionController as versionCtrl">
  		<div class="container-fluid">
			<div class="navbar-header">
		      <a class="navbar-brand" href="#">&nbsp;&nbsp; MLMFOTO Products List @{{ "ver." + versionUpdate }}</a>
		      <div class="navbar-form navbar-left" role="search">
		        <button type="submit" class="btn btn-warning" ng-click="createVersionUpdate()">Update Version</button>
		      </div>
		    </div>

	    	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	    		<ul class="nav navbar-nav navbar-right">
		        <li><a href="{{ url('/logout') }}"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Logout</a></li>
	    	</div>
	    </div>
	</header>