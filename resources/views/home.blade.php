@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    {{ __('Dashboard') }}
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(auth()->user()->is_admin)
                        @forelse($notifications as $notification)
                            <div class="alert alert-success" role="alert">
                                {{ __('[:created_at] User :username (:email) has just registered.', ['created_at' => $notification->created_at, 'username' => $notification->data['name'], 'email' => $notification->data['email']]) }}
                                <a href="#" class="float-right mark-as-read" data-id="{{ $notification->id }}">
                                    {{ __('Mark as read') }}
                                </a>
                            </div>

                            @if($loop->last)
                                <a href="#" id="mark-all">
                                    {{ __('Mark all as read') }}
                                </a>
                            @endif
                        @empty
                            {{ __('There are no new notifications') }}
                        @endforelse
                    @else
                        {{ __('You are logged in!') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
@if(auth()->user()->is_admin)
    <script>
    function sendMarkRequest(id = null) {
        return $.ajax("{{ route('admin.markNotification') }}", {
            method: 'POST',
            data: {
                _token,
                id
            }
        });
    }

    $(function() {
        $('.mark-as-read').click(function() {
            let request = sendMarkRequest($(this).data('id'));

            request.done(() => {
                $(this).parents('div.alert').remove();
            });
        });

        $('#mark-all').click(function() {
            let request = sendMarkRequest();

            request.done(() => {
                $('div.alert').remove();
            })
        });
    });
    </script>
@endif
@endsection
