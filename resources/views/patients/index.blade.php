@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista de Pacientes</h1>
    <a href="{{ route('patients.create') }}">Agregar Paciente</a>
    <input type="text" id="inputLiPac" onkeyup="busqueda()" placeholder="Search for names..">

<ul id="LiPac">
    @foreach($patients as $patient)
        <li>
            {{ $patient->rut }} - {{ $patient->name }} - {{ $patient->birth_date }} - {{ $patient->gender }} - {{ $patient->adress }}
            <a href="{{ route('patients.show', $patient) }}">Mostrar</a>
            <a href="{{ route('patients.edit', $patient) }}">Editar</a>
            <form action="{{ route('patients.destroy', $patient) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit">Eliminar</button>
            </form>
        </li>
    @endforeach
</ul>




</div>
@endsection
<script>
function busqueda() {
  // Declare variables
  var input, filter, ul, li, a, i, txtValue;
  input = document.getElementById('InputLiPac');
  filter = input.value.toUpperCase();
  ul = document.getElementById("rut");
  li = ul.getElementsByTagName('li');

  // Loop through all list items, and hide those who don't match the search query
  for (i = 0; i < li.length; i++) {
    a = li[i].getElementsByTagName("a")[0];
    txtValue = a.textContent || a.innerText;
    if (txtValue.toUpperCase().indexOf(filter) > -1) {
      li[i].style.display = "";
    } else {
      li[i].style.display = "none";
    }
  }
}
</script>
