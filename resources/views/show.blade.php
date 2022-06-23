@extends('layouts.main')

@section('content')
    @if(@isset($data['error']))
        <div class="row text-danger">
            {{ $data['error'] }}
        </div>
    @else
        @foreach ($data as $val)
            <div class="row">
                <div class="col-8">{{ $val['label'] }}</div>
                <div class="col-4">{!! $val['content'] !!}</div>
            </div>
        @endforeach
    @endif
@endsection
