<?php

namespace SystemUsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use SystemUsersBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AppController extends Controller
{

    public $statusCode = 200;
    public $userId;
    public $roles;
    public $cUser;
    public $changeUserId;

    public function __construct()
    {

//        $active_menu             = 0;
//        $_SESSION['active_menu'] = $active_menu;
//        setcookie('active_menu', $active_menu, time() - (86400 * 7), '/');
    }

    public function getActionName($request)
    {
        $request->attributes->get('_controller');

        $explode    = explode('::', $request->attributes->get('_controller'));
        $controller = explode('\\', $explode[0]);

        $params['controller'] = substr($controller[count($controller) - 1], 0, -10);
        $params['action']     = substr($explode[1], 0, -6);

        $role = $this->getSpecificEntity('Resources')->getRolesName($params);
        if ($role) {
            $role = 'ROLE_' . strtoupper($role);
        }
        if (false === $this->get('security.context')->isGranted($role)) {
            throw new AccessDeniedException();
        }
//        return true;
        echo "<pre>";
        print_r($role);
        print_r($this->get('security.context')->isGranted('ROLE_ADMIN'));
        die();
    }

    public function getCurrentUser()
    {

        $securityContext = $this->get('security.context');
        $authObj         = $securityContext->getToken()->getUser();

        if ($authObj == "anon.") {
            $this->userId = 0;
        } else {
            $this->userId = $authObj->getId();
            $this->roles  = $authObj->getRoles();
            $this->cUser  = $this->chooseUserRole($authObj->getRoles());
        }

        if (!$this->userId) {
            return $this->redirect($this->generateUrl('user_login'));
        }
        return $authObj;
    }

    public function userCMS()
    {
        return array('ROLE_EDITOR', 'ROLE_AUTHOR');
    }

    public function getSpecificEntity($custom)
    {
        return $this->getEntity($custom);
    }

    public function getUserEntity()
    {
        return $this->getEntity('User');
    }

    public function getUserMetaEntity()
    {
        return $this->getEntity('UserMeta');
    }

    protected function getEntity($refName)
    {
        return $this->getDoctrine()->getManager()->getRepository("SystemUsersBundle:{$refName}");
    }

    public function getBaseUrl($request)
    {
        return $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
    }

    public function setActiveMenu($num = 0)
    {
        $active_menu = (int) $num;
        setcookie('active_menu', $active_menu, time() + (86400 * 7), '/');
        $session     = $this->get('session');
        $session->set('active_menu', $active_menu);
    }

