<?php

namespace Cortex;

use OutboundHook\UserProfile;
use Supplier\GeneralStatics;

class PredefinedResponse
{

    private const Reflex = './cortex/reflex/';
    private const Cortex = 'cortex.json';
    private const CortexDeep = 'cortex.deep';

    private static $envars;

    public static function initiator($message, $envars) {
        self::configureReflex();
        self::$envars = $envars;
        return self::cortexParse($message);
    }

    private static function configureReflex() {
        if (!is_dir(self::Reflex)) {
            unlink(self::Reflex); mkdir(self::Reflex);
        }
        if (!file_exists(self::Reflex . self::Cortex)) {
            file_put_contents(self::Reflex . self::Cortex,
                base64_decode('WwogICAgewogICAgICAgICJzdGF0ZW1lbnQi' .
                              'OiAiSGkiLAogICAgICAgICJhY3Rpb24iOiBb' .
                              'MV0sCiAgICAgICAgIm1hdGNoX2F0IjogMTAw' .
                              'CiAgICB9Cl0='));
        }
        if (!file_exists(self::Reflex . self::CortexDeep)) {
            file_put_contents(self::Reflex . self::CortexDeep,
                base64_decode('SGVsbG8ge3t7ZnVsbF9uYW1lfX19IQ=='));
        }
        return true;
    }

    private static function cortexParse($message) {
        $responseID = self::cortexGetResponseID($message);
        return self::cortexGetResponse($responseID);
    }

    private static function cortexGetResponseID($message, $match_at = 85) {
        $cortex = json_decode(file_get_contents(self::Reflex . self::Cortex));
        foreach($cortex as $subcortex) {
            $subcortex->statement = GeneralStatics::curlify($subcortex->statement,
                                                            self::$envars);
            similar_text(GeneralStatics::strSanitize($subcortex->statement),
                         GeneralStatics::strSanitize($message),
                         $impulse);
            $match_at = (isset($subcortex->match_at) ?
                         $subcortex->match_at :
                         $match_at);
            if ($impulse >= $match_at) {
                $return = GeneralStatics::arrRandoMix($subcortex->action);
            }
        }
        return (!empty($return) ? $return : false);
    }

    private static function cortexGetResponse($id) {
        $cortex = self::Reflex . self::CortexDeep;
        if (is_array($id)) {
            foreach ($id as $item) {
                $payload = trim(file($cortex){$item-1});
                $response[] = GeneralStatics::curlify($payload, self::$envars);
            }
        } else {
            $payload = trim(file($cortex){$id-1});
            $response = GeneralStatics::curlify($payload, self::$envars);
        }
        if (!empty($response)) {
            if (is_array($response)) {
                foreach ($response as $item) {
                    $return[] = GeneralStatics::basicStrEscape($item);
                }
                $return = GeneralStatics::flattenArray($return);
            } else {
                $return = GeneralStatics::basicStrEscape($response);
            }
        } else {
            $return = false;
        }
        return $return;
    }

}