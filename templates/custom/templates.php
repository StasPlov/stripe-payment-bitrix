<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @author darkfriend <hi@darkfriend.ru>
 * @version 1.3.7
 */
$locale = [
    'card' => [
        'cardLabel' => '',
        'submitButton' => 'Перейти к оплате',
    ],
    'sepa' => [
        'name' => 'Имя',
        'email' => 'Email',
        'submitButton' => 'Перейти к оплате',
    ],
    'sofort' => [
        'name' => 'Имя',
        'email' => 'Email',
        'bank' => 'Страна банка',
        'submitButton' => 'Перейти к оплате',
    ],
    'giropay' => [
        'name' => 'Имя',
        'submitButton' => 'Перейти к оплате',
    ],
];
$modeList = [
    'CARD' => [
        'key' => 'card',
        'value' => 'VISA / MasterCard',
    ],
    'SEPA' => [
        'key' => 'sepa',
        'value' => 'Sepa Debit',
    ],
    'SOFORT' => [
        'key' => 'sofort',
        'value' => 'Sofort',
    ],
    'GIROPAY' => [
        'key' => 'giropay',
        'value' => 'Giropay',
    ],
];
$stripeMods = $SALE_CORRESPONDENCE['STRIPE_MODS']['VALUE'];
if (empty($stripeMods)) {
    $stripeMods = array_keys($modeList);
} else {
    $stripeMods = explode(',', strtoupper($stripeMods));
}
foreach ($stripeMods as &$stripeMod) {
    $stripeMod = trim($stripeMod);
    if (!isset($modeList[$stripeMod])) continue;
    $stripeMod = $modeList[$stripeMod];
}
unset($stripeMod);

$finalUrl = (CMain::IsHTTPS() ? 'https' : 'http') . '://' . SITE_SERVER_NAME;

if(!empty($SALE_CORRESPONDENCE['URL_TO_PAYMENT']['VALUE'])) {
    $sessionUrl = $SALE_CORRESPONDENCE['URL_TO_PAYMENT']['VALUE'];
} else {
    $sessionUrl = $APPLICATION->GetCurDir();
}

?>
<script type="text/javascript" src="https://js.stripe.com/v3/"></script>
<div>
    <a href="<?= $paymentLink ?>" class="stripe-button" target="_blank">Перейти к оплате</a>
</div>
<style>
.stripe-button {
    text-align: center;
    display: block;
    width: 100%;
    max-width: 300px;
    padding: 12px;
    background-color: #8DAA83;
    color: #fff;
    border: 1px solid #8DAA83;
    border-radius: 4px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.stripe-button:hover {
    background-color: #fff;
    color: #8DAA83;
}
</style>
