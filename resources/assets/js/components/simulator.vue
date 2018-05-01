
<template>
	<div class="content">
	<form>
		<h4 class="has-text-centered"><button v-on:click="createsituation" class="button is-success is-outlined ">Click to Create a Situation</button></h4>
		<progress class="progress is-primary" :value="progress" max="100"> </progress>

	  <div class="has-text-centered">	
	  <div class="select">
		<select v-model="whereQ" @change.once="progress = progress +  20" required>
		  <option value="" disabled> Where ?</option>
		  <option v-for="scenario in scenarios" v-if="scenario.question_id == 1" :value="scenario.id">{{ scenario.name }}</option>
		</select>
	  </div>
	  <div class="select">
		<select v-model="whatQ" @change.once="progress = progress +  20" required>
		  <option value="" disabled> What ?</option>
		  <option v-for="scenario in scenarios" v-if="scenario.question_id == 2" :value="scenario.id">{{ scenario.name }}</option>
		</select>
	  </div>
	  <div class="select">
		<select v-model="howQ" @change.once="progress = progress +  20" required>
		  <option value="" disabled> How ?</option>
		  <option v-for="scenario in scenarios" v-if="scenario.question_id == 3" :value="scenario.id">{{ scenario.name }}</option>
		</select>
	  </div>
	  <div class="select">
		<select v-model="whoQ" @change.once="progress = progress +  20" required>
		  <option value="" disabled> Who ?</option>
		  <option v-for="scenario in scenarios" v-if="scenario.question_id == 4" :value="scenario.id">{{ scenario.name }}</option>
		</select>
	  </div>
	  <div class="select">
		<select v-model="whyQ" @change.once="progress = progress +  20" required>
		  <option value="" disabled> Why ?</option>
		  <option v-for="scenario in scenarios" v-if="scenario.question_id == 5" :value="scenario.id">{{ scenario.name }}</option>
		</select>
	  </div>
	  <div class="select" v-if="howQ == 38">
		<select v-model="pinQ"  required>
		  <option value="" disabled> Pin Entry Capability ?</option>
		  <option v-for="scenario in scenarios" v-if="scenario.question_id == 6" :value="scenario.id">{{ scenario.name }}</option>
		</select>
	  </div>
	  </div>
	  </form> 
	  
	  <h2 class="has-text-centered">Generate Transactions</h2>
	  <form>
	  <div class="field has-addons has-addons-centered">
		  <div class="control">
			<div class="select">  
			<select v-model="notxns" required>
		  		<option value="" disabled>Number of Txns</option>
				<option :value="1">1</option>
				<option :value="20">20</option>
				<option :value="500">500</option>
				<option :value="1000">1000</option>
			</select>
			</div>
		  </div>
		  <div class="control">
	  		<button v-on:click="generate()" class="button is-danger is-outlined ">Let's Simulate</button>
		  </div>
		  <div class="control">
			<div class="select">  
			<select v-model="label" required>
		  		<option value="" disabled>Select a Label</option>
				<option :value="1">Valid</option>
				<option :value="2">Invalid</option>
			</select>
			</div>
		  </div>
		  <div class="control">
	  		<div class="select">
				<select name="aggregator" v-on:change="getclients()" v-model="aggregator"  required>
				  <option value="" disabled>Select Aggregator</option>
				  <option v-for="aggregator in aggregators" :value="aggregator.id">{{ aggregator.name }}</option>
				</select>
			</div>
		  </div>	  
		  <div class="control">
	  		<div class="select">
				<select name="client" v-model="client"  required>
				  <option value="" disabled>Select Client</option>
				  <option value=0>000000</option>
				  <option v-for="client in clients" :value="client.id">{{ client.name }}</option>
				</select>
			</div>
		  </div>	  
	   </div>	  
	   </form>	


	<table class="table">
	    <thead>
	      <tr>
		<th></th>      
	        <th>Simulation Scenario</th>
	        <th>Transactions Count</th>
	      </tr>
	    </thead>
	    <tbody>
		<tr v-for="situation in situations">
			<td><label class="radio"><input type="radio" id="situationid" v-model="situationid" :value="situation.id"></label></td>
			<td><span class="is-small">{{ situation.name }}</span></td>
			<td><span>{{ situation.transactions_count }}</span></td>
		</tr>
	    </tbody>
	  </table>

	</div>
