@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1>Lista de Usuarios</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Usuarios registrados</h3>
        </div>
        <div class="card-body">
            <a href="{{ route('users.create') }}" class="btn btn-success mb-3" style="background-color:#0d6efd; color:white">Nuevo Usuario</a>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ ucfirst($user->role) }}</td>
                            <td>{{ ucfirst($user->status) }}</td>
                            <td>
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit mr-1"></i> Editar
                            </a>
                            <!-- Botón Toggle Estado -->
                            
                            <!-- Botón Eliminar (con confirmación JS) -->
                            <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger delete-btn">
                                    <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </td>
                        </tr>
                        
                    @endforeach
                </tbody>
                @section('js')
    <script>
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('¿Seguro que quieres ELIMINAR este usuario?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
            </table>
        </div>
    </div>
@stop