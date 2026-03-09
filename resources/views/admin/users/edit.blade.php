@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1>Editar {{ $user->name }}</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Rol</label>
                    <select name="role" class="form-control" required>
                        <option value="root" {{ $user->role == 'root' ? 'selected' : '' }}>Root</option>
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="lector" {{ $user->role == 'lector' ? 'selected' : '' }}>Lector</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="status" class="form-control" required>
                        <option value="activo" {{ $user->status == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="bloqueado" {{ $user->status == 'bloqueado' ? 'selected' : '' }}>Bloqueado</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Contraseña (opcional)</label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="form-group">
                    <label>Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Guardar cambios</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>
@stop