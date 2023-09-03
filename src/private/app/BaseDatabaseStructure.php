<?php

namespace Sicroc;

class BaseDatabaseStructure
{
    private $db = null;
    private array $structure = [];

    private array $wikiContent = [];

    public int $changeCount = 0;

    public function __construct()
    {
        $this->db = \libAllure\DatabaseFactory::getInstance();
    }

    public function defineCoreStructure()
    {
        # page ID:1 needs to be WELCOME
        $this->addPage('WELCOME', 'Welcome!', [$this->defineWidgetWiki('welcome', "This is the welcome page, if you are reading this for the first time, <strong>Sicroc is ready</strong>!\n\nNow <a href = '?pageIdent=REGISTER'>register your first user</a> if you have not done that already. The first user that is registered will automatically be given SUPERUSER permissions.\n\n<strong>Note:</strong> you should not edit this wiki page with the welcome message - it gets reset everytime setup is run. Instead, <a href = '?pageIdent=WIDGET_INSTANCE_UPDATE&widgetToUpdate=1'>update this wiki widget</a> so that the page title points to a new page - call that 'home' or something like that, so that you stop seeing this welcome message every time you login! ")]);
        $this->addPage('TABLE_CONFIGURATION_CREATE', 'Create Table Configuration', [$this->defineWidgetForm('FormCreateTableConfiguration')]);
        # tc ID:1 needs to be table_configurations
        $this->addPage('TABLE_CONFIGURATION_LIST', 'List of Table Configurations', [$this->defineWidgetTable('table_configurations', null, [
            'createPhrase' => 'Create TC',
            'createPageDelegate' => 'TABLE_CONFIGURATION_CREATE',
        ])]);

        # Anything else can come now.

        $this->addPage('CONTROL_PANEL', 'Control Panel', [
            [
                'type' => '\Sicroc\ControlPanel',
                'title' => 'Control Panel',
                'args' => [],
            ]
        ]);
        $this->addPageForm('USERGROUP_CREATE', 'Create Usergroup', 'FormCreateUsergroup');
        $this->addPageForm('USERGROUP_ASSIGN', 'Add User to Group', 'FormAddUserToGroup');
        $this->addPage('USERGROUP_LIST', 'Usergroups', [$this->defineWidgetTable('groups', null, [
            'createPhrase' => 'Create Usergroup',
            'createPageDelegate' => 'USERGROUP_CREATE',
        ])]);
        $this->addPageForm('WIKI_EDIT', 'Wiki Edit', 'FormWikiUpdate');
        $this->addPage('NAVIGATION_CREATE', 'Create Navigation Link', [$this->defineWidgetForm('FormNavigationLinkCreate')]);
        $this->addPage('NAVIGATION_LIST', 'List of Navigation Links', [$this->defineWidgetTable('navigation_links', null, [
            'createPhrase' => 'Create Link',
            'createPageDelegate' => 'NAVIGATION_CREATE',
        ])]);
        $this->addPage('NAVIGATION_UPDATE', 'Update Navigation Link', [$this->defineWidgetForm('FormNavigationLinkUpdate')]);
        $this->addPage('PAGE_CREATE', 'Create Page', [$this->defineWidgetForm('FormPageCreate')]);
        $this->addPage('PAGE_LIST', 'List of Pages', [$this->defineWidgetTable('pages', null, [
            'createPhrase' => 'Create page',
            'createPageDelegate' => 'PAGE_CREATE',
        ])]);
        $this->addPage('TABLE_ROW_EDIT', 'Edit Table Row', [$this->defineWidgetForm('FormTableEditRow')]);
        $this->addPageForm('WIDGET_INSTANCE_UPDATE', 'Update Widget Instance', 'FormWidgetUpdate');
        $this->addPage('WIDGET_CREATE', 'Create Widget', [$this->defineWidgetForm('FormWidgetCreate')]);
        $this->addPage('WIDGET_LIST', 'List of Widgets', [$this->defineWidgetTable('widget_instances', null, [
            'createPhrase' => 'Instanciate Widget',
            'createPageDelegate' => 'WIDGET_CREATE',
        ])]);

        $this->addPage('WIDGET_REGISTER', 'Register Widget', [$this->defineWidgetForm('FormWidgetClassRegister')]);
        $this->addPage('WIDGET_TYPES_LIST', 'List of Widget Types', [$this->defineWidgetTable('widget_types', null, [
            'createPhrase' => 'Register Widget Type',
            'createPageDelegate' => 'WIDGET_REGISTER',
        ])]);

        $this->addPage('PAGE_UPDATE', 'Update Page', [
            $this->defineWidgetForm('FormPageUpdate'),
            $this->defineWidgetForm('FormAddToPage'),
            $this->defineWidgetForm('FormPageContentDelete'),
        ]);
        $this->addPage('TABLE_STRUCTURE', 'Table Structure', [
            $this->defineWidgetForm('FormTableDropColumn'),
            $this->defineWidgetForm('FormTableAddColumn'),
            $this->defineWidgetForm('FormAddForeignKey'),
        ]);
        $this->addPage('TABLE_INSERT', 'Insert Row', [$this->defineWidgetForm('FormTableInsert')]);
        $this->addPage('USER_PREFERENCES', 'User Preferences', [$this->defineWidgetForm('FormUserPreferences')]);
        $this->addPage('USER_LIST', 'Users', [$this->defineWidgetTable('users', null, [
            'createPhrase' => 'Create User',
        ])]);
        $this->addPage('LOGIN', 'Login', [$this->defineWidgetForm('FormLogin')]);
        $this->addPage('LOGOUT', 'Logout', [$this->defineWidget('\Sicroc\Logout', 'Logout')]);
        $this->addPage('REGISTER', 'Register', [$this->defineWidgetForm('FormRegister')]);
        $this->addPage('TABLE_ROW_DELETE', 'Delete Row', [$this->defineWidget('\Sicroc\TableRowDelete', 'Delete Row')]);
        $this->addPage('TABLE_CONDITIONAL_FORMATTING', 'Conditional Formatting', [
            $this->defineWidgetForm('FormTableConditionalFormatting', 'Conditional Formatting'),
            $this->defineWidgetTable('table_conditional_formatting')
        ]);
        
        $this->addPage('SETTINGS', 'Settings', [$this->defineWidgetTable('site_settings', null)]);

        $this->addPage('DUMMY', 'Dummy page', [
            $this->defineWidget('\Sicroc\CalendarView', 'Calendar View'),
        ]);
    }

