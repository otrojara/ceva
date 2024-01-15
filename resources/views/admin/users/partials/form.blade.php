<div class="form-group">
    {!! Form::label('name', 'Nombre:') !!}
    {!! Form::text('name',null, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),'placeholder' => 'Escriba el nombre']) !!}

    {!! Form::label('name', 'Rut:') !!}
    {!! Form::text('rut',null, ['class' => 'form-control' . ($errors->has('rut') ? ' is-invalid' : ''),'placeholder' => 'Escriba el RUT','id'=>'rut','oninput' => "checkRut(this)"]) !!}

   @error('rut')
        <span class="invalid-feedback">
            <strong>{{$message}}</strong>
        </span>
    @enderror


    {!! Form::label('name', 'Correo:') !!}
    {!! Form::text('email',null, ['class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),'placeholder' => 'Escriba correo']) !!}



    <script type="text/javascript">
  
        function checkRut(rut) {
            // Despejar Puntos
            var valor = rut.value.replace('.','');
            // Despejar Guión
            valor = valor.replace('-','');
            
            // Aislar Cuerpo y Dígito Verificador
            cuerpo = valor.slice(0,-1);
            dv = valor.slice(-1).toUpperCase();
            
            // Formatear RUN
            rut.value = cuerpo + '-'+ dv
            
            // Si no cumple con el mínimo ej. (n.nnn.nnn)
            if(cuerpo.length < 7) { rut.setCustomValidity("RUT Incompleto"); return false;}
            
            // Calcular Dígito Verificador
            suma = 0;
            multiplo = 2;
            
            // Para cada dígito del Cuerpo
            for(i=1;i<=cuerpo.length;i++) {
            
                // Obtener su Producto con el Múltiplo Correspondiente
                index = multiplo * valor.charAt(cuerpo.length - i);
                
                // Sumar al Contador General
                suma = suma + index;
                
                // Consolidar Múltiplo dentro del rango [2,7]
                if(multiplo < 7) { multiplo = multiplo + 1; } else { multiplo = 2; }
          
            }
            
            // Calcular Dígito Verificador en base al Módulo 11
            dvEsperado = 11 - (suma % 11);
            
            // Casos Especiales (0 y K)
            dv = (dv == 'K')?10:dv;
            dv = (dv == 0)?11:dv;
            
            // Validar que el Cuerpo coincide con su Dígito Verificador
            if(dvEsperado != dv) { rut.setCustomValidity("RUT Inválido"); return false; }
            
            // Si todo sale bien, eliminar errores (decretar que es válido)
            rut.setCustomValidity('');
        }
          
        </script>

</div>

<strong>Permisos</strong>
@error('role_id')
<br>
    <small class="text-danger">
        <strong>{{$message}}</strong>
    </small>
    <br>
@enderror

    <div class="form-group col-md-12">
        {!! Form::select('role_id',$roles, (isset($userRole)) ? $userRole : null,['class'=>'form-control']) !!}

        
    </div>
    

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
                {{-- //{!! Form::checkbox('BusinessUnits[]', $BusinessUnit->id, null, ['class' => 'mr-1']) !!} --}}

               
                    @if ($BusinessUnit->user_id == null)
                        <input type="checkbox" name="BusinessUnits[]" class="switch-input" value="{{$BusinessUnit->id}}"  />
                    @else
                        <input type="checkbox" name="BusinessUnits[]" class="switch-input" value="{{$BusinessUnit->id}}"  checked="checked"/>
                    @endif
                    
    
                
                {{-- <input type="checkbox" name="active" value="true"> --}}
                {{$BusinessUnit->nombre}}
            </label>
        </div>
        
    @endforeach
    


    