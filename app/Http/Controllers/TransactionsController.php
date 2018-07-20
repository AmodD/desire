<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\JAK8583;
use App\Repositories\NeuralNetwork;


use Exception;
use DB;

use App\Data;
use App\Field;
use App\Transaction;
use App\Mlmodel;
use App\Relationship;
use App\Label;
use App\RelationshipTransaction;
use App\Scenario;
use App\Situation;

use Phpml\Classification\MLPClassifier;
use Phpml\Preprocessing\Normalizer;
use Phpml\Math\Set;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Classification\SVC;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\Classification\NaiveBayes ;
use Phpml\Classification\KNearestNeighbors;
use Phpml\Pipeline;
use Phpml\Preprocessing\Imputer;
use Phpml\Preprocessing\Imputer\Strategy\MostFrequentStrategy;
use Phpml\ModelManager;

use Kaperys\Financial\Financial;
use Kaperys\Financial\Cache\CacheManager;
use Kaperys\Financial\Message\Schema\ISO8583;
use Kaperys\Financial\Message\Schema\SchemaManager;
use Kaperys\Financial\Message\Packer\MessagePacker;
use Kaperys\Financial\Message\Unpacker\MessageUnpacker;

class TransactionsController extends Controller
{
	public function show(Transaction $transaction)
	{
		return $transaction->with('data')->find($transaction->id);;
	}