    public function addPage($ident, $title, $widgets)
    {
        $page = [
            'ident' => $ident,
            'title' => $title,
            'widgets' => $widgets,
        ];

        $this->ensurePageExists($page);

        $this->structure[] = $page;
    }

    public function addPageForm($ident, $title, $formClass)
    {
        $this->addPage($ident, $title, [$this->defineWidgetForm($formClass)]);
    }

    public function defineWidget($class, $title)
    {
        return [
            'type' => $class,
            'title' => $title,
            'args' => [],
        ];
    }

    public function defineWidgetForm($arg)
    {
        return [
            'type' => '\Sicroc\WidgetForm',
            'title' => 'Form: ' . $arg,
            'args' => [
                'formClass' => '\Sicroc\Forms\\' . $arg,
            ]
        ];
    }

    public function defineWidgetTable($tbl, $db = null, $tcArgs = [])
    {
        if ($db == null) {
            $db = 'Sicroc';
        }

        return [
            'type' => '\Sicroc\Table',
            'title' => 'Table: ' . $tbl,
            'args' => [
                'table_configuration' => $this->ensureTableConfigurationExists($tbl, $db, $tcArgs),
            ]
        ];
    }

    public function defineWidgetWiki($title, $content)
    {
        $this->wikiContent[$title] = $content;

        return [
            'type' => '\Sicroc\WikiContent',
            'title' => 'Wiki page: ' . $title,
            'args' => [
                'pageTitle' => $title,
            ]
        ];
    }

