
<template>
<div class="content">

  <pre>
// valid set of values
DE2 DE3 DE4 DE18 DE19 DE49 --------- RANDOM VALUES
POSEM = ["010","020","050","900","950","011","021","051","901","951","012","022","052","902","952","016","026","056","906","956"];
POSCC = ["00","01","02","03","05","07","08","52","59"];

// valid TEST DATA    ( ---------- INPUT VECTOR -----------  ) ( OUTPUT )
$n->addTestData(array (DE2,DE3,DE4,DE18,DE19,POSEM,POSCC,DE49),array (1));

// invalid set of values
POSEM = ["100","200","300","400","500","600","700","800"];
POSCC = ["10","20","30","40","50","60","70","80"];

// invalid TEST DATA  ( --- INPUT VECTOR ---  ), ( OUTPUT )    
$n->addTestData(array (0,0,0,0,0,POSEM,POSCC,0),array (0));
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
			<input class="input" type="text" v-model="learningrate" style="width:40%;" disabled>
		  </div></div>
		  <div class="field-label is-normal"> <label class="label">Momentum</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" type="text" v-model="momentum" style="width:40%;" disabled>
		  </div></div>
	        </div>


		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">Model Name</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" type="text" v-model="modelname" :placeholder="modelNameComputed">
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
			inputnodes : 8,
			hiddennodes : 4,
			outputnodes : 1,
			maxtrain : 3,
			epochs : 1000,
			maxerror : 0.01,
			learningrate : 0.1,
			momentum : 0.1,
			modelname : '',
			response : '',
			weights : ''
		}
	},
	    computed : {
		modelNameComputed : function() {
			this.modelname = 'model_'+this.inputnodes+'_'+this.hiddennodes+'_'+this.outputnodes+'_'+this.maxtrain+'_'+this.epochs+'_'+this.maxerror+'_'+this.learningrate+'_'+this.momentum;

			return this.modelname ;
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
		this.modelname = 'model_'+this.inputnodes+'_'+this.hiddennodes+'_'+this.outputnodes+'_'+this.maxtrain+'_'+this.epochs+'_'+this.maxerror+'_'+this.learningrate+'_'+this.momentum ;
	}
    }
</script>    