	public function mldemo(Request $request)
	{

		$validposem = ["010","020","050","900","950","011","021","051","901","951","012","022","052","902","952","016","026","056","906","956"];
		$validposcc = ["00","01","02","03","05","07","08","52","59"];
		$validmaxem = 956;
		$validminem = 10; 
	        $validmaxcc = 59;
		$validmincc = 0;	

		$invalidposem = ["121","245","383","437","582","628","772","802"];
		$invalidposcc = ["10","23","35","43","50","66","74","89"];
		$invalidmaxem = 802;
		$invalidminem = 121; 
	        $invalidmaxcc = 89;
		$invalidmincc = 10;	

		$dataSet = array();		
		$targetSet = array();		

		foreach($validposem as $vposem)
		{
			//$normalizedEM = ($validmaxem - intval($vposem))/($validmaxem - $validminem) ;
			//$decEM = str_pad(decbin($vposem), 10, '0', STR_PAD_LEFT);	
			$posem = str_pad($vposem, 3, '0', STR_PAD_LEFT);	

			foreach($validposcc as $vposcc)
			{
				$poscc = str_pad($vposcc, 2, '0', STR_PAD_LEFT);	
				$dataSet[] = array_map('intval', str_split($posem.$poscc));
				
				//$normalizedCC = ($validmaxcc - intval($vposcc))/($validmaxcc - $validmincc) ;
				//$decCC = str_pad(decbin($vposcc), 7, '0', STR_PAD_LEFT);	
				//$dataSet[] = array(intval($decEM) , intval($decCC));
				//$dataSet[] = array(intval($vposem) , intval($vposcc));
				//$dataSet[] = array($normalizedEM , $normalizedCC);
				$targetSet[] = "correct";
			}
		}

		foreach($invalidposem as $invposem)
		{
			//$normalizedEM = ($invalidmaxem - intval($invposem))/($invalidmaxem - $invalidminem) ;
			//$decEM = str_pad(decbin($invposem), 10, '0', STR_PAD_LEFT);	
			$posem = str_pad($invposem, 3, '0', STR_PAD_LEFT);	
			foreach($invalidposcc as $invposcc)
			{
				$poscc = str_pad($invposcc, 2, '0', STR_PAD_LEFT);	
				$dataSet[] = array_map('intval', str_split($posem.$poscc))	;
				
				//$normalizedCC = ($invalidmaxcc - intval($invposcc))/($invalidmaxcc - $invalidmincc) ;
				//$decCC = str_pad(decbin($invposcc), 7, '0', STR_PAD_LEFT);	
				//$dataSet[] = array(intval($decEM) , intval($decCC));
				//$dataSet[] = array(intval($invposem) , intval($invposcc));
				//$dataSet[] = array($normalizedEM , $normalizedCC);
				$targetSet[] = "fraud";
			}
		}


$mlp = new MLPClassifier(5, [4], ['correct','fraud']);		
$mlp->train($dataSet, $targetSet);
$filepath = 'model123';
$modelManager = new ModelManager();
$modelManager->saveToFile($mlp, $filepath);
dd($mlp->predict([[0,1,6,0,5], [2,4,5,4,3]]));

$decEM1 = intval(str_pad(decbin(952), 10, '0', STR_PAD_LEFT));	
$decCC1 = intval(str_pad(decbin(59), 7, '0', STR_PAD_LEFT));	
$decEM2 = intval(str_pad(decbin(437), 10, '0', STR_PAD_LEFT));	
$decCC2 = intval(str_pad(decbin(89), 7, '0', STR_PAD_LEFT));	
$predicted = $mlp->predict([[$decEM1,$decCC1],[$decEM2,$decCC2]]);
dd($predicted);

$transformers = [new Normalizer()];
$estimator = new MLPClassifier(2, [4], ["correct", "fraud"]);
//$estimator = new NaiveBayes();
$pipeline = new Pipeline($transformers, $estimator);
$pipeline->train($dataSet, $targetSet);

$decEM1 = str_pad(decbin(952), 10, '0', STR_PAD_LEFT);	
$decCC1 = str_pad(decbin(59), 7, '0', STR_PAD_LEFT);	
$decEM2 = str_pad(decbin(437), 10, '0', STR_PAD_LEFT);	
$decCC2 = str_pad(decbin(89), 7, '0', STR_PAD_LEFT);	
$predicted = $pipeline->predict([[$decEM1,$decCC1],[$decEM2,$decCC2]]);


dd($predicted);
		
//		dd($dataSet,$targetSet);
		$mlp->train($dataSet,$targetSet);
		dd($mlp->predict([[0,5,1], [1,0,0], [3,3,3]]));




		//foreach($invalidposem as $invposem)
		for($i=0 ; $i < 999 ; $i++)
		{
			//foreach($invalidposcc as $invposcc)
			for($j=0 ; $j < 99 ; $j++)
			{
				if((in_array($i,$validposem)) && (in_array($j,$validposcc))) continue;
	//			if(in_array($i,$validposem)) continue;
				else {

					//$posem = str_pad($i, 3, '0', STR_PAD_LEFT);	
					//$poscc = str_pad($j, 2, '0', STR_PAD_LEFT);	
				//	$n->addTestData(array_map('intval', str_split($posem)),
					$n->addTestData(array ($i/999,$j/99),
					array (0));
				}
			}
		}

		$mlp->train(
		    $samples = [[1, 0, 0, 0], [0, 1, 1, 0], [1, 1, 1, 1], [0, 0, 0, 0]],
		    $targets = ['a', 'a', 'b', 'c']
		);
		
		
		$mlp->predict([[1, 1, 1, 1], [0, 0, 0, 0]]);		
		
		
		
		
	
		$n = new NeuralNetwork(2, 4 , 1);
		$n->setVerbose(false);

		$validposem = ["010","020","050","900","950","011","021","051","901","951","012","022","052","902","952","016","026","056","906","956"];
		$validposcc = ["00","01","02","03","05","07","08","52","59"];

		$invalidposem = ["100","200","300","400","500","600","700","800"];
		$invalidposcc = ["10","20","30","40","50","60","70","80"];


		foreach($validposem as $vposem)
		{
			foreach($validposcc as $vposcc)
			{

			//	$n->addTestData(array_map('intval', str_split($vposem)),
				$n->addTestData(array ($vposem/999,$vposcc/99),
				array (1));
			}
		}
		
		//foreach($invalidposem as $invposem)
		for($i=0 ; $i < 999 ; $i++)
		{
			//foreach($invalidposcc as $invposcc)
			for($j=0 ; $j < 99 ; $j++)
			{
				if((in_array($i,$validposem)) && (in_array($j,$validposcc))) continue;
	//			if(in_array($i,$validposem)) continue;
				else {

					//$posem = str_pad($i, 3, '0', STR_PAD_LEFT);	
					//$poscc = str_pad($j, 2, '0', STR_PAD_LEFT);	
				//	$n->addTestData(array_map('intval', str_split($posem)),
					$n->addTestData(array ($i/999,$j/99),
					array (0));
				}
			}
		}

//		for($i=0 ; $i < 100 ; $i++)	
	//	{
	//	$Vposem = array_random($validposem);
	//	$Vposcc = array_random($validposcc);
			
	//	$n->addTestData(array_map('intval', str_split($Vposem.$Vposcc)),
	//			array (1));

	//	$Iposem = array_random($invalidposem);
	//	$Iposcc = array_random($invalidposcc);

	//	$n->addTestData(array_map('intval', str_split($Iposem.$Iposcc)),
	//			array (-1));
	//	}
		$max = 3;
		$i = 0;

//		$n->setLearningRate(0.9);

		while (!($success = $n->train(500, 0.03)) && ++$i<$max) 
		{

		}

		dd($n->calculate(array(950/999,52/99)),$n->calculate(array(100/999,10/99)),$n->calculate(array(888/999,88/99)));
		

	}
	
	public function destroy(Transaction $transaction)
        {
	    $transaction->delete();
        }

