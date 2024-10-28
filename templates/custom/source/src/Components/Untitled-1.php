<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * @author darkfriend <hi@darkfriend.ru>
 * @copyright (c) 2019-2023, darkfriend
 * @version 1.5.2
 */

use \Bitrix\Main\Application;
use \Bitrix\Sale\Order;

global $SALE_CORRESPONDENCE, $USER, $APPLICATION;
\Bitrix\Main\Loader::registerAutoLoadClasses(
    "dev2fun.stripepayment",
    array(
        'dev2fun\StripeHelper' => 'sale_payment/stripe/lib/StripeHelper.php',
    )
);

\Bitrix\Main\Loader::includeModule('dev2fun.stripepayment');
$request = Application::getInstance()->getContext()->getRequest();

$orderKey = $SALE_CORRESPONDENCE['FIND_ORDER_ID']['VALUE'];
if (!$orderKey) {
    $orderKey = 'ORDER_ID';
}

$orderId = (int)($GLOBALS["SALE_INPUT_PARAMS"]["ORDER"]["ID"] ?? 0);
if (!$orderId) {
    $orderId = isset($_REQUEST[$orderKey]) ? $_REQUEST[$orderKey] : null;
}
if (!$orderId) {
    $orderId = $request->get('ORDER_ID');
}
if (!$orderId) {
    $orderId = $request->get('ID');
}
if (!$orderId) {
    $orderId = $request->getPost('accountNumber');
}
if (!$orderId) {
    ShowError('Order Id is not found!');
    return;
}

/** @var \Bitrix\Sale\Order $order */
$order = Order::load($orderId);
if (!$order) {
    ShowError('Order is not found!');
    return;
}
$arOrder = $order->getFieldValues();

$sum = $arOrder['PRICE'];
$sum = \number_format($sum, 2, '.', '');
if (!Dev2funModuleStripeClass::isSupportCurrency($arOrder['CURRENCY'])) {
    ShowError('Currency "' . $arOrder['CURRENCY'] . '" is not support!');
    return;
}

//if ($arOrder['CURRENCY'] != 'EUR') {
//    $arOrder['PRICE_EUR'] = CCurrencyRates::ConvertCurrency(
//        $arOrder['PRICE'],
//        $arOrder['CURRENCY'],
//        'EUR'
//    );
//} else {
//    $arOrder['PRICE_EUR'] = $arOrder['PRICE'];
//}

//if (empty($arOrder['PRICE_EUR'])) {
//    $arOrder['PRICE_EUR'] = \number_format($arOrder['PRICE'], 2, '.', '');
//}

$orderID = $order->getId();

$events = GetModuleEvents("dev2fun.stripepayment", "OnBeforeShowStripe", true);
foreach ($events as $arEvent) {
    ExecuteModuleEventEx($arEvent, array(&$arOrder));
}

if (empty($SALE_CORRESPONDENCE['STRIPE_TEMPLATE']['VALUE'])) {
    $SALE_CORRESPONDENCE['STRIPE_TEMPLATE']['VALUE'] = 'CUSTOM';
}

if (isset($SALE_CORRESPONDENCE['LIVE_MODE']) && $SALE_CORRESPONDENCE['LIVE_MODE']['VALUE'] === 'Y') {
    $secretKey = $SALE_CORRESPONDENCE['LIVE_SECRET_KEY']['VALUE'];
    $publishKey = $SALE_CORRESPONDENCE['LIVE_PUBLISH_KEY']['VALUE'];
} else {
    $secretKey = $SALE_CORRESPONDENCE['TEST_SECRET_KEY']['VALUE'];
    $publishKey = $SALE_CORRESPONDENCE['TEST_PUBLISH_KEY']['VALUE'];
}

if (!empty($_REQUEST['sessionMode'])) {
    include __DIR__ . '/vendor/autoload.php';
    
    $stripeSessionManager = new \Dev2fun\StripeSessionManager($secretKey, $order, $SALE_CORRESPONDENCE);
    
    try {
        $session = $stripeSessionManager->createSession();
        $response = $session->toArray();
    } catch (\Throwable $e) {
        $response = [
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ];
    }

    $APPLICATION->RestartBuffer();
    ob_end_clean();
    ob_end_flush();
    ob_clean();
    if (!empty($_REQUEST['redirect']) && !empty($session)) {
        $urlRedirect = $session->url;
        echo "<script>";
        echo "window.location.href = '$urlRedirect'";
        echo "</script>";
        exit();
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

if (empty($SALE_CORRESPONDENCE['STRIPE_TEMPLATE']['VALUE'])) {
    $fileTemplate = 'custom';
} else {
    $fileTemplate = $SALE_CORRESPONDENCE['STRIPE_TEMPLATE']['VALUE'];
}

$fileTemplate = Dev2funModuleStripeClass::GetPathTemplate($SALE_CORRESPONDENCE['STRIPE_TEMPLATE']['VALUE']);
$fileTemplate .= '/templates.php';

$error = false;

if ($fileTemplate && file_exists($fileTemplate)) {
    include $fileTemplate;
} else {
    ShowError('No template "' . $SALE_CORRESPONDENCE['STRIPE_TEMPLATE']['VALUE'] . '"');
}
