<?php

include_once 'config.php';
include_once 'functions.php';

$results ='';

$ResultSodrosMokesciai = '';
$ResultGpm = '';
$ResultAlgaAntPopieriaus='';
$ResultAlgaIrankas='';
$ResultDarbdavioMokesciai = '';
$ResultDarboVietosKaina = '';

$algaIrankas = 0;
$algaAntPopieriaus = 0;

//paimama įvesta reikšmė iš laukelio "atlyginimas į rankas". Čia skaičiuojami tab'o "Atlyginimas į rankas" rezultatai:
if (isset($_GET['alga_rankas']) && !empty($_GET['alga_rankas'])) {
	$algaIrankas = (float)$_GET['alga_rankas'];

//kuriamas kintamasis "alga ant popieriaus", kuris bus išvedamas į langą. Kintamasis išvedamas su funkcija "algaAntPopieriaus"
	$proc = 100 - SODRA_VAT - GPM;
	$ResultAlgaAntPopieriaus = algaAntPopieriaus($algaIrankas, $proc);

//kuriamas kintamasis "sodros mokesčiai", kuris bus išvedamas į langą. Kintamasis išvedamas su funkcija "Sodros mokesčiai"
	$ResultSodrosMokesciai = sodrosMokesciai($ResultAlgaAntPopieriaus, SODRA_VAT);

//kuriamas kintamasis "GPM mokesčiai", kuris bus išvedamas į langą.
	$ResultGpm = gpmMokesciai ($ResultAlgaAntPopieriaus, GPM);

//kuriamas kintamasis "darbdavio mokami mokesčiai", kuris bus išvedamas į langą.
	$ResultDarbdavioMokesciai = darbdavioMokesciai ($ResultAlgaAntPopieriaus, DARBDAVIO_VAT);

//kuriamas kintamasis "darbo vietos kaina", kuris bus išvedamas į langą.
 $ResultDarboVietosKaina = darboVietosKaina ($ResultAlgaAntPopieriaus, $ResultDarbdavioMokesciai);
}

//paimama įvesta reikšmė iš laukelio "atlyginimas ant popieriaus". Čia skaičiuojami tab'o "Atlyginimas ant popieriaus" rezultatai:
if (isset($_GET['alga_ant_popieriaus']) && !empty($_GET['alga_ant_popieriaus'])) {
	$algaAntPopieriaus = (float)$_GET['alga_ant_popieriaus'];

//kuriamas kintamasis "sodros mokesčiai", kuris bus išvedamas į langą. Kintamasis išvedamas su funkcija "Sodros mokesčiai"
	$ResultSodrosMokesciai = sodrosMokesciai($algaAntPopieriaus, SODRA_VAT);

//kuriamas kintamasis "GPM mokesčiai", kuris bus išvedamas į langą.
	$ResultGpm = gpmMokesciai($algaAntPopieriaus, GPM);

//kuriamas kintamasis "alga ant popieriaus", kuris bus išvedamas į langą. Kintamasis išvedamas su funkcija "algaAntPopieriaus"
	$ResultAlgaIrankas = algaIrankas($algaAntPopieriaus, $ResultSodrosMokesciai, $ResultGpm);

//kuriamas kintamasis "darbdavio mokami mokesčiai", kuris bus išvedamas į langą.
	$ResultDarbdavioMokesciai = darbdavioMokesciai ($algaAntPopieriaus, DARBDAVIO_VAT);

//kuriamas kintamasis "darbo vietos kaina", kuris bus išvedamas į langą.
 $ResultDarboVietosKaina = darboVietosKaina ($algaAntPopieriaus, $ResultDarbdavioMokesciai);
}

$autorinesSutartys = '';
$ResultGpmAutorines = '';
$ResultSodrosMokesciaiAutorines = '';
$ResultAutorinesPoMokesciu = '';
$ResultBendrosPajamos = '';

