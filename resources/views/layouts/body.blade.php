
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
	@elseif(Route::currentRouteName() == 'simulator' )
		<demo selected="simulator"></demo> 
		<simulator pocurl="{{ env('POC_URL') }}"></simulator>
	@elseif(Route::currentRouteName() == 'transactions' )
		<demo selected="transactions"></demo> 
		<transactions></transactions>
	@else
		<demo selected="home"></demo> 
		<div class="content">
			<h1>POC 8385 ML</h1>
			<h2>Goals</h2>
			<h5><u>Stage 3</u> <span class="tag is-warning">In Progress</span></h5>
			<p>Simulator & Expansion of Fields Coverage</p>
			<ul>
			    <li>Add Fields with more DEs</li>
			    <li>Create a Simulator</li>
			    <li>Generate Data</li>
			</ul>
			<h5><u>Stage 2</u> <span class="tag is-success">Complete</span></h5>
			<p>Modelling Data & finalizing Algorithms for Relationships</p>
			<ul>
			    <li>Create a relationship</li>
			    <li>Define Labels</li>
			    <li>Add Data</li>
			    <li>Select Algorithms</li>
			    <li>Make Models</li>
			    <li>Test Data</li>
			</ul>
			<h5><u>Stage 1</u> <span class="tag is-success">Complete</span></h5>
			<p>Play around ISO8385 Message & Create a Neural Network Model</p>
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
