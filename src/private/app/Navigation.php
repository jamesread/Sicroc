<?php

namespace Sicroc;

use libAllure\DatabaseFactory;
use libAllure\HtmlLinksCollection;

class Navigation
{
    private function addSectionLinks($ll)
    {
        $sql = 'SELECT s.title, s.master, s.index FROM sections s ORDER BY s.ordinal, s.title ASC';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->execute();

        foreach ($stmt->fetchAll() as $section) {
            $ll->add('?page=' . $section['index'], $section['title']);
        }
    }

    public function lastPage(int $pageId): int|null
    {
        if ($pageId !== null) {
            $_SESSION['pageId'] = $pageId;
        }

        if (is_numeric($_SESSION['pageId'])) {
            return $_SESSION['pageId'];
        }

        return null;
    }

    public function getLinks()
    {
        $links = new HtmlLinksCollection('Navigation');

        $this->addSectionLinks($links);

        return $links;
    }
}