// paimama reikšmė, įvesta į autorinių sutarčių laukelį:
if (isset($_GET['autoriniu_suma']) && !empty($_GET['autoriniu_suma'])) {
	$autorinesSutartys = $_GET['autoriniu_suma'];

//kuriamas kintamasis "sodros mokesčiai", kuris bus išvedamas į langą. Kintamasis išvedamas su funkcija "Sodros mokesčiai"
	$ResultSodrosMokesciaiAutorines = sodrosMokesciaiAutorines($autorinesSutartys, SODRA_VAT);

//kuriamas kintamasis "GPM mokesčiai", kuris bus išvedamas į langą.
		$ResultGpmAutorines = gpmMokesciaiAutorines($autorinesSutartys, GPM);

//kuriamas kintamasis "Autorinių suma po mokesčių", kuris bus išvedamas į langą.
		$ResultAutorinesPoMokesciu = autorinesPoMokesciu($autorinesSutartys, $ResultSodrosMokesciaiAutorines, $ResultGpmAutorines);

//apskaišiuojamos bendros mėnesio pajamos, atskaičius mokesčius:
//parasomas if'as, nes bendrų pajamų apskaičiavimui reikia "algos į rankas". Vienu atveju algą į rankas gauname iš karto iš inputo, kitu atveju, ją reikia apsiskaičiuoti.
//todėl, kad būtų galima taikyti tą pačią funkciją skaičiavimui ir išvedimui, pasirašiau if'ą, kuris pagal sąlygą paduoda funkcijai reikalingą kintamąjį.
	if ($ResultAlgaIrankas && $ResultAlgaIrankas > 0) {
		$dataAlga = $ResultAlgaIrankas;
	} else {
			$dataAlga = $algaIrankas;
	}

	$ResultBendrosPajamos = bendrosPajamos ($ResultAutorinesPoMokesciu, $dataAlga);
}

?>

<html>
	<head>
		<meta charset="utf-8">
		<title><?php echo $site_title; ?></title>
		<link rel="stylesheet" href="libs/bootstrap4/css/bootstrap.min.css">
		<link rel="stylesheet" href="css/master.css">
	</head>
	<body>
<!--Navigacijos tabai  -->
		<ul class="nav nav-tabs row" role="tablist">
			<li role="presentation" class="<?php if ($algaIrankas > 0) { ?>active<?php } ?> col tab-i-rankas"><a href="#irankas" aria-controls="irankas" role="tab" data-toggle="tab">Kai žinomas atlyginimas į rankas</a></li>
			<li role="presentation" class="<?php if ($algaAntPopieriaus > 0) { ?>active<?php } ?> col tab-ant-popieriaus"><a href="#antpopieriaus" aria-controls="antpopieriaus" role="tab" data-toggle="tab">Kai žinomas atlyginimas ant popieriaus</a></li>
		</ul>
<!--Navigacijos tab'ų turinys. -->
		<form method="get" action="">
		<div class="tab-content ">
			<div role="tabpanel" data-toggle="tab" class="tab-pane <?php if ($algaIrankas > 0) { ?>active<?php } ?> col forma-i-rankas" id="irankas">
				<div class="group">
					<lable>Atlyginimas į rankas:</lable>
					<input type="text" name="alga_rankas" id="input_a" value="<?php echo $algaIrankas; ?>" onblur="noneInputA()" /> <br>
				</div>
			</div>
	    <div role="tabpanel" data-toggle="tab" class="tab-pane <?php if ($algaAntPopieriaus > 0) { ?>active<?php } ?> col forma-ant-popieriaus" id="antpopieriaus">
				<div class="group">
					<lable>Atlyginimas ant popieriaus:</lable>
					<input type="text" name="alga_ant_popieriaus" id="input_b" value="<?php echo $algaAntPopieriaus; ?>" onblur="noneInputB()" />
				</div>
			</div>
	  </div>
		<!--Blokas autorinių sutarčių įvedimui -->
		<div class="autorines">
			<lable>Įtraukti sumą, gautą už autorines sutartis?</lable> <br> <br>
			<input type="radio" onclick="javascript:yesnoCheck();" <?php if ($autorinesSutartys > 0) { ?>checked<?php } ?> name="autorines_sutartys" value="itraukti" id= "itraukti"> Taip<br>
			<input type="text" id="ifYes" style="visibility:hidden <?php if ($autorinesSutartys > 0) { ?>display: block<?php } ?>" name="autoriniu_suma" value="<?php echo $autorinesSutartys; ?>" placeholder="Įrašykite sumą"/> <br>
			<input type="radio" onclick="javascript:yesnoCheck();" name="autorines_sutartys" value="neitraukti" id= "itraukti"> Ne<br>
		</div>
		<button type="submit" class="btn btn-info btn-block" value="SKAIČIUOTI">SKAIČIUOTI</button>
	</form>

