<html>
<head>
<style>
td {
	color:white;
	padding-left:3px;
	padding-right:3px;
	padding-top:3px;
	padding-bottom:3px;
}
</style>

<?php

function getBTCPrices1() {

	$source = 'https://blockchain.info/ticker';

	$fi = file($source);
	
	if($fi) {
		$json = '';
		foreach($fi as $k => $v) {
			$json = $json.$v;
		}

		$obj = json_decode($json);
		$prices['BTC_USD'] = $obj->USD->last;
		$prices['BTC_EUR'] = $obj->EUR->last;
	}
	else {
		$prices['BTC_USD'] = '-';
		$prices['BTC_EUR'] = '-';
	}

	return $prices;
}

function getBTCPrices2() {
	$source1 = 'https://www.bitstamp.net/api/v2/ticker/btcusd/';
	$source2 = 'https://www.bitstamp.net/api/v2/ticker/btceur/';
	
	$fi = file($source1);
	if($fi) {
		$obj = json_decode($fi[0]);
		$prices['BTC_USD'] = $obj->last;
	}
	else {
		$prices['BTC_USD'] = '-';
	}
	$fi = file($source2);
	if($fi) {	
		$obj = json_decode($fi[0]);
		$prices['BTC_EUR'] = $obj->last;
	}
	else {
		$prices['BTC_EUR'] = '-';
	}
	
	return $prices;
	
}

function getCurrencyRate1() {
	$source = 'http://api.fixer.io/latest';
	$fi = file($source);
	if($fi) {
		$obj = json_decode($fi[0]);
		$rate = $obj->rates->USD;
	}
	else {
		$rate = '-';
	}
	return $rate;
}

function getCurrencyRate2() {
	$source = 'http://www.floatrates.com/daily/eur.json';
	$fi = file($source);
	if($fi) {
		$obj = json_decode($fi[0]);
		$rate = $obj->usd->rate;
	}
	else {
		$rate = '-';
	}
	return $rate;
}

function getCurrencyRate3() {
	$source = 'https://spreadsheets.google.com/feeds/list/0Av2v4lMxiJ1AdE9laEZJdzhmMzdmcW90VWNfUTYtM2c/1/public/basic?alt=json';
	$fi = file($source);
	if($fi) {
		$obj = json_decode($fi[0]);
		$rate = substr($obj->feed->entry[80]->content->{'$t'},8);
	}
	else {
		$rate = '-';
	}
	return $rate;
}

$bitcoinFeedNames = array(
'Blockchain.info',
'bitstamp.net'
);

$currencyFeedNames = array(
'fixer.io',
'floatrates.com',
'Private feed'
);

foreach($bitcoinFeedNames as $k => $v) {
	$n = $k + 1;
	$selectedBitcoinFeed[$n] = '';
}
$selectedBitcoinFeed[1] = 'selected';

foreach($currencyFeedNames as $k => $v) {
	$n = $k + 1;
	$selectedCurrencyFeed[$n] = '';
}
$selectedCurrencyFeed[1] = 'selected';


if($_POST) {
	foreach($bitcoinFeedNames as $k => $v) {
		$n = $k + 1;
		if($_POST['feed1'] == $n) {
			$selectedBitcoinFeed[$n] = 'selected';
		}
	}
	foreach($currencyFeedNames as $k => $v) {
		$n = $k + 1;
		if($_POST['feed2'] == $n) {
			$selectedCurrencyFeed[$n] = 'selected';
		}
	}
}


echo '<form method="post" name="form1">';
echo '<table cellspacing="0" cellpadding="0" border="1" bgcolor="#555555">';
echo '<tr><td>Bitcoin price:</td><td><select name="feed1">';

foreach($bitcoinFeedNames as $k => $v) {
	$n = $k + 1;
	echo '<option value="'.$n.'" '.$selectedBitcoinFeed[$n].'>'.$v.'</option>';
}
echo '</select></td></tr>';
echo '<tr><td>Currency rate:</td><td><select name="feed2">';

foreach($currencyFeedNames as $k => $v) {
	$n = $k + 1;
	echo '<option value="'.$n.'" '.$selectedCurrencyFeed[$n].'>'.$v.'</option>';
}

echo '</select></td></tr>';
echo '<tr><td colspan="2"><input type="submit" value="Submit"></td></tr>';
echo '</table>';
echo '</form>';

if($_POST) {
	if($_POST['feed1']) {
		$currentFeed1 = $_POST['feed1'];
	}
	if($_POST['feed2']) {
		$currentFeed2 = $_POST['feed2'];
	}
}
else {
	$currentFeed1 = 1;
	$currentFeed2 = 1;
}

// get BTC prices
$funcName = 'getBTCPrices'.$currentFeed1;
$prices = call_user_func($funcName);

// get currency rates
$funcName = 'getCurrencyRate'.$currentFeed2;
$rate = call_user_func($funcName);


echo 'BTC/USD: '.$prices['BTC_USD'];
if(is_numeric($rate)) {
	echo ' EUR/USD: '.number_format($rate,5);
}
else {
	echo ' EUR/USD: -';
}
echo ' BTC/EUR: '.$prices['BTC_EUR'];
echo ' Active sources:';
echo ' BTC/USD ('.$currentFeed1.' of '.count($bitcoinFeedNames).')';
echo ' EUR/USD ('.$currentFeed2.' of '.count($currencyFeedNames).')';


?>

</html>
