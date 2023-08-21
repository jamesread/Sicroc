<?php

namespace Sicroc;

use libAllure\DatabaseFactory;
use libAllure\HtmlLinksCollection;
use libAllure\Session;

class Navigation
{
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

        if (Session::isLoggedIn()) {
            $sql = 'SELECT l.title, l.master, l.index_page, l.usergroup FROM navigation_links l ORDER BY l.ordinal, l.title ASC';
            $stmt = DatabaseFactory::getInstance()->prepare($sql);
            $stmt->execute();

            $usergroupIds = array_column(Session::getUser()->getUsergroups(), 'id');

            foreach ($stmt->fetchAll() as $link) {
                if (empty($link['usergroup']) || in_array($link['usergroup'], $usergroupIds)) {
                    $links->add('?page=' . $link['index_page'], $link['title']);
                }
            }
        }

        return $links;
    }
}
