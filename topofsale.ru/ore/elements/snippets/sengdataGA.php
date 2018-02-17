<?php
/* 
 * Снипет формирования js-скрипта для отправки данных в GA 
 * https://developers.google.com/analytics/devguides/collection/analyticsjs/ecommerce?hl=ru
  https://inweb.ua/blog/ecommerce-setup-google-tag-manager/
 */
if (!empty($_GET['msorder'])) {
    $id = (int)$_GET['msorder'];
}
/*
echo '<!-- $order -->';

var_dump($order);
echo ' -->';

echo '<!-- $products -->';
var_dump($products);
echo ' -->';


echo '<!-- $delivery -->';
echo var_dump($delivery);
echo ' -->';

echo '<!-- $payment -->';
echo var_dump($payment);
echo ' -->';

echo '<!-- $cost -->';
echo var_dump($cost); 
echo ' -->';

echo '<!-- $deliveries -->';
echo var_dump($deliveries); 
echo ' -->';

echo '<!-- $payments -->';
echo var_dump($payments);
echo ' -->';
*/

if (count($products)){ 
    $str='<script>' . "\n";
    $str=$str . "window.dataLayer = window.dataLayer || [];" . "\n";
    $str=$str . "dataLayer = [{" . "\n";
    $str=$str . "'transactionId': '" . $order["num"] ."'," . "\n"; // идентификатор транзакции = ID заказа ??
    $str=$str . "'transactionAffiliation': '". $modx->config["site_name"] ."'," . "\n"; // название магазина где была осуществлена продажа;
    $str=$str . "'transactionTotal': '". $order["cost"] ."'," . "\n"; // общая сумма транзакции;
    $str=$str . "'transactionTax': '0'," . "\n"; // сумма налога;
    $str=$str . "'transactionShipping': '" . $order["delivery_cost"] . "'," . "\n"; // стоимость доставки
    $str=$str . "'transactionProducts': [" . "\n"; 
    foreach ($products as $product) {
        // Получаем название группы        
        $parent = $modx->getObject('modResource',$product["parent"]);
        $name_group = $parent ->get($pagetitle);
        $str=$str . "{" . "\n"; 
        $str=$str . "'sku': '" . $product["id"] . "',". "\n"; // артикул товара = id товарв ???
        $str=$str . "'name': '" . $product["pagetitle"] . "'," . "\n"; 
        $str=$str . "'category': '" . $name_group . "'," . "\n";
        $str=$str . "'price': '" . $product["price"] ."'," . "\n";
        $str=$str . "'quantity': '1'" . "\n";
        $str=$str . "}," . "\n"; 
    }
    $str=$str . "]" . "\n";
    $str=$str . "}];" . "\n";
    $str=$str .'</script>' . "\n";
}
$result=$str;
return $result;

