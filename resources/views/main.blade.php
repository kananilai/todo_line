@extends('layouts.app')

@section('content')

<div class="add_form">
    <form action="/add" method="POST">
        @csrf
        <input type="text" class="task_add" placeholder="タスクを入力してください" name="task" style="height:30px;" size=30 required>
        <input type="datetime-local" name="deadline" style="height:30px;" required class="task_add_date"></input>
        <button type="submit" class="add main_button">
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </form>
</div>
@if(!empty($items))
<div class="task_table">
    <table>
        <tr>
            <td></td>
            <td align="center">
                <h1 class="font theader">Deadline</h1>
            </td>
            <td align="center">
                <h1 class="font theader">タスク</h1>
            </td>
        </tr>
        @foreach($items as $item)
        <tr>
            <td class="td" valign="bottom">
                <form action="/done" method="post">
                    @csrf
                    <input type="hidden" value="{{ $item->id }}" name="id">
                    <button type="submit" class="done main_button">
                        <i class="fa-solid fa-circle-check fa-2x"></i>
                    </button>
                </form>
            </td>
            <td class="td">
                @if(($item->deadline) < $today)
                    <p class="font limit_date" style="color:#e12424; font-weight:">{{ ($item->deadline) ->format('Y/m/d H時i分')}}</p>
                @else
                <p class="font">{{ $item->deadline }}</p>
                @endif
            </td>
            <td class="td">
                <form action="/update" method="post" class="task_add_input">
                    @csrf
                    <input type="text" value=" {{ $item->task }}" name="task"  size=30 style="height:30px; font-size:15px;" class="font">
                    <input type="hidden" value="{{ $item->id }}" name="id">
                    <button type="submit" class="update main_button">
                        <i class="fa-solid fa-pen fa-lg"></i>
                    </button>
                </form>
            </td>
            <td class="td">
                <form action="/delete" method="post">
                    @csrf
                    <input type="hidden" value="{{ $item->id }}" name="id">
                    <button type="submit" class="delete main_button">
                        <i class="fa-solid fa-trash-can fa-lg"></i>
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@else
<div class="no_task">
    <p class="font">タスクはありません！🎉</p>
</div>
@endif

@if(!empty($done_items))
<div class="task_table done_table">
    <table>
        <tr>
            <td colspan="2" class="font done_title">
                ⭐️ 完了済みタスク ⭐️
            </td>
        </tr>
    @foreach($done_items as $done_item)
        <tr>
            <td class="table_check">
                <i class="fa-solid fa-circle-check fa-2x"></i>
            <td align="left" class="done_task_td font">
                {{ $done_item->task }}
            </td>
        </tr>
    @endforeach
    </table>
</div>
@endif
@endsection('content')