<!--rezultatai išvedami su if'u, nes vienu atveju reikalina eilutė "atlyginimas į rankas", o kitu "atlyginimas ant popieriaus"-->
<div class="rezultatai">
<?php if ($algaIrankas) { ?>
	<div class="results">GMP 15% (EUR): <?php echo round ($ResultGpm, 2); ?></div>
		<hr />
	<div class="results">Sodros mokesčiai 9% (EUR): <?php echo round ($ResultSodrosMokesciai, 2); ?></div>
		<hr />
	<div class="results">Atlyginimas ant popieriaus (EUR): <?php echo round ($ResultAlgaAntPopieriaus, 2); ?></div>
		<hr />
	<div class="results"><strong>Darbdavio mokesčiai 31% (EUR): <?php echo round ($ResultDarbdavioMokesciai, 2); ?></strong></div>
		<hr />
	<div class="results"><strong>Darbo vietos kaina darbdaviui (EUR): <?php echo round ($ResultDarboVietosKaina, 2); ?></strong></div>
		<hr />
<?php } else if ($algaAntPopieriaus) { ?>
	<div class="results">GMP 15% (EUR): <?php echo round ($ResultGpm, 2); ?></div>
		<hr />
	<div class="results">Sodros mokesčiai 9% (EUR): <?php echo round ($ResultSodrosMokesciai, 2); ?></div>
		<hr />
	<div class="results">Atlyginimas į rankas (EUR): <?php echo round ($ResultAlgaIrankas, 2); ?></div>
		<hr />
	<div class="results"><strong>Darbdavio mokesčiai 31% (EUR): <?php echo round ($ResultDarbdavioMokesciai, 2); ?></strong></div>
		<hr />
	<div class="results"><strong>Darbo vietos kaina darbdaviui (EUR): <?php echo round ($ResultDarboVietosKaina, 2); ?></strong></div>
		<hr />
<?php } ?>
</div>

<!--parašoma sąlyga, kuri išveda su autorinėmis sutartimis susijusius mokesčių laukus, jei pažymima, jog į skaičiavimus būtų įtrauktos autorinės sutartys-->
<div class="autoriniu-mokesciai">
		<?php if ($autorinesSutartys) {
				echo 'Autorinių sutarčių Sodros mokesčiai 9% (EUR): '. round ($ResultSodrosMokesciaiAutorines, 2);
				echo '<hr />';
				echo 'Autorinių sutarčių GPM mokesčiai 15% (EUR): '. round ($ResultGpmAutorines, 2);
				echo '<hr />';
				echo 'Autorinių sutarčių suma po mokesčių (EUR): '. round ($ResultAutorinesPoMokesciu, 2);
				echo '<hr />';
				echo '<strong>Bendros mėnesinės pajamos, atskaičius mokesčius (EUR): '. round ($ResultBendrosPajamos, 2).'<strong>';
		}
		?>
</div>

		<script type="text/javascript">
		// skriptas, kuris nuresetina atlyginimą inpute
					noneInputA = function noneInputA() {
			  			$("#input_b").val('');
					};

					noneInputB = function noneInputA() {
			  			$("#input_a").val('');
					};

 //skriptas, kuris iššaukia input laukelį, pažymėjus "Taip" prie klausimo dėl autorinių sutarčių įvedimo
			function yesnoCheck() {
    			if (document.getElementById('itraukti').checked) {
        			document.getElementById('ifYes').style.visibility = 'visible';
    			} else {
        			document.getElementById('ifYes').style.visibility = 'hidden';
    			}
				}
		</script>

	</body>

	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</html>
