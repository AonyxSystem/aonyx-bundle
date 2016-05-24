<?php
/**
 * @author: galicher
 * Date: 22/03/16
 * Time: 21:54
 */

namespace Aonyx\Abstracts;

use Aonyx\Interfaces\InterfaceValidation;
use Config\Database;
use Modules\Members\Models\UserRepository;

/**
 * Défini les controlles de validation récurrents
 * Class AbstractValidation
 * @package Aonyx\Abstracts
 */
abstract class AbstractMembersValidation implements InterfaceValidation
{
    //@todo : faire des constants pour tout les messages d'erreurs et les types d'erreurs ainsi que leurs clés correspondantes

    private $aErrors = [];
    private $_oUserRepository = null;

    public function __construct()
    {
        $this->_oUserRepository = new UserRepository();
    }

    /**
     * @param $email
     */
    public function validEmail($email)
    {
        // Longueur de l'email
        if(strlen($email) > 254) {
            $this->aErrors['email'] = ['danger', 'L\'email est trop long !'];
        }

        // Vérifie la chaine
        if(!preg_match('#^[\w.-]+@[\w.-]+\.[a-z]{2,6}$#i', $email)) {

            $this->aErrors['email'] = ['danger', 'L\'email n\'est pas valide !'];
        }
    }

    /**
     * @param $password
     * @param $confirmPassword
     */
    public function passwordMatching($password, $confirmPassword)
    {
        // Vérifie que les mot de passe correspond
        if(null != $password && null != $confirmPassword) {

            if($confirmPassword !== $password)
            {

                $this->aErrors['password'] = ['danger', 'Les mots de passe ne concordent pas !'];
            }
        } else {

            $this->aErrors['password'] = ['danger', 'Le mot de passe n\'est pas renseigné'];
        }
    }

    /**
     * @param array $required
     */
    public function requiredFields(array $required)
    {
        foreach($required as $key => $value)
        {
            if(null == $value)
            {
                $this->aErrors[$key] = ['warning', 'Le champ ' . $key . ' ne peut pas être vide !'];
            }
        }

    }

    /**
     * @param $field
     */
    public function isAlpha($field) {
        if(!preg_match('/^[a-zA-Z0-9_]+$/', $field)) {
            $this->aErrors['username'] = ['danger', 'Les caractères doivent être alphanumérique.'];
        }
    }

    /**
     *
     */
    public function validUsername($username) {

        // Longueur du username
        if(strlen($username) > 15) {
            $this->aErrors['username'] = ['danger', 'Le nom d\'utilisateur est trop long !'];
        }
    }

    /**
     * @param $value
     * @throws \Exception
     */
    public function captcha($value)
    {

        if(isset($_SESSION)) {

            if($_SESSION['captcha'] != $value) {
                $this->aErrors['captcha'] = ['danger', 'Le captcha n\'est pas bon.'];
            }
        }
    }

    public function isFetchEmail() {

        if(!$this->_oUserRepository->fetchUserByEmail())
        {

            $this->aErrors['email'] = ['danger', 'Cet utilisateur n\'existe pas !'];
        }
    }

    public function isFetchPasswordMatching()
    {
        if(crypt($_POST['password'], $this->_oUserRepository->fetchUserByEmail()['password']) != $this->_oUserRepository->fetchUserByEmail()['password'])
        {

            $this->aErrors['password'] = ['danger', 'Le mot de passe est pas bon !'];
        }
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->aErrors;
    }

}