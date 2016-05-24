<?php
namespace Aonyx\Abstracts;

use Aonyx\Classes\DbManager;
use Aonyx\Classes\Errors;
use Aonyx\Interfaces\InterfaceController;
use Config\Config;
use Config\Database;

/**
 * @author: galicher
 * Date: 17/03/16
 * Time: 19:00
 */

abstract class AbstractController implements InterfaceController
{

    protected $_oService;
    protected $_oServiceConfig;
    protected $_oConfig;
    protected $_oManager;
    protected $_oValidation;

    /**
     * Récupère l'objet PDO
     * et connecte la database avec les infos fournies
     * @return \PDO
     */
    public function getDatabase()
    {
        // Retourne l'objet PDO
        return Database::connect();
    }

    /**
     * Fonction pour rediriger vers une autre page
     * @param $module
     * @param null $action
     */
    public function redirect($module, $action = null)
    {
        if(null == $module) {

            Errors::exception('Redirect Error : no module specified !', get_class(), __LINE__, get_class($this));
        } else {

            if (null != $action) {

                header('location:/' . $module . '/' . $action . '/');
            } else {

                header('location:/' . $module . '/');
            }
        }
    }

    /**
     * Affiche une vue avec des paramètres si il y'en a
     * @param array $return
     * @param $path
     */
    public function render(array $return = [], $path)
    {
        // Transmission des variables à la vue
        foreach($return as $variableOut => $variableIn) {

            ${$variableOut} = (object) $variableIn;
        }

        // Configurations générales du site
        $config = new Config();
        $config->fetchConfigSite();

        // Rend la vue
        if(file_exists($path)) {
            include_once $path;
        } else {

            Errors::exception('Render Error : ' . $path . ' doesn\'t exist', get_class(), __LINE__, get_class($this));
        }
    }

    /**
     * Set un service plus général a l'application
     * @todo : à revoir
     * @param array $options
     * @return $this
     */
    public function setServiceConfig(array $options)
    {
        foreach($options as $className => $methodAction) {

            // Parcours le dossier pour trouver le service recherché
            include 'services/' . $className . '.php';
            // Instancie le nouvel objet
            $className = new $className;

            if(null != $methodAction) {
                $className->$methodAction();
            }
            // Retourne l'objet
            $this->_oServiceConfig = $className;
            return $this;
        }
    }

    /**
     * Set un ou des services pour une action
     * @param array $config
     * @param array $servicesList
     */
    public function setServices(array $config, array $servicesList)
    {

        if(empty($config)) {

            Errors::exception('You have not specified a configuration file !', get_class(), __LINE__, get_class($this));
        }

        if(empty($servicesList)) {

            Errors::exception('You have not specified a service !', get_class(), __LINE__, get_class($this));
        } else {

            foreach($servicesList as $services) {

                // Parcours le dossier pour trouver le service recherché
                include_once 'modules/' . $config[$services]['module'] . '/src/Services/' .
                    (array_key_exists('src', $config[$services]) ? $config[$services]['src'] : null) .
                    $config[$services]['class'] . '.php';

                // Instancie le nouvel objet
                $this->_oService[$services] = new $config[$services]['namespace'];
            }
        }

    }

    /**
     * Pour setter un fichier de config d'un module ou non
     * @param $config
     * @param null $module
     * @return $this
     */
    public function setConfig($config, $module = null)
    {
        // si le module n'est pas égal à rien
        if(null != $module) {

            // vérifie si le fichier existe bel et bien
            if(!file_exists('modules/' . $module . '/config/' . $config . '.config.php')) {
                die('Erreur de config : modules/' . $module . '/config/' . $config . '.config.php introuvable !');
            }
            // si ca passe on inclus le fichier de config
            include_once 'modules/' . $module . '/config/' . $config . '.config.php';
            $this->_oConfig = $config;
            return $this;
        } else {

            // vérifie si le fichier existe bel et bien
            if(!file_exists('config/' . $config . '.config.php')) {
                die('Erreur de config : config/' . $config . '.config.php introuvable !');
            }
            // si ca passe on inclus le fichier de config
            include_once 'config/' . $config . '.config.php';
            $this->_oConfig = $config;
            return $this;
        }

    }

    // GETTERS ...
    public function getServices()
    {
        return $this->_oService;
    }

    public function getServiceConfig()
    {
        return $this->_oServiceConfig;
    }

    public function getConfig()
    {
        return $this->_oConfig;
    }

    public function getServiceManager()
    {
        return new DbManager();
    }
}
