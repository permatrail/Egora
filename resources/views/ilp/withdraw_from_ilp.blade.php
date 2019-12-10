@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="panel ">
            <div class="panel-body text-center">
                <h3>{{ __('International Logic Party') }}</h3>
                <h4>{{ __('Membership Withdrawal') }}</h4>
                <div class="card">
                    <div class="card-body text-justify">
                        <p>Philosopher @if(auth('web')->check()){{ auth('web')->user()->name }}@else [user’s name] @endif,</p>
                        <p>Through this function, you are able to withdraw yourself from membership in
                        the International Logic Party (ILP). This is a very serious decision because
                        you will not be able to declare yourself as a member again. As an ILP
                        Member, you are a citizen of the Republic of Egora, and Egora only wants
                        citizens who are loyal to their fellow citizens, i.e. other members of the ILP.</p>
                        <p>We want you to remain our equal. Please choose wisely.</p>
                        
                        <p>Cezary Jurewicz<br/>
                        Filosofos Vasileus<br/>
                        International Logic Party<br/>
                        “Transparency is the price of power”</p>
                    </div>
                </div>
                <h3 class="mt-3">International Logic Party Principles</h3>
                <div class="text-justify">
                    @include('blocks.principles')
                </div>
                
                <div class="text-center">
                    <img width="200px" src='{{ asset('img/ILP_logo.jpg') }}'>
                </div>
                
                <form action="{{ route('users.withdraw_from_ilp_process', auth('web')->user()->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="text-justify mt-5 mb-5">
                        I, <input class='line' value="{{ old('name') ?: '' }}" placeholder=" (user name)" name="name">, withdraw and forever forfeit my membership in the International Logic Party.
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <a class='btn btn-ilp btn-block' href="{{ route('users.ideological_profile', auth('web')->user()->id) }}">{{__('some.Cancel and Close')}}</a>
                        </div>
                        <div class="col-md-3 offset-6">
                            <button type='submit' class='btn btn-primary btn-block'>{{__('some.Save and Close')}}</button>                            
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
