<template>

<div>	
<div class="box" v-for="result in results">
	<div class="content">
	        <p>
		<strong>ID</strong> <small>{{ result.id }}</small>
		<strong>Time</strong> <small>{{ result.created_at }}</small>
		<strong>Model Name</strong> <small>{{ result.mlmodel.name }}</small>
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
		}
	},
        mounted() {
                console.log('Generate Component mounted.');
		axios.get('/lasttxns')
			.then(response => this.results = response.data)
			.catch(function (error) {
			    console.log(error);
			  });

	}
    }
</script>    

