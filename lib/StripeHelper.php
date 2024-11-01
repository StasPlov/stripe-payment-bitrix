<?php
/**
 * @author StasPlov <SaviouR.S@mail.ru>
 * @version  1.0.0
 */

namespace StasPlov;

class StripeHelper
{
    static public function getCurDir($schema = false)
    {
        global $APPLICATION;
        $url = $APPLICATION->GetCurDir();
        if ($schema) $url = self::getSchema() . $url;
        return $url;
    }

    static public function getSchema()
    {
        if (\CMain::IsHTTPS()) {
            $url = 'https://';
        } else {
            $url = 'http://';
        }
        return $url . $_SERVER['HTTP_HOST'];
    }

    static public function getSchemaByBitrix()
    {
        $site = \CSite::GetByID(SITE_ID)->Fetch();
        return $site['SITE_URL'];
    }
}