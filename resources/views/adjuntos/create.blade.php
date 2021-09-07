<x-portal>
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header">
                    <h3 class="text-center font-weight-light my-4">Adjuntar Documentos</h3>
                </div>
                <div class="card-body">
                    <form enctype="multipart/form-data" method="POST" action="{{ url('/adjuntar') }}">
                        @csrf
                        <input type="text" hidden name="id" value="{{ $id }}">
                        <div class="mb-3">
                            <input required type="file" name="documentos[]" multiple class="form-control"
                                type="file" />
                        </div>
                        <div class="mt-4 mb-0">
                            <div class="d-grid">
                                <button class="btn btn-primary btn-block" type="submit">Subir</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-portal>