	public function analyze(Request $request)
	{
		$mti = "1200"; 
		$dataElement = collect([]);

		$model = $request->model;
		$score = 0;
		$label = $request->label;
		$relationship = $request->relationship;


		$dataElement->put("2",$request->pan);
		$dataElement->put("3",$request->procode);
		$dataElement->put("4",$request->amount);
		$dataElement->put("18",$request->mcc);
		$dataElement->put("19",$request->aqcountry);
		$dataElement->put("22",$request->posem);
		$dataElement->put("25",$request->poscc);
		$dataElement->put("48",$request->prmsg6);
		$dataElement->put("49",$request->currency);
		$dataElement->put("55",$request->chipdata);
		$dataElement->put("60",$request->addposdata);

		$isoMessage = $this->pack($mti,$dataElement);

		if (strpos($isoMessage, 'Error') !== false) {
		    return $isoMessage;
		}
		else{
		    $this->store($mti,$isoMessage,$dataElement,$model,$score,$label,$relationship);
			
		    return $this->lasttxns($relationship);
		}	
	}

	public function lasttxns($relid=0)
	{
		$request = request();
		$rel = $request->relationship;


//		dd($relationship->transactions,Transaction::with('data','annmodel','relationships')->take(10)->orderby('id','desc')->get());
		if($rel || $relid) {
			$relationship = Relationship::find($rel);
			return $relationship->transactions;
		}
		else return Transaction::with('data')->orderBy('id', 'desc')->paginate(15);
		
	//	$label = request('label'):
	//	$relationship = request('relationship');

	//	if($label && $relationship) return Transaction::with('data','relationship')->orderby('id','desc')->get();
	//	else return Transaction::with('data','annmodel')->take(10)->orderby('id','desc')->get();
	}
	
	public function generate(Request $request)
	{
		$dataElement = collect([]);
		$model =  $request->model;
		$score = 1;//$request->label;
		$label = $request->label;
		$relationship = $request->relationship;
		$aggregator = $request->aggregator;
		$client = $request->client;
		
		$faker = \Faker\Factory::create('en_US');
		$situation = \App\Situation::find($request->situation);

		$mti = '';
		$procode = '';
		$poscapture = '';
		$posem = '';
		$mcc = '';
		
		foreach( $situation->scenarios  as $scenario)
		{
			foreach($scenario->fields as $field)
			{
				// DE 0
				if($field->pivot->field_id == '0')
				{
					$mti =  $field->pivot->value ;
				}

				// DE 3
				if($field->pivot->field_id == '3')
				{
					$procode =  $field->pivot->value ;
				}
				
				// DE 18
				if($field->pivot->field_id == '18')
				{
					$mcc = $this->getmcc($field->pivot->value) ;			
				}

				// DE 26
				if($field->pivot->field_id == '26')
				{
					$poscapture = $field->pivot->value ;			
				}

				// DE 22
				if($field->pivot->field_id == '22')
				{
					if($scenario->question_id == '3') $posem = $field->pivot->value ;		
	
					if($field->pivot->scenario_id == '38') $posem = $posem.'0';
					else if(($poscapture == '8') && ($scenario->question_id == '6')) $posem = $posem.'1';
					else if(($poscapture == '4') && ($scenario->question_id == '6')) $posem = $posem.$field->pivot->value;
				}
			}
		}	

		// DE 19	
		$country = 356;
		// DE 41
		$cardAceptorTerminal = "";
		// DE 42
		$cardAceptorIdentification = "";
		// DE 48
		$de48 = "" ; //$faker->word;	
		// DE 49
		$currency = 356;
		// DE 55
		$chipdata = "" ; //$faker->word;
		// DE 60
		$addposdata = "" ; // $faker->word;
		// DE 61
		$reservedPrivate2 = "";
		// DE 90
		$original = "";
		// DE 127
		$reservedPrivate12 = "";

		for ($i = 0; $i < $request->notxns; ++$i)
		{
			// DE 2
			$pan = $faker->creditCardNumber;
			$pan = str_pad($pan, 16, '0', STR_PAD_LEFT);	
			
			// DE 4 5 6
			$amnt = str_pad($faker->numberBetween($min = 10, $max = 10000), 12, '0', STR_PAD_LEFT);

			// DE 7
			$transmissionDate = $faker->date($format = 'mdHis', $max = 'now');

			// DE 25
			$arrayposcc = ["00","01","02","03","05","07","08","52","59"];
			$poscc = array_random($arrayposcc);

			// DE 32
		//	$acquiringInstitution = intval($faker->numberBetween($min = 10, $max = 10000));
			$acquiringInstitution =  1234;
			// DE 33
		//	$forwardingInstitution = intval($faker->numberBetween($min = 10, $max = 10000));
			$forwardingInstitution =   5678;

			// DE 38
			$air = $faker->bothify('?#####');
			
			// DE 39
			$responseCode = $faker->numerify('##');

			// DE 52
//			$personalIdentification = $faker->randomNumber(8);
//		$mcc = mt_rand(6000,9999);

		//$dataElement->put("pan",$pan);
	//	$dataElement->put("amnt",$amnt);
	//	$dataElement->put("country",$country);
	//	$dataElement->put("currency",$currency);
	//	$dataElement->put("de48",$de48);
	//	$dataElement->put("procode",$procode);
	//	$dataElement->put("posem",$posem);
	//	$dataElement->put("poscc",$poscc);
	//	$dataElement->put("chipdata",$chipdata);
	//	$dataElement->put("addposdata",$addposdata);
	//	$dataElement->put("mcc",$mcc);

		$dataElement->put("2",$pan);
		$dataElement->put("3",$procode);
		$dataElement->put("4",$amnt);
		$dataElement->put("5",$amnt);
		$dataElement->put("6",$amnt);
		$dataElement->put("7",$transmissionDate);
		$dataElement->put("18",$mcc);
		$dataElement->put("19",$country);
		$dataElement->put("22",$posem);
		$dataElement->put("25",$poscc);
		$dataElement->put("26",$poscapture);
		$dataElement->put("32",$acquiringInstitution);
		$dataElement->put("33",$forwardingInstitution);
		$dataElement->put("38",$air);
		$dataElement->put("39",$responseCode);
		$dataElement->put("41",$cardAceptorTerminal);
		$dataElement->put("42",$cardAceptorIdentification);
		$dataElement->put("48",$de48);
		$dataElement->put("49",$currency);
		//$dataElement->put("52",$personalIdentification);
		$dataElement->put("55",$chipdata);
		$dataElement->put("60",$addposdata);
		$dataElement->put("61",$reservedPrivate2);
		$dataElement->put("90",$original);
		$dataElement->put("127",$reservedPrivate12);

		$isoMessage = $this->pack($mti,$dataElement);
		
		if (strpos($isoMessage, 'Error') !== false) {
			return $isoMessage;
			//continue;
		}
		else{
//			$this->tempJAK($mti,$dataElement,$isoMessage);	

			 $this->store($mti,$isoMessage,$dataElement,$model,$score,$label,$relationship,$situation,$aggregator,$client);
		}	

		} // for loop ends

	//	return $this->lasttxns($relationship);

	} // method ends

