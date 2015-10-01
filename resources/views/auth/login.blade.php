@include('template.header.login')

{{-- CONTENT --}}
<div class="row">
	<div class="col-xs-4 col-xs-offset-4">
		<div class="panel panel-default">
			<div class="panel-heading text-center">
				<img src="{{ asset('images/logo.png') }}" />
			</div>
			<div class="panel-body">
				<form method="POST">
					{!! csrf_field() !!}
					<div class="form-group">
						<label for="email">Email</label>
						<input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Insert your email address here.." />
					</div>
					<div class="form-group">
						<label for="password">Password</label>
						<input type="password" class="form-control" id="password" name="password" placeholder="Insert your password here.." />
					</div>
					<div class="checkbox">
					    <label>
					    	<input type="checkbox" name="remember"> Remember me
					    </label>
					</div>
					<p class="text-danger"></p>
					<div class="form-group text-center">
						<button type="submit" class="btn btn-primary col-xs-12">Login</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@include('template.footer.login')