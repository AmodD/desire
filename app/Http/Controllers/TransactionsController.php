<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\JAK8583;
use Exception;

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
		return $this->pack($request);
	}


	public function pack($request)
	{

//		return 'in pack';

try {
$cacheManager = new CacheManager();
$cacheManager->generateSchemaCache(new ISO8583());

/** @var ISO8583 $schemaManager */
$schemaManager = new SchemaManager(new ISO8583(), $cacheManager);

$schemaManager->setPan($request->pan);
$schemaManager->setAmountTransaction($request->amount);
$schemaManager->setCountryCodeAcquiring($request->aqcountry);
$schemaManager->setCurrencyCodeTransaction($request->currency);
$schemaManager->setPrivateReserved6($request->prmsg6);



/** @var MessagePacker $message */
$message = (new Financial($cacheManager))->pack($schemaManager);

$message->setHeaderLength(2);
$message->setMti('0200');


	return $message->generate();

} catch (Exception $e) {
	
	return $e->getmessage();

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
