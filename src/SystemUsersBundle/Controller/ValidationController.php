<?php

namespace SystemUsersBundle\Controller;

class ValidationController
{

    public static function urlDecode($url)
    {
        $decode = base64_decode(urldecode($url));
        $data   = explode('-', $decode);

        return $data[0];
    }

    public function yearValidate($year)
    {
        return ($year >= 1900 && $year <= date('Y')) ? true : false;
    }

    public static function convertSingleObject($object)
    {
        if (count($object) == 0) {
            return false;
        }
        foreach ($object as $value) {
            $value = $value;
        }
        return $value;
    }

    public function getBasePath()
    {
        $renderer = $this->serviceLocator->get('Zend\View\Renderer\RendererInterface');
        return $renderer->basePath();
    }

    public static function error($string, $flag = 0)
    {

        echo "<pre><b>";
        switch ($flag) {
            case 0:
                print_r($string);
                break;
            case 1:
                var_dump($string);
                break;
            case 2:
                foreach ($string as $value) {
                    echo "<pre>";
                    print_r($value);
                    echo "</pre>";
                }
                break;

            default:
                break;
        }
        die();
    }

    public static function debug($string, $flag = 0)
    {

        echo "<pre><b>";
        switch ($flag) {
            case 0:
                print_r($string);
                break;
            case 1:
                var_dump($string);
                break;
            case 2:
                foreach ($string as $value) {
                    echo "<pre>";
                    print_r($value);
                    echo "</pre>";
                }
                break;

            default:
                break;
        }
    }

}
