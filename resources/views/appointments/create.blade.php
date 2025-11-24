@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-visitor" style="background-color: rgba(255, 255, 255, 0.8);">
                <div class="card-header text-center fw-bold card-visitor-header" style="background-color: rgba(255, 255, 255, 0.5); color: #000;">Crear Cita</div>

                <div class="card-body">
                    <form action="{{ route('appointments.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="patient_id" class="form-label">Paciente</label>
                            <select name="patient_id" id="patient_id" class="form-control">
                                @foreach($patients as $p)
                                    <option value="{{ $p->id }}">{{ $p->rut ?? '' }} - {{ $p->name }} {{ $p->apellido_paterno ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Fecha</label>
                            <input type="date" name="date" id="date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="time" class="form-label">Hora</label>
                            <input type="time" name="time" id="time" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notas</label>
                            <textarea name="notes" id="notes" class="form-control"></textarea>
                        </div>

                        <hr>
                        <div class="mb-2 d-flex align-items-center">
                            <h5 class="mb-0">Receta Médica (Opcional)</h5>
                            <small class="ms-2 text-muted">Rellena para crear una receta junto con la cita.</small>
                        </div>
                        <div class="border rounded p-3 mb-3" style="background:rgba(255,255,255,0.6)">
                            @if(isset($prescriptions) && $prescriptions->count())
                                <div class="mb-3">
                                    <label for="prescription_select" class="form-label">Seleccionar receta previa</label>
                                    <select id="prescription_select" class="form-control">
                                        <option value="" selected>-- Ninguna --</option>
                                        @foreach($prescriptions as $r)
                                            <option value="{{ $r->id }}" data-title="{{ e($r->title) }}" data-content="{{ e($r->content) }}" data-indications="{{ e($r->indications) }}">
                                                {{ $r->title }} ({{ $r->patient?->name ?? 'Sin paciente' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Al seleccionar una receta se copiarán sus campos abajo.</small>
                                </div>
                            @endif
                            <div class="mb-3">
                                <label for="prescription_title" class="form-label">Título de la Receta</label>
                                <input type="text" name="prescription_title" id="prescription_title" class="form-control" placeholder="Ej: Tratamiento antibiótico">
                            </div>
                            <div class="mb-3">
                                <label for="prescription_content" class="form-label">Contenido (medicamentos)</label>
                                <textarea name="prescription_content" id="prescription_content" rows="4" class="form-control" placeholder="Listado de medicamentos, dosis, etc."></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="prescription_indications" class="form-label">Indicaciones</label>
                                <textarea name="prescription_indications" id="prescription_indications" rows="3" class="form-control" placeholder="Instrucciones adicionales"></textarea>
                            </div>
                            <small class="text-muted">Si deja título y contenido vacíos, no se creará ninguna receta.</small>
                            <script>
                                (function(){
                                    const select = document.getElementById('prescription_select');
                                    if(!select) return;
                                    const titleInput = document.getElementById('prescription_title');
                                    const contentInput = document.getElementById('prescription_content');
                                    const indicationsInput = document.getElementById('prescription_indications');
                                    select.addEventListener('change', function(){
                                        const opt = select.options[select.selectedIndex];
                                        if(!opt || !opt.value){
                                            titleInput.value='';
                                            contentInput.value='';
                                            indicationsInput.value='';
                                            return;
                                        }
                                        titleInput.value = opt.getAttribute('data-title') || '';
                                        contentInput.value = opt.getAttribute('data-content') || '';
                                        indicationsInput.value = opt.getAttribute('data-indications') || '';
                                    });
                                })();
                            </script>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection