<?php

namespace Sicroc;

use libAllure\DatabaseFactory;
use libAllure\Shortcuts as LA;

class WikiContent extends Widget
{
    public function getPage($pageTitle)
    {
        $sql = 'SELECT w.principle AS title, w.content FROM wiki_content w WHERE w.principle = :principle';
        $stmt = LA::db()->prepare($sql);
        $stmt->bindValue(':principle', $pageTitle);
        $stmt->execute();

        return $stmt->fetchRow();
    }

    public function createWikiPage($pageTitle)
    {
        $sql = 'INSERT INTO wiki_content (principle) VALUES (:principle) ';
        $stmt = LA::db()->prepare($sql);
        $stmt->bindValue(':principle', $pageTitle);
        $stmt->execute();
    }

    public function widgetSetupCompleted()
    {
        $pageTitle = $this->getArgumentValue('pageTitle');

        if ($pageTitle == null) {
            $wiki = [];
        } else {
            $wiki = $this->getPage($pageTitle);

            if ($wiki === false) {
                $this->createWikiPage($pageTitle);
                $wiki = $this->getPage($pageTitle);
            }

            if ($wiki != false && $wiki['content'] != null) {
                $wiki['content'] = str_replace("\n", '<br />', $wiki['content']);
            }
        }

        if ($this->displayEdit) {
            $this->navigation->add('dispatcher.php?controller=WikiContent&amp;pageIdent=WIKI_EDIT&amp;pageTitle=' . $pageTitle, 'Edit');
        }

        $this->tpl->assign('wikiPage', $wiki);
    }

    public function render()
    {
        $this->tpl->display('wiki.tpl');
    }

    public function getTitle()
    {
        return 'Wiki content';
    }

    public function getArguments()
    {
        $args = array();
        $args[] = array('type' => 'varchar', 'name' => 'pageTitle', 'default' => '', 'description' => 'The page name');

        return $args;
    }
}
