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
use App\Annmodel;

use Phpml\Classification\MLPClassifier;

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

		$n = new NeuralNetwork(3, 6, 1);
		$n->setVerbose(false);

		$n->addTestData(array (-1,-1,1), array (-1));
		$n->addTestData(array (-1,1,1), array (1));
		$n->addTestData(array (1,-1,1), array (1));
		$n->addTestData(array (1,1,1), array (-1));

		$max = 3;
		$i = 0;

//		$n->setLearningRate(0.9);

		while (!($success = $n->train(1000, 0.01)) && ++$i<$max) 
		{

		}

		$data = collect([]);

		$data->put('calculate',$n->calculate(array(1,1,-1)));
		$data->put('export',$n->export());	
//		$n->showWeights(true);
//		dd($n->calculate(array(0,1,0)),$n->export());
		
		return  $data;

	}

	public function analyze(Request $request)
	{
		$mti = "1200"; 
		$dataElement = collect([]);

		$model =  $request->model;

		$dataElement->put("pan",$request->pan);
		$dataElement->put("amnt",$request->amount);
		$dataElement->put("country",$request->aqcountry);
		$dataElement->put("currency",$request->currency);
		$dataElement->put("de48",$request->prmsg6);
		$dataElement->put("procode",$request->procode);
		$dataElement->put("posem",$request->posem);
		$dataElement->put("poscc",$request->poscc);
		$dataElement->put("chipdata",$request->chipdata);
		$dataElement->put("addposdata",$request->addposdata);
			
		$isoMessage = $this->pack($mti,$dataElement);

		if (strpos($isoMessage, 'Error') !== false) {
		    return $isoMessage;
		}
		else{
			return $this->store($mti,$isoMessage,$dataElement,$model);
		}	
	}
	
	public function generate(Request $request)
	{
		$dataElement = collect([]);
		$output = collect([]);
		$model =  $request->model;
		
		$mti = "1200"; 
		$faker = \Faker\Factory::create('en_US');

		$pan = $faker->creditCardNumber;
		$amnt = str_pad(rand(100,99999), 12, '0', STR_PAD_LEFT);	
		$arrayCountry = [601, 715, 807, 950, 785, 963];
		$country = array_random($arrayCountry);
		$currency = mt_rand(611,999);
		$de48 = $faker->word;
		$arrayProCode = [200004, 315007, 267007, 159008, 244007, 576009];
		$procode = array_random($arrayProCode);
		$posem = mt_rand(666,999);
		$poscc = "99";
		$chipdata = $faker->word;
		$addposdata = $faker->word;

		
		$dataElement->put("pan",$pan);
		$dataElement->put("amnt",$amnt);
		$dataElement->put("country",$country);
		$dataElement->put("currency",$currency);
		$dataElement->put("de48",$de48);
		$dataElement->put("procode",$procode);
		$dataElement->put("posem",$posem);
		$dataElement->put("poscc",$poscc);
		$dataElement->put("chipdata",$chipdata);
		$dataElement->put("addposdata",$addposdata);

		$isoMessage = $this->pack($mti,$dataElement);
		
		if (strpos($isoMessage, 'Error') !== false) {
			return $isoMessage;
		}
		else{
			return $this->store($mti,$isoMessage,$dataElement,$model);
		}	

	}

	public function store($mti,$isoMessage,$dataElement,$model)
	{
		try{
			$jak = new JAK8583();
			$jak->addISO($isoMessage);

		} catch (Exception $e) {
			return 'Error : '.$e->getmessage();
		}

		$transaction = new Transaction();
		$transaction->message = $isoMessage;
		$transaction->annmodel_id = $model;
		$transaction->score = head($this->trainedScore(($jak->getBitmap()),$model)) ;
		$transaction->save();

	    	DB::table('data')->insert([
		            ['transaction_id' => $transaction->id  , 'field_id' => 0 ,  'value' => $mti],
		            ['transaction_id' => $transaction->id  , 'field_id' => 1 ,  'value' => $jak->getBitmap()],
		            ['transaction_id' => $transaction->id  , 'field_id' => 2 ,  'value' => $dataElement->get("pan")],
		            ['transaction_id' => $transaction->id  , 'field_id' => 3 ,  'value' => $dataElement->get("procode")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 4 ,  'value' => $dataElement->get("amnt")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 19 ,  'value' => $dataElement->get("country")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 22 ,  'value' => $dataElement->get("posem")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 25 ,  'value' => $dataElement->get("poscc")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 48 ,  'value' => $dataElement->get("de48")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 49 ,  'value' => $dataElement->get("currency")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 55 ,  'value' => $dataElement->get("chipdata")],
			    ['transaction_id' => $transaction->id  , 'field_id' => 60 ,  'value' => $dataElement->get("addposdata")]
		    ]);


		$transactions = Transaction::with('data','annmodel')->take(10)->orderby('id','desc')->get();

		return $transactions;


	}

	public function saveModel(Request $request)
	{
		$n = new NeuralNetwork(64, $request->nodes, 1);
		$n->setVerbose(false);


$n->addTestData(array (1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
       		array (1));

$n->addTestData(array (0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
		array (0));

		$max = $request->max;
		$i = 0;

		$n->setLearningRate($request->learningrate);
// 		$n->setMomentum($request->momentum);

		while (!($success = $n->train($request->epochs, $request->error)) && ++$i<$max) 
		{

		}

		if($n->save(''.$request->name))
		{
			$model = new Annmodel();
			$model->name = 	$request->name;
			$model->nodes = $request->nodes;
			$model->save();

			return 'success' ;
		}
		else return 'failure';

	}

	public function loadModelStats($id=0)
	{
		$model = new Annmodel();
		if(!$id) $modelToLoad = $model->get()->last();
		else $modelToLoad = $model->find($id);

		$n = new NeuralNetwork(64, $modelToLoad->nodes, 1);
		$n->load($modelToLoad->name);

		$n->showWeights(true);
//		return $n->export() ;

	}


	public function getModels()
	{
		$model = new Annmodel();
		$models = $model->all()->pluck('id','name');

		return $models ;


	}


	public function trainedScore($vector,$modelId)
	{

		$model = new Annmodel();
		$m = $model->find($modelId);
		//		$n = new NeuralNetwork(64, 8, 1);
		$n = new NeuralNetwork(64,$m->nodes,1);
		$n->load($m->name);
		
		return  $n->calculate(array_map('intval', str_split($vector)));

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

if($dataElement->get('pan')) $schemaManager->setPan($dataElement->get('pan'));
if($dataElement->get('procode')) $schemaManager->setProcessingCode($dataElement->get('procode'));
if($dataElement->get('amnt')) $schemaManager->setAmountTransaction($dataElement->get('amnt'));
if($dataElement->get('country')) $schemaManager->setCountryCodeAcquiring($dataElement->get('country'));
if($dataElement->get('posem')) $schemaManager->setPointOfServiceEntryMode($dataElement->get('posem'));
if($dataElement->get('poscc')) $schemaManager->setPointOfServiceCodeCondition($dataElement->get('poscc'));
if($dataElement->get('de48')) $schemaManager->setAdditionalDataPrivate($dataElement->get('de48'));
if($dataElement->get('currency')) $schemaManager->setCurrencyCodeTransaction($dataElement->get('currency'));
if($dataElement->get('chipdata')) $schemaManager->setIsoReserved1($dataElement->get('chipdata'));
if($dataElement->get('addposdata')) $schemaManager->setPrivateReserved1($dataElement->get('addposdata'));

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
