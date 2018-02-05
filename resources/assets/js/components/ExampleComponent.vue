<template>
	<div>	
		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">Bit 2</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" type="text" v-model="pan"  placeholder="Permanent Account Number"></div></div>
	        </div>			  
		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">Bit 4</label> </div>
	          <div class="field-body"><div class="field">
		        <input class="input"  v-model="amount" type="text" placeholder="Amount"></div></div>
	        </div>			  
		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">Bit 19</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" type="text" v-model="aqcountry"  placeholder="Acquiring Instituition Country Code"></div></div>
	        </div>			  
		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">Bit 49</label> </div>
	          <div class="field-body"><div class="field">
			<input class="input" type="text"  v-model="currency" placeholder="Currency Code of Transaction"></div></div>
	        </div>			  
		<div class="field is-horizontal">
		  <div class="field-label is-normal"> <label class="label">Bit 121</label> </div>
	          <div class="field-body"><div class="field">
		        <input class="input" type="text"  v-model="prmsg6" placeholder="Private Reserved Message"></div></div>
	        </div>			  









        <div class="row">
            <div class="col-md-8 col-md-offset-2">
               <div class="panel-block">
    <button v-on:click="analyze" class="button is-link is-outlined">
      click to analyze
    </button>
  </div> 
		<div class="panel panel-default">
		<div class="panel-heading">ISO 8583 Message HEX</div>
                    <div class="panel-body">
			    {{ msg8583 }}
                    </div>
	  	</div>
	    </div>
        </div>
	</div>
</template>

<script>
    export default {
	data : function() {
		return {
			txnSpec : false,
			bitmap : '',
			msg8583 : '',
			pan : '',
			currency : '',
			procode : '',
			amount : '',
			aqcountry : '',
			msgnumber : 0,
			prmsg6 : ''
		}
	},	 
	computed : {
		txnSpecValue : function() {
			return 'This is a ' + this.txnSpec + ' transaction message' ;
		}
	},
	methods : {
		analyze(){
			console.log("to analyze "+this.msg8583);
			axios.get('/analyze?pan='+this.pan+'&currency='+this.currency+'&prmsg6='+this.prmsg6+'&amount='+this.amount+'&aqcountry='+this.aqcountry)
//			.then(function (response) {
//			    console.log(response);
//			    this.msg8583 = response.data;
//			  })
			.then(response => this.msg8583 = response.data)
			.catch(function (error) {
			    console.log(error);
			  });
		}
	},	
        mounted() {
                console.log('Component mounted.')
		const iso8583 = require('iso_8583');
		console.log(	iso8583.getFieldDescription([0,24, 37, 39]) );

let data = {
    0: "0100",
    2: "4761739001010119",
    3: "000000",
    4: "000000005000",
    7: "0911131411",
    12: "131411",
    13: "0911",
    14: "2212",
    18: "4111",
    22: "051",
    23: "001",
    25: "00",
    26: "12",
    32: "423935",
    33: "111111111",
    35: "4761739001010119D22122011758928889",
    41: "12345678",
    42: "MOTITILL_000001",
    43: "My Termianl Business                    ",
    49: "404",
    52: "7434F67813BAE545",
    56: "1510",
    123: "91010151134C101",
    127: "000000800000000001927E1E5F7C0000000000000000500000000000000014A00000000310105C000128FF0061F379D43D5AEEBC8002800000000000000001E0302031F000203001406010A03A09000008CE0D0C840421028004880040417091180000014760BAC24959"
};
 
let isopack = new iso8583(this.msg8583);
console.log(isopack);
this.txnSpec = isopack.validateMessage(); 
console.log(isopack.validateMessage());
this.bitmap = isopack.getBmpsBinary();

        }
    }
</script>
