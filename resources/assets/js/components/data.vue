<template>

<div class="content">	
	<h1>Train Data</h1>
  	<div class="select">
	<p><select v-model="selectedrelationship" v-on:change="getinfo">
		  <option disabled value="">Select a relationship</option>
		  <option v-for="relationship in relationships" :value="relationship.id" > {{ relationship.name }}</option>
	</select>
	</p>
	</div>	

	<table class="tablei is-bordered">
	  <thead>
		  <tr>
		      <th v-for="field in fields">DE{{ field.id  }}</th>
		      <th>Label</th>
		      <th>Action</th>
		  </tr>
          </thead>
	  <tbody>
		<tr>
			<th v-for="field in fields">		  
				<input class="input" v-if="field.id === 2" type="text" v-model="pan"  :placeholder="field.element">
				<input class="input" v-if="field.id === 3" type="text" v-model="procode"  :placeholder="field.element">
				<input class="input" v-if="field.id === 4" type="text" v-model="amount"  :placeholder="field.element">
				<input class="input" v-if="field.id === 18" type="text" v-model="mcc"  :placeholder="field.element">
				<input class="input" v-if="field.id === 19" type="text" v-model="aqcountry"  :placeholder="field.element">
				<input class="input" v-if="field.id === 22" type="text" v-model="posem"  :placeholder="field.element">
				<input class="input" v-if="field.id === 25" type="text" v-model="poscc"  :placeholder="field.element">
				<input class="input" v-if="field.id === 49" type="text" v-model="currency"  :placeholder="field.element">
			</th>
			<th>
  			<div class="select">
			<select v-model="selectedlabel">
			  <option disabled value="">Select a label</option>
			  <option v-for="label in labels" :value="label.id" > {{ label.value }}</option>
			</select>
			</div>	
			</th>	
		        <th><a class="button is-success" v-on:click="adddata">Add</a></th>
		</tr>
		<tr v-if="output">{{ txns }}</tr>
		<tr v-else-if="selectedrelationship" v-for="txn in txns">
			<td v-for="field in txn.data" v-if="defields.includes(field.field_id)">{{ field.value  }}</td>
			<td> {{ scorelabels(txn.pivot.label_id) }}</td>
		        <td><a class="button is-danger is-small" v-on:click="deletedata(txn.id)">Delete</a></td>
		</tr>	
	    <tr><th></th></tr>		  
	  </tbody>
	</table>  



	    	    





</div>
</template>


<script>
    export default {

	data : function() {
		return {
			relationshipname : "",
			relationships : [],
			selectedrelationship : "",
			labels : [],
			fields : [],
			fieldids : [],
			inputlabel : "",
			inputfields : [],
			pan : '',
			currency : '',
			procode : '',
			mcc : '',
			posem : '',
			poscc : '',
			amount : '',
			aqcountry : '',
			selectedlabel : "",
			selectedmodel : 1,
			txns : ''
		}
	},
	computed : {
		output : function() {
			if(this.txns.includes('Error')) return this.txns;
		       else return "";	
		},
		defields : function(){
			var count = this.fields.length ;
			for (var i=0; i<count; i++)
			{
				this.fieldids.push(""+this.fields[i].id) ;
			}

			return this.fieldids ;
//			return ["3","18"];
		}
	},
	methods : {
		scorelabels(score) {
			var scorevalue = "";
			for (var j=0; j<this.labels.length; j++)
			{
			    if((""+this.labels[j].id) === score ) scorevalue = this.labels[j].value ;	
			}

			return scorevalue ;

		},
		deletedata(txnid){
			let self = this;
			axios.delete('/transactions/'+txnid)
			     .then(function (response) {
				self.getLastTxns(self.selectedrelationship);
			  })
			     .catch(function (error) {
			     console.log(error);
			  });
		},
		adddata(){
			axios.get('/analyze?pan='+this.pan+'&currency='+this.currency+'&amount='+this.amount+'&aqcountry='+this.aqcountry+'&procode='+this.procode+'&posem='+this.posem+'&poscc='+this.poscc+'&model='+this.selectedmodel+'&mcc='+this.mcc+'&label='+this.selectedlabel+'&relationship='+this.selectedrelationship)
			  .then(response => this.txns = response.data)
			 // .then(function (response) {
			 //   console.log(response);
			 // })
			  .catch(function (error) {
			    console.log(error);
			  });
		},
		getinfo(){
			this.getlabels();
			this.getfields();
			this.getLastTxns(this.selectedrelationship);
			this.fieldids.length = 0;
			this.pan = '';
			this.currency = '';
			this.procode = '';
			this.mcc = '';
			this.posem = '';
			this.poscc = '';
			this.amount = '';
			this.aqcountry = '';
		},
		getlabels(){
			axios.get('/labels?relid='+this.selectedrelationship)
			     .then(response => this.labels = response.data)
			     .catch(function (error) {
			     console.log(error);
			  });
		},
		getfields(){
			axios.get('/getfields?relationship='+this.selectedrelationship)
			     .then(response => this.fields = response.data)
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
		getLastTxns(rel){
			axios.get('/lasttxns?relationship='+rel)
		     .then(response => this.txns = response.data)
		     .catch(function (error) {
		     console.log(error);
		 });
		}


	},
        mounted() {
                console.log('Test Data Component mounted.');
		this.getRelationships();

	}
    }
</script>    
