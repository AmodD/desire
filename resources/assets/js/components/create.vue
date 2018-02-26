<template>
<div>

		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">DE 2</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" type="text" v-model="pan"  placeholder="Permanent Account Number"></div></div>
	        </div>			  
		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">DE 3</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" type="text" v-model="procode"  placeholder="Processing Code"></div></div>
	        </div>			  
		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">DE 4</label> </div>
	          <div class="field-body"><div class="field">
		        <input class="input"  v-model="amount" type="text" placeholder="Amount"></div></div>
	        </div>			  
		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">DE 18</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" type="text" v-model="mcc"  placeholder="Merchant Category Code"></div></div>
	        </div>			  
		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">DE 19</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" type="text" v-model="aqcountry"  placeholder="Acquiring Instituition Country Code"></div></div>
	        </div>			  
		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">DE 22</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" type="text" v-model="posem"  placeholder="Point of Service Entry Mode"></div></div>
	        </div>			  
		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">DE 25</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" type="text" v-model="poscc"  placeholder="Point of Service Condition Code"></div></div>
	        </div>			  
		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">DE 49</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" type="text"  v-model="currency" placeholder="Currency Code of Transaction"></div></div>
	        </div>			 
	       <!-- 	
		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">DE 48</label> </div>
	        <div class="field-body"><div class="field">
	  			  <input class="input" type="text"  v-model="prmsg6" placeholder="Additional Data Private"></div></div>
	        </div>			  
		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">DE 55</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" type="text" v-model="chipdata"  placeholder="Chip Data"></div></div>
	        </div>			  
		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">DE 60</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" type="text" v-model="addposdata"  placeholder="Additional POS Data"></div></div>
	        </div>			  
		-->

       <div class="row">
            <div class="col-md-8 col-md-offset-2">
               <div class="panel-block">
		    <button v-on:click="analyze" class="button is-link is-outlined ">click to create & analyze </button>
<div class="field">
  <div class="control">
    <div class="select is-primary">
      <select v-model="selectedmodel">
	<option disabled value="">Select a Model</option>
	<option v-for="(value, key) in models" :value="value" >{{ key }}</option>
      </select>
    </div>
  </div>
</div>
		    &nbsp; {{ output }}
	       </div>
	    </div>   
       </div>	    



<div class="box" v-for="result in results">
	<div class="content">
	        <p>
		<strong>ID</strong> <small>{{ result.id }}</small>
		<strong>Time</strong> <small>{{ result.created_at }}</small>
		<strong>Model Name</strong> <small>{{ result.annmodel.name }}</small>
		<strong>Score</strong> <small>{{ result.score }}</small>
	          <br>
		  <strong>ISO8385 message</strong><small>  {{ result.message }}</small>
		  
		<div v-for="detail in result.data">
			<div v-if="detail.field_id == 1"><strong>Vector</strong> <small>{{ detail.value }}</small> </div>
			<span v-if="detail.field_id == 0"><strong>MTI</strong> <small>{{ detail.value }}</small></span>
			<span v-if="detail.field_id == 2"><strong>PAN</strong> <small>{{ detail.value }}</small></span>
			<span v-if="detail.field_id == 3"><strong>Processing Code</strong> <small>{{ detail.value }}</small></span>
			<span v-if="detail.field_id == 4"><strong>Amount</strong> <small>{{ detail.value }}</small></span>
			<span v-if="detail.field_id == 18"><strong>Merchant Category Code</strong> <small>{{ detail.value }}</small></span>
			<span v-if="detail.field_id == 19"><strong>Country</strong> <small>{{ detail.value }}</small></span>
			<span v-if="detail.field_id == 22"><strong>POS Entry Mode</strong> <small>{{ detail.value }}</small></span>
			<span v-if="detail.field_id == 25"><strong>POS Condition Code</strong> <small>{{ detail.value }}</small></span>
			<span v-if="detail.field_id == 49"><strong>Currency</strong> <small>{{ detail.value }}</small></span>
			<!-- <span v-if="detail.field_id == 48"><strong>Additional Private Data</strong> <small>{{ detail.value }}</small></span>
			<span v-if="detail.field_id == 55"><strong>Chip Data</strong> <small>{{ detail.value }}</small></span>
			<span v-if="detail.field_id == 60"><strong>Additional POS Data</strong> <small>{{ detail.value }}</small></span> -->
		</div>	
      </div>

 </div>	 

</div>	
</template>


<script>
    export default {

	data : function() {
		return {
			txnSpec : false,
			bitmap : '',
			msg8583 : '',
			pan : '',
			currency : '',
			procode : '',
			mcc : '',
			posem : '',
			poscc : '',
			chipdata : '',
			addposdata : '',
			amount : '',
			aqcountry : '',
			msgnumber : 0,
			prmsg6 : '',
			msgdid : 0,
			msgtime : '',
			results : [],
			pass : [0.07,0.12],
			fail : [0.98,0.84],
			input : '',
			models : [],
			selectedmodel : ""
		}
	},	 
	computed : {
		txnSpecValue : function() {
			return 'This is a ' + this.txnSpec + ' transaction message' ;
		},
		output : function() {
			if(this.input.includes('Error')) return  this.msg8583 = this.input;
		       else {
			       this.results = this.input;
				       return "";	
			       }
		}
	},
        mounted() {
                console.log('Create Component mounted.');
		axios.get('/getmodels')
			.then(response => this.models = response.data)
			.catch(function (error) {
			    console.log(error);
			  });


	},
	methods : {
		analyze(){
			//console.log("to analyze "+this.msg8583);
			axios.get('/analyze?pan='+this.pan+'&currency='+this.currency+'&prmsg6='+this.prmsg6+'&amount='+this.amount+'&aqcountry='+this.aqcountry+'&procode='+this.procode+'&posem='+this.posem+'&poscc='+this.poscc+'&chipdata='+this.chipdata+'&addposdata='+this.addposdata+'&model='+this.selectedmodel+'&mcc='+this.mcc)
//			.then(function (response) {
//			    console.log(response);
//
//			    if(response.data.includes('Error'))  this.msg8583 = response.data;
//			    else this.results = response.data;
//			  })
			.then(response => this.input = response.data)
			.catch(function (error) {
			    console.log(error);
			  });
		}
	}	
    }
</script>
    