    public function encodeSearchSrting($string, $ext = null)
    {

        $replace = '+';
        $string  = strtolower($string);

//remove query string
        if (preg_match("#^http(s)?://[a-z0-9-_.]+\.[a-z]{2,4}#i", $string)) {
            $parsed_url = parse_url($string);
            $string     = $parsed_url['host'] . ' ' . $parsed_url['path'];

//if want to add scheme eg. http, https than uncomment next line
//$string = $parsed_url['scheme'].' '.$string;
        }

//replace / and . with white space
        $string = preg_replace("/[\/\.]/", " ", $string);

        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);

//remove multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);

//convert whitespaces and underscore to $replace
        $string = preg_replace("/[\s_]/", $replace, $string);

//limit the slug size
        $string = substr($string, 0, 100);

//slug is generated
        return ($ext) ? $string . $ext : $string;
    }

    public function chooseUserRole($roles)
    {
        if (in_array('ROLE_SUPER_ADMIN', $roles)) {
            return $this->changeUserId = 'ROLE_SUPER_ADMIN';
        } elseif (in_array('ROLE_ADMIN', $roles)) {
            return $this->changeUserId = 'ROLE_ADMIN';
        } elseif (in_array('ROLE_EDITOR', $roles)) {
            return $this->changeUserId = 2;
        } elseif (in_array('ROLE_AUTHOR', $roles)) {
            return $this->changeUserId = 3;
        } else {
            return $this->changeUserId = 4;
        }
    }

    public function setRoleIdToStr($roleId)
    {
        switch ($roleId) {
            case 'ROLE_SUPER_ADMIN':
                return 'ROLE_SUPER_ADMIN';
                break;
            case 'ROLE_ADMIN':
                return 'ROLE_ADMIN';
                break;
            case 'ROLE_EDITOR':
                return 'ROLE_EDITOR';
                break;
            case 3:
                return 'ROLE_AUTHOR';
                break;
            default:
                return 'ROLE_USER';
                break;
        }
    }

    public function getRoleName($roleName)
    {

        $role = array(
            'user'        => 'ROLE_USER',
            'agent'       => 'ROLE_AGENT',
            'accountant'  => 'ROLE_ACCOUNT',
            'member'      => 'ROLE_MEMBER',
            'marketing'   => 'ROLE_MARKETING',
            'zonal'       => 'ROLE_ZONAL',
            'admin'       => 'ROLE_ADMIN',
            'super_admin' => 'ROLE_SUPER_ADMIN',
        );
        return $role[$roleName];
    }

    public function getRoleNameorId($roleName)
    {
        echo "<pre>";

        $role = array(
            'ROLE_USER'        => 0,
            'ROLE_AGENT'       => 1,
            'ROLE_ACCOUNT'     => 2,
            'ROLE_MEMBER'      => 3,
            'ROLE_MARKETING'   => 4,
            'ROLE_ZONAL'       => 5,
            'ROLE_ADMIN'       => 6,
            'ROLE_SUPER_ADMIN' => 7,
        );

//        if (is_int($roleName)) {
//            arsort($role);
//        }
//        if(!$roleName){
//            $role['ROLE_USER']=2
//        }
        print_r($role);
        die();
        return $role[$roleName];
    }

    public function sendJsonData($jsonData, $statusCode = 200)
    {
        $this->statusCode = $statusCode;
        $response         = new JsonResponse();
        $response->setData($jsonData);
        $response->setStatusCode($this->statusCode);
        return $response;
    }

    /**
     * URL Slug
     * @param str $str
     * @return str
     */
    /* function urlSlug($str)
      {
      #convert case to lower
      $str = strtolower($str);
      #remove special characters
      $str = preg_replace('/[^a-zA-Z0-9]/i', ' ', $str);
      #remove white space characters from both side
      $str = trim($str);
      #remove double or more space repeats between words chunk
      $str = preg_replace('/\s+/', ' ', $str);
      #fill spaces with hyphens
      $str = preg_replace('/\s+/', '-', $str);
      return $str;
      } */

    /**
     * Create a web friendly URL slug from a string.
     *
     * Although supported, transliteration is discouraged because
     *     1) most web browsers support UTF-8 characters in URLs
     *     2) transliteration causes a loss of information
     *
     * @author Sean Murphy <sean@iamseanmurphy.com>
     * @copyright Copyright 2012 Sean Murphy. All rights reserved.
     * @license http://creativecommons.org/publicdomain/zero/1.0/
     *
     * @param string $str
     * @param array $options
     * @return string
     */
    function urlSlug($str, $options = array())
    {
// Make sure string is in UTF-8 and strip invalid UTF-8 characters
        $str = mb_convert_encoding((string) $str, 'UTF-8', mb_list_encodings());

        $defaults = array(
            'delimiter'     => '-',
            'limit'         => null,
            'lowercase'     => true,
            'replacements'  => array(),
            'transliterate' => false,
        );

// Merge options
        $options = array_merge($defaults, $options);

        $char_map = array(
// Latin
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
            'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
            'ß' => 'ss',
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
            'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
            'ÿ' => 'y',
            // Latin symbols
            '©' => '(c)',
            // Greek
            'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
            'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
            'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
            'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
            'Ϋ' => 'Y',
            'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
            'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
            'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
            'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
            'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
            // Turkish
            'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
            'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
            // Russian
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
            'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
            'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
            'Я' => 'Ya',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
            'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
            'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
            'я' => 'ya',
            // Ukrainian
            'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
            'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
            // Czech
            'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
            'Ž' => 'Z',
            'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
            'ž' => 'z',
            // Polish
            'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
            'Ż' => 'Z',
            'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
            'ż' => 'z',
            // Latvian
            'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
            'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
            'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
            'š' => 's', 'ū' => 'u', 'ž' => 'z'
        );

// Make custom replacements
        $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);

