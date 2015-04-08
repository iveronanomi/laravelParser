@extends('app')

@section('content')
<script src="{{ asset('/admin_panel/js/socialParser.js') }}"></script>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading panel-error">List of parsed news</div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>URI</th>
                                <th>Viewed</th>
                                <th>Archived</th>
                                <th>Dates</th>
                                <th colspan="2">User</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($news as $topic)
                            <tr>
                                <th scope="row">{{ $topic->id }}</th>
                                <td>
                                    @if($topic->title)
                                        {{ $topic->title }}
                                    @else
                                        <kbd>Empty</kbd>
                                    @endif
                                </td>
                                <td>
                                    @if($topic->description)
                                        {{ $topic->description }}
                                    @else
                                        <kbd>Empty</kbd>
                                    @endif
                                </td>
                                <td>{{ $topic->uri }}</td>
                                <td>
                                    @if($topic->is_viewed)
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                    @endif
                                </td>
                                <td>
                                    @if($topic->is_archived)
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                                    @endif
                                </td>
                                <td>
                                    <nobr><strong>Created at: </strong>
                                    {{date("F j, Y, G:i:s",strtotime($topic->created_at))}}</nobr><br/>
                                    <nobr><strong>Updated at: </strong>
                                    {{date("F j, Y, G:i:s",strtotime($topic->updated_at))}}</nobr><br/>
                                    <nobr><strong>Viewed at: </strong>
                                    @if(!strtotime($topic->viewed_at))
                                        {{date("F j, Y, G:i:s",strtotime($topic->viewed_at))}}
                                    @else
                                        <span>Not viewed</span>
                                    @endif
                                    </nobr>
                                </td>
                                <td>@if($topic->user)
                                    {{ $topic->user->name }}
                                    @else
                                    <kbd>Not seen yet</kbd>
                                    @endif</td>
                                <td>
                                    <a type="button" class="newsOpenButton btn btn-success btn-xs"
                                        href="{{ route('parser-news-by-id', $topic->id) }}">Read</a>
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
@endsection