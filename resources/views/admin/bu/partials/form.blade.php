<div class="form-group">
    {!! Form::label('codigo', 'Código:') !!}
    {!! Form::text('codigo',null, ['class' => 'form-control' . ($errors->has('codigo') ? ' is-invalid' : ''),'placeholder' => 'Escribe un código']) !!}

    @error('codigo')
        <span class="invalid-feedback">
            <strong>{{$message}}</strong>
        </span>
    @enderror

    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('nombre',null, ['class' => 'form-control' . ($errors->has('nombre') ? ' is-invalid' : ''),'placeholder' => 'Escribe un nombre']) !!}

    @error('nombre')
        <span class="invalid-feedback">
            <strong>{{$message}}</strong>
        </span>
    @enderror
    {!! Form::label('gerente_operacion', 'Gerente Operación:') !!}
    {!! Form::text('gerente_operacion',null, ['class' => 'form-control' . ($errors->has('gerente_operacion') ? ' is-invalid' : ''),'placeholder' => 'Escribe nombre de Gerente']) !!}

    @error('gerente_operacion')
        <span class="invalid-feedback">
            <strong>{{$message}}</strong>
        </span>
    @enderror
    {!! Form::label('contract_id', 'ID Contrato:') !!}
    {!! Form::text('contract_id',null, ['class' => 'form-control' . ($errors->has('contract_id') ? ' is-invalid' : ''),'placeholder' => 'Escribe ID Contrato']) !!}

    @error('contract_id')
        <span class="invalid-feedback">
            <strong>{{$message}}</strong>
        </span>
    @enderror

    {!! Form::label('grupo_geovictoria', 'Grupo Geovictoria:') !!}
    {!! Form::text('grupo_geovictoria',null, ['class' => 'form-control' . ($errors->has('grupo_geovictoria') ? ' is-invalid' : ''),'placeholder' => 'Escribe Grupo Geovictoria']) !!}

    @error('grupo_geovictoria')
        <span class="invalid-feedback">
            <strong>{{$message}}</strong>
        </span>
    @enderror

</div>

<strong>Comuna</strong>
@error('comuna_id')
<br>
    <small class="text-danger">
        <strong>{{$message}}</strong>
    </small>
    <br>
@enderror

    <div>
        {{-- <label>
            {!! Form::select('region_id',$regions, (isset($regions->id)) ? $regions->id : null,['class'=>'form-control']) !!}
        </label> --}}
        <label>
            {!! Form::select('comuna_id',$comunas, (isset($idcomuna)) ? $idcomuna : null,['class'=>'form-control']) !!}
        </label>
    </div>
   
    {!! Form::label('zona', 'Zona:') !!}
    {!! Form::select('zona', [ 'NORTE' => 'NORTE','CENTRO' => 'CENTRO','SUR' => 'SUR','XD' => 'XD' ], null, ['class' => 'form-control']) !!}
