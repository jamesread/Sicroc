<?php

namespace Sicroc\Controllers;

class BaseStructure
{
    private $db = null; 
    var $structure = [
        [
            'ident' => 'ADMIN',
            'title' => 'Admin panel',
            'widgets' => [
            ],
        ],
        [
            'ident' => 'WIDGET_INSTANCE_UPDATE',
            'title' => 'Update widget instance',
            'widgets' => [
                [
                    'type' => '\Sicroc\Controllers\WidgetForm',
                    'args' => [
                        'formClass' => 'FormWidgetUpdate',
                    ]
                ],
            ],
        ],
        [
            'ident' => 'PAGE_UPDATE',
            'title' => 'Update Page',
            'widgets' => [
                [
                    'type' => '\Sicroc\Controllers\WidgetForm',
                    'args' => [
                        'formClass' => 'FormPageUpdate',
                    ]
                ],
                [
                    'type' => '\Sicroc\Controllers\WidgetForm',
                    'args' => [
                        'formClass' => 'FormAddToPage',
                    ]
                ]
            ],
        ],
        [
            'ident' => 'SECTION_LIST',
            'title' => 'List of sections',
            'widgets' => [
                [
                    'type' => '\Sicroc\Controllers\Table',
                    'args' => [
                        'table' => 'sections'
                    ]
                ]
            ],
        ],
        [
            'ident' => 'PAGE_LIST',
            'title' => 'List of Pages',
            'widgets' => [
                [
                    'type' => '\Sicroc\Controllers\Table',
                    'args' => [
                        'table' => 'pages',
                    ]
                ]
            ]
        ],
        [
            'ident' => 'WIKI_EDIT',
            'title' => 'Wiki Edit',
            'widgets' => [
                [
                    'type' => '\Sicroc\Controllers\WikiContent',
                    'args' => [],
                ]
            ]
        ],
        [
            'ident' => 'LOGOUT',
            'title' => 'Logout',
            'widgets' => [
                [
                    'type' => '\Sicroc\Controllers\Logout',
                    'args' => [],
                ]
            ]
        ]
    ];

    public function __construct()
    {
        $this->db = \libAllure\DatabaseFactory::getInstance();

        $this->definePage('WIDGET_LIST', 'List of Widgets', [$this->defineWidgetTable('widget_instances')]);
        $this->definePage('SECTION_UPDATE', 'Update Section', [$this->defineWidgetForm('FormSectionUpdate')]);
        $this->definePage('TABLE_ROW_EDIT', 'Edit Table Row', [$this->defineWidgetForm('FormTableEditRow')]);
        $this->definePage('SECTION_CREATE', 'Create Section', [$this->defineWidgetForm('FormSectionCreate')]);
        $this->definePage('PAGE_CREATE', 'Create Page', [$this->defineWidgetForm('FormPageCreate')]);
        $this->definePage('WIDGET_CREATE', 'Create Widget', [$this->defineWidgetForm('FormWidgetCreate')]);
        $this->definePage('WIDGET_REGISTER', 'Register Widget', [$this->defineWidgetForm('FormWidgetClassRegister')]);
        $this->definePage('TABLE_STRUCTURE', 'Table Structure', [
            $this->defineWidgetForm('FormTableDropColumn'),
            $this->defineWidgetForm('FormTableAddColumn'),
        ]);
        $this->definePage('TABLE_CREATE', 'Create Table', [$this->defineWidgetForm('FormTableCreate')]);
        $this->definePage('TABLE_INSERT', 'Insert Row', [$this->defineWidgetForm('FormTableInsert')]);
        $this->definePage('USER_PREFERENCES', 'User Preferences', [$this->defineWidgetForm('FormUserPreferences')]);
        $this->definePage('LOGIN', 'Login', [$this->defineWidgetForm('FormLogin')]);
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
            'type' => '\Sicroc\Controllers\WidgetForm',
            'args' => [
                'formClass' => $arg,
            ]
        ];
    }

    public function defineWidgetTable($arg)
    {
        return [
            'type' => '\Sicroc\Controllers\Table',
            'args' => [
                'table' => $arg,
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

    public function ensurePageExists($page)
    {
        $sql = 'INSERT INTO pages (ident, title) VALUES (:ident, :title) ON DUPLICATE KEY UPDATE id=last_insert_id(id)';
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

        $sql = 'INSERT INTO widget_instances (type, principle) VALUES (:type, :principle) ON DUPLICATE KEY UPDATE id=last_insert_id(id)';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':type', $widgetTypeId);
        $stmt->bindValue(':principle', current($widget['args']));
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
