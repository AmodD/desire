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

use Phpml\Classification\MLPClassifier;
use Phpml\Preprocessing\Normalizer;
use Phpml\Math\Set;
use Phpml\FeatureExtraction\TfIdfTransformer;
use Phpml\Classification\SVC;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\Classification\NaiveBayes ;
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
		else return Transaction::with('data','mlmodel')->take(10)->orderby('id','desc')->get();
		
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
		
		$mti = "1200"; 
		$faker = \Faker\Factory::create('en_US');

		for ($i = 0; $i < $request->notxns; ++$i)
	       	{
		$pan = $faker->creditCardNumber;
		$pan = str_pad($pan, 16, '0', STR_PAD_LEFT);	
		$amnt = str_pad(rand(100,99999), 12, '0', STR_PAD_LEFT);	
		$arrayCountry = [601, 715, 807, 950, 785, 963];
		$country = array_random($arrayCountry);
		$currency = mt_rand(611,999);
		$de48 = "" ; //$faker->word;
		$arrayProCode = [200004, 315007, 267007, 159008, 244007, 576009];
		$procode = array_random($arrayProCode);
		$arrayposem = ["010","020","050","900","950","011","021","051","901","951","012","022","052","902","952","016","026","056","906","956"];
		$posem = array_random($arrayposem);
		$arrayposcc = ["00","01","02","03","05","07","08","52","59"];
		$poscc = array_random($arrayposcc);
		$chipdata = "" ; //$faker->word;
		$addposdata = "" ; // $faker->word;
		$mcc = mt_rand(6000,9999);

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
		$dataElement->put("18",$mcc);
		$dataElement->put("19",$country);
		$dataElement->put("22",$posem);
		$dataElement->put("25",$poscc);
		$dataElement->put("48",$de48);
		$dataElement->put("49",$currency);
		$dataElement->put("55",$chipdata);
		$dataElement->put("60",$addposdata);

		$isoMessage = $this->pack($mti,$dataElement);
		
		if (strpos($isoMessage, 'Error') !== false) {
			//	return $isoMessage;
			continue;
		}
		else{
			 $this->store($mti,$isoMessage,$dataElement,$model,$score,$label,$relationship);
		}	

		} // for loop ends

		return $this->lasttxns($relationship);

	} // method ends

	public function testdata()
	{


	}

	public function store($mti,$isoMessage,$dataElement,$model,$score,$label,$relationship)
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
		if($model == 1) $transaction->score = $score;
		else $transaction->score = $this->trainedScore($dataSet,$model) ; 
		//else $transaction->score = head($this->trainedScore($vector,$model))  ; 
		//$transaction->score = head($this->trainedScore(($jak->getBitmap()),$model)) ;
		$transaction->save();


	    	DB::table('data')->insert([
		            ['transaction_id' => $transaction->id  , 'field_id' => 0 ,  'value' => $mti],
		            ['transaction_id' => $transaction->id  , 'field_id' => 1 ,  'value' => implode("",$vector)]
//		            ['transaction_id' => $transaction->id  , 'field_id' => 2 ,  'value' => $dataElement->get("pan")],
//		            ['transaction_id' => $transaction->id  , 'field_id' => 3 ,  'value' => $dataElement->get("procode")],
//			    ['transaction_id' => $transaction->id  , 'field_id' => 4 ,  'value' => $dataElement->get("amnt")],
//			    ['transaction_id' => $transaction->id  , 'field_id' => 18 ,  'value' => $dataElement->get("mcc")],
//			    ['transaction_id' => $transaction->id  , 'field_id' => 19 ,  'value' => $dataElement->get("country")],
//			    ['transaction_id' => $transaction->id  , 'field_id' => 22 ,  'value' => $dataElement->get("posem")],
//			    ['transaction_id' => $transaction->id  , 'field_id' => 25 ,  'value' => $dataElement->get("poscc")],
//			    ['transaction_id' => $transaction->id  , 'field_id' => 48 ,  'value' => $dataElement->get("de48")],
//			    ['transaction_id' => $transaction->id  , 'field_id' => 49 ,  'value' => $dataElement->get("currency")],
//			    ['transaction_id' => $transaction->id  , 'field_id' => 55 ,  'value' => $dataElement->get("chipdata")],
//			    ['transaction_id' => $transaction->id  , 'field_id' => 60 ,  'value' => $dataElement->get("addposdata")]
		    ]);

		foreach($dataElement as $number => $value)
		{
			if($value){
				$data = new Data();
				$data->transaction_id = $transaction->id;
				$data->field_id = $number;
				$data->value = $value;
				$data->save();
			}
		}


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
		
	if($dataElement->get('posem')) $vectorString = $vectorString.$dataElement->get('posem'); else  $vectorString = $vectorString.'000';
	if($dataElement->get('poscc'))  $vectorString = $vectorString.$dataElement->get('poscc'); else  $vectorString = $vectorString.'00';

	return	array_map('intval', str_split($vectorString));
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

		}
		else if($algorithm == 2)
		{
			$algo =  new SVC(Kernel::LINEAR, $cost = 1000);
		}
		else if($algorithm == 3)
		{

		}
		else if($algorithm == 4)
		{
			$algo = new NaiveBayes();
		}
		else if($algorithm == 5)
		{

		}
		else if($algorithm == 6)
		{

		}
		else if($algorithm == 7)
		{

		}
		else if($algorithm == 8)
		{

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

		return "model successfully created";

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
if($dataElement->get('19')) $schemaManager->setCountryCodeAcquiring($dataElement->get('19'));
if($dataElement->get('22')) $schemaManager->setPointOfServiceEntryMode($dataElement->get('22'));
if($dataElement->get('25')) $schemaManager->setPointOfServiceCodeCondition($dataElement->get('25'));
if($dataElement->get('48')) $schemaManager->setAdditionalDataPrivate($dataElement->get('48'));
if($dataElement->get('49')) $schemaManager->setCurrencyCodeTransaction($dataElement->get('49'));
if($dataElement->get('55')) $schemaManager->setIsoReserved1($dataElement->get('55'));
if($dataElement->get('60')) $schemaManager->setPrivateReserved1($dataElement->get('60'));
if($dataElement->get('18')) $schemaManager->setMerchantType($dataElement->get('18'));

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
//$parsedMessage = $message->parse("004630323230c00000000000200000000000000000803135333031313237373833393239383734474250303235596f757220746f70757020776173207375636365737366756c");

	$parsedMessage = $message->parse("0200B2200000001000000000000000800000201234000000010000011072218012345606A5DFGR021ABCDEFGHIJ 1234567890");
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
