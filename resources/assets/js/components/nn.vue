
<template>
<div class="content">

  <pre>
// We are training the ANN with a basic crude set of data as below
// the first test data has input vector as ...1111111... with target output as 1
// the second test data has input vector as ...00000... with target output as 0
$n->addTestData(array (1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
       		array (1));
$n->addTestData(array (0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
		array (0));
  </pre>
		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">ANN nodes</label> </div>
	          <div class="field-body"><div class="field">
			input
			<input class="input" type="text" v-model="inputnodes" disabled style="width:20%;">
			hidden
			<input class="input" type="text" v-model="hiddennodes" style="width:20%;">
			output
			<input class="input" type="text" v-model="outputnodes" disabled style="width:20%;">
		  </div></div>
	        </div>

		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">Iterations</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" type="text" v-model="maxtrain" style="width:40%;">
		  </div></div>
		  <div class="field-label is-normal"> <label class="label">Epochs</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" type="text" v-model="epochs" style="width:40%;">
		  </div></div>
		  <div class="field-label is-normal"> <label class="label">Error</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" type="text" v-model="maxerror" style="width:40%;">
		  </div></div>
		  <div class="field-label is-normal"> <label class="label">Learning</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" type="text" v-model="learningrate" style="width:40%;">
		  </div></div>
		  <div class="field-label is-normal"> <label class="label">Momentum</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" type="text" v-model="momentum" style="width:40%;">
		  </div></div>
	        </div>


		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">Model Name</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" type="text" v-model="modelNameComputed">
		  </div></div>
		  <div class="field-body">
		    <div class="field">
		      <div class="control">
		        <button v-on:click="create" class="button is-primary">
		          Create Model
		        </button>
		    &nbsp; {{ response }}
		      </div>
		    </div>
		  </div>
		</div>  	 
		<span v-html="weights"></span></p>

</div>
</template>


<script>
    export default {

	data : function() {
		return {
			results : [],
			inputnodes : 64,
			hiddennodes : 8,
			outputnodes : 1,
			maxtrain : 3,
			epochs : 1000,
			maxerror : 0.01,
			learningrate : 0.1,
			momentum : 0.8,
			modelname : '',
			response : '',
			weights : ''  
		}
	},
	    computed : {
		modelNameComputed : function() {
			return 'model_'+this.inputnodes+'_'+this.hiddennodes+'_'+this.outputnodes+'_'+this.maxtrain+'_'+this.epochs+'_'+this.maxerror+'_'+this.learningrate+'_'+this.momentum;
		}
	    },
	methods : {
		create(){
		let self = this;	
		axios.post('/savemodel',{ 
			nodes: this.hiddennodes,
			max: this.maxtrain,
			epochs : this.epochs,
			error : this.maxerror,
			learning : this.learningrate,
			momentum : this.momentum,
			name : this.modelNameComputed
		})
//		.then(response => this.response = response.data)
		.then(function (response) {
			    console.log(response);
			    self.response = response.data;
			    self.load();
		})
		.catch(function (error) {
			   console.log(error);
		 });
		},
		load(){
			   console.log("in load");
			   axios.get('/loadmodelstats')
				.then(response => this.weights = response.data)
				.catch(function (error) {
					   console.log(error);
				 });
		}
	},
        mounted() {
                console.log('NN Component mounted.');
	}
    }
</script>    