	public function tempJAK($mti,$dataElement,$isoMessage)
	{
		$jak = new JAK8583();
		$jak->addMTI($mti);
		
		foreach($dataElement as $bit => $data)
		{
			$jak->addData($bit,$data);	
		}

		dd($jak->getBitmap(),$jak->getISO(),$isoMessage);

	}

	public function getmcc($category = "0")
	{

		$apparel = [5611, 5631, 5641, 5655, 5621, 5661, 5691, 5697, 5699, 5941, 7296];
		$dining = [5812,5814];
		$entertainment = [7032, 7832, 7841, 7911, 7922, 7929, 7932, 7933, 7991, 7992, 7994,7999];
		$gas = [5541, 5542, 5983];
		$groceries = [5122, 5411, 5441, 5451, 5462, 5499, 5811, 5912 ];	
		$electronics = [4816, 5200, 5211, 5231, 5251, 5712, 5713, 5718, 5719, 5732, 7622, 7623, 7629, 7641];
		$shopping = [5261, 5300, 5309,5311, 5331, 5698, 5714, 5733, 5735, 5921, 5931,5933, 5937, 5940, 5942, 5944,5945, 5947,5950, 5970,5973, 5975,5977, 5992,5999, 7278, 7631, 7993];
		$travel = [ 3000,3003, 3005,3033, 3035,3055, 3058,3068, 3071, 3072, 3075,3079, 3082,3085, 3087,3090, 3094, 3096,3100, 3102, 3103, 3106, 3111, 3112, 3117, 3125, 3127, 3129,3132, 3135, 3136, 3144, 3146, 3148, 3151, 3156, 3159, 3161, 3164, 3167, 3171, 3172, 3174, 3175, 3177, 3178, 3180,3188, 3190,3193, 3196, 3197, 3200, 3204, 3206, 3211,3213, 3217, 3219,3223, 3226, 3228, 3229, 3231, 3234, 3236, 3239,3243, 3245,3248, 3252, 3253, 3256, 3260, 3261, 3263, 3266, 3267, 3280, 3282, 3285,3287, 3292,3299, 3501,3755, 3757,3815, 3817,3821, 4411, 4511, 4582, 4722, 7011, 7012, 7033,3351,3355, 3357, 3359,3362, 3364, 3366, 3368, 3370, 3374, 3376, 3380, 3381, 3385,3391, 3393,3396, 3398, 3400, 3405, 3409, 3412, 3420, 3421, 3423, 3425, 3427,3436, 3438, 3439, 3441, 4011, 4111, 4112, 4121, 4131, 4214, 4457, 4468, 4784, 4789, 5271, 5511, 5521, 5531,5533, 5551, 5561, 5571, 5592, 5598, 5599, 7511,7513, 7519, 7523, 7531, 7534, 7535, 7538, 7542, 7549, 8675 ];
		$utilities = [4814, 4899, 4900];
		$office = [4215, 4812,  5111, 5722, 5734, 5943, 5946, 7333, 7338, 7372, 7375, 7394, 9402];

		switch($category)
		{
			case 0 : return array_random($apparel);
			case 1 : return array_random($dining);
			case 2 : return array_random($entertainment);
			case 3 : return array_random($gas);
			case 4 : return array_random($groceries);
			case 5 : return array_random($electronics);
			case 6 : return array_random($shopping);
			case 7 : return array_random($travel);
			case 8 : return array_random($utilities);
			case 9 : return array_random($office);
			default : return "5611";

		}	
	}

