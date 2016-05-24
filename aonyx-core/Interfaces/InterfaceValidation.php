<?php
/**
 * @author: galicher
 * Date: 03/04/16
 * Time: 12:49
 */

namespace Aonyx\Interfaces;


interface InterfaceValidation
{

    public function validEmail($email);
    public function requiredFields(array $required);
    public function isAlpha($field);
    public function getErrors();

}