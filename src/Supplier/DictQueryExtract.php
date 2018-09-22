<?php

namespace Supplier;

use Cortex\Dictionary;

class DictQueryExtract
{

    private const DefinitionRules = '/^(?:(?:meaning|def(?:in(?:e|ition))?)(?: of)?|' .
                                    'what(?:(?: i|\')s| are)) (?:the (?:meaning )?)?' .
                                    '(?:of (?:the (?:word )?)?)?(?:a[n]? )?(.+)$/i';

    private const SynonymRules = '/^(?:(?:what(?:(?: i|\')s| are) (' .
                                 '?:the )?)?(?:syno?n?y?m?s?)(?: of' .
                                 ')?)(?: the(?: word)?)? (.+)$/i';

    private const ExampleRules = '/^(?:use )?(.+)(?: (?:in|as) a sentence)$/i';

    public static function getDefWord($string, $passive = false) {
        preg_match(self::DefinitionRules, GeneralStatics::strSanitize($string), $word);
        $return = (isset($word{1}) && !empty(Dictionary::defFetch($word{1})) ?
                    GeneralStatics::strSanitize($word{1}) : 
                        (isset($word{1}) ? true : false ));
        return ($passive != false ? (isset($word{1}) ? $word{1} : false) : $return);
    }

    public static function getSynWord($string, $passive = false) {
        preg_match(self::SynonymRules, GeneralStatics::strSanitize($string), $word);
        $return = (isset($word{1}) && !empty(Dictionary::synFetch($word{1})) ?
                    GeneralStatics::strSanitize($word{1}) : 
                        (isset($word{1}) ? true : false ));
        return ($passive != false ? (isset($word{1}) ? $word{1} : false) : $return);
    }

    public static function getExWord($string, $passive = false) {
        preg_match(self::ExampleRules, GeneralStatics::strSanitize($string), $word);
        $return = (isset($word{1}) && !empty(Dictionary::exFetch($word{1})) ?
                    GeneralStatics::strSanitize($word{1}) : 
                        (isset($word{1}) ? true : false ));
        return ($passive != false ? (isset($word{1}) ? $word{1} : false) : $return);
    }

}