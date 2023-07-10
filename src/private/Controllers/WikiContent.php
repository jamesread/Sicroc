<?php

use \libAllure\DatabaseFactory;

class WikiContent extends Widget
{
    public function view()
    {
        global $tpl;

        $sql = 'SELECT w.principle, w.content FROM wiki_content w WHERE w.principle = :principle';
        $stmt = DatabaseFactory::getInstance()->prepare($sql);
        $stmt->bindValue(':principle', $this->principle);
        $stmt->execute();

        $wiki = $stmt->fetchRow();

        $wiki['content'] = str_replace("\n\n", '<br />', $wiki['content']);

        $this->navigation->add('dispatcher.php?controller=WikiContent&amp;pageIdent=WIKI_EDIT&amp;pageTitle=' . $this->principle, 'Edit');
        $tpl->assign('wikiPage', $wiki);
    }

    public function render()
    {
        global $tpl;
        $tpl->display('wiki.tpl');
    }

    function display()
    {
        return $this->view();
    }

}

?>
