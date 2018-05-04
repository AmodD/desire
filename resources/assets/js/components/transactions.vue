<template>

<div class="box content is-small" style="overflow-x: auto;">	
<div v-if="false" class="box" v-for="result in results">
	<div class="content">
		<strong>ID</strong> <small>{{ result.id }}</small>
		<strong>Time</strong> <small>{{ result.created_at }}</small>
		<strong>Model Name</strong> <small>{{ result.mlmodel.name }}</small>
		<strong>Score</strong> <small>{{ result.score }}</small>
	          <br>
		  <strong>ISO8385 message</strong><small>  {{ result.message }}</small>
		<span v-for="detail in result.data" v-if="detail.value">
			<strong>{{ detail.field.element }}</strong> <small>{{ detail.value }}</small>  &nbsp;&nbsp;
		</span>
       	</div>
</div>	


<table class="table">
	<thead>
		<th v-for="detail in results[0].data" v-if="detail.field.id != 1"><span class="is-size-7">DE{{ detail.field.id }}</span></th>
	</thead>	
	<tbody>
		<tr v-for="result in results">
			<td v-for="detail in result.data"><span class="is-size-7">{{ detail.value }}</span></td>
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

