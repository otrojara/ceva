@extends('adminlte::page')

@section('title', 'Inicio')

@section('content_header')
    <h1>Asistencia</h1>
@stop

@section('content')
@if (session('info'))

<div class="alert alert-primary" role="alert">
    <strong>¡Éxito!</strong> {{session('info')}}
</div>

@endif

<section class="content">


    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Main content -->
                <div class="invoice p-1 mb-1">
                  <div class="row">
                    <div class="col-6">
                      <div class="card-tools text-left">
                            <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
                              REPORTES
                            </button>
                            <div class="dropdown-menu">
                              <a href="{{route('repAsistencia')}}"  class="dropdown-item">INFORME ASISTENCIA</a>
                              <a href="{{route('repSemanalMeli')}}"  class="dropdown-item">INFORME ASISTENCIA MELI</a>
                              <a href="{{route('repProforma')}}"  class="dropdown-item">INFORME PROFORMA</a>
                              <a href="{{route('PDFAsistencia')}}"  class="dropdown-item">PDF ERRORES</a>
                              <a href="{{route('analistaPDF')}}"  class="dropdown-item">PDF ERRORES ANALISTA</a>
                            </div>
                      </div>
                    </div>
                  </div>

                </div>
                <!-- /.invoice -->
              </div>
        </div>
    </div>






    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">


        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{ $GeoTrabajadores}}</h3>

                <p>Trabajadores</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>

            </div>
          </div>

          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{$cargoscount}}</h3>

                <p>Sin cargos</p>
              </div>
              <div class="icon">
                <i class="ion ion-man"></i>
              </div>

            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{$turnoscount}}</h3>

                <p>Sin turnos</p>
              </div>
              <div class="icon">
                <i class="ion ion-clock"></i>
              </div>

            </div>
          </div>
          <!-- ./col -->

          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{$icontratocount}}</h3>

                <p>Sin inicio de contrato</p>
              </div>
              <div class="icon">
                <i class="ion ion-document-text"></i>
              </div>

            </div>
          </div>
        <!-- ./col -->

        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->

      <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->



    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class=" col-6">
                <div class="card">

                    <div class="card-header">
                        TRABAJADORES SIN CARGO
                    </div>

                    <div class="card-body">
                        @if($sinCargos->isEmpty())
                            <img src="{{ URL::asset('images/no-data.jpg') }}" style="max-width: 100%; height: auto; display: block; margin: 0 auto;">
                        @else
                            <table id="usersTable" class="table table-striped table-sm">
                                <thead>
                                <tr>
                                    <th>nombre</th>
                                    <th>Rut</th>
                                    <th>BU</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($sinCargos as $bu)
                                    <tr>
                                        <td>{{ $bu->nombres }}</td>
                                        <td>{{ $bu->rut }}</td>
                                        <td>{{ $bu->grupo }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">No hay registros</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        @endif
                    </div>



                </div>
            </div>

            <div class=" col-6">
                <div class="card">

                    <div class="card-header">
                        TRABAJADORES SIN INICIO CONTRATO
                    </div>
                    <div class="card-body">
                        @if($sinInicioContrato->isEmpty())
                            <img src="{{ URL::asset('images/no-data.jpg') }}" style="max-width: 100%; height: auto; display: block; margin: 0 auto;">
                        @else
                            <table id="sinIC" class="table table-striped table-sm">
                                <thead>
                                <tr>
                                    <th>nombre</th>
                                    <th>Rut</th>
                                    <th>BU</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($sinInicioContrato as $sic)
                                    <tr>
                                        <td>{{ $sic->nombres }}</td>
                                        <td>{{ $sic->rut }}</td>
                                        <td>{{ $sic->grupo }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">No hay registros</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        @endif
                    </div>




                </div>
            </div>

        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->












  </section>



@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">


    <link href="datatables/datatables.min.css" rel="stylesheet">
@stop

@section('js')

<script src="{{ asset('datatables/datatables.min.js') }}"></script>
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

<script>
    $(function () {
        $("#usersTable").DataTable({
        "responsive": true,
        "autoWidth": false,
        "order": [[ 0, "asc" ]]
    });

    $("#sinIC").DataTable({
        "responsive": true,
        "autoWidth": false,
        "order": [[ 0, "asc" ]]
    });

    });
  </script>

@stop
