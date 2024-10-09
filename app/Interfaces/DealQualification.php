<?php

namespace App\Interfaces;

interface DealQualification
{
    public function init();

    public function neverReplied();

    public function noWhatsApp();

    public function lostContact();

    public function qualified();

    public function unqualified();

    public function anotherChannel();

    public function missingInfo();
}
