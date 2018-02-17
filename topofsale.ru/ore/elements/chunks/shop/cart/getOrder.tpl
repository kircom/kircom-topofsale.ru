<h4>{'lw.orderThanks'|lexicon}</h4>
<h4>{'lw.orderNum'|lexicon} {$order.num}</h4>
{'lw.orderComment'|lexicon}

{if $delivery['id']=='4'}
{set $usd = $_modx->runSnippet('CRcalc',[
 				  	'input'	=> '1',
 				  	'multiplier' => 'USD'])} 		
<!-- {$usd} --> 				  	
{* $_modx->setPlaceholder('usd', $usd) *}
{* set $order["delivery_cost"]= $usd * $delivery['price'] *}
{/if}




