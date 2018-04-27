
<template>
	<div class="content">
	<form>
		<h4><button v-on:click="createsituation" class="button is-success is-outlined ">Click to Create a Situation</button></h4>
		<progress class="progress is-primary" :value="progress" max="100"> </progress>

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
	  </form> 
	  
	  <hr>	
	  <h2>Generate Transactions For</h2>
	<table class="table">
	    <thead>
	      <tr>
	        <th>Simulation Scenario</th>
	        <th>Valid Data ?</th>
		<th>Number of Txns</th>
		<th>Action</th>
	      </tr>
	    </thead>
	    <tbody>
		<tr v-for="situation in situations">
			<td><span class="is-small">{{ situation.name }}</span></td>
			<td><label class="checkbox"><input v-model="valid"  type="checkbox"></label></td>
			<td>
	  			<label class="radio"><input type="radio" id="notxns20" v-model="notxns" :value=20> 20</label>
				<label class="radio"><input type="radio" id="notxns100" v-model="notxns" :value=100> 100</label>
				<label class="radio"><input type="radio" id="notxns500" v-model="notxns" :value=500> 500</label>
			</td>
			<td>		
				<button v-on:click="generate(situation.id)" class="button is-danger is-outlined is-small ">Generate Transactions</button>
	    		</td>
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
			notxns : 1,
			valid : true,
			situations : []
		}
	},
	computed : {
		label : function() {
			if(this.valid) return 1;
			else return 2;
		},
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
		generate(id){
			console.log("to generate ");
			axios.get('/generate?model=1&notxns='+this.notxns+'&label='+this.label+'&relationship=1&situation='+id)
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
		getsituations(){
			axios.get('/situations')
			     .then(response => this.situations = response.data)
			     .catch(function (error) {
			     console.log(error);
			  });
		},
	},
        mounted() {
                console.log('Simulator Component mounted.');
		this.getquestions();
		this.getsituations();
	}
    }
</script>    
