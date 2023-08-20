<?php

namespace Sicroc;

class BaseDatabaseStructure
{
    private $db = null;
    private array $structure = [];

    public int $changeCount = 0;

    public function __construct()
    {
        $this->db = \libAllure\DatabaseFactory::getInstance();
    }

    public function defineCoreStructure() {
        $this->addPage('HOME', 'Homepage', [$this->defineWidgetWiki('home', 'This is the homepage.')]);
        $this->addPage('CONTROL_PANEL', 'Control Panel', [
            [
                'type' => '\Sicroc\ControlPanel',
                'title' => 'Control Panel',
                'args' => [],
            ]
        ]);
        $this->addPageForm('USERGROUP_CREATE', 'Create Usergroup', 'FormCreateUsergroup');
        $this->addPageForm('USERGROUP_ASSIGN', 'Add User to Group', 'FormAddUserToGroup');
        $this->addPage('USERGROUP_LIST', 'Usergroups', [$this->defineWidgetTable('groups')]);
        $this->addPageForm('WIKI_EDIT', 'Wiki Edit', 'FormWikiUpdate');
        $this->addPage('WIDGET_LIST', 'List of Widgets', [$this->defineWidgetTable('widget_instances')]);
        $this->addPage('NAVIGATION_LIST', 'List of Navigation Links', [$this->defineWidgetTable('navigation_links')]);
        $this->addPage('NAVIGATION_UPDATE', 'Update Navigation Link', [$this->defineWidgetForm('FormNavigationLinkUpdate')]);
        $this->addPage('NAVIGATION_CREATE', 'Create Navigation Link', [$this->defineWidgetForm('FormNavigationLinkCreate')]);
        $this->addPage('PAGE_LIST', 'List of Pages', [$this->defineWidgetTable('pages')]);
        $this->addPage('TABLE_CONFIGURATION_LIST', 'List of Table Configurations', [$this->defineWidgetTable('table_configurations')]);
        $this->addPage('TABLE_ROW_EDIT', 'Edit Table Row', [$this->defineWidgetForm('FormTableEditRow')]);
        $this->addPage('PAGE_CREATE', 'Create Page', [$this->defineWidgetForm('FormPageCreate')]);
        $this->addPage('WIDGET_CREATE', 'Create Widget', [$this->defineWidgetForm('FormWidgetCreate')]);
        $this->addPage('WIDGET_REGISTER', 'Register Widget', [$this->defineWidgetForm('FormWidgetClassRegister')]);
        $this->addPageForm('WIDGET_INSTANCE_UPDATE', 'Update Widget Instance', 'FormWidgetUpdate');
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
        $this->addPage('USER_LIST', 'User List', [$this->defineWidgetTable('users')]);
        $this->addPage('LOGIN', 'Login', [$this->defineWidgetForm('FormLogin')]);
        $this->addPage('LOGOUT', 'Logout', [$this->defineWidget('\Sicroc\Logout', 'Logout')]);
        $this->addPage('REGISTER', 'Register', [$this->defineWidgetForm('FormRegister')]);
        $this->addPage('TABLE_CONFIGURATION_CREATE', 'Create Table Configuration', [$this->defineWidgetForm('FormCreateTableConfiguration')]);
        $this->addPage('TABLE_ROW_DELETE', 'Delete Row', [$this->defineWidget('\Sicroc\TableRowDelete', 'Delete Row')]);
        $this->addPage('TABLE_CONDITIONAL_FORMATTING', 'Conditional Formatting', [
            $this->defineWidgetForm('FormTableConditionalFormatting', 'Conditional Formatting'),
            $this->defineWidgetTable('table_conditional_formatting')
        ]);

        $this->addPage('DUMMY', 'Dummy page', [
            $this->defineWidget('\Sicroc\CalendarView', 'Calendar View'),
        ]);
    }

    public function addPage($ident, $title, $widgets)
    {
        $this->structure[] = [
            'ident' => $ident,
            'title' => $title,
            'widgets' => $widgets,
        ];
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

    public function defineWidgetTable($tbl, $db = null)
    {
        if ($db == null) {
            $db = 'Sicroc';
        }

        return [
            'type' => '\Sicroc\Table',
            'title' => 'Table: ' . $tbl,
            'args' => [
                'table_configuration' => $this->ensureTableConfigurationExists($tbl, $db),
            ]
        ];
    }

    public function defineWidgetWiki($title, $content)
    {
        return [
            'type' => '\Sicroc\WikiContent',
            'title' => $title,
            'args' => [
                'content' => $content,
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
    }

    public function ensureTableConfigurationExists($tbl, $db)
    {
        $sql = 'INSERT INTO table_configurations (`table`, `database`, isSystem) VALUES (:table, :database, true) ON DUPLICATE KEY UPDATE id=last_insert_id(id)';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':table', $tbl);
        $stmt->bindValue('database', $db);
        $stmt->execute();

        return $this->db->lastInsertId();
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
