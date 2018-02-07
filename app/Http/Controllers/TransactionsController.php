<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\JAK8583;

use Exception;
use DB;

use App\Data;
use App\Field;
use App\Transaction;

use Kaperys\Financial\Financial;
use Kaperys\Financial\Cache\CacheManager;
use Kaperys\Financial\Message\Schema\ISO8583;
use Kaperys\Financial\Message\Schema\SchemaManager;
use Kaperys\Financial\Message\Packer\MessagePacker;
use Kaperys\Financial\Message\Unpacker\MessageUnpacker;

class TransactionsController extends Controller
{
	public function analyze(Request $request)
	{
		$mti = "1200"; 
		$dataElement = collect([]);
		$dataElement->put("pan",$request->pan);
		$dataElement->put("amnt",$request->amount);
		$dataElement->put("country",$request->aqcountry);
		$dataElement->put("currency",$request->currency);
		$dataElement->put("de48",$request->prmsg6);

		return "done";
			
		return $this->pack($mti,$dataElement);
	}
	
	public function generate()
	{
		//return "abc";
		$dataElement = collect([]);
		$output = collect([]);

//		$panA = mt_rand(100000000000000,999999999999999);
//		$panB = mt_rand(1,9999);
		//		$pan = $panA.$panB;
		//

		$mti = "1200"; 
		$faker = \Faker\Factory::create('en_US');

		$pan = $faker->creditCardNumber;
		$amnt = str_pad(rand(100,99999), 12, '0', STR_PAD_LEFT);
		$country = rand(100,999);
		$currency = rand(100,999);
		$de48 = $faker->word;



		//dd($faker->creditCardNumber,$faker->numberBetween($min = 100, $max = 9000),$pan,$panA,$panB,$amntrand,$amntcount,str_random(rand(1,999)));

		$dataElement->put("pan",$pan);
		$dataElement->put("amnt",$amnt);
		$dataElement->put("country",$country);
		$dataElement->put("currency",$currency);
		$dataElement->put("de48",$de48);

		$isoMessage = $this->pack($mti,$dataElement);

		if(!$isoMessage) return 'false'; 

		$transaction = new Transaction();

		$transaction->message = $isoMessage;

		$transaction->save();
		
		$jak = new JAK8583();
		$jak->addISO($isoMessage);

	    	DB::table('data')->insert([
		            ['transaction_id' => $transaction->id  , 'field_id' => 0 ,  'value' => $mti],
		            ['transaction_id' => $transaction->id  , 'field_id' => 1 ,  'value' => $jak->getBitmap()],
		            ['transaction_id' => $transaction->id  , 'field_id' => 2 ,  'value' => $pan],
			    ['transaction_id' => $transaction->id  , 'field_id' => 4 ,  'value' => $amnt],
			    ['transaction_id' => $transaction->id  , 'field_id' => 19 ,  'value' => $country],
			    ['transaction_id' => $transaction->id  , 'field_id' => 48 ,  'value' => $de48],
			    ['transaction_id' => $transaction->id  , 'field_id' => 49 ,  'value' => $currency]
		    ]);


		$transactions = Transaction::with('data')->take(10)->orderby('id','desc')->get();

		return $transactions;

		$output->put('msg',$isoMessage);
		$output->put('bitmap',$jak->getBitmap());
		$output->put('id',$transaction->id);
		$output->put('time',$transaction->created_at);


//get parsing result
//print 'ISO: '. $isoMessage. "\n";
//print 'MTI: '. $jak->getMTI(). "\n";
//print 'Bitmap: '. $jak->getBitmap(). "\n";
//print 'Data Element: '; print_r($jak->getData());
//dd($isoMessage,$jak->getMTI(),bin2hex('0200'),str_pad(bin2hex('0200'), 8, 0, STR_PAD_LEFT));
//dd($isoMessage,$jak,$jak->getMTI(),$jak->getBitmap(),$jak->getData());

		return $output;
	}


	public function pack($mti,$dataElement)
	{

//		return 'in pack';

try {
$cacheManager = new CacheManager();
$cacheManager->generateSchemaCache(new ISO8583());

/** @var ISO8583 $schemaManager */
$schemaManager = new SchemaManager(new ISO8583(), $cacheManager);

$schemaManager->setPan($dataElement->get('pan'));
$schemaManager->setAmountTransaction($dataElement->get('amnt'));
$schemaManager->setCountryCodeAcquiring($dataElement->get('country'));
$schemaManager->setCurrencyCodeTransaction($dataElement->get('currency'));
$schemaManager->setAdditionalDataPrivate($dataElement->get('de48'));



/** @var MessagePacker $message */
$message = (new Financial($cacheManager))->pack($schemaManager);

$message->setHeaderLength(0);
$message->setMti($mti);

//dd($message);
	return $message->generate();

} catch (Exception $e) {
	
	//	return $e->getmessage();
	return false ;

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
