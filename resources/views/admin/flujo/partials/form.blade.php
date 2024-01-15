<div class="form-group">
    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('nombre',null, ['class' => 'form-control' . ($errors->has('nombre') ? ' is-invalid' : ''),'placeholder' => 'Escribe un nombre']) !!}

    @error('nombre')
        <span class="invalid-feedback">
            <strong>{{$message}}</strong>
        </span>
    @enderror

    <strong>Business Unit</strong>
    @error('BusinessUnits')
    <br>
        <small class="text-danger">
            <strong>{{$message}}</strong>
        </small>
        <br>
    @enderror
    @foreach ($BusinessUnits as $BusinessUnit)
    <div>
        <label>
            {!! Form::checkbox('BusinessUnits[]', $BusinessUnit->id, ($BusinessUnit->checked == 1) ? true : null, ['class' => 'mr-1']) !!}
            {{$BusinessUnit->nombre}}
        </label>
    </div>
    
@endforeach


</div>

