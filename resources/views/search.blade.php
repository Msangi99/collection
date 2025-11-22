<div class="row justify-content-center mb-5">
    <div class="col-sm-6">
        <form id="busSearchForm" action="{{ route('search') }}" method="POST" class="search-form">
            @csrf
            <div class="input-group shadow-sm rounded-pill bg-white">
                <span class="input-group-text bg-transparent border-0" id="busSelectLabel">
                    <i class="bi bi-bus-front text-primary fs-5"></i>
                </span>
                <select class="form-select border-0 bg-transparent" id="busSelect" name="campany_id" required>
                    <option value="" selected disabled>Select a bus company...</option>
                    @foreach($buses as $bus)
                        <option value="{{ $bus->busname->id }}"
                                {{ request('campany_id') == $bus->busname->id ? 'selected' : '' }}>
                            {{ $bus->busname->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-search me-1"></i> Search
                </button>
            </div>
        </form>
    </div>
</div>