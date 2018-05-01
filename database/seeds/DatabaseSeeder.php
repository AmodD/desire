<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	// $this->call(UsersTableSeeder::class);
	    DB::table('mlmodels')->insert([
		    ['id' => 1 ,  'name' => 'Test Data', 'relationship_id' => 0 , 'algorithm_id' => 0]
	    ]);

	    DB::table('algorithms')->insert([
		            ['id' => 1 ,  'name' => 'Apriori' , 'type' => 'Association'],
			    ['id' => 2 ,  'name' => 'SVC' , 'type' => 'Classification'],
			    ['id' => 3 ,  'name' => 'K-Nearest Neighbours' , 'type' => 'Classification'],
			    ['id' => 4 ,  'name' => 'Naive Bayes' , 'type' => 'Classification'],
			    ['id' => 5 ,  'name' => 'Least Squares' , 'type' => 'Regression'],
			    ['id' => 6 ,  'name' => 'SVR' , 'type' => 'Regression'],
			    ['id' => 7 ,  'name' => 'K-Means' , 'type' => 'Clustering'],
			    ['id' => 8 ,  'name' => 'DBSCAN' , 'type' => 'Clustering'],
			    ['id' => 9 ,  'name' => 'MLP Classifier Backpropagation' , 'type' => 'ANN']
	    ]);

	    DB::table('fields')->insert([
		            ['id' => 0 ,  'element' => 'MTI'],
		            ['id' => 1 ,  'element' => 'BitMap'],
		            ['id' => 2 ,  'element' => 'Primary account number (PAN)'],
		            ['id' => 3 ,  'element' => 'Processing Code'],
			    ['id' => 4 ,  'element' => 'Amount, transaction'],
			    ['id' => 5 ,  'element' => 'Amount, settlement'],
			    ['id' => 6 ,  'element' => 'Amount, cardholder billing'],
			    ['id' => 7 ,  'element' => 'Transmission Date & Time'],
			    ['id' => 18 , 'element' => 'Merchant Category Code'],
			    ['id' => 19 , 'element' => 'Acquiring institution country code'],
		            ['id' => 22 , 'element' => 'Point of Service Entry Mode'],
		            ['id' => 25 , 'element' => 'POS Condition Code'],
			    ['id' => 26 ,  'element' => 'Point of service capture code' ],
			    ['id' => 32 ,  'element' => 'Acquiring institution identification code'],
			    ['id' => 33 ,  'element' => 'Forwarding institution identification code'],
			    ['id' => 38 ,  'element' => 'Authorization identification response'],
			    ['id' => 39 ,  'element' => 'Response code'],
			    ['id' => 41 ,  'element' => 'Card acceptor terminal identification'],
			    ['id' => 42 ,  'element' => 'Card acceptor identification code'],
			    ['id' => 48 , 'element' => 'Additional data - private'],
			    ['id' => 49 , 'element' => 'Currency code, transaction'],
			    ['id' => 52 ,  'element' => 'Personal identification number data'],
		            ['id' => 55 , 'element' => 'Chip Data'],
		            ['id' => 60 , 'element' => 'Additional POS Data'],
			    ['id' => 61 ,  'element' => 'Reserved (private) 2'],
			    ['id' => 90 ,  'element' => 'Original data elements'],
			    ['id' => 127 ,  'element' => 'Reserved for private use 12']

		    ]);
	    
	    DB::table('relationships')->insert([
		    ['id' => 1 ,  'name' => 'Simulation']
	    ]);

	    DB::table('field_relationship')->insert([
		    ['id' => 1 ,  'field_id' => '0', 'relationship_id' => 1],
		    ['id' => 2 ,  'field_id' => '3', 'relationship_id' => 1],
		    ['id' => 3 ,  'field_id' => '22', 'relationship_id' => 1],
		    ['id' => 4 ,  'field_id' => '26', 'relationship_id' => 1],
	    ]);

	    DB::table('labels')->insert([
		    ['id' => 1 ,  'relationship_id' => 1 , 'value' => 'Valid'],
		    ['id' => 2 ,  'relationship_id' => 1 , 'value' => 'Invalid']
	    ]);

	    DB::table('scenarios')->insert([
		            ['id' => 1 , 'name' => 'ATM' , 'question_id' => '1' , 'question_name' => 'Where'],
		            ['id' => 2 , 'name' => 'AUTOMATED_DISPENSING_MACHINES' , 'question_id' => '1' , 'question_name' => 'Where'],
		            ['id' => 3 , 'name' => 'BANK' , 'question_id' => '1' , 'question_name' => 'Where'],
		            ['id' => 4 , 'name' => 'BANK/MERCHANT' , 'question_id' => '1' , 'question_name' => 'Where'],
		            ['id' => 5 , 'name' => 'FILE' , 'question_id' => '1' , 'question_name' => 'Where'],
		            ['id' => 6 , 'name' => 'IN_FLIGHT' , 'question_id' => '1' , 'question_name' => 'Where'],
		            ['id' => 7 , 'name' => 'LIMITED_AMOUNT_TERMINALS' , 'question_id' => '1' , 'question_name' => 'Where'],
		            ['id' => 8 , 'name' => 'MERCHANT' , 'question_id' => '1' , 'question_name' => 'Where'],
		            ['id' => 9 , 'name' => 'MPOS' , 'question_id' => '1' , 'question_name' => 'Where'],
		            ['id' => 10 , 'name' => 'PHONE' , 'question_id' => '1' , 'question_name' => 'Where'],
		            ['id' => 11 , 'name' => 'SELF_SERVICED_TERMINAL' , 'question_id' => '1' , 'question_name' => 'Where'],
		            ['id' => 12 , 'name' => 'TRANSPONDER' , 'question_id' => '1' , 'question_name' => 'Where'],
		            ['id' => 13 , 'name' => 'UNATTENDED_TERMINAL' , 'question_id' => '1' , 'question_name' => 'Where'],
		            ['id' => 14 , 'name' => 'UNKNOWN' , 'question_id' => '1' , 'question_name' => 'Where'],
			    ['id' => 15 , 'name' => 'WEB' , 'question_id' => '1' , 'question_name' => 'Where'],

			    ['id' => 16 , 'name' => 'PURCHASE' , 'question_id' => '2' , 'question_name' => 'What'],
			    ['id' => 17 , 'name' => 'CASHADV' , 'question_id' => '2' , 'question_name' => 'What'],
			    ['id' => 18 , 'name' => 'CASHBACK' , 'question_id' => '2' , 'question_name' => 'What'],
			    ['id' => 19 , 'name' => 'CASHWDW' , 'question_id' => '2' , 'question_name' => 'What'],
			    ['id' => 20 , 'name' => 'PINCHANGE' , 'question_id' => '2' , 'question_name' => 'What'],
			    ['id' => 21 , 'name' => 'PINUNBLK' , 'question_id' => '2' , 'question_name' => 'What'],
			    ['id' => 22 , 'name' => 'PURCHASE_ADVICE' , 'question_id' => '2' , 'question_name' => 'What'],
			    ['id' => 23 , 'name' => 'PURCHASE_ADVICE_SAF' , 'question_id' => '2' , 'question_name' => 'What'],
			    ['id' => 24 , 'name' => 'PURCHASE_ACTIVATION' , 'question_id' => '2' , 'question_name' => 'What'],
			    ['id' => 25 , 'name' => 'REFUND' , 'question_id' => '2' , 'question_name' => 'What'],
			    ['id' => 26 , 'name' => 'REFUND_ADVICE' , 'question_id' => '2' , 'question_name' => 'What'],
			    ['id' => 27 , 'name' => 'RETURN' , 'question_id' => '2' , 'question_name' => 'What'],
			    ['id' => 28 , 'name' => 'VERIFICATION' , 'question_id' => '2' , 'question_name' => 'What'],

			    ['id' => 29 , 'name' => 'MSR' , 'question_id' => '3' , 'question_name' => 'How'],
			    ['id' => 30 , 'name' => 'COF' , 'question_id' => '3' , 'question_name' => 'How'],
			    ['id' => 31 , 'name' => 'ELECTRONIC' , 'question_id' => '3' , 'question_name' => 'How'],
			    ['id' => 32 , 'name' => 'FALLBACK_MAN' , 'question_id' => '3' , 'question_name' => 'How'],
			    ['id' => 33 , 'name' => 'FALLBACK_MSR' , 'question_id' => '3' , 'question_name' => 'How'],
			    ['id' => 34 , 'name' => 'ICC_CONTACT' , 'question_id' => '3' , 'question_name' => 'How'],
			    ['id' => 35 , 'name' => 'ICC_CONTACTLESS' , 'question_id' => '3' , 'question_name' => 'How'],
			    ['id' => 36 , 'name' => 'ICC_ELECTRONIC' , 'question_id' => '3' , 'question_name' => 'How'],
			    ['id' => 37 , 'name' => 'MANUAL' , 'question_id' => '3' , 'question_name' => 'How'],
			    ['id' => 38 , 'name' => 'MSR_CONTACTLESS' , 'question_id' => '3' , 'question_name' => 'How'],
			    ['id' => 39 , 'name' => 'PHONE' , 'question_id' => '3' , 'question_name' => 'How'],
			    ['id' => 40 , 'name' => 'UNKNOWN' , 'question_id' => '3' , 'question_name' => 'How'],

			    ['id' => 41 , 'name' => 'HUMAN' , 'question_id' => '4' , 'question_name' => 'Who'],
			    ['id' => 42 , 'name' => 'MACHINE' , 'question_id' => '4' , 'question_name' => 'Who'],
			    ['id' => 43 , 'name' => 'UNKNOWN' , 'question_id' => '4' , 'question_name' => 'Who'],

			    ['id' => 44 , 'name' => 'Goods_or_Service' , 'question_id' => '5' , 'question_name' => 'Why'],
			    ['id' => 45 , 'name' => 'FORGOT_PIN' , 'question_id' => '5' , 'question_name' => 'Why'],
			    ['id' => 46 , 'name' => 'GET_MONEY_BACK' , 'question_id' => '5' , 'question_name' => 'Why'],
			    ['id' => 47 , 'name' => 'INQUIRE/FIND OUT' , 'question_id' => '5' , 'question_name' => 'Why'],
			    ['id' => 48 , 'name' => 'NEED_CASH' , 'question_id' => '5' , 'question_name' => 'Why'],
			    ['id' => 49 , 'name' => 'UNHAPPY_WITH_SERVICE' , 'question_id' => '5' , 'question_name' => 'Why'],
			    ['id' => 50 , 'name' => 'VERIFY' , 'question_id' => '5' , 'question_name' => 'Why'],
			    
			    ['id' => 51 , 'name' => 'CAPABLE' , 'question_id' => '6' , 'question_name' => 'Pin'],
			    ['id' => 52 , 'name' => 'NO CVM' , 'question_id' => '6' , 'question_name' => 'Pin'],
			    ['id' => 53 , 'name' => 'NOT CAPABLE' , 'question_id' => '6' , 'question_name' => 'Pin'],
			    ['id' => 54 , 'name' => 'OFFLINE PIN' , 'question_id' => '6' , 'question_name' => 'Pin'],
			    ['id' => 55 , 'name' => 'ON BEHALF' , 'question_id' => '6' , 'question_name' => 'Pin'],
			    ['id' => 56 , 'name' => 'ONLINE PIN' , 'question_id' => '6' , 'question_name' => 'Pin'],
			    ['id' => 57 , 'name' => 'SIGNATURE' , 'question_id' => '6' , 'question_name' => 'Pin'],
			    ['id' => 58 , 'name' => 'SIGNATURE OR NO CVM' , 'question_id' => '6' , 'question_name' => 'Pin'],
			    ['id' => 59 , 'name' => 'UNKNOWN' , 'question_id' => '6' , 'question_name' => 'Pin'],
	    ]);


	    DB::table('field_scenario')->insert([
		    ['id' => 1 ,  'scenario_id' => '1', 'field_id' => 26 , 'value' => '04'],
		    ['id' => 2 ,  'scenario_id' => '2', 'field_id' => 26 , 'value' => '08'],
		    ['id' => 3 ,  'scenario_id' => '3', 'field_id' => 26 , 'value' => '08'],
		    ['id' => 4 ,  'scenario_id' => '4', 'field_id' => 26 , 'value' => '08'],
		    ['id' => 5 ,  'scenario_id' => '5', 'field_id' => 26 , 'value' => '08'],
		    ['id' => 6 ,  'scenario_id' => '6', 'field_id' => 26 , 'value' => '08'],
		    ['id' => 7 ,  'scenario_id' => '7', 'field_id' => 26 , 'value' => '08'],
		    ['id' => 8 ,  'scenario_id' => '8', 'field_id' => 26 , 'value' => '08'],
		    ['id' => 9 ,  'scenario_id' => '9', 'field_id' => 26 , 'value' => '08'],
		    ['id' => 10 ,  'scenario_id' => '10', 'field_id' => 26 , 'value' => '08'],
		    ['id' => 11 ,  'scenario_id' => '11', 'field_id' => 26 , 'value' => '08'],
		    ['id' => 12 ,  'scenario_id' => '12', 'field_id' => 26 , 'value' => '08'],
		    ['id' => 13 ,  'scenario_id' => '13', 'field_id' => 26 , 'value' => '08'],
		    ['id' => 14 ,  'scenario_id' => '14', 'field_id' => 26 , 'value' => '08'],
		    ['id' => 15 ,  'scenario_id' => '15', 'field_id' => 26 , 'value' => '08'],


		    ['id' => 16 ,  'scenario_id' => '16', 'field_id' => 0 , 'value' => '0100'],
		    ['id' => 17 ,  'scenario_id' => '16', 'field_id' => 3 , 'value' => '000000'],

		    ['id' => 18 ,  'scenario_id' => '17', 'field_id' => 0 , 'value' => '0100'],
		    ['id' => 19 ,  'scenario_id' => '17', 'field_id' => 3 , 'value' => '010000'],

		    ['id' => 20 ,  'scenario_id' => '18', 'field_id' => 0 , 'value' => '0100'],
		    ['id' => 21 ,  'scenario_id' => '18', 'field_id' => 3 , 'value' => "090000" ],

		    ['id' => 22 ,  'scenario_id' => '19', 'field_id' => 0 , 'value' => '0100'],
		    ['id' => 23 ,  'scenario_id' => '19', 'field_id' => 3 , 'value' => '170000'],

		    ['id' => 24 ,  'scenario_id' => '20', 'field_id' => 0 , 'value' => '0100'],
		    ['id' => 25 ,  'scenario_id' => '20', 'field_id' => 3 , 'value' => '910000'],

		    ['id' => 26 ,  'scenario_id' => '21', 'field_id' => 0 , 'value' => '0100'],
		    ['id' => 27 ,  'scenario_id' => '21', 'field_id' => 3 , 'value' => '920000'],

		    ['id' => 28 ,  'scenario_id' => '22', 'field_id' => 0 , 'value' => '0120'],
		    ['id' => 29 ,  'scenario_id' => '22', 'field_id' => 3 , 'value' => '000000'],

		    ['id' => 30 ,  'scenario_id' => '23', 'field_id' => 0 , 'value' => '0120'],
		    ['id' => 31 ,  'scenario_id' => '23', 'field_id' => 3 , 'value' => '000000'],

		    ['id' => 32 ,  'scenario_id' => '24', 'field_id' => 0 , 'value' => '0100'],
		    ['id' => 33 ,  'scenario_id' => '24', 'field_id' => 3 , 'value' => '000000'],

		    ['id' => 34 ,  'scenario_id' => '25', 'field_id' => 0 , 'value' => '0100'],
		    ['id' => 35 ,  'scenario_id' => '25', 'field_id' => 3 , 'value' => '280000'],

		    ['id' => 36 ,  'scenario_id' => '26', 'field_id' => 0 , 'value' => '0120'],
		    ['id' => 37 ,  'scenario_id' => '26', 'field_id' => 3 , 'value' => '280000'],

		    ['id' => 38 ,  'scenario_id' => '27', 'field_id' => 0 , 'value' => '0100'],
		    ['id' => 39 ,  'scenario_id' => '27', 'field_id' => 3 , 'value' => '200000'],

		    ['id' => 40 ,  'scenario_id' => '28', 'field_id' => 0 , 'value' => '0100'],
		    ['id' => 41 ,  'scenario_id' => '28', 'field_id' => 3 , 'value' => '330000'],

		    ['id' => 42 ,  'scenario_id' => '29', 'field_id' => 22 , 'value' => "90"],
		    ['id' => 43 ,  'scenario_id' => '30', 'field_id' => 22 , 'value' => "10"],
		    ['id' => 44 ,  'scenario_id' => '31', 'field_id' => 22 , 'value' => "81"],
		    ['id' => 45 ,  'scenario_id' => '32', 'field_id' => 22 , 'value' => "79"],
		    ['id' => 46 ,  'scenario_id' => '33', 'field_id' => 22 , 'value' => "80"],
		    ['id' => 47 ,  'scenario_id' => '34', 'field_id' => 22 , 'value' => "05"],
		    ['id' => 48 ,  'scenario_id' => '35', 'field_id' => 22 , 'value' => "07"],
		    ['id' => 49 ,  'scenario_id' => '36', 'field_id' => 22 , 'value' => "09"],
		    ['id' => 50 ,  'scenario_id' => '37', 'field_id' => 22 , 'value' => "01"],
		    ['id' => 51 ,  'scenario_id' => '38', 'field_id' => 22 , 'value' => "91"],
		    ['id' => 52 ,  'scenario_id' => '39', 'field_id' => 22 , 'value' => "01"],
		    ['id' => 53 ,  'scenario_id' => '40', 'field_id' => 22 , 'value' => "00"],

		    ['id' => 54 ,  'scenario_id' => '51', 'field_id' => 22 , 'value' => "1"],
		    ['id' => 55 ,  'scenario_id' => '52', 'field_id' => 22 , 'value' => "0"],
		    ['id' => 56 ,  'scenario_id' => '53', 'field_id' => 22 , 'value' => "2"],
		    ['id' => 57 ,  'scenario_id' => '54', 'field_id' => 22 , 'value' => "1"],
		    ['id' => 58 ,  'scenario_id' => '55', 'field_id' => 22 , 'value' => "9"],
		    ['id' => 59 ,  'scenario_id' => '56', 'field_id' => 22 , 'value' => "1"],
		    ['id' => 60 ,  'scenario_id' => '57', 'field_id' => 22 , 'value' => "2"],
		    ['id' => 61 ,  'scenario_id' => '58', 'field_id' => 22 , 'value' => "2"],
		    ['id' => 62 ,  'scenario_id' => '59', 'field_id' => 22 , 'value' => "0"],
	    ]);

    }
}
