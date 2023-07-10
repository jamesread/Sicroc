<?php 

require_once 'Auth.php';
require_once 'Auth/Container.php';
require_once 'Auth/Container/MDB2.php';

class Users extends Controller
{
    function getVersion()
    {
        return 0.3;
    }

    function getIcon()
    {
        return '';
    }

    function getName()
    {
        return 'Users';
    }

    function getDescription()
    {
        return 'A module to manage users.';
    }

    function logout()
    {
        global $user;

        $user->logout();

        include_once 'widgets/header.php';

        messageBox('You have been logged out.', 'Logout', true);
    }

    function login()
    {
        global $user;

        include_once 'widgets/header.php';

        if ($user->isLoggedIn()) {
            // Perhaps this is unessisary?
            echo 'You are already logged in.';
            include_once 'widgets/footer.php';
        }

        $f = new Form('login');

        $f->addElement('text', 'username', 'Username', 'class=title');
        $f->addElement('password', 'password', 'Password');
        $f->addRule('username', 'Please enter your username.', 'required');

        $f->addModuleHandler($this, 'Login');

        $f->addButtons();

        if ($f->validate()) {
            echo 'foo';
            print_r($user->listUsers());
            echo sha1($f->getElement('password')->getValue());
            $user->checkAuth();

            if ($user->checkAuth()) {        
                messageBox('You have been logged in. Thankyou.', 'Login Successful', true);    
            } else {
                messageBox('Please double check your username & password.', 'Login Failed.', false);

                $f->display();
            }    
        } else {
            echo 'form failed validation';
            echo '<h2 class = "formTitle">Login</h2>';
            $f->display();
        }

        echo 'dsoing footer';

        include_once 'widgets/footer.php';
    }

    function getNavigationMain()
    {
        global $user;

        $ll = new LinkList(get_class(&$this));

        if ($user->isLoggedIn()) {
            $ll->addHref(Module::makeLink($this, 'logout'), 'Logout', 0);
        } else {
            $ll->addHref(Module::makeLink($this, 'login'), 'Login', 0);
        }

        return $ll;
    }
}

define('USER_LEVEL_ADMIN', 1);

class User extends Auth
{
    function isLoggedIn()
    {
        return $this->checkAuth();
    }

    function getId()
    {
        if (!$this->isLoggedIn()) { return;
        }

        return $this->session['data']['user_id'];
    }

    /**
     * Get the data stored in a field for a user.
     */
    function getData($field, $useCache = true)
    {
        if (!$this->isLoggedIn()) { return;
        }

        if (!$useCache) {
            global $db;
            $sql = 'SELECT `' . $db->escape($field) . '` FROM `users` WHERE `email_address` = "' . $db->escape($this->getUsername()) . '" LIMIT 1';
            $db->setFetchMode(MDB2_FETCHMODE_ORDERED);
            $result = $db->query($sql);

            $result = $result->fetchRow();
            $result = $result[0];
            $db->setFetchMode(MDB2_FETCHMODE_ASSOC);
            return $result;
        } else {
            if (isset($this->session['data'][$field])) {
                return $this->session['data'][$field];
            } else {
                return null;
            }
        }
    }

    function setData($key, $value)
    {
        global $db;
        $sql = 'UPDATE `users` SET `' . $db->escape($key) . '` = "' . $db->escape($value) . '" WHERE `email_address` = "' . $this->getUsername() . '" LIMIT 1';
        $result = $db->query($sql);
    }

    function getLevel()
    {
        if (!$this->isLoggedIn()) { return;
        }


        return $this->session['data']['userlevel'];
    }

    function isAdmin()
    {
        if (!$this->isLoggedIn()) { return false; 
        }

        return $this->getLevel() <= USER_LEVEL_ADMIN;
    }
}

?>