</template>

<script>
    export default {
	data : function() {
		return {
			progress : 0,
			whereQ : '',
			whatQ : '',
			howQ : '',
			whoQ : '',
			whyQ : '',
			pinQ : '',
			scenarios : '',
			notxns : "",
			label : '',
			situations : [],
			situationid: 1,
			aggregators : [],
			clients : [],
			aggregator : '',
			client : ''

		}
	},
	props : ['pocurl'],
	computed : {
		simulatorname : function () {
			
			let whereQname = '';	
			let whatQname = '';	
			let howQname = '';	
			let whoQname = '';	
			let whyQname = '';

			if(this.scenarios[(this.whereQ - 1)]) whereQname =  this.scenarios[(this.whereQ -1)].name;
			if(this.scenarios[(this.whatQ - 1)]) whatQname =  this.scenarios[(this.whatQ -1)].name;
			if(this.scenarios[(this.howQ - 1)]) howQname =  this.scenarios[(this.howQ -1)].name;
			if(this.scenarios[(this.whoQ - 1)]) whoQname =  this.scenarios[(this.whoQ -1)].name;
			if(this.scenarios[(this.whyQ - 1)]) whyQname =  this.scenarios[(this.whyQ -1)].name;

			return whereQname +  '-' + whatQname + '-' + howQname + '-' + whoQname + '-' + whyQname ;
		}
	},
	methods : {
		createsituation(){
			console.log("in creating situation");
			
			let self = this ;
			
			if(!this.whereQ || !this.whatQ || !this.howQ || !this.whoQ || !this.whyQ ) return ;
			if(this.howQ == 38 && !this.pinQ) return ;
			
			console.log("about to creating situation");

			axios({
				method: 'post',
				url: '/situations',
	       			data :{
			   		 whereQ: self.whereQ,
				   	 whatQ: self.whatQ,
				   	 howQ: self.howQ,
				   	 whoQ: self.whoQ,
				   	 whyQ: self.whyQ,
					 pinQ: self.pinQ,
			        	 name: self.simulatorname
				       }
				})
				.then(function (response) {location.reload();})
				.catch(function (error) {console.log(error);});
//				.catch(error => this.errors.record(error.response.data.errors));
		},
		generate(){
			console.log("to generate ");
			axios.get('/generate?model=1&notxns='+this.notxns+'&label='+this.label+'&relationship=1&situation='+this.situationid+'&aggregator='+this.aggregator+'&client='+this.client)
			.then(response => this.results = response.data)
			.catch(function (error) {
			    console.log(error);
			  });
		},
		getquestions(){
			axios.get('/getquestions')
			     .then(response => this.scenarios = response.data)
			     .catch(function (error) {
			     console.log(error);
			  });
		},
		getaggregators(){
			axios.get(this.pocurl+'api/getaggregators')
			     .then(response => this.aggregators = response.data)
			     .catch(function (error) {
			     console.log(error);
			  });
		},
		getclients(){
			axios.get(this.pocurl+'api/getclients?aggregator='+this.aggregator)
			     .then(response => this.clients = response.data)
			     .catch(function (error) {
			     console.log(error);
			  });
		},
		getsituations(){
			let self = this;
			axios.get('/situations')
//			     .then(response => this.situations = response.data)
			     .then(function (response) {
				     self.situations = response.data;
				     if(self.situations[0]) self.situationid = self.situations[0].id;
			     })
			     .catch(function (error) {
			     console.log(error);
			  });

		},
	},
        mounted() {
                console.log('Simulator Component mounted.');
		this.getquestions();
		this.getsituations();
		this.getaggregators();
		this.getclients();
	}
    }
</script>    
