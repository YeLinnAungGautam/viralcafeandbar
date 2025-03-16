@extends('emails.email')

@section('content')
    <main>
        <div>
            {!! $body !!}
        </div>

        @include('emails.order.data-table')

        <div class="mb-3" id="footer">
            {!! $footer !!}
        </div>
        <div class="mb-3" id="t-and-c">
            <h3>Terms and Conditions</h3>
            {!! $terms_and_conditions !!}
        </div>
    </main>
@endsection
