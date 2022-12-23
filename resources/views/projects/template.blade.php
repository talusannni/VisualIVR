<x-layout>
    <style>
        .card-img-top {
            width: 100%;
            height: 20vw;
            object-fit: cover;
        }
    </style>
    <h2>Templates</h2>
    <div class="row">
        @foreach ($templates as $plate)
        <div class="col-sm-3">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ ucfirst($plate->name) }}
                        @if($template == $plate->id)
                        <form>
                            <button class="btn btn-secondary btn-sm" disabled>Select</button>
                        </form>
                        @else
                        <form action="{{ url('/project/template/'.$id) }}" method="post">
                            @csrf
                            <input type="hidden" name="template_id" value="{{ $plate->id }}"/>
                            <button type="submit" class="btn btn-success btn-sm">Select</button>
                        </form>
                        @endif
                    </h5>
                    <img class="card-img-top" src="{{ asset('templates/'.$plate->screenshot.'.PNG') }}"/>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</x-layout>