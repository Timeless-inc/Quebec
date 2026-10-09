<div class="btn-group" role="group">
    <form action="{{ route('application.updateStatus', $application->id) }}" method="POST">
        @csrf
        @method('PATCH')
        
        <button type="submit" name="status" value="em_andamento" class="btn btn-primary">
            Em Aberto
        </button>
        
        <button type="submit" name="status" value="deferido" class="btn btn-success">
            Finalizar
        </button>
        
        <button type="submit" name="status" value="indeferido" class="btn btn-danger">
            Indeferir
        </button>
        
        <button type="submit" name="status" value="pendente" class="btn btn-warning">
            Pendência
        </button>
    </form>
</div>
