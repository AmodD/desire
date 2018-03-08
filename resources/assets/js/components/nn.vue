
<template>
<div class="content columns">
<!--<form method="POST" action="/savemodel">
		<slot name="csrf-field"></slot>
		<slot name="method-field"></slot>
-->
<div class="column">
  <p>	
  <div class="select">
	<select v-model="selectedrelationship" v-on:change="getlabels">
		  <option disabled value=0>Select a relationship</option>
		  <option v-for="relationship in relationships" :value="relationship.id" > {{ relationship.name }}</option>
	</select>
  </div>
  </p>
  <p>  
  <div class="select">
	<select v-model="selectedalgorithm" v-on:change="getlabels">
		  <option disabled value=0>Select a algorithm</option>
		  <option v-for="algorithm in algorithms" :value="algorithm.id" > {{ algorithm.name }}</option>
	</select>
  </div>
  </p>

   <p><input class="input" name="name" type="text" v-model="modelname" :placeholder="modelname"></p>

   <p><button v-on:click="create" class="button is-warning">Create & Train a  Model</button></p>

   <p><h5>{{ response }}</h5></p>

</div>

<div v-if="false" class="column">
		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">ANN nodes</label> </div>
	          <div class="field-body"><div class="field">
			input
			<input class="input" name="inputnodes"  type="text" v-model="inputnodes" disabled style="width:20%;">
			hidden
			<input class="input" name="nodes"  type="text" v-model="hiddennodes" style="width:20%;">
			output
			<input class="input" name="outputnodes" type="text" v-model="outputnodes" disabled style="width:20%;">
		  </div></div>
	        </div>

		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">Iterations</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" name="max" type="text" v-model="maxtrain" style="width:40%;">
		  </div></div>
		  <div class="field-label is-normal"> <label class="label">Epochs</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" name="epochs" type="text" v-model="epochs" style="width:40%;">
		  </div></div>
		  <div class="field-label is-normal"> <label class="label">Error</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" name="error" type="text" v-model="maxerror" style="width:40%;">
		  </div></div>
		  <div class="field-label is-normal"> <label class="label">Learning</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" name="learning" type="text" v-model="learningrate" style="width:40%;" disabled>
		  </div></div>
		  <div class="field-label is-normal"> <label class="label">Momentum</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" name="momentum" type="text" v-model="momentum" style="width:40%;" disabled>
		  </div></div>
	        </div>


		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">Model Name</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" name="name" type="text" v-model="modelname" :placeholder="modelNameComputed">
		  </div></div>
		  <div class="field-body">
		    <div class="field">
		      <div class="control">
		        <button v-on:click="save" class="button is-primary">
		          Save Model
		        </button>
		      </div>
		    </div>
		  </div>
		</div>  	 
		<span v-html="response"></span>
		<span v-html="weights"></span></p>

		<!--  </form>		-->
  </div>
</div>
</template>


<script>
    export default {

	data : function() {
		return {
			results : [],
			algorithms : [],
			selectedalgorithm : 0,
			relationships : [],
			selectedrelationship : 0,
			labels : [],
			inputnodes : 5,
			hiddennodes : 4,
			outputnodes : 1,
			maxtrain : 3,
			epochs : 1000,
			maxerror : 0.01,
			learningrate : 0.1,
			momentum : 0.1,
			modelname : 'Name of the model',
			response : '',
			weights : ''
		}
	},
	    computed : {
		modelNameComputed : function() {
			this.modelname = 'model_'+this.inputnodes+'_'+this.hiddennodes+'_'+this.outputnodes+'_'+this.maxtrain+'_'+this.epochs+'_'+this.maxerror+'_'+this.learningrate+'_'+this.momentum;


//		this.modelname = 'model_'+this.relationships[this.selectedrelationship].name+'_'+this.algorithms[this.selectedalgorithm].name;
			return this.modelname ;
		}
	    },
	methods : {
		getlabels(){
			axios.get('/labels?relid='+this.selectedrelationship)
			     .then(response => this.labels = response.data)
			     .catch(function (error) {
			     console.log(error);
			  });
		name = 'model';
		if(this.selectedrelationship) name = name + '_' + this.relationships[this.selectedrelationship - 1].name ;
		if(this.selectedalgorithm) name = name + '_' + this.algorithms[this.selectedalgorithm - 1].name;

		this.modelname = name.split(' ').join('_');;

		return this.modelname;

		},
		getAlgorithms(){
		axios.get('/algorithms')
		     .then(response => this.algorithms = response.data)
		     .catch(function (error) {
		     console.log(error);
		 });
		},
		getRelationships(){
		axios.get('/relationships')
		     .then(response => this.relationships = response.data)
		     .catch(function (error) {
		     console.log(error);
		 });
		},
		create(){
		let self = this;	
		axios.post('/createmodel',{ 
			relationship: this.selectedrelationship,
			algorithm: this.selectedalgorithm,
			name : this.modelname
		})
		.then(response => this.response = response.data)
		.catch(function (error) {
			   console.log(error);
		 });
		},
		save(){
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
		 this.getRelationships();
		 this.getAlgorithms();
		//this.modelname = 'model_'+this.inputnodes+'_'+this.hiddennodes+'_'+this.outputnodes+'_'+this.maxtrain+'_'+this.epochs+'_'+this.maxerror+'_'+this.learningrate+'_'+this.momentum ;

	}
    }
</script>    
