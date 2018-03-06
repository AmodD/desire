
<section class="section">
    <div class="container">

	<div id="app">
	@if(Route::currentRouteName() == 'create' )
		<demo selected="message"></demo> 
		<create></create>
	@elseif(Route::currentRouteName() == 'auto' )
		<demo selected="auto"></demo> 
		<generate></generate>
	@elseif(Route::currentRouteName() == 'nn' )
		<demo selected="model"></demo> 
		<nn></nn>
	@elseif(Route::currentRouteName() == 'traindata' )
		<demo selected="traindata"></demo> 
		<traindata></traindata>
	@elseif(Route::currentRouteName() == 'relationship' )
		<demo selected="relationship"></demo> 
		<relationship></relationship>
	@else
		<demo selected="home"></demo> 
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
			    <li>Generate Test Data</li>	
			</ul>
		</div>
	@endif
	</div>

    </div>
</section>	

   <script src="{{ asset('js/app.js') }}"></script> 
