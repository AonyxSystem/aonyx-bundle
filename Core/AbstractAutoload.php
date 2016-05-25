<?php
/**
 * @author: Damien Galicher (Inso-61)
 * Date: 23/04/16
 * Time: 21:45
 */

namespace Aonyx\Core;


abstract class AbstractAutoload
{

    const FUNCTION_FACTORY = 'factory';
    const FUNCTION_MODELS = 'models';
    const FUNCTION_CONTROLLERS = 'controllers';
    const FUNCTION_AONYX_ABSTRACTS = 'aonyxEssentialsAbstracts';
    const FUNCTION_AONYX_INTERFACES = 'aonyxEssentialsInterface';
    const FUNCTION_AONYX_CLASSES = 'aonyxEssentialsClasses';
    const FOLDER_ABSTRACTS = 'Abstracts';
    const FOLDER_INTERFACES = 'Interfaces';
    const FOLDER_CLASSES = 'Classes';

    static function registerCoreAonyx() {

        spl_autoload_register(array(__CLASS__, self::FUNCTION_AONYX_ABSTRACTS));
        spl_autoload_register(array(__CLASS__, self::FUNCTION_AONYX_INTERFACES));
        spl_autoload_register(array(__CLASS__, self::FUNCTION_AONYX_CLASSES));
    }

    static function aonyxEssentialsAbstracts($class_name){
        $class_name = str_replace('Aonyx\\' . self::FOLDER_ABSTRACTS . '\\', '', $class_name);
        if(file_exists('vendor/aonyx-cms/' . self::FOLDER_ABSTRACTS . '/' . $class_name . '.php'))
            require 'vendor/aonyx-cms/' . self::FOLDER_ABSTRACTS . '/' . $class_name . '.php';
    }

    static function aonyxEssentialsInterface($class_name){
        $class_name = str_replace('Aonyx\\' . self::FOLDER_INTERFACES . '\\', '', $class_name);
        if(file_exists('vendor/aonyx-cms/' . self::FOLDER_INTERFACES . '/' . $class_name . '.php'))
            require 'vendor/aonyx-cms/' . self::FOLDER_INTERFACES . '/' . $class_name . '.php';
    }

    static function aonyxEssentialsClasses($class_name){
        $class_name = str_replace('Aonyx\\' . self::FOLDER_CLASSES . '\\', '', $class_name);
        if(file_exists('vendor/aonyx-cms/' . self::FOLDER_CLASSES . '/' . $class_name . '.php'))
            require 'vendor/aonyx-cms/' . self::FOLDER_CLASSES . '/' . $class_name . '.php';
    }

    
}