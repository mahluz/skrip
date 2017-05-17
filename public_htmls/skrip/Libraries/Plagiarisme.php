<?php

namespace App\Libraries;

use DB;
use PDO;
use App\Setting;

class Plagiarisme{

	public function detection($text1, $text2)
	{
        DB::connection()->setFetchMode(PDO::FETCH_ASSOC);
        $mak = DB::table('stoplist')
            ->limit(10)
            ->get();
        $mak = array_column($mak,"kata");
        array_walk($mak, function(&$value,$key){
            $value = "/\b".$value."\b/i";
        });
        DB::connection()->setFetchMode(PDO::FETCH_CLASS);
		$file1 = preg_replace($mak, '', $text1);

		$file2 = preg_replace($mak, '', $text2);

        $setting = Setting::find(1)->first();

		$kgram1 = $this->buatKGram($file1,$setting->kgram);
		$kgram2 = $this->buatKGram($file2,$setting->kgram);

		return $this->similarity($kgram1,$kgram2);			//Algoritma Rabin Karp
	}

	/*-----------------------------------------------------
			Algoritma Rabin Karp
	-------------------------------------------------------*/

    private $modulo=256;
	
    private function hash($kgram){
        $h=0;
        for($i=0;$i<strlen($kgram);$i++){
            $h = (256 * $h + substr($kgram,$i,1)) % $this->modulo;
        }
        
        return $h;
    }
    
    private function similarity($kgramAsli, $kgramUji){
        $c=0;
        $a = count($kgramAsli);
        $b = count($kgramUji);
        
        for($i=0;$i<count($kgramUji);$i++){
            for($j=0;$j<count($kgramAsli);$j++){
                if($this->hash($kgramUji[$i])==$this->hash($kgramAsli[$j])){
                    if($kgramUji[$i]==$kgramAsli[$j]) {
                        $c++;
                        break;
                    }
                }
            }
        }
        
        return round(((2*$c)/($a+$b))*100,2);
    }

    private function buatKGram($teks,$k){
    	$teks = str_replace(" ", "", $teks);

        $KGram = array();
        if(strlen($teks)<=$k){
        	$KGram[0] = $teks;
        }
        else{
	        for($i=0;$i<=strlen($teks)-$k;$i++){
	            $KGram[$i] = substr($teks, $i, $k);
	        }
	    }
        return $KGram;
    }
}