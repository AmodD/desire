
<template>
	<div class="content">
	<form>

<a v-if="!showsituation" href="javascript:void(0);" @click="showsituation = true" class="button is-primary is-rounded is-medium">Create Situation</a>

<a v-if="showsituation" @click="showsituation = false" class="button is-dark is-small is-pulled-right"><span class="icon has-text-danger"><i class="fa fa-bug"></i></span>Close</a>	
<div class="box" v-if="showsituation">
<div  class="tile is-ancestor">
  <div class="tile is-vertical is-8">
    <div class="tile">
      <div class="tile is-parent is-vertical">
        <article class="tile is-child notification is-primary">
          <p class="subtitle">Where ?</p>
	  <div class="select">
		<select v-model="whereQ" @change.once="progress = progress +  20" required>
		  <option value="" disabled> select scenario</option>
		  <option v-for="scenario in scenarios" v-if="scenario.question_id == 1" :value="scenario.id">{{ scenario.name }}</option>
		</select>
	  </div>
        </article>
        <article class="tile is-child notification is-warning">
          <p class="subtitle">What ?</p>
	  <div class="select">
		<select v-model="whatQ" @change.once="progress = progress +  20" required>
		  <option value="" disabled> select scenario</option>
		  <option v-for="scenario in scenarios" v-if="scenario.question_id == 2" :value="scenario.id">{{ scenario.name }}</option>
		</select>
	  </div>
        </article>
        <article class="tile is-child notification is-danger">
          <p class="subtitle">How?</p>
	  <div class="select">
		<select v-model="howQ" @change.once="progress = progress +  20" required>
		  <option value="" disabled> select scenario</option>
		  <option v-for="scenario in scenarios" v-if="scenario.question_id == 3" :value="scenario.id">{{ scenario.name }}</option>
		</select>
	  </div>
        </article>
      </div>
      <div class="tile is-parent">
        <article class="tile is-child notification is-info">
		<h4 class="has-text-centered"><button v-on:click="createsituation" class="button is-success ">Click to Create a Situation</button></h4>
          <p class="title has-text-centered">The Situation</p>
          <figure class="image is-4by3">
            <img src="https://bulma.io/images/placeholders/640x480.png">
          </figure>
        </article>
      </div>
    </div>
  </div>
  <div class="tile is-parent is-vertical">
        <article class="tile is-child notification is-primary">
          <p class="subtitle">Who?</p>
	  <div class="select">
		<select v-model="whoQ" @change.once="progress = progress +  20" required>
		  <option value="" disabled> select scenario</option>
		  <option v-for="scenario in scenarios" v-if="scenario.question_id == 4" :value="scenario.id">{{ scenario.name }}</option>
		</select>
	  </div>
        </article>
        <article class="tile is-child notification is-warning">
          <p class="subtitle">Why?</p>
	  <div class="select">
		<select v-model="whyQ" @change.once="progress = progress +  20" required>
		  <option value="" disabled> select scenario</option>
		  <option v-for="scenario in scenarios" v-if="scenario.question_id == 5" :value="scenario.id">{{ scenario.name }}</option>
		</select>
	  </div>
        </article>
        <article class="tile is-child notification is-danger" v-if="(howQ != 38) && (whereQ != 1)">
          <p class="subtitle">Pin Entry Capability ?</p>
	  <div class="select" v-if="(howQ != 38) && (whereQ != 1)">
		<select v-model="pinQ"  required>
		  <option value="" disabled> select scenario</option>
		  <option v-for="scenario in scenarios" v-if="scenario.question_id == 6" :value="scenario.id">{{ scenario.name }}</option>
		</select>
	  </div>
        </article>
        <article class="tile is-child notification is-link">
          <p class="subtitle">MCC?</p>
	  <div class="select">
		<select v-model="mccQ" @change.once="progress = progress +  20" required>
		  <option value="" disabled> select scenario</option>
		  <option v-for="scenario in scenarios" v-if="scenario.question_id == 7" :value="scenario.id">{{ scenario.name }}</option>
		</select>
	  </div>
        </article>
  </div>
</div>

<progress class="progress is-success" :value="progress" max="100"> </progress>

</div>

</form> 

<form>
	  <h2 class="has-text-centered">Generate Transactions</h2>
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
			showsituation : false,
			progress : 0,
			whereQ : '',
			whatQ : '',
			howQ : '',
			whoQ : '',
			whyQ : '',
			pinQ : '',
			mccQ : '',
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
			let mccQname = '';

			if(this.scenarios[(this.whereQ - 1)]) whereQname =  this.scenarios[(this.whereQ -1)].name;
			if(this.scenarios[(this.whatQ - 1)]) whatQname =  this.scenarios[(this.whatQ -1)].name;
			if(this.scenarios[(this.howQ - 1)]) howQname =  this.scenarios[(this.howQ -1)].name;
			if(this.scenarios[(this.whoQ - 1)]) whoQname =  this.scenarios[(this.whoQ -1)].name;
			if(this.scenarios[(this.whyQ - 1)]) whyQname =  this.scenarios[(this.whyQ -1)].name;
			if(this.scenarios[(this.mccQ - 1)]) mccQname =  this.scenarios[(this.mccQ -1)].name;

			return whereQname +  '-' + whatQname + '-' + howQname + '-' + whoQname + '-' + whyQname + '-' + mccQname ;
		}
	},
	methods : {
		createsituation(){
			console.log("in creating situation");
			
			let self = this ;
			
			if(!this.whereQ || !this.whatQ || !this.howQ || !this.whoQ || !this.whyQ || !this.mccQ ) return ;
			if(this.howQ != 38 && this.whereQ != 1  && !this.pinQ) return ;
			
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
					 mccQ: self.mccQ,
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
