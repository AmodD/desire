
<template>
<div class="columns content">

<div class="column">
<h4>Create a Relationship</h4>	
  <div class="select is-multiple">
	  <p><input class="input" type="text" v-model="relationshipname"  placeholder="Give a name to the relationship"></p>
	  <p>  <select multiple size="10" v-model="selectedfields" multiple>
		  <option disabled value="">Select multiple Data Elements</option>
		  <option v-for="field in fields" :value="field.id" >{{ field.id }} - {{ field.element }}</option>
	       </select> 
	  </p>    
	  <p>  <button v-on:click="create" class="button is-info is-rounded ">Create with selected DE numbers {{ selectedfields }}</button></p>
  </div>
</div>

<div class="column">
<h4>Add Labels(Target Scores / Possible Outputs) to a Relationship</h4>	
  <div class="select">
	<p><select v-model="selectedrelationship" v-on:change="getlabels">
		  <option disabled value="">Select a relationship</option>
		  <option v-for="relationship in relationships" :value="relationship.id" > {{ relationship.name }}</option>
	</select>
	</p>
	<p><ul><li v-for="label in labels">{{ label.value }}</li></ul></p>	
	<p><input class="input" type="text" v-model="inputlabel"  placeholder="Name the label"></p>
	<p><button v-on:click="addlabel" class="button is-success">Add</button></p>
  </div>	
</div>

</div>
</template>


<script>
    export default {

	data : function() {
		return {
			fields : [],
			selectedfields : [],
			relationshipname : "",
			relationships : [],
			selectedrelationship : "",
			labels : [],
			inputlabel : ""
		}
	},
	methods : {
		getlabels(){
			axios.get('/labels?relid='+this.selectedrelationship)
			     .then(response => this.labels = response.data)
			     .catch(function (error) {
			     console.log(error);
			  });
		},
		addlabel(){
		let self = this;	
			axios.post('/labels', {
			    relationship: this.selectedrelationship,
			    label: this.inputlabel 
			  })
			  .then(function (response) {
			    self.getlabels();
			    console.log(response);
			  })
			  .catch(function (error) {
			    console.log(error);
			  });

		},
		create(){
		let self = this;	
			axios.post('/relationships', {
			    name: this.relationshipname,
			    fields: this.selectedfields 
			  })
			  .then(function (response) {
			    self.getRelationships();
			    console.log(response);
			  })
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
		}
	},
        mounted() {
                console.log('BP Component mounted.');
//		let self = this;	
		axios.get('/allfields')
		     .then(response => this.fields = response.data)
//			.then(function (response) {
//			    console.log(response);
//			    self.fields = response; 
//			})
		     .catch(function (error) {
		     console.log(error);
		 });

		 this.getRelationships();


	}
    }
</script>    
