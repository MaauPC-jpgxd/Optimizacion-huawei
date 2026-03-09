@extends('adminlte::page')

@section('title', 'Crear Usuario')

@section('content_header')
    <h1>Crear Nuevo Usuario</h1>
@stop

@section('content')
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Registro de usuarios </h3>
        </div>

        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="card-body">
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Rol</label>
                    <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                        <option value="root">Root</option>
                        <option value="admin">Admin</option>
                        <option value="lector">Lector</option>
                    </select>
                    @error('role') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="status" class="form-control" required>
                        <option value="activo">Activo</option>
                        <option value="bloqueado">Bloqueado</option>
                    </select>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Crear Usuario</button>
            </div>
        </form>
    </div>
@stop