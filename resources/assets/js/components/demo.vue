<template>

	<div class="tabs">
	  <ul>
	    <li v-if="selected === 'home'" class="is-active" ><a href="/">Home</a></li>
	    <li v-else ><a href="/">Home</a></li>
	    
	    <li v-if="selected === 'simulator'" class="is-active" ><a href="/simulator">Simulator</a></li>
	    <li v-else ><a href="/simulator">Simulator</a></li>
	    
	    <li v-if="selected === 'relationship'" class="is-active" ><a href="/relationship">Relationships</a></li>
	    <li v-else ><a href="/relationship">Relationships</a></li>
	    
	    <li v-if="selected === 'traindata'" class="is-active" ><a href="/traindata">Data</a></li>
	    <li v-else ><a href="/traindata">Data</a></li>
	    
	    <li v-if="selected === 'model'" class="is-active" ><a href="/nn">Modelling</a></li>
	    <li v-else ><a href="/nn">Modelling</a></li>
	    
	    <li v-if="selected === 'message'" class="is-active" ><a href="/create">Testing</a></li>
	    <li v-else ><a href="/create">Testing</a></li>
	    
<!--	    <li v-if="selected === 'auto'" class="is-active" ><a href="/auto">Auto Generate Messages</a></li>
	    <li v-else ><a href="/auto">Auto Generate Messages</a></li>
-->	    
	    <li v-if="selected === 'transactions'" class="is-active" ><a href="/transactions">Transactions</a></li>
	    <li v-else ><a href="/transactions">Transactions</a></li>

	  </ul>
	</div>



</template>


<script>
    export default {

	props: ['selected'],
        mounted() {
                console.log('Demo Component mounted.');

	}
    }
</script>    