    public function execute()
    {
        foreach ($this->structure as $page) {
            $pageId = $this->ensurePageExists($page);

            foreach ($page['widgets'] as $widget) {
                $widgetInstanceId = $this->ensureWidgetExists($widget);

                $this->ensureWidgetOnPage($pageId, $widgetInstanceId);
            }
        }

        foreach ($this->wikiContent as $title => $content) {
            $sql = 'INSERT INTO wiki_content (principle, content) VALUES (:title, :content) ON DUPLICATE KEY UPDATE content = :content';
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'title' => $title,
                'content' => $content,
            ]);
        }
    }

    public function ensureTableConfigurationExists($tbl, $db, $tcArgs)
    {
        $sql = 'INSERT INTO table_configurations (`table`, `database`, isSystem, createPhrase, createPageDelegate) VALUES (:table, :database, true, :createPhrase, :createPageDelegate) ON DUPLICATE KEY UPDATE createPhrase = :createPhrase, createPageDelegate = :createPageDelegate, id=last_insert_id(id)';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':table', $tbl);
        $stmt->bindValue(':database', $db);

        if (isset($tcArgs['createPhrase'])) {
            $stmt->bindValue('createPhrase', $tcArgs['createPhrase']);
        } else {
            $stmt->bindValue('createPhrase', 'Insert');
        }

        if (isset($tcArgs['createPageDelegate'])) {
            $stmt->bindValue('createPageDelegate', $this->getPageIdFromIdent($tcArgs['createPageDelegate']));
        } else {
            $stmt->bindValue('createPageDelegate', null);
        }

        $stmt->execute();

        return $this->db->lastInsertId();
    }

    public function getPageIdFromIdent($ident): int
    {
        $sql = 'SELECT p.id FROM pages p WHERE p.ident = :ident LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':ident' => $ident,
        ]);

        $row = $stmt->fetchRow();

        if ($row == null) {
            throw new \Exception('Page ID not found: ' . $ident);
        }

        return $row['id'];
    }

    public function ensurePageExists($page)
    {
        $sql = 'INSERT INTO pages (ident, title, isSystem) VALUES (:ident, :title, true) ON DUPLICATE KEY UPDATE id=last_insert_id(id)';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':ident', $page['ident']);
        $stmt->bindValue(':title', $page['title']);
        $stmt->execute();

        return $this->db->lastInsertId();
    }

    public function ensureWidgetExists($widget)
    {
        $sql = 'INSERT INTO widget_types (viewableController) VALUES (:type) ON DUPLICATE KEY UPDATE id=last_insert_id(id)';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':type', $widget['type']);
        $stmt->execute();

        $widgetTypeId = $this->db->lastInsertId();

        $sql = 'INSERT INTO widget_instances (type, title) VALUES (:type, :title) ON DUPLICATE KEY UPDATE id=last_insert_id(id)';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':type', $widgetTypeId);
        $stmt->bindValue(':title', $widget['title']);
        $stmt->execute();

        $widgetInstance = $this->db->lastInsertId();

        foreach ($widget['args'] as $key => $val) {
            $sql = 'INSERT INTO widget_argument_values (widget, `key`, `value`) VALUES (:widget, :key, :value) ON DUPLICATE KEY UPDATE id=id';
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':widget', $widgetInstance);
            $stmt->bindValue(':key', $key);
            $stmt->bindValue(':value', $val);
            $stmt->execute();
        }

        return $widgetInstance;
    }

    public function ensureWidgetOnPage($pageId, $widgetInstanceId)
    {
        $sql = 'INSERT INTO page_content (page, widget) VALUES (:page, :widget) ON DUPLICATE KEY UPDATE id=id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':page', $pageId);
        $stmt->bindValue(':widget', $widgetInstanceId);
        $stmt->execute();
    }

    public function getStructure(): array
    {
        return $this->structure;
    }
}
