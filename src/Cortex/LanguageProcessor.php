<?php

namespace Cortex;

use Supplier\DictQueryExtract;
use Supplier\TimeQueryExtract;
use Supplier\GeneralStatics;

class LanguageProcessor
{

    public function feedIn($message) {
        if ($SynonThis = DictQueryExtract::getSynWord($message)) {
            unset($return);
            if ($SynonThis === true) {
                $return = 'I\'m sorry but I can\'t think of a synonym for "' .
                          DictQueryExtract::getSynWord($message, true) . '".';
            } else {
                $return = 'Synonyms of "' . $SynonThis . '": ' .
                            implode('; ', Dictionary::synFetch($SynonThis));
            }
        } elseif ($DefineThis = DictQueryExtract::getDefWord($message)) {
            unset($return);
            if ($DefineThis === true) {
                $return = ['I\'m sorry but "' . DictQueryExtract::getDefWord($message, true) .
                           '" is not yet in my vocabulary.',
                           'But don\'t worry I\'ll research about that later. 😉'];
            } else {
                $return = '"' . ucfirst($DefineThis) . '" means ' .
                           GeneralStatics::arrRandomix(
                               Dictionary::defFetch($DefineThis));
            }
        } elseif ($ExampleThis = DictQueryExtract::getExWord($message)) {
            unset($return);
            if ($ExampleThis === true) {
                $return = ['I\'m sorry but I don\'t know how to use "' .
                           DictQueryExtract::getExWord($message, true) . '" in a sentence.',
                           'Huhuhuhu!! 😥😥'];
            } else {
                $return = 'Here is an example: "' . GeneralStatics::arrRandomix(
                                        Dictionary::exFetch($ExampleThis)) . '"';
            }
        }
        if ($TimeThis = TimeQueryExtract::getTime($message)) {
            unset($return);
            if ($TimeThis === true) {
                $return = ['I\'m sorry but I don\'t know where that place is.',
                           'I\'m very sorry about that. 😥'];
            } else {
                $return = 'The time' . (isset($TimeThis{1}) ? ' in ' . $TimeThis{1} :
                                        '').' is ' . Time::getFinalTime($TimeThis,
                                                               'g:i A (l jS \of F Y)');
            }
        }
        if (!isset($return) || empty($return)) {
            if ($PredefResp = PredefinedResponse::initiator($message)) {
                $return = $PredefResp;
            } else {
                $return = ['I was not explicitly taught to answer that ' .
                               'statement of yours. I am still learning.',
                           '😥😥😥'];
            }
        }
        return $return;
    }

}