<?php

//funkcija, apskaičiuojanti atlyginimą ant popieriaus, pagal paduotas reikšmes:
	function algaAntPopieriaus ($algaIrankas, $proc) {
		 $count = $algaIrankas * 100 / $proc;
		 return $count;
	 }

//funkcija, apskaičiuojanti kokią dalį sumos, nuo savo atlyginimo darbuotojas sumoka sodrai:
	function sodrosMokesciai ($alga, $sodra) {
			$count = $sodra / 100 * $alga;
			return $count;
		};

 //funkcija, apskaičiuojanti kokią dalį sumos, nuo savo atlyginimo ant popieriaus darbuotojas sumoka VMI:
  function gpmMokesciai ($alga, $gpm) {
 	 	$count = $gpm / 100 * $alga;
 	 	return $count;
  }

// funkcija apskaičiuoja kokią sumos dalį sumoka darbdavys Sodrai nuo darbuotojo atlyginimo ant popieriaus:
	function darbdavioMokesciai ($algaAntPopieriaus, $darbdavioMokesciai) {
		 $count = $darbdavioMokesciai / 100 * $algaAntPopieriaus;
		 return $count;
	}

// funkcija apskaičiuoja visą darbo vietos kainą, sudėdama, anksčiau apskaičiuotų darbdavio mokesčių dydį ir atlyginimą ant popieriaus:
	function darboVietosKaina ($algaAntPopieriaus, $ResultDarbdavioMokesciai) {
			$count = $algaAntPopieriaus + $ResultDarbdavioMokesciai;
			return $count;
		}

//funkcija, apskaičiuojanti atlyginimą į rankas, pagal paduotas reikšmes:
	function algaIrankas ($algaAntPopieriaus, $ResultSodrosMokesciai, $ResultGpm) {
			$count = $algaAntPopieriaus - $ResultSodrosMokesciai - $ResultGpm;
	  	return $count;
	}

//apskaicuojami autoriniu sutarciu mokesciai:
	function sodrosMokesciaiAutorines($autorinesSutartys, $sodra){
		$count = $sodra / 100 * $autorinesSutartys;
		return $count;
	}

	function gpmMokesciaiAutorines ($autorinesSutartys, $gpm) {
		$count = $gpm / 100 * $autorinesSutartys;
		return $count;
	}


//funkcija, apskaičiuojanti autoriniu sutarciu suma, atskaicius mokescius:
	function autorinesPoMokesciu ($autorinesSutartys, $ResultSodrosMokesciai, $ResultGpm ){
		$count = $autorinesSutartys - $ResultSodrosMokesciai - $ResultGpm;
		return $count;
	}

//funkcija apskaičiuoja bendras mėnesines darbuotojo pajamas, atskaičius mokesčius:
	function bendrosPajamos ($autorines_sutartys, $algaIrankas) {
		$count = $autorines_sutartys + $algaIrankas;
		return $count;
	}
?>
