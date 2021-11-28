@extends('layouts.app')
@section('content')

<form class="partida shadow rounded-3" action="/cost" method="POST" name="cost">
    @csrf
    <table class="table">
        <tr>
            <th scope="col">Descripción</th>
            <th scope="col">Monto</th>
            <th scope="col">Tipo</th>
            <th scope="col"></th>
        </tr>
        <tr>
            <td scope="row">
            <input class="descripcion-input mt-2" type="text" name="descripcion">
            </td>
            <td>
                <input class="mt-2" type="number" name="monto" id=""  min="0" step="0.01" placeholder="00.00">
            </td>
            <td>
                <select class="selectpicker my-2" data-live-search="true" data-width="100%" name="elements">
                <option value="MD">Materiales Directos</option>  
                <option value="MOD">Mano de Obra Directa</option>  
                <option value="CIF">Costos Indirectos de Frabricacion</option>  
                <option value="CDA">Costos de ventas generales y administrativos</option>  
                </select>
            </td>
            <td>
                <button style="Background-color: transparent; font-size: 2.2rem; color: Dodgerblue; border-style: none;" type="submit"  title="Guardar costo"><i class="fas fa-plus-circle"></i></button>
            </td>
        </tr>
    </table>
    
    <div class="div-botones mt-3">
    </div>
    <div class="error text-danger text-center mt-3" style="font-weight: 500;"></div>
</form>

@if(count($registro))
    <div class="partida shadow rounded-3 mt-3">
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Monto</th>
                    <th>Elemento</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($registro as $item)
                <tr>
                    <td>{{$item->id}}</td>
                    <td>{{$item->description}}</td>
                    <td>${{$item->amount}}</td>
                    <td>{{$item->element}}</td>
                    <td>
                        <form action="/cost/{{$item->id}}" method="POST" style="display: inline-block">
                            @csrf
                            @method('delete')
                            <button class="px-1 py-0 btn text-danger " type="submit" onclick="return confirm('Esta seguro?')"><i class="fas fa-trash"></i></button>
                        </form>

                        <!-- Button trigger modal -->
                        <button class="px-1 py-0 btn text-secondary " type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="fas fa-pen"></i></button>
                        
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Editar registro de costo</h5>
                                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                
                                    <form class="d-flex flex-row w-100" action="cost.update" method="post">
                                        @csrf
                                        @method('put')
                                        <table>
                                        <tr>
                                            <th class="text-dark" >ID</th>
                                            <td>
                                            <input class="descripcion-input mt-2" type="text" name="idEdit" value="{{$item->id}}" readonly>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-dark">Descripción &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
                                            <td>
                                            <input class="descripcion-input mt-2" type="text" name="descripcionEdit" value="{{$item->description}}">
                                            </td>
                                        </tr>
                                            <tr>
                                                <th class="text-dark">Monto</th>
                                                <td>
                                                    <input class="mt-2" type="number" name="montoEdit" min="0" step="0.01" value="{{$item->amount}}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-dark">Elemento</th>
                                                <td>
                                                    <select class="selectpicker my-2" data-live-search="true" data-width="100%" name="elementEdit">
                                                    <option value="MD">Materiales Directos</option>  
                                                    <option value="MOD">Mano de Obra Directa</option>  
                                                    <option value="CIF">Costos Indirectos de Frabricacion</option>  
                                                    <option value="CDA">Costos de ventas generales y administrativos</option>  
                                                    </select>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                </div>
                                <div class="modal-footer">
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                  <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                </div>
                                </form>
                              </div>
                            </div>
                          </div>
                    </td>
                </tr>   
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@endsection
@section('js')


@endsection