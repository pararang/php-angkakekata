<?php
/**
 * Created by PhpStorm.
 * User: prrng
 * Date: 07/07/17
 * Time: 18:00
 */

include 'AngkaKeKata.php';

$converter = new AngkaKeKata();
echo $converter->convert(230001)."\n";
