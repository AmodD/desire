<template>

<div class="box content is-small" style="overflow-x: auto;">	

<table class="table">
	<thead v-if="results.data">
		<th><span class="is-size-7">ID</span></th>
		<th v-for="field in results.data[0].data" ><span class="is-size-7">DE{{ field.field_id }}</span></th>
	</thead>	
	<tbody>
		<tr v-for="txn in results.data">
			<td>{{ txn.id }}</td>
			<td v-for="txndetail in txn.data"><span class="is-size-7">{{ txndetail.value }}</span></td>
		</tr>
	</tbody>	
</table>	

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

