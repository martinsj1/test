<?php

function getBTCPrices1() {

	$source = 'https://blockchain.info/ticker';

	$fi = file($source);
	$json = '';
	foreach($fi as $k => $v) {
		$json = $json.$v;
	}

	$obj = json_decode($json);
	$prices['BTC_USD'] = $obj->USD->last;
	$prices['BTC_EUR'] = $obj->EUR->last;

	return $prices;

}

function getBTCPrices2() {
	$source1 = 'https://www.bitstamp.net/api/v2/ticker/btcusd/';
	$source2 = 'https://www.bitstamp.net/api/v2/ticker/btceur/';
	$fi = file($source1);
	$obj = json_decode($fi[0]);
	$prices['BTC_USD'] = $obj->last;
	$fi = file($source2);
	$obj = json_decode($fi[0]);
	$prices['BTC_EUR'] = $obj->last;
	
	return $prices;
	
}

function getCurrencyRate1() {
	$source = 'http://api.fixer.io/latest';
	$fi = file($source);
	$obj = json_decode($fi[0]);
	$rate = $obj->rates->USD;
	return $rate;
}

function getCurrencyRate2() {
	$source = 'http://www.floatrates.com/daily/eur.json';
	$fi = file($source);
	$obj = json_decode($fi[0]);
	$rate = $obj->usd->rate;
	return $rate;
}

function getCurrencyRate3() {
	$source = 'https://spreadsheets.google.com/feeds/list/0Av2v4lMxiJ1AdE9laEZJdzhmMzdmcW90VWNfUTYtM2c/1/public/basic?alt=json';
	$fi = file($source);
	$obj = json_decode($fi[0]);
	$rate = substr($obj->feed->entry[80]->content->{'$t'},8);
	return $rate;
}

$selected1 = '';
$selected2 = '';
if($_POST) {
	if($_POST['feed1'] == 1) {
		$selected1 = 'selected';
	}
	else {
		$selected2 = 'selected';
	}
	if($_POST['feed2'] == 1) {
		$selected3 = 'selected';
	}
	elseif($_POST['feed2'] == 2) {
		$selected4 = 'selected';
	}
	else {
		$selected5 = 'selected';
	}
}
else {
	$prices = getBTCPrices1();
}

echo '<form method="post" name="form1">';
echo 'Bitcoin price:<select name="feed1">';
echo '<option value="1" '.$selected1.'>Blockchain.info</option>';
echo '<option value="2" '.$selected2.'>bitstamp.net</option>';
echo '</select><br>';
echo 'Currency rate:<select name="feed2">';
echo '<option value="1" '.$selected3.'>fixer.io</option>';
echo '<option value="2" '.$selected4.'>floatrates.com</option>';
echo '<option value="3" '.$selected5.'>Private feed</option>';
echo '</select><br>';
echo '<input type="submit" value="Submit">';
echo '</form>';

if($_POST) {
	if($_POST['feed1'] == 1) {
		$prices = getBTCPrices1();
	}
	else {
		$prices = getBTCPrices2();
	}
	if($_POST['feed2'] == 1) {
		$rate = getCurrencyRate1();
	}
	elseif($_POST['feed2'] == 2) {
		$rate = getCurrencyRate2();
	}
	else {
		$rate = getCurrencyRate3();
	}
}
else {
	$prices = getBTCPrices1();
	$rate = getCurrencyRate1();
}

echo 'BTC/USD: '.$prices['BTC_USD'];
echo ' EUR/USD: '.number_format($rate,5);
echo ' BTC/EUR: '.$prices['BTC_EUR'];




?>