	public function store($mti,$isoMessage,$dataElement,$model,$score,$label,$relationship,$situation,$aggregator,$client)
	{
		try{
			$jak = new JAK8583();
			$jak->addISO($isoMessage);

		} catch (Exception $e) {
			return 'Error : '.$e->getmessage();
		}

		$vector = $this->getVector($dataElement);
		$dataSet = array();
		
		foreach($dataElement as $number => $value)
		{
			if($value){
				$dataSet[] = intval($value);
			}
		}


//		dd($dataSet);
		$transaction = new Transaction();
		$transaction->message = $isoMessage;
		$transaction->mlmodel_id = $model;
		$transaction->situation_id = $situation->id;
		$transaction->aggregator = $aggregator;
		$transaction->client = $client;
		if($model == 1) $transaction->score = $score;
		else $transaction->score = $this->trainedScore($dataSet,$model) ; 
		//else $transaction->score = head($this->trainedScore($vector,$model))  ; 
		//$transaction->score = head($this->trainedScore(($jak->getBitmap()),$model)) ;
		$transaction->save();


	    	DB::table('data')->insert([
		            ['transaction_id' => $transaction->id  , 'field_id' => 0 ,  'value' => $mti],
		            ['transaction_id' => $transaction->id  , 'field_id' => 1 ,  'value' => implode("",$vector)],
		            ['transaction_id' => $transaction->id  , 'field_id' => 2 ,  'value' => $dataElement->get("2")],
		            ['transaction_id' => $transaction->id  , 'field_id' => 3 ,  'value' => $dataElement->get("3")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 4 ,  'value' => $dataElement->get("4")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 5 ,  'value' => $dataElement->get("5")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 6 ,  'value' => $dataElement->get("6")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 7 ,  'value' => $dataElement->get("7")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 18 ,  'value' => $dataElement->get("18")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 19 ,  'value' => $dataElement->get("19")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 22 ,  'value' => $dataElement->get("22")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 25 ,  'value' => $dataElement->get("25")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 26 ,  'value' => $dataElement->get("26")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 32 ,  'value' => $dataElement->get("32")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 33 ,  'value' => $dataElement->get("33")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 38 ,  'value' => $dataElement->get("38")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 39 ,  'value' => $dataElement->get("39")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 41 ,  'value' => $dataElement->get("41")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 42 ,  'value' => $dataElement->get("42")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 48 ,  'value' => $dataElement->get("48")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 49 ,  'value' => $dataElement->get("49")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 55 ,  'value' => $dataElement->get("55")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 60 ,  'value' => $dataElement->get("60")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 61 ,  'value' => $dataElement->get("61")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 90 ,  'value' => $dataElement->get("90")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 127 ,  'value' => $dataElement->get("127")],
		    ]);


/*		foreach($dataElement as $number => $value)
		{
			if($value){
				$data = new Data();
				$data->transaction_id = $transaction->id;
				$data->field_id = $number;
				$data->value = $value;
				$data->save();
			}
		}
 */

$ch = curl_init( env('POC_URL').'api/txncase' );
# Setup request to send json via POST.
$txn = json_encode( array( "transaction" => $transaction , "de" => $dataElement , "aggregator" => $aggregator , "client" => $client ) );
curl_setopt( $ch, CURLOPT_POSTFIELDS, $txn );
curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
# Return response instead of printing.
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
# Send request.
$result = curl_exec($ch);
curl_close($ch);

//echo "<pre>$result</pre>";

		if($relationship)
		{
			DB::table('relationship_transaction')->insert([
				['transaction_id' => $transaction->id  , 'relationship_id' => $relationship ,  'label_id' => $label]
			]);
		}

	}

