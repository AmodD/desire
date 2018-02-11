
<section class="section">
    <div class="container">


	<div class="tabs">
	  <ul>
	    <li class="is-active"><a href="/">Home</a></li>
	    <li><a href="/create">Create a Message</a></li>
	    <li><a href="/auto">Auto Generate</a></li>
	    <li><a href="/demo">Machine Learning Demo</a></li>
	  </ul>
	</div>

	<div id="app">
	@if(Route::currentRouteName() == 'create' )
		<create></create>
	@endif
	@if(Route::currentRouteName() == 'auto' )
		<generate></generate>
	@endif
	@if(Route::currentRouteName() == 'demo' )
		<demo></demo>
	@endif
	</div>

    </div>
</section>	

   <script src="{{ asset('js/app.js') }}"></script> 
