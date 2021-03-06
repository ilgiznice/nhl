<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Team;
use App\Match;
use App\Goal;

class TournamentController extends Controller
{
    public function index() {
        /*
         * Исходные массивы
         */
        $output = array();
        $teams = Team::all();
        $matches = Match::all();

        /*
         * Выходной массив
         */
        foreach ($teams as $team) {
            $inner = array();
            $inner['id'] = $team->id;
            $inner['name'] = $team->name;
            $inner['games'] = 0;
            $inner['wins'] = 0;
            $inner['wins_overtime'] = 0;
            $inner['wins_bullitt'] = 0;
            $inner['loses_bullitt'] = 0;
            $inner['loses_overtime'] = 0;
            $inner['loses'] = 0;
            $inner['goals'] = '0 - 0';
            $inner['points'] = 0;
            $output[] = $inner;
        }

        /*
         * Изменение выходного массива
         */
        foreach($matches as $match) {
            /*
             * Получение индекса
             */
            $home_id = $match->home_id;
            $guest_id = $match->guest_id;
            $count = count($output);
            for($i = 0; $i < $count; $i++ ) {
                if ($output[$i]['id'] == $home_id) {
                    $h_i = $i; //home_index
                }
                if ($output[$i]['id'] == $guest_id) {
                    $g_i = $i; //guest_index
                }
            }

            /*
             * Логика
             */

            /*
             * Количество игр
             */
            array_set($output[$h_i], 'games', array_get($output[$h_i], 'games') + 1);
            array_set($output[$g_i], 'games', array_get($output[$g_i], 'games') + 1);

            /*
             * Выигрыши в основное и дополнительное время
             */
            if ($match->win_main_time == $home_id) {
                array_set($output[$h_i], 'wins', array_get($output[$h_i], 'wins') + 1);
            }
            else if ($match->win_main_time == $guest_id) {
                array_set($output[$g_i], 'wins', array_get($output[$g_i], 'wins') + 1);
            }
            if ($match->win_additional_time == $home_id) {
                array_set($output[$h_i], 'wins_overtime', array_get($output[$h_i], 'wins_overtime') + 1);
            }
            else if ($match->win_additional_time == $guest_id) {
                array_set($output[$g_i], 'wins_overtime', array_get($output[$g_i], 'wins_overtime') + 1);
            }
            if ($match->lose_main_time == $home_id) {
                array_set($output[$h_i], 'loses', array_get($output[$h_i], 'loses') + 1);
            }
            else if ($match->lose_main_time == $guest_id) {
                array_set($output[$g_i], 'loses', array_get($output[$g_i], 'loses') + 1);
            }
            if ($match->lose_additional_time == $home_id) {
                array_set($output[$h_i], 'loses_overtime', array_get($output[$h_i], 'loses_overtime') + 1);
            }
            else if ($match->lose_additional_time == $guest_id) {
                array_set($output[$g_i], 'loses_overtime', array_get($output[$g_i], 'loses_overtime') + 1);
            }

            /*
             * Число забитых шайб
             */
            $home_score = explode(' - ', $output[$h_i]['goals']); //Счёт домашней команды
            $guest_score = explode(' - ', $output[$g_i]['goals']);
            $home_goals = $match->home_goals; //Число шайб домашней команды
            $guest_goals = $match->guest_goals; //Число шайб гостевой команды
            $home_score[0] = $home_score[0] + $home_goals; //Число забитых домашней команды
            $home_score[1] = $home_score[1] + $guest_goals; //Число пропущенных домашней команды
            $guest_score[0] = $guest_score[0] + $guest_goals;
            $guest_score[1] = $guest_score[1] + $home_goals;
            $home_score = implode(' - ', $home_score);
            $guest_score = implode(' - ', $guest_score);
            array_set($output[$h_i], 'goals', $home_score);
            array_set($output[$g_i], 'goals', $guest_score);

            /*
             * Выигрыши по буллитам
             */
            $goals = Goal::all()->where('match_id', $match->id);
            foreach ($goals as $goal) {
                if ($goal->bullitt == true AND $goal->win_bullitt == true) {
                    if ($home_id == $goal->team_id) {
                        array_set($output[$h_i], 'wins_overtime', array_get($output[$h_i], 'wins_overtime') - 1);
                        array_set($output[$h_i], 'wins_bullitt', array_get($output[$h_i], 'wins_bullitt') + 1);
                        array_set($output[$g_i], 'loses_overtime', array_get($output[$g_i], 'loses_overtime') - 1);
                        array_set($output[$g_i], 'loses_bullitt', array_get($output[$g_i], 'loses_bullitt') + 1);
                    }
                    else if ($guest_id == $goal->team_id) {
                        array_set($output[$g_i], 'wins_overtime', array_get($output[$g_i], 'wins_overtime') - 1);
                        array_set($output[$g_i], 'wins_bullitt', array_get($output[$g_i], 'wins_bullitt') + 1);
                        array_set($output[$h_i], 'loses_overtime', array_get($output[$h_i], 'loses_overtime') - 1);
                        array_set($output[$h_i], 'loses_bullitt', array_get($output[$h_i], 'loses_bullitt') + 1);
                    }
                }
            }

            /*
             * Очки
             */
            //$inner['points'] = $inner['wins'] * 3 + $inner['wins_overtime'] * 2 + $inner['wins_bullitt'] * 2 +
               // $inner['loses_bullitt'] + $inner['loses_overtime'];
        }



        return view('tournament.index', compact('output'));
    }
}
