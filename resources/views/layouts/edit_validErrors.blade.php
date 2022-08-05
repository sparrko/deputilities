@if(session()->has('error'))
    <div class="validErrors">
        <h3>Ошибка</h3>
        <p>{{ session()->get('error') }}</p>
    </div>
@elseif($errors->any())
    <div class="validErrors">
        <h3>{{ count($errors->all()) > 1 ? 'Ошибки' : 'Ошибка' }}</h3>
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
