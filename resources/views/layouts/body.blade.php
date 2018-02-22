
<section class="section">
    <div class="container">
	<div class="tabs">
	  <ul>
	    <li class="is-active"><a href="/">Home</a></li>
	    <li><a href="/create">Create a Message</a></li>
	    <li><a href="/auto">Auto Generate</a></li>
	    <li><a href="/nn">Create a Model</a></li>
	  </ul>
	</div>

	<div id="app">
	@if(Route::currentRouteName() == 'create' )
		<create></create>
	@elseif(Route::currentRouteName() == 'auto' )
		<generate></generate>
	@elseif(Route::currentRouteName() == 'demo' )
		<demo></demo>
	@elseif(Route::currentRouteName() == 'nn' )
		<nn></nn>
	@elseif(Route::currentRouteName() == 'bp' )
		<bp></bp>
	@else
		<div class="content">
			<h1>POC 8385 ML</h1>
			<h2>Goals</h2>
			<p>Will try and achieve following </p>
			<ul>
			    <li>Finalize a few DEs</li>
			    <li>Manually create a msg by enetering DE data</li>
			    <li>Automatically generate msgs through a click event</li>
			    <li>Test a feature by creating a vector of 1's and 0's on presense </li>	
			    <li>Use a Neuron Network to generate a score for given vector</li>	
			    <li>Asign scores to through ML for each txn</li>
			    <li>Create a Model with weights from variable input params</li>	
			    <li>Store it in the database and use it to assign scores</li>	
			</ul>
		</div>
	@endif
	</div>

    </div>
</section>	

   <script src="{{ asset('js/app.js') }}"></script> 