	public function getVector($dataElement)
	{
		
		$vector = collect([]);
		$vectorString = '';

//	if($dataElement->get('pan')) $vector->push($dataElement->get('pan')); else  $vector->push(0);
//	if($dataElement->get('procode')) $vector->push($dataElement->get('procode')); else  $vector->push(0);
//	if($dataElement->get('amnt')) $vector->push($dataElement->get('amnt')); else  $vector->push(0);
//	if($dataElement->get('mcc'))  $vector->push($dataElement->get('mcc')); else  $vector->push(0);
//	if($dataElement->get('country'))  $vector->push($dataElement->get('country')); else  $vector->push(0);
//	if($dataElement->get('posem'))  $vector->push(($dataElement->get('posem'))); else  $vector->push(0);
//	if($dataElement->get('poscc'))  $vector->push(($dataElement->get('poscc'))); else  $vector->push(0);
//	if($dataElement->get('currency'))  $vector->push($dataElement->get('currency')); else  $vector->push(0);
		
//	if($dataElement->get('posem')) $vectorString = $vectorString.$dataElement->get('posem'); else  $vectorString = $vectorString.'000';
//	if($dataElement->get('poscc'))  $vectorString = $vectorString.$dataElement->get('poscc'); else  $vectorString = $vectorString.'00';

//	return	array_map('intval', str_split($vectorString));
//	return $vector->toArray();

	if($dataElement->get('pan')) $vector->push(1); else  $vector->push(0);
	if($dataElement->get('procode')) $vector->push(1); else  $vector->push(0);
	if($dataElement->get('amnt')) $vector->push(1); else  $vector->push(0);
	if($dataElement->get('mcc'))  $vector->push(1); else  $vector->push(0);
	if($dataElement->get('country'))  $vector->push(1); else  $vector->push(0);
	if($dataElement->get('posem'))  $vector->push(1); else  $vector->push(0);
	if($dataElement->get('poscc'))  $vector->push(1); else  $vector->push(0);
	if($dataElement->get('currency'))  $vector->push(1); else  $vector->push(0);
	
	return $vector->toArray();

	}


	public function getSets($relationship_id)
	{
		$sets = collect([]);
		$dataSet = array();
		$targetSet = array();

		$relationship = Relationship::find($relationship_id);

		$reltxns = RelationshipTransaction::where('relationship_id',$relationship_id)->get();

		$data = $relationship->data;
		//dd($reltxns,$data);
		//dd($data);
		$label = new Label();

		foreach($reltxns as $reltxn)
		{
//			dd($data->where('transaction_id',$reltxn->transaction_id)->pluck('value')->implode(''));
			//$dataSet[] = array_map('intval',str_split($data->where('transaction_id',$reltxn->transaction_id)->pluck('value')->implode('')));
			$dataSet[] = array_map('intval',$data->where('transaction_id',$reltxn->transaction_id)->pluck('value')->toArray());
			$targetSet[] = $label->find($reltxn->label_id)->value;	
		}
		//$dataSet[] = array(intval($vposem) , intval($vposcc));
//	dd($dataSet,$targetSet);

		$sets->put('dataset',$dataSet);
		$sets->put('targetset',$targetSet);
		
		return $sets;

	}


	public function createModel(Request $request)
	{
		$relationship = $request->relationship;
		$algorithm = $request->algorithm;
		$modelname = $request->name;

		$sets = $this->getSets($relationship);
		$dataSet = $sets->get('dataset');
		$targetSet = $sets->get('targetset');

		$labels = Label::where('relationship_id',$relationship)->pluck('value')->toArray();
//		dd($dataSet,$targetSet,$labels);

		if($algorithm == 1)
		{
			return "Sorry , algorithm not yet set up for modelling";
		}
		else if($algorithm == 2)
		{
			$algo =  new SVC(Kernel::LINEAR, $cost = 1000);
		}
		else if($algorithm == 3)
		{
			$algo = new KNearestNeighbors();
		}
		else if($algorithm == 4)
		{
			$algo = new NaiveBayes();
		}
		else if($algorithm == 5)
		{
			return "Sorry , algorithm not yet set up for modelling";
		}
		else if($algorithm == 6)
		{
			return "Sorry , algorithm not yet set up for modelling";
		}
		else if($algorithm == 7)
		{
			return "Sorry , algorithm not yet set up for modelling";
		}
		else if($algorithm == 8)
		{
			return "Sorry , algorithm not yet set up for modelling";
		}
		else if($algorithm == 9)
		{

			$fields = Relationship::find($relationship)->fields;
			$inputs = $fields->count();
//			dd($inputs,$inputs*2);
			$algo = new MLPClassifier($inputs, [$inputs*2], $labels);		
		}


		$algo->train($dataSet, $targetSet);
		$filepath = $modelname;
		$modelManager = new ModelManager();
		$modelManager->saveToFile($algo, $filepath);

		$model = new Mlmodel();
		$model->name = $modelname ;
		$model->relationship_id = $relationship ;
		$model->algorithm_id = $algorithm;
		$model->save();

		return $modelname." successfully created";


	}



