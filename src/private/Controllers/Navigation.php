<?php

namespace Sicroc\Controllers;

use \libAllure\DatabaseFactory;
use \libAllure\HtmlLinksCollection;

class Navigation
{
    public function __construct($pageId)
    {
        $this->section = $this->getSection($pageId);
    }

    private function getSection()
    {
        global $tpl;

        $sql = 'SELECT s.id, s.title FROM sections s WHERE s.id = :id ORDER BY s.ordinal ASC LIMIT 1';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':id', 1);
        $stmt->execute();

        $section = $stmt->fetchRow();

        $tpl->assign('section', $section);

        return $section;
    }

    private function getSubsections()
    {
        $sql = 'SELECT s.title, s.master, s.index FROM sections s WHERE s.master = :masterSectionId ORDER BY s.ordinal, s.title ASC';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':masterSectionId', $this->section['id']);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getSectionTitles(): array
    {
        $ll = new HtmlLinksCollection('Navigation');

        foreach ($this->getSubsections() as $section) {
            $ll->add('?page=' . $section['index'], $section['title']);
        }

        return $ll->getAll();
    }

}

?>
