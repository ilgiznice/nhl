@extends('app')

@section('title')
    Кабинет супервайзера
@stop

@section('content')
    <div class="backend">
        <ul id="menu">
            <li><a href="#">Матч</a>
                <ul>
                    <li class="show" data-tab="match" data-action="create"><a href="#">Создание</a></li>
                    <li class="show" data-tab="match" data-action="export"><a href="#">Экспорт</a></li>
                    <li class="show" data-tab="match" data-action="import"><a href="#">Импорт</a></li>
                    <li class="show" data-tab="match" data-action="settings"><a href="#">Настройки</a></li>
                </ul>
            </li>
            <li><a href="#">Новости</a>
                <ul>
                    <li class="show" data-tab="news" data-action="settings"><a href="#">Настройки</a></li>
                </ul>
            </li>
            <li><a href="#">Игроки</a>
                <ul>
                    <li class="show" data-tab="players" data-action="export"><a href="#">Экспорт</a></li>
                    <li class="show" data-tab="players" data-action="import"><a href="#">Импорт</a></li>
                    <li class="show" data-tab="players" data-action="settings"><a href="#">Настройки</a></li>
                </ul>
            </li>
            <li><a href="#">Команды</a>
                <ul>
                    <li class="show" data-tab="teams" data-action="export"><a href="#">Экспорт</a></li>
                    <li class="show" data-tab="teams" data-action="import"><a href="#">Импорт</a></li>
                    <li class="show" data-tab="teams" data-action="settings"><a href="#">Настройки</a></li>
                </ul>
            </li>
            <li><a href="#">Сезоны</a>
                <ul>
                    <li class="show" data-tab="seasons" data-action="settings"><a href="#">Настройки</a></li>
                </ul>
            </li>
        </ul>
        <div class="block">
            <div class="shown match create">
                <div class="container_12">
                    <div class="grid_12">
                        <div class="box">
                            <div class="box_title">Создание матча</div>
                            <div class="box_bot">
                                <div class="maxheight">
                                    {!! Form::open(['url' => '/import']) !!}

                                    {!! Form::hidden('action', 'match') !!}

                                    {!! Form::label('num', 'Номер игры') !!}
                                    {!! Form::input('number', 'num', 0, ['min' => '0']) !!}

                                    {!! Form::label('date', 'Дата') !!}
                                    {!! Form::input('date', 'date') !!}

                                    {!! Form::label('start', 'Время начала') !!}
                                    {!! Form::input('time', 'start') !!}

                                    {!! Form::label('finish', 'Время окончания') !!}
                                    {!! Form::input('time', 'finish') !!}

                                    {!! Form::label('home', 'Домашная команда') !!}
                                    {!! Form::select('home', $teams) !!}

                                    {!! Form::label('guest', 'Гостевая команда') !!}
                                    {!! Form::select('guest', $teams) !!}

                                    {!! Form::submit('Добавить', ['class' => 'btn']) !!}

                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="shown match export">
                <div class="container_12">
                    <div class="grid_12 export">
                        <div class="box">
                            <div class="box_title">Экспорт</div>
                            <div class="box_bot">
                                <div class="maxheight">
                                    <table>
                                        <thead>
                                        <tr>
                                            <th>Номер игры</th>
                                            <th>Дата</th>
                                            <th>Домашная команда</th>
                                            <th>Гостевая команда</th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($matches as $m)
                                            <tr>
                                                <td>{{ $m->num }}</td>
                                                <td>{{ $m->date }}</td>
                                                <td>{{ $m->home }}</td>
                                                <td>{{ $m->guest }}</td>
                                                <td>
                                                    {!! Form::open() !!}

                                                    {!! Form::hidden('action', 'export') !!}
                                                    {!! Form::hidden('id', $m->id) !!}
                                                    {!! Form::hidden('season', $m->season_id) !!}
                                                    {!! Form::hidden('num', $m->num) !!}
                                                    {!! Form::hidden('date', $m->date) !!}
                                                    {!! Form::hidden('start', $m->start) !!}
                                                    {!! Form::hidden('finish', $m->finish) !!}
                                                    {!! Form::hidden('home_id', $m->home_id) !!}
                                                    {!! Form::hidden('guest_id', $m->guest_id) !!}
                                                    {!! Form::hidden('home', $m->home) !!}
                                                    {!! Form::hidden('guest', $m->guest) !!}

                                                    {!! Form::submit('Скачать', ['class' => 'btn']) !!}

                                                    {!! Form::close() !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="shown match import">
                <div class="container_12">
                    <div class="grid_6 import">
                        <div class="box">
                            <div class="box_title">Импорт</div>
                            <div class="box_bot">
                                <div class="maxheight">
                                    {!! Form::open(['url' => '/import', 'enctype' => 'multipart/form-data']) !!}

                                    {!! Form::hidden('action', 'result') !!}

                                    {!! Form::label('result', 'Результаты матча') !!}
                                    {!! Form::input('file', 'result', '', ['accept' => 'xlsx']) !!}

                                    {!! Form::submit('Отправить', ['class' => 'btn']) !!}

                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="shown match settings">
                <div class="container_12">
                    @foreach($matches as $m)
                        <div class="block">
                            <div class="match">{{$m->home}} vs {{$m->guest}}</div>
                            <div class="edit" data-id="{{$m->id}}"><span class="fa fa-pencil"></span></div>
                            <div class="delete"><span class="fa fa-trash-o"></span></div>
                        </div>
                        <div class="subblock" data-id="{{$m->id}}">
                            {!! Form::model($m) !!}

                            {!! Form::label('season_id', 'Сезон') !!}
                            {!! Form::text('season_id') !!}

                            {!! Form::label('num', 'Номер игры') !!}
                            {!! Form::text('num') !!}

                            {!! Form::label('stage', 'Стадия') !!}
                            {!! Form::text('stage') !!}

                            {!! Form::label('status', 'Статус') !!}
                            {!! Form::text('status') !!}

                            {!! Form::label('date', 'Дата') !!}
                            {!! Form::text('date') !!}

                            {!! Form::label('start', 'Время начала') !!}
                            {!! Form::text('start') !!}

                            {!! Form::label('finish', 'Время окончания') !!}
                            {!! Form::text('finish') !!}

                            {!! Form::label('audience', 'Зрители') !!}
                            {!! Form::text('audience') !!}

                            {!! Form::label('home_participants', 'Домашная команда') !!}
                            {!! Form::text('home_participants') !!}

                            {!! Form::label('guest_participants', 'Гостевая команда') !!}
                            {!! Form::text('guest_participants') !!}

                            {!! Form::label('home_goals', 'Голы домашней команды') !!}
                            {!! Form::text('home_goals') !!}

                            {!! Form::label('guest_goals', 'Голы гостевой команды') !!}
                            {!! Form::text('guest_goals') !!}

                            {!! Form::submit('Сохранить', '', array(['class' =>'update'])) !!}

                            {!! Form::close() !!}
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="shown news settings"></div>
            <div class="shown players export"></div>
            <div class="shown players import"></div>
            <div class="shown players settings"></div>
            <div class="shown teams export"></div>
            <div class="shown teams import"></div>
            <div class="shown teams settings"></div>
            <div class="shown seasons settings"></div>
        </div>
    </div>
@stop