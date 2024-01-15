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
<b>* Sin Cargos :</b> Todos los trabajadores activos en sistema cargados sin un codigo de cargo.<br>
<b>* Sin inicio de Contrato :</b> Todos los trabajadores activos en sistema sin un inicio de contrato cargado.<br>
<b>* Sin fin de Contrato :</b> Todos los trabajadores desactivados en sistema sin un fin de contrato cargado.<br>
<b>* Sin turno :</b> Trabajadores activos cargados, sin turno asignado.<br>
<b>* Sin salida :</b> Trabajadores con marcación de entrada pero no de salida.<br>
</small</p>
<hr>
  <table width="100%">
    <thead style="background-color: lightgray;">
      <tr>
        <td align="center" colspan="4">SIN CARGOS</td>
      </tr>
      <tr>
        <th>NOMBRES</th>
        <th>RUT</th>
        <th>BU</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach ($cargos as $ca)
      <tr>
        <td align="left">{{$ca->nombres}} {{$ca->apellidos}}</td>
        <td align="center">{{$ca->rut}}</td>
        <td align="center">{{$ca->bu}}</td>
        <td align="center"></td>

      </tr>
    @endforeach
    </tbody>

  </table>

  <br/>
  <table width="100%">
    <thead style="background-color: lightgray;">
      <tr>
        <td align="center" colspan="4">SIN INICIO DE CONTRATO</td>
      </tr>
      <tr>
        <th>NOMBRES</th>
        <th>RUT</th>
        <th>BU</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      @foreach ($icontrato as $ic)
      <tr>
        <td align="left">{{$ic->nombres}} {{$ic->apellidos}}</td>
        <td align="center">{{$ic->rut}}</td>
        <td align="center">{{$ic->bu}}</td>
        <td align="center"></td>
  
      </tr>
    @endforeach
    </tbody>
  
  </table>
<br>

<table width="100%">
  <thead style="background-color: lightgray;">
    <tr>
      <td align="center" colspan="4">SIN FIN DE CONTRATO</td>
    </tr>
    <tr>
      <th>NOMBRES</th>
      <th>RUT</th>
      <th>BU</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @foreach ($fcontrato as $fc)
    <tr>
      <td align="left">{{$fc->nombres}} {{$fc->apellidos}}</td>
      <td align="center">{{$fc->rut}}</td>
      <td align="center">{{$fc->bu}}</td>
      <td align="center"></td>

    </tr>
  @endforeach
  </tbody>

</table>
<br>

<table width="100%">
  <thead style="background-color: lightgray;">
    <tr>
      <td align="center" colspan="4">SIN TURNO</td>
    </tr>
    <tr>
      <th>NOMBRES</th>
      <th>RUT</th>
      <th>BU</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @foreach ($turnos as $turno)
    <tr>
      <td align="left">{{$turno->nombres}} {{$turno->apellidos}}</td>
      <td align="center">{{$turno->rut}}</td>
      <td align="center">{{$turno->bu}}</td>
      <td align="center"></td>

    </tr>
  @endforeach
  </tbody>

</table>


<br>

<table width="100%">
  <thead style="background-color: lightgray;">
    <tr>
      <td align="center" colspan="4">SIN SALIDA</td>
    </tr>
    <tr>
      <th>NOMBRES</th>
      <th>RUT</th>
      <th>BU</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    @foreach ($salida as $sal)
    <tr>
      <td align="left">{{$sal->nombres}} {{$sal->apellidos}}</td>
      <td align="center">{{$sal->rut}}</td>
      <td align="center">{{$sal->bu}}</td>
      <td align="center"></td>

    </tr>
  @endforeach
  </tbody>

</table>

</body>
</html>