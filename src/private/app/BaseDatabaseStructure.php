<?php

namespace Sicroc;

class BaseDatabaseStructure
{
    private $db = null;
    private array $structure = [
        [
            'ident' => 'LOGOUT',
            'title' => 'Logout',
            'widgets' => [
                [
                    'type' => '\Sicroc\Logout',
                    'title' => 'Logout',
                    'args' => [],
                ]
            ]
        ]
    ];

    public function __construct()
    {
        $this->db = \libAllure\DatabaseFactory::getInstance();

        $this->definePage('HOME', 'Homepage', [$this->defineWidgetWiki('home', 'This is the homepage.')]);
        $this->definePage('ADMIN', 'Admin panel', []);
        $this->definePageForm('USERGROUP_CREATE', 'Create Usergroup', 'FormCreateUsergroup');
        $this->definePageForm('USERGROUP_ASSIGN', 'Add User to Group', 'FormAddUserToGroup');
        $this->definePageForm('WIKI_EDIT', 'Wiki Edit', 'FormWikiUpdate');
        $this->definePage('WIDGET_LIST', 'List of Widgets', [$this->defineWidgetTable('widget_instances')]);
        $this->definePage('SECTION_LIST', 'List of Sections', [$this->defineWidgetTable('sections')]);
        $this->definePage('PAGE_LIST', 'List of Pages', [$this->defineWidgetTable('pages')]);
        $this->definePage('TABLE_CONFIGURATION_LIST', 'List of Table Configurations', [$this->defineWidgetTable('table_configurations')]);
        $this->definePage('SECTION_UPDATE', 'Update Section', [$this->defineWidgetForm('FormSectionUpdate')]);
        $this->definePage('TABLE_ROW_EDIT', 'Edit Table Row', [$this->defineWidgetForm('FormTableEditRow')]);
        $this->definePage('SECTION_CREATE', 'Create Section', [$this->defineWidgetForm('FormSectionCreate')]);
        $this->definePage('PAGE_CREATE', 'Create Page', [$this->defineWidgetForm('FormPageCreate')]);
        $this->definePage('WIDGET_CREATE', 'Create Widget', [$this->defineWidgetForm('FormWidgetCreate')]);
        $this->definePage('WIDGET_REGISTER', 'Register Widget', [$this->defineWidgetForm('FormWidgetClassRegister')]);
        $this->definePageForm('WIDGET_INSTANCE_UPDATE', 'Update Widget Instance', 'FormWidgetUpdate');
        $this->definePage('PAGE_UPDATE', 'Update Page', [
            $this->defineWidgetForm('FormPageUpdate'),
            $this->defineWidgetForm('FormAddToPage'),
            $this->defineWidgetForm('FormPageContentDelete'),
        ]);
        $this->definePage('TABLE_STRUCTURE', 'Table Structure', [
            $this->defineWidgetForm('FormTableDropColumn'),
            $this->defineWidgetForm('FormTableAddColumn'),
            $this->defineWidgetForm('FormAddForeignKey'),
        ]);
        $this->definePage('TABLE_CREATE', 'Create Table', [$this->defineWidgetForm('FormTableCreate')]);
        $this->definePage('TABLE_INSERT', 'Insert Row', [$this->defineWidgetForm('FormTableInsert')]);
        $this->definePage('USER_PREFERENCES', 'User Preferences', [$this->defineWidgetForm('FormUserPreferences')]);
        $this->definePage('LOGIN', 'Login', [$this->defineWidgetForm('FormLogin')]);
        $this->definePage('TABLE_CONFIGURATION_CREATE', 'Create Table Configuration', [$this->defineWidgetForm('FormCreateTableConfiguration')]);
    }

    public function definePageForm($ident, $title, $formClass)
    {
        $this->definePage($ident, $title, [$this->defineWidgetForm($formClass)]);
    }

    public function definePage($ident, $title, $widgets)
    {
        $this->structure[] = [
            'ident' => $ident,
            'title' => $title,
            'widgets' => $widgets,
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

    public function defineWidgetTable($tbl)
    {
        return [
            'type' => '\Sicroc\Table',
            'title' => 'Table: ' . $tbl,
            'args' => [
                'table_configuration' => $this->ensureTableConfigurationExists($tbl),
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

    public function check()
    {
        foreach ($this->structure as $page) {
            $pageId = $this->ensurePageExists($page);

            foreach ($page['widgets'] as $widget) {
                $widgetInstanceId = $this->ensureWidgetExists($widget);

                $this->ensureWidgetOnPage($pageId, $widgetInstanceId);
            }
        }
    }

    public function ensureTableConfigurationExists($tbl)
    {
        $sql = 'INSERT INTO table_configurations (`table`, `database`, isSystem) VALUES (:table, :database, true) ON DUPLICATE KEY UPDATE id=last_insert_id(id)';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':table', $tbl);
        $stmt->bindValue('database', 'Sicroc');
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
}
