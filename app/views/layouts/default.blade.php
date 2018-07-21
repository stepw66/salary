<!doctype html>
<html>
<head>
	@include('includes.head')
</head>
<body>

	
	@include('includes.header')
	

	<div class="g-page">
		<div class="g-box">
			<div class="uk-container uk-container-center">
				<div class="uk-grid" data-uk-grid-margin="" data-uk-grid-match="">
					<div class="uk-width-medium-1-1">

						@yield('content')

					</div>						
				</div>
			</div>
		</div>
	</div>

	
	@include('includes.footer')
	

</body>
</html>