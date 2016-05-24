<?php
/**
 * @author: Damien Galicher (Inso-61)
 * Date: 05/04/16
 * Time: 19:34
 */

namespace Aonyx\Classes;


class Application
{
    /**
     * Crée un token aléatoire
     * @return string
     */
    public function generateToken($iLength)
    {
        $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";

        return substr(str_shuffle(str_repeat($alphabet, $iLength)), 0, $iLength);
    }

}