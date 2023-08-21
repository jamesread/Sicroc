<?php

namespace Sicroc;

use \libAllure\Shortcuts as LA;

class CalendarView extends Widget {
    private TableConfiguration $tc;
    private array $events = [];
    private string $dateStart = 'start';
    private string $dateFinish = 'finish';
    private string $dateNext = 'next';
    private string $datePrev = 'prev';

    public function getArguments()
    {
        $args = [];
        $args[] = array('type' => 'int', 'name' => 'table_configuration', 'default' => 0, 'description' => 'Table Configuration');
        $args[] = array('type' => 'int', 'name' => 'start_field', 'default' => 0, 'description' => 'Start field');

        return $args;
    }

    public function widgetSetupCompleted()
    {
        $id = $this->getArgumentValue('table_configuration');
        $this->tc = new TableConfiguration($id);
    }

    private function getEvents(): array
    {
        $dtfield = $this->getArgumentValue('start_field');

        $rows = $this->tc->getRows();

        foreach ($rows as $row) 
        {
            $eventDay = date_create($row[$dtfield]);
            $eventDay = $eventDay->format('Y-m-d');

            if (!isset($this->events[$eventDay])) {
                $this->events[$eventDay] = [];
            }

            $this->events[$eventDay][] = [
                'title' => $row['event_title'],
                'url' => '?pageIdent=TABLE_ROW&tc=' . $this->tc->id . '&primaryKey=' . $row['id'],
            ];
        }
        
        return $rows;
    }

    private function getDays(): array
    {
        $weeks = [];

        $now = date_create()->format('Y-m-d');

        $start = LA::san()->filterString('start');

        if ($start == null) {
            $dt = date_create();
        } else {
            $dt = date_create($start);
        }

        // rewind from now to Monday
        while ($dt->format('N') != '1') {
            $dt->modify('-24 hours'); 
        }

        $this->datePrev = date_create($dt->format('Y-m-d'))->modify('-14 days')->format('Y-m-d');
        $this->dateStart = $dt->format('jS M');

        // add 5 weeks of days
        for ($week = 0; $week < 5; $week++) {
            $weekContents = [];

            for ($i = 0; $i != 7; $i++) {
                $date = $dt->format('Y-m-d');

                $events = isset($this->events[$date]) ? $this->events[$date] : [];
              
                $day = [
                    'title' => $dt->format('jS M'),
                    'datetime' => $date,
                    'today' => $date == $now,
                    'weekend' => $dt->format('N') == '6' || $dt->format('N') == '7',
                    'events' => $events,
                ];

                $weekContents[] = $day;

                $dt->modify('+24 hours'); 
            }

            $weeks[] = [
                'days' => $weekContents,
            ];
        }

        $this->dateFinish = $dt->format('jS M');
        $this->dateNext = $dt->modify('+14 days')->format('Y-m-d');

        return $weeks;
    }

    public function render()
    {
        $this->getEvents();

        $this->tpl->assign('weeks', $this->getDays());
        $this->tpl->assign('dateStart', $this->dateStart);
        $this->tpl->assign('dateFinish', $this->dateFinish);
        $this->tpl->assign('dateNext', $this->dateNext);
        $this->tpl->assign('datePrev', $this->datePrev);
        $this->tpl->assign('tc', $this->tc->id);
        $this->tpl->assign('pid', $this->page->getId());
        $this->tpl->display('calendar.tpl');
    }
}
