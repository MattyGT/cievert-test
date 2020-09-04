<?php
namespace App\Interfaces;

interface CommandProcessInterface {
    public function configure();
    public function execute();
}
?>