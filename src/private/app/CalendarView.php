<?php

namespace Sicroc;

use libAllure\Shortcuts as LA;

class CalendarView extends Widget
{
    private ?TableConfiguration $tc = null;
    private array $events = [];
    private string $currentMonth = 'month';
    private string $dateNext = 'next';
    private string $datePrev = 'prev';
    private ?array $days = [];

    public function getArguments(): array
    {
        $args = [];
        $args[] = array('type' => 'int', 'name' => 'table_configuration', 'default' => 0, 'description' => 'Table Configuration');
        $args[] = array('type' => 'string', 'name' => 'start_field', 'default' => '', 'description' => 'Start field', 'required' => true);
        $args[] = array('type' => 'string', 'name' => 'title_field', 'default' => '', 'description' => 'Title field', 'required' => true);

        return $args;
    }

    public function widgetSetupCompleted(): void
    {
        $id = (int)$this->getArgumentValue('table_configuration');

        if ($this->page == null) {
            return;
        } // FIXME error on edit

        if ($id != null) {
            $this->tc = new TableConfiguration($id);

            $this->loadEvents();
            $this->days = $this->getDays();

            if ($this->tc->createPageDelegate == null) {
                $this->navigation->add('?pageIdent=TABLE_INSERT&amp;tc=' . $this->tc->id, $this->tc->createPhrase);
            } else {
                $this->navigation->add('?page=' . $this->tc->createPageDelegate, $this->tc->createPhrase);
            }

            $now = date_create()->format('Y-m-d');
            $this->navigation->add("?page={$this->page->getId()}&start={$now}", 'Today');

            $this->navigation->add("?page={$this->page->getId()}&start={$this->datePrev}", '&laquo;');
            $this->navigation->add("?page={$this->page->getId()}&start={$this->dateNext}", '&raquo;');
            $this->navigation->add('#', $this->currentMonth, null, 'noLink');

            $this->navigation->addIf(LayoutManager::get()->getEditMode(), 'dispatcher.php?pageIdent=TABLE_STRUCTURE&amp;tc=' . $this->tc->id, 'Table Structure');
        } else {
            throw new \Exception('TC is not set.');
        }

        if ($this->getArgumentValue('start_field') == '') {
            throw new \Exception('start_field is not set.');
        }

        $_SESSION['lastTcViewPage'] = $this->page->getId();
    }

    private function loadEvents(): void
    {
        if ($this->tc == null) {
            return;
        }

        $dtfield = $this->getArgumentValue('start_field');
        $titleField = $this->getArgumentValue('title_field');

        $rows = $this->tc->getRows();

        foreach ($rows as $row) {
            if (!isset($row[$dtfield]) || !isset($row[$titleField])) { // arg may point to a missing field
                throw new \Exception('Got a row, but it did not have the expected date or title fields. Are they set correctly?');
            }

            $eventDt = date_create($row[$dtfield]);
            $eventDay = $eventDt->format('Y-m-d');

            if (!isset($this->events[$eventDay])) {
                $this->events[$eventDay] = [];
            }

            $datetime = $eventDt->format('H:i');

            if ($datetime == '00:00') {
                $datetime = '';
            } else {
                $datetime = $datetime . ' ';
            }

            $this->events[$eventDay][] = [
                'title' => $row[$titleField],
                'datetime' => $datetime,
                'url' => '?pageIdent=TABLE_ROW&tc=' . $this->tc->id . '&primaryKey=' . $row['id'],
            ];
        }
    }

    private function getDays(): array
    {
        $weeks = [];

        $now = date_create()->format('Y-m-d');

        $start = LA::san()->filterString('start');

        if ($start != null) {
            $start = date_create($start);
        } else {
            if (isset($_SESSION['calendarStart'])) {
                $start = date_create($_SESSION['calendarStart']);
            } else {
                $start = date_create();
            }
        }

        $_SESSION['calendarStart'] = $start->format('Y-m-d');

        // Set to the 1st of the Month
        $dt = \DateTime::createFromInterface($start);
        $dt->setDate((int)$dt->format('Y'), (int)$dt->format('m'), 1);

        // Rewind to the nearest Monday
        while ($dt->format('N') != '1') {
            $dt->modify('-24 hours');
        }

        $this->datePrev = date_create($dt->format('Y-m-d'))->modify('-14 days')->format('Y-m-d');
        $this->currentMonth = $start->format('F Y');

        // add 5 weeks of days
        for ($week = 0; $week < 5; $week++) {
            $weekContents = [];

            for ($i = 0; $i != 7; $i++) {
                $date = $dt->format('Y-m-d');

                $events = isset($this->events[$date]) ? $this->events[$date] : [];

                $day = [
                    'day' => $dt->format('jS'),
                    'month' => $dt->format('M'),
                    'datetime' => $date,
                    'today' => $date == $now,
                    'weekend' => $dt->format('N') == '6' || $dt->format('N') == '7',
                    'events' => $events,
                    'anotherMonth' => $dt->format('F Y') != $this->currentMonth,
                ];

                $weekContents[] = $day;

                $dt->modify('+24 hours');
            }

            $weeks[] = [
                'days' => $weekContents,
            ];
        }

        $this->dateNext = $dt->modify('+14 days')->format('Y-m-d');

        return $weeks;
    }

    public function render(): void
    {
        $lm = LayoutManager::get();
        $lm->additionalClasses = 'tall';

        $this->tpl->assign('weeks', $this->days);
        $this->tpl->assign('currentMonth', $this->currentMonth);
        $this->tpl->assign('tc', $this->tc);
        $this->tpl->assign('pid', $this->page->getId());
        $this->tpl->display('calendar.tpl');
    }
}