	public function saveModel(Request $request)
	{

		$n = new NeuralNetwork(5, $request->nodes, 1);
		$n->setVerbose(false);

//		$transactions = Transaction::with('data')->where('annmodel_id',1)->orderby('id','desc')->take(200)->get();
		$validposem = ["010","020","050","900","950","011","021","051","901","951","012","022","052","902","952","016","026","056","906","956"];
		$validposcc = ["00","01","02","03","05","07","08","52","59"];
		
		$invalidposem = ["100","200","300","400","500","600","700","800"];
		$invalidposcc = ["10","20","30","40","50","60","70","80"];

		//	foreach($transactions as $txn)
		for($i=0 ; $i < 100 ; $i++)	
		{
//			$n->addTestData( array(
				//	$txn->data->get(2)->value,
				//	$txn->data->get(3)->value,
				//	$txn->data->get(4)->value,
				//	$txn->data->get(5)->value,
				//	$txn->data->get(6)->value,
//					$txn->data->get(7)->value,
//					$txn->data->get(8)->value
				//	$txn->data->get(10)->value
//					),
//					array ($txn->score));
		$Vposem = array_random($validposem);
		$Vposcc = array_random($validposcc);
			
		$n->addTestData(array_map('intval', str_split($Vposem.$Vposcc)),
				array (1));

		$Iposem = array_random($invalidposem);
		$Iposcc = array_random($invalidposcc);

		$n->addTestData(array_map('intval', str_split ($Iposem.$Iposcc)),
				array (0));
		}

		$max = $request->max;
		$i = 0;

//		$n->setLearningRate($request->learningrate);
// 		$n->setMomentum($request->momentum);

		while (!($success = $n->train($request->epochs, $request->error)) && ++$i<$max) 
		{

		}

		if($n->save(''.$request->name))
		{
			$model = new Mlmodel();
			$model->name = 	$request->name;
			$model->nodes = $request->nodes;
			$model->save();

			return $n->trainInputs ;
		}
		else return 'failure';

	}

	public function loadModelStats($id=0)
	{
		$model = new Mlmodel();
		if(!$id) $modelToLoad = $model->get()->last();
		else $modelToLoad = $model->find($id);

		$n = new NeuralNetwork(5, $modelToLoad->nodes, 1);
		$n->load($modelToLoad->name);

		$n->showWeights(true);
//		return $n->export() ;

	}


	public function getModels()
	{
		$model = new Mlmodel();
		$models = $model->where('id','>',1)->pluck('id','name');

		return $models ;

	}


	public function trainedScore($dataSet,$modelId)
	{

		$model = Mlmodel::find($modelId);

		$modelManager = new ModelManager();

		$algo = $modelManager->restoreFromFile($model->name);
		return $algo->predict($dataSet);

 
	
		$model = new Mlmodel();
		$m = $model->find($modelId);
		//		$n = new NeuralNetwork(64, 8, 1);
		$n = new NeuralNetwork(5,$m->nodes,1);
		$n->load($m->name);
		return $n->calculate($vector);	
	//	return  $n->calculate(array_map('intval', str_split($vector)));

	}

