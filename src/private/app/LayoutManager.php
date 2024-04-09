<?php

namespace Sicroc;

use Sicroc\Page;
use Sicroc\Table;
use Sicroc\Navigation;
use Sicroc\Utils;
use libAllure\Session;
use libAllure\HtmlLinksCollection;

class LayoutManager
{
    private Page $page;
    private ?Navigation $nav;

    private static ?LayoutManager $inst = null;

    private function __construct()
    {
        global $db;
    }

    public static function get(): LayoutManager
    {
        if (self::$inst == null) {
            self::$inst = new LayoutManager();
        }

        return self::$inst;
    }

    public function getPage(): Page
    {
        return $this->page;
    }

    public function getEditMode(): bool
    {
        $isSystemPage = $this->page->isSystem();

        if (Session::isLoggedIn()) {
            $v = Session::getUser()->getData('editMode');
            return $v;
        } else {
            return false;
        }
    }

    public function render(): void
    {
        global $tpl;

        assert($tpl != null);

        $this->page = new Page();
        $this->page->resolve();

        $this->assertPageRenderable();

        $this->nav = new Navigation();

        $tpl->assign('navigation', $this->nav->getLinks());
        $tpl->assign('actionNavigation', $this->getActionNavigation());
        $tpl->assign('editMode', $this->getEditMode());
        $tpl->assign('isLoggedIn', Session::isLoggedIn());

        $tpl->assign('page', $this->page->getForTpl());
        $tpl->assign('widgets', $this->page->getWidgetsForTpl());

        $tpl->display('layout.' . $this->page->getLayout() . '.tpl');
    }

    private function getActionNavigation(): HtmlLinksCollection
    {
        $links = new HtmlLinksCollection();
        /*
                                <li><strong>Admin</strong></li>
                                <li><a href = "?pageIdent=USER_PREFERENCES">User Preferences</a></li>
                                <li><a href = "?pageIdent=ADMIN">Control Panel</a></li>
                                <li><a href = "?pageIdent=USERGROUP_CREATE">Create Usergroup</a></li>
                                <li><a href = "?pageIdent=USERGROUP_ASSIGN">Assign</a></li>
                                <li><a href = "setup.php">Rerun Setup</a></li>
                                <li><strong>Section</strong></li>
                                <li><a href = "?pageIdent=SECTION_LIST">Section list</a></li>
                                <li><a href = "?pageIdent=SECTION_CREATE">Create section</a></li>
                                <li><strong>Tables</strong></li>
                                <li><a href = "?pageIdent=TABLE_CONFIGURATION_LIST">TC List</a></li>
                                <li><a href = "?pageIdent=TABLE_CONFIGURATION_CREATE">Create Table Configuration</a></li>
                                <li><strong>Page</strong></li>
                                <li><a href = "?pageIdent=PAGE_LIST">Page list</a>
                                <li><a href = "?pageIdent=PAGE_CREATE">Create page</a>
                                <li><strong>Widgets</strong></li>
                                <li><a href = "?pageIdent=WIDGET_LIST">Widget Instance List</a></li>
                                <li><a href = "?pageIdent=WIDGET_CREATE">Create Widget Instance</a></li>
                                <li><a href = "?pageIdent=WIDGET_REGISTER">Register widget class</a></li>
                                <li><strong>Current view</strong></li>
                                <li><a href = "?pageIdent=SECTION_UPDATE&sectionToEdit={$section.id}">Update section</a></li>
                                <li><a href = "?pageIdent=PAGE_UPDATE&pageToEdit={$page.id}">Update page</a></li>
                                <li><strong>Account</strong></li>
                                <li><a href = "?pageIdent=LOGOUT">Logout</a></li>
                        {else}
                                <li><strong>Account</strong></li>
                                <li><a href = "?pageIdent=LOGIN">Login</a></li>
        {/if}
         */

        if (Session::isLoggedIn()) {
            $links->addIfPriv('ADMIN', '?pageIdent=CONTROL_PANEL', 'Control Panel');
            $links->addSeparator();
            $links->addIfPriv('ADMIN', '?pageIdent=PAGE_UPDATE&pageToEdit=' . $this->page->getId(), 'Page update');
            $links->addSeparator();
            $links->add('?pageIdent=USER_PREFERENCES', 'Preferences');
            $links->add('?pageIdent=LOGOUT', 'Logout');
        } else {
            $links->addIf(!Utils::getSiteSetting('disable_registration'), '?pageIdent=REGISTER', 'Register');
            $links->add('?pageIdent=LOGIN', 'Login');
        }

        return $links;
    }

    private function assertPageRenderable(): void
    {
        assert(!empty($this->page));
        assert(!empty($this->page->getId()));
    }
}
