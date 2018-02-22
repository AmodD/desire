<template>
<div>

       <div class="row">
            <div class="col-md-8 col-md-offset-2">
               <div class="panel-block">
    <button v-on:click="generate" class="button is-danger is-outlined ">
      generate and see last 10 transactions
    </button>
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
			<div v-if="detail.field_id == 1"><strong>Vector</strong> <small>{{ detail.value }}</small></div>
			<span v-if="detail.field_id == 0"><strong>MTI</strong> <small>{{ detail.value }}</small> </span>
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
			results : [],
			models : [],
			selectedmodel : ""
		}
	},
        mounted() {
                console.log('Generate Component mounted.');
		axios.get('/getmodels')
			.then(response => this.models = response.data)
			.catch(function (error) {
			    console.log(error);
			  });

	},
	methods : {
		generate(){
			console.log("to generate ");
			axios.get('/generate?model='+this.selectedmodel)
			.then(response => this.results = response.data)
//			.then(function (response) {
//			    console.log(response);
	//		    this.msg8583 = response.data.msg;
//			    this.msgid = response.data.id;
	//		    this.msgtime = response.data.time;
	//		    this.bitmap = response.data.bitmap;
//	this.results = response.data ;
//			  })
			.catch(function (error) {
			    console.log(error);
			  });
		}
	}
    }
</script>    

