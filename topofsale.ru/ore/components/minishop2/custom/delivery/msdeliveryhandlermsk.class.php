<?php
 error_reporting(E_ALL); 
 ini_set("display_errors", 1); 



if(!class_exists('msDeliveryInterface')) {
      require_once MODX_CORE_PATH . 'components/minishop2/model/minishop2/msdeliveryhandler.class.php';
}




class msDeliveryHandlerMsk extends msDeliveryHandler implements msDeliveryInterface{
 /* 
     Пользовательская настройка доставки
     Алгоритм доставки:
     1. Тип Оплаты Самовывоз - стоимость = 0 руб.
     2. Доставка курьером по Москве:
        Если сумма заказа <= 4000 руб. и количество товаров <=2  то сумма 250 руб. 
        Если сумма заказа  > 4000 руб. и количество товаров > 2  то сумма   0 руб. 
     3. Доставка по России
        Общая сумма заказа <= 10 000 руб. = 850 руб. - количество товаров - ? (любое)
        Общая сумма заказа  > 10 000 руб. =   0 руб. - количество товаров - ? (любое)
     4. Доставка за рубеж   
*/  
    public function getCost(msOrderInterface $order, msDelivery $delivery, $cost = 0) {



        $lang = $_COOKIE['lang']; 
		$cart = $order->ms2->cart->status();
        $cart_cost = $cart['total_cost'];
        $delivery_id=$delivery->get('id');
        $price = $delivery->get('price');

		/* Доставка курьером по Москве: */        
  		if (($delivery_id == 2 )  and ($lang ='ru')) {      
	       	$freedeliverysumm = 4000;
	        
	        if($cart_cost > $freedeliverysumm){
	            return $cost;
	        }else{
	            $delivery_cost = parent::getCost($order, $delivery, $cost);
	            return $delivery_cost;
	        }
	    }  
		/*  Доставка по России */
  		if (($delivery_id == 3 )  and ($lang ='ru')) {      
	       	$freedeliverysumm = 10000;	        
	        if($cart_cost > $freedeliverysumm){
	            return $cost; 
	        }else{
	            $delivery_cost = parent::getCost($order, $delivery, $cost);
	            return $delivery_cost;
	        }
	    
	    } 
		/*   Доставка за рубеж */

   		if (($delivery_id == 4 ) and ($lang == 'ru')) {          			
 			/* получаем стоимость доставки в $USD и пересчитывам по курсу  */  			
/** @var array $scriptProperties */
/** @var currencyrate $currencyrate */
if (!$currencyrate = $modx->getService('currencyrate', 'currencyrate', $modx->getOption('currencyrate_core_path', null,
        $modx->getOption('core_path') . 'components/currencyrate/') . 'model/currencyrate/', $scriptProperties)
) {
	echo 'Could not load currencyrate class!';
    return 'Could not load currencyrate class!';
}
// $currencyrate->initialize($modx->context->key, $scriptProperties);
//     $cost = $modx->runSnippet('@FILE:snippets/CRCalc.php',[
// 		    'divider' => 'USD',
// 		    'input' => ($price)
// 			])

//    			    $cost = $modx->runSnippet('@FILE:snippets/CRCalc.php',[
//    			    $cost = $modx->runSnippet('@FILE:'. MODX_CORE_PATH .'elements/snippets/CRCalc.php',[
//    			                             'multiplier' => 'USD',
//    			                             'input' => $price,
//    			                             'show'=>'1'
//    			   //     
//	                         ]);
    // $cost = $modx->runSnippet('@FILE:snippets/CRCalc.php',[
    // 'multiplier' => 'USD',
    // 'input' => ($price) ]);

     $cost = $modx->runSnippet('CRcalc',[
  		  	'input'	=> $price,
 	 	  	'multiplier' => 'USD']);	
    return $cost; 

   			} else {	
 	            $delivery_cost = parent::getCost($order, $delivery, $cost);
 	            return $delivery_cost;  				 
            }    
	}
}	


// <?php
// if ($miniShop2 = $modx->getService('miniShop2')) {
//     $miniShop2->removeService('delivery', ' mscustomdeliveryhandler');
// }

