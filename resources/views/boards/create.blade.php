@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h1 class="h4 mb-0">Créer un Board</h1>
                </div>
                <div class="card-body">
                    <form action="{{ route('boards.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nom :</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="name" 
                                   name="name" 
                                   required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description :</label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="4"></textarea>
                        </div>

                        <input type="hidden" name="workspace_id" value="{{ $workspace->id }}">
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                Créer le Board
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

