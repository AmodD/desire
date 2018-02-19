<template>
<div class="content">
  <h4>ML Algo</h4>
  <p>MLP with backpropogation</p>
  <pre>

// Create a new neural network with 64 input neurons,
// 14 hidden neurons, and 1 output neuron
$n = new NeuralNetwork(64, 14, 1);
$n->setVerbose(false);

// Add test-data to the network. 
// vector and score
$n->addTestData(array (0,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
       		array (0.4));

$n->addTestData(array (0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,1,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,0,0,0,0,0,1,0,0,0,0,1,0,0,0,0),
       		array (0.8));

$n->addTestData(array (1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
       		array (1));

$n->addTestData(array (0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
       		array (0));

// HAVE ADDED LAST 10 TXNS AND THIER SCORES TOO	

$max = 3;
$i = 0;
// we try training the network for at most $max times
// train the network in max 1000 epochs, with a max squared error of 0.01

while (!($success = $n->train(1000, 0.01)) && ++$i<$max) 
{

}

return  $n->calculate(array_map('intval', str_split($vector)));

  
</pre>


</div>
</template>


<script>
    export default {

        mounted() {
                console.log('Demo Component mounted.');

	}
    }
</script>    