// Transliterate characters to ASCII
        if ($options['transliterate']) {
            $str = str_replace(array_keys($char_map), $char_map, $str);
        }

// Replace non-alphanumeric characters with our delimiter
        $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);

// Remove duplicate delimiters
        $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);

// Truncate slug to max. characters
        $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');

// Remove delimiter from ends
        $str = trim($str, $options['delimiter']);

        return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
    }

    public function siteURL()
    {
        $protocol   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];
        return $protocol . $domainName;
    }

    function objectToArray($obj)
    {
        $arrObj = is_object($obj) ? get_object_vars($obj) : $obj;
        foreach ($arrObj as $key => $val) {
            $val       = (is_array($val) || is_object($val)) ? $this->objectToArray($val) : $val;
            $arr[$key] = $val;
        }
        return $arr;
    }

    public function ckProfileImages($artwork)
    {
        $orgPath       = '/bundles/webview/images/profile_images/';
        $returnArtwork = $this->siteURL() . $orgPath . 'default.jpg';
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $orgPath . $artwork)) {
            $returnArtwork = $this->siteURL() . $orgPath . $artwork;
        }
        return $returnArtwork;
    }

    public function clearAllCache()
    {
        apc_clear_cache();
        apc_clear_cache('user');
        apc_clear_cache('opcode');
    }

    public function clearCollectionAPC($pattern)
    {
        $toDelete = new \APCIterator('user', '/^' . $pattern . '/', APC_ITER_VALUE);
        apc_delete($toDelete);
    }

    public function checkRedirect()
    {
        $this->getCurrentUser();
        $userId = $this->userId;
        $url    = 0;

        if (!empty($userId)) {
            $userId = $this->getCurrentUser()->getId();
            $id     = $userId;
        } else {
            $url = $this->generateUrl("indieshuffle_web_homepage");
        }
        return $url;
    }

    public function formatMetaData($data, $type = '')
    {

        $metadata = array(
            'artwork'     => '',
            'title'       => '',
            'description' => 'Indie Shuffle is a global music blog that helps you discover and download new indie rock, hip hop, electronica, remixes and more.',
            'slug'        => '',
        );
        switch ($type) {
            case 'collection':
                if (!empty($data) && (count($data) > 0)) {
                    foreach ($data as $collection) {
                        $tracks[0] = empty($collection['track'][0]) ? '' : $collection['track'][0];
                    }
                }
                if (!empty($tracks) && (count($tracks) > 0)) {
                    foreach ($tracks as $datum) {
                        $metadata['artwork']     = $datum['artwork'];
                        $metadata['title']       = $datum['name'] . ' - ' . $datum['title'] . ' :: Indie Shuffle';
                        $metadata['description'] = "Listen to and find details to download " . $datum['name'] . " - " . $datum['title'] . ". Sounds like: " . $datum['soundlike'] . "  | What's so good? Listen to a curated playlist crafted from this song.";
                        $metadata['slug']        = $datum['slug'];
                    }
                }
                break;
            default :
                $tracks = $data;
                if (!empty($tracks) && (count($tracks) > 0)) {
                    foreach ($tracks as $datum) {
                        if (!empty($datum)) {
                            $metadata['artwork']     = $datum['artwork'];
                            $metadata['title']       = $datum['name'] . ' - ' . $datum['title'] . ' :: Indie Shuffle';
                            $metadata['description'] = "Listen to and find details to download " . $datum['name'] . " - " . $datum['title'] . ". Sounds like: " . $datum['soundlike'] . " | What's so good? Listen to a curated playlist crafted from this song.";
                            $metadata['slug']        = $datum['slug'];
                        }
                    }
                }
                break;
        }

        return $metadata;
    }

    public function traceData($data = array(), $type)
    {
        $traceStatus = $this->container->getParameter('trace_status');
        if (!empty($traceStatus)) {
            $filepath = $this->container->getParameter('trace_file_path');
            error_log($type . ": " . date("Y-m-d h:i:s") . "\n", 3, $filepath);
            if (!empty($data)) {
                foreach ($data as $key => $txt) {
                    error_log($key . ": " . $txt . "\n", 3, $filepath);
                }
            }
            error_log("\n\n", 3, $filepath);
        }
    }

}
