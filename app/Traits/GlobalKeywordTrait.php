<?php
namespace App\Traits;

trait GlobalKeywordTrait {

    public function getGlobalKeywords() {
        return [
            'HELP' => 'help',
            'INFO' => 'help',
            'STOP' => 'stop',
            'SUBSCRIBE' => 'subscribe',
            'START' => 'start',
        ];
    }

    public function matchGloalKeyword($match) {
        $formatted = trim(strtoupper($match));
        if (array_key_exists($formatted, $this->getGlobalKeywords())) {
            return $this->getGlobalKeywords()[$formatted];
        }
        else {
            false;
        }
    }
}

?>