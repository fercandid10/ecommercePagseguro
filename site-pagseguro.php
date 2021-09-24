<?php 

use \Hcode\Page;
use \Hcode\Model\User;

use \Hcode\pagSeguro\Config;
use \Hcode\pagSeguro\Document;
use \Hcode\pagSeguro\Transporter;
use \Hcode\Model\Order;
use \Hcode\pagSeguro\Phone;
use \Hcode\pagSeguro\Address;
use \Hcode\pagSeguro\Sender;
use \Hcode\pagSeguro\CreditCard;
use \Hocde\pagSeguro\CreditCard\Holder;
use \Hcode\pagSeguro\Shipping;
use \Hcode\pagSeguro\CreditCard\Installment;
use \Hcode\pagSeguro\Item;
use \Hcode\pagSeguro\Payment;

$app->get("/payment/success", function(){

	User::verifyLogin(false);

	$order = new Order();

	$order->getFromSession();

	$page = new Page();
	$page->setTpl('payment-success', [
		'order'=>$order->getValues()
	]);
});

$app->post("/payment/credit", function(){

	User::verifyLogin(false);

	$order = new Order();
	$order->getFromSession();
	$order->get((int)$order->getidorder());
	$address = $order->getAddress();

	$cart = $order->getCart();

	$cpf = new Document(Document::CPF, $_POST['cpf']);

	$phone = new Phone($_POST['ddd'], $_POST['phone']);

	$shippingAddress = new Address(
		$address->getdesaddress(),
		$address->getdesnumber(),
		$address->getdescomplement(),
		$address->getdesdistrict(),
		$address->getdeszipcode(),
		$address->getdescity(),
		$address->getdesstate(),
		$address->getdescountry()
		
	);

	$birthDate = new DateTime($_POST['birth']);

	$sender = new Sender($order->getdesperson(), $cpf, $birthDate, $phone, $order->getdesemail(), $_POST['hash'] );

	$holder = new Holder($order->getdesperson(), $cpf, $birthDate, $phone);

	$shipping = new Shipping($address, (float)$cart->getvlfreight(), Shipping::PAC);

	$installment = new Installment((int)$_POST["installments_qtd"], (float)$_POST["installments_value"]);

	$billingAddress = new Address(
		$address->getdesaddress(),
		$address->getdesnumber(),
		$address->getdescomplement(),
		$address->getdesdistrict(),
		$address->getdeszipcode(),
		$address->getdescity(),
		$address->getdesstate(),
		$address->getdescountry()
	);

	$creditCard = new CreditCard($_POST['token'], $installment, $holder, $billingAddress);

	$payment = new Payment($order->getidorder(), $sender, $shipping);

	foreach($cart->getProducts() as $product){
		
		$item = new Item(
			(int)$product['idproduct'],
			$product['desproduct'],
			(float)$product['vlprice'],
			(int)$product['nrqtd']
		);

		$payment->addItem($item);
	}

	$payment->setCreditCard($creditCard);

	Transporter::sendTransaction($payment);

	echo json_encode(['success'=>true]);


});


$app->get('/payment', function(){



	User::verifyLogin(false);

	$order = new Order();

	$order->getFromSession();

	$years = [];

	for ($y = date('Y'); $y < date('Y')+14; $y++){

		array_push($years, $y);
	}
	
	$page = new Page();

	$page->setTpl("payment", [
		"order"=>$order->getValues(),
		"msgError"=>Order::getError(),
		"years"=>$years,
		"pagseguro"=>[
			"urlJS"=>Config::getUrlJS(),
			"id"=>Transporter::createSession(),
			" maxInstallmentNoInterest"=>Config::MAX_INSTALLMENT_NO_INTEREST,
			" maxInstallment"=>Config::MAX_INSTALLMENT
		]
	]);
});



