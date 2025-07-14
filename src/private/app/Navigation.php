<?php

namespace Sicroc;

use libAllure\DatabaseFactory;
use libAllure\HtmlLinksCollection;
use libAllure\Session;

class Navigation
{
    public function lastPage(int $pageId = null): int|null
    {
        if ($pageId !== null) {
            $_SESSION['pageId'] = $pageId;
        }

        if (is_numeric($_SESSION['pageId'])) {
            return $_SESSION['pageId'];
        }

        return null;
    }

    public function getLinks(): array
    {
        $topLinks = [];
        $allLinks = [];

        if (Session::isLoggedIn()) {
            $sql = 'SELECT l.title, l.master, l2.title AS masterTitle, l.index_page, l.usergroup FROM navigation_links l JOIN navigation_links l2 ON l.master = l2.id ORDER BY l.master, l.ordinal, l.title ASC';
            $stmt = DatabaseFactory::getInstance()->prepare($sql);
            $stmt->execute();

            $usergroupIds = array_column(Session::getUser()->getUsergroups(), 'id');

            foreach ($stmt->fetchAll() as $link) {
                if (empty($link['usergroup']) || in_array($link['usergroup'], $usergroupIds)) {
                    $newLink = [
                        'title' => $link['title'],
                        'url' => '?page=' . $link['index_page'],
                        'children' => array(),
                    ];

                    $allLinks[$link['title']] = $newLink;

                    if ($link['master'] == 1) {
                        $topLinks[$link['title']] = $newLink;
                    } else if ($link['masterTitle'] != $link['title']) { // Check, because it's otherwise possible to create loops.
                        $topLinks[$link['masterTitle']]['children'][] = $newLink;
                    }
                }
            }
        }

        return $topLinks;
    }
}
