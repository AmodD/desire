
<section class="section">
    <div class="container">


	<div class="tabs">
	  <ul>
	    <li class="is-active"><a href="/">Home</a></li>
	    <li><a href="/create">Create a Message</a></li>
	    <li><a href="/auto">Auto Generate</a></li>
	    <li><a href="/demo">Machine Learning</a></li>
	  </ul>
	</div>

	<div id="app">
	@if(Route::currentRouteName() == 'create' )
		<create></create>
	@elseif(Route::currentRouteName() == 'auto' )
		<generate></generate>
	@elseif(Route::currentRouteName() == 'demo' )
		<demo></demo>
	@else
<div class="content">
  <h1>POC 8385 ML</h1>
<h2>Goals</h2>
  <p>Will try and achieve following </p>
  <ul>
    <li>Finalize a few DEs</li>
    <li>Manually create a msg by enetering DE data</li>
    <li>Automatically generate msgs through a click event</li>
    <li></li>	
    <li>Zero down on a ML Algo</li>	
    <li></li>	
    <li></li>	
    <li>Asign scores to through ML for each txn</li>
    <li></li>	
    <li></li>	
  </ul>
</div>



	@endif
	</div>

    </div>
</section>	

   <script src="{{ asset('js/app.js') }}"></script> 
