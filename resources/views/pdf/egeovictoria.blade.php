<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>INFORME ERRORES</title>

<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
    }
    table{
        font-size: x-small;
    }
    tfoot tr td{
        font-weight: bold;
        font-size: x-small;
    }
    .gray {
        background-color: lightgray
    }
</style>

</head>
<body>

  <table width="100%">
    <tr>

      <td valign="top"><img src="{{public_path('vendor/adminlte/dist/img/ceva.png')}}" alt="" width="150"/></td>
        <td align="right">
            <h3>INFORME ERRORES ASISTENCIA</h3>
            <b> {{$fecha}} </b> <br>
            
        </td>

    </tr>
  </table>

  <table width="100%">
    <tr>
        {{-- <td valign="top">
              <h3>HOJA DE CONTROL</h3>
              <b>CUENTA DE COSTO:</b> {{$HCCAB->DESCRIPCION}} <br>
              <b>CODIGO CUENTA DE COSTO:</b> {{$HCCAB->IDCENTROCOSTO}}<br>
              <b>PROPUESTA CREADA:</b> {{$HCCAB->updated_at}}<br>
              <b>PROPUESTA CREADA POR:</b> {{$HCCAB->USUARIO}}
           
        </td> --}}
    </tr>

</table>


<br>

<table width="100%">
    <thead style="background-color: RED; ">
      <tr>
        <th style="color:white">SIN CARGO</th>
        <th style="color:white">SIN I. CONTRATO</th>
        <th style="color:white">SIN F. CONTRATO</th>
        <th style="color:white">SIN TURNOS</th>
        <th style="color:white">SIN SALIDA</th>
      </tr>
    </thead>
    <tbody>
      <tr>

        <td align="center"> {{$cargoscount}} </td>
        <td align="center"> {{$icontratocount}} </td>
        <td align="center"> {{$fcontratocount}} </td>
        <td align="center"> {{$turnoscount}} </td>
        <td align="center"> {{$salidacount}} </td>

      </tr>


  

    </tbody>

  </table>
  
<br>
<hr> 
<p><small>
<b>* Sin Cargos (SC):</b> Todos los trabajadores activos en sistema cargados sin un codigo de cargo.<br>
<b>* Sin turno (ST):</b> Trabajadores activos cargados, sin turno asignado.<br>
<b>* Sin inicio de Contrato (SIC):</b> Todos los trabajadores activos en sistema sin un inicio de contrato cargado.<br>
<b>* Sin fin de Contrato (SFC):</b> Todos los trabajadores desactivados en sistema sin un fin de contrato cargado.<br>
<b>* Sin salida (SS):</b> Trabajadores con marcación de entrada pero no de salida.<br>
</small</p>
<hr>
  <table width="100%">
    <thead style="background-color: lightgray;">
      <tr>
        <td align="center" colspan="8">ERRORES</td>
      </tr>
      <tr>
        <th>NOMBRES</th>
        <th>RUT</th>
        <th>BU</th>
        <th>SC</th>
        <th>ST</th>
        <th>SIC</th>
        <th>SFC</th>
        <th>SS</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($errores as $e)
      <tr>
        <td align="left">{{$e->nombre}}</td>
        <td align="center">{{$e->rut}}</td>
        <td align="center">{{$e->bu}}</td>

    @if ($e->sin_cargo == 1)
        <td align="center" bgcolor="red">X</td>
    @else
        <td align="center"> </td>
    @endif

    @if ($e->sin_turno == 1)
        <td align="center" bgcolor="red">X</td>
    @else
        <td align="center"> </td>
    @endif

    @if ($e->sin_inicio_contrato == 1)
        <td align="center" bgcolor="red">X</td>
    @else
        <td align="center"> </td>
    @endif

    @if ($e->sin_fin_contrato == 1)
        <td align="center" bgcolor="red">X</td>
    @else
        <td align="center"> </td>
    @endif

    @if ($e->sin_salida == 1)
        <td align="center" bgcolor="red">X</td>
    @else
        <td align="center"> </td>
    @endif

      </tr>
    @endforeach
    </tbody>

  </table>

  <br/>




</body>
</html>