	public function score($vector)
	{
		$transactions = Transaction::with('data')->take(10)->orderby('id','desc')->get();

		$n = new NeuralNetwork(64, 32, 1);
		$n->setVerbose(false);


$n->addTestData(array (0,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
       		array (0.4));

$n->addTestData(array (0,1,1,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0,1,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,1,1,0,0,0,0,0,1,0,0,0,0,1,0,0,0,0),
       		array (0.8));

$n->addTestData(array (1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
       		array (1));

$n->addTestData(array (0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
		array (0));

		foreach($transactions as $transaction)
		{
			$n->addTestData( (array_map('intval', str_split($transaction->data->get(1)->value))),array ($transaction->score));
		}

		$max = 3;
		$i = 0;

		while (!($success = $n->train(1000, 0.01)) && ++$i<$max) 
		{

		}

		return  $n->calculate(array_map('intval', str_split($vector)));
	}





	public function pack($mti,$dataElement)
	{


		try {



$cacheManager = new CacheManager();
$cacheManager->generateSchemaCache(new ISO8583());

/** @var ISO8583 $schemaManager */
$schemaManager = new SchemaManager(new ISO8583(), $cacheManager);

if($dataElement->get('2')) $schemaManager->setPan($dataElement->get('2'));
if($dataElement->get('3')) $schemaManager->setProcessingCode($dataElement->get('3'));
if($dataElement->get('4')) $schemaManager->setAmountTransaction($dataElement->get('4'));
if($dataElement->get('5')) $schemaManager->setAmountSettlement($dataElement->get('5'));
if($dataElement->get('6')) $schemaManager->setAmountCardholderBilling($dataElement->get('6'));
if($dataElement->get('7')) $schemaManager->setTransmissionDateTime($dataElement->get('7'));
if($dataElement->get('18')) $schemaManager->setMerchantType($dataElement->get('18'));
if($dataElement->get('19')) $schemaManager->setCountryCodeAcquiring($dataElement->get('19'));
if($dataElement->get('22')) $schemaManager->setPointOfServiceEntryMode($dataElement->get('22'));
if($dataElement->get('25')) $schemaManager->setPointOfServiceCodeCondition($dataElement->get('25'));
if($dataElement->get('26')) $schemaManager->setPointOfServiceCaptureCode($dataElement->get('26'));
if($dataElement->get('32')) $schemaManager->setAcquiringInstitutionIdentificationCode($dataElement->get('32'));
if($dataElement->get('33')) $schemaManager->setForwardingInstitutionIdentificationCode($dataElement->get('33'));
if($dataElement->get('38')) $schemaManager->setAuthorizationIdentificationResponse($dataElement->get('38'));
if($dataElement->get('39')) $schemaManager->setResponseCode($dataElement->get('39'));
if($dataElement->get('41')) $schemaManager->setCardAcceptorTerminalIdentification($dataElement->get('41'));
if($dataElement->get('42')) $schemaManager->setCardAcceptorIdentificationCode($dataElement->get('42'));
if($dataElement->get('48')) $schemaManager->setAdditionalDataPrivate($dataElement->get('48'));
if($dataElement->get('49')) $schemaManager->setCurrencyCodeTransaction($dataElement->get('49'));
if($dataElement->get('55')) $schemaManager->setIsoReserved1($dataElement->get('55'));
if($dataElement->get('60')) $schemaManager->setPrivateReserved1($dataElement->get('60'));
if($dataElement->get('61')) $schemaManager->setPrivateReserved2($dataElement->get('61'));
if($dataElement->get('90')) $schemaManager->setOriginalDataElements($dataElement->get('90'));
if($dataElement->get('127')) $schemaManager->setPrivateReserved12($dataElement->get('127'));


/** @var MessagePacker $message */
$message = (new Financial($cacheManager))->pack($schemaManager);

$message->setHeaderLength(0);
$message->setMti($mti);

//dd($message);
	return $message->generate();

} catch (Exception $e) {
	
		return 'Error : '.$e->getmessage();

}


//dd($message,$schemaManager,$cacheManager);
//004630323030c00000000000200000000000000000803135333031313237373833393239383734474250303235596f757220746f70757020776173207375636365737366756c
//004630323230c00000000000200000000000000000803135333031313237373833393239383734474250303235596f757220746f70757020776173207375636365737366756c
//004630343232c00000000000200000000000000000803135333031313237373833393239383734474250303235596f757220746f70757020776173207375636365737366756c
	}


	public function unpack()
	{
try {
$cacheManager = new CacheManager();
$cacheManager->generateSchemaCache(new ISO8583());

/** @var ISO8583 $schemaManager */
$schemaManager = new SchemaManager(new ISO8583(), $cacheManager);

/** @var MessageUnpacker $message */
$message = (new Financial($cacheManager))->unpack($schemaManager);

$message->setHeaderLength(2);
//$parsedMessage = $message->parse("012430323030f23e4491a8e08020000000000000002031362a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a303030303030303030303030303031303030313231323134353430383030303030393134353430383132313231373033313231333030303039303230304330303030303030303036303030303230303630303030323033372a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a2a504652333437303030303039323837353937353830303030303030303030333039333733303134373430342054657374204167656e74203220204861746669656c64202020202048654742383236303238303030303030323136317c303030307c504652333437303030303039303135353630313031323634303243313031");
$parsedMessage = $message->parse("004630323230c00000000000200000000000000000803135333031313237373833393239383734474250303235596f757220746f70757020776173207375636365737366756c");

	//$parsedMessage = $message->parse("0200B2200000001000000000000000800000201234000000010000011072218012345606A5DFGR021ABCDEFGHIJ 1234567890");
        // Validate the value...
    } catch (Exception $e) {
	    //        report($e);
	    $pac = $e->getpropertyAnnotationContainer();
dd($e,$pac->getDescription(),$e->getmessage());
 //       return false;
    }
//3032303080000000000020000000000000000080474250303235596f757220746f70757020776173207375636365737366756c

//$parsedMessage = $message->parse($isoMessage);
/** @var ISO8583 $schema */
$schema = $schemaManager->getSchema();
$messageDetail = [
    'mti'  => $parsedMessage->getMti(),
    'data' => $schemaManager->getData(),
    'pan'  => $schema->getPan(),
];

//dd($messageDetail,$parsedMessage,$schemaManager,$schema);
return $messageDetail;
//var_dump($messageDetail);



		return "success";
	}
}
