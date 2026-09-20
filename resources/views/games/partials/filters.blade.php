{{-- Filter Toolbar: Search, Group, Status, Date, Submit & Reset --}}
<div class="schedule-filter-card">
    <form action="{{ route('schedule') }}" method="GET" class="row g-2 align-items-center" id="scheduleFilterForm">
        @if(request('tournament'))
            <input type="hidden" name="tournament" value="{{ request('tournament') }}">
        @endif

        {{-- Team Search --}}
        <div class="{{ isset($groups) && $groups->isNotEmpty() ? 'col-12 col-md-6 col-lg-3' : 'col-12 col-md-6 col-lg-4' }}">
            <div class="input-group input-group-sm">
                <span class="input-group-text bg-transparent border-end-0 text-secondary">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" 
                       placeholder="Search teams..." value="{{ request('search') }}">
            </div>
        </div>

        {{-- Group Filter (if available) --}}
        @if(isset($groups) && $groups->isNotEmpty())
            <div class="col-6 col-md-3 col-lg-2">
                <select name="group" class="form-select form-select-sm">
                    <option value="all">All groups</option>
                    @foreach($groups as $group)
                        <option value="{{ $group }}" {{ request('group') == $group ? 'selected' : '' }}>
                            Group {{ $group }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        {{-- Status Filter --}}
        <div class="{{ isset($groups) && $groups->isNotEmpty() ? 'col-6 col-md-3 col-lg-2' : 'col-6 col-md-3 col-lg-3' }}">
            <select name="status" class="form-select form-select-sm">
                <option value="all">All status</option>
                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Live</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </div>

        {{-- Date Picker --}}
        <div class="{{ isset($groups) && $groups->isNotEmpty() ? 'col-12 col-md-6 col-lg-2' : 'col-12 col-md-6 col-lg-3' }}">
            <input type="date" name="date" class="form-control form-control-sm" 
                   value="{{ request('date') }}">
        </div>

        {{-- Action Buttons --}}
        <div class="{{ isset($groups) && $groups->isNotEmpty() ? 'col-6 col-lg-1' : 'col-6 col-lg-1' }}">
            <button type="submit" class="btn-action-primary btn-action-sm w-100 justify-content-center">
                <i class="bi bi-funnel"></i> Filter
            </button>
        </div>

        <div class="{{ isset($groups) && $groups->isNotEmpty() ? 'col-6 col-lg-2' : 'col-6 col-lg-1' }}">
            <a href="{{ route('schedule') }}{{ request('tournament') ? '?tournament=' . request('tournament') : '' }}" 
               class="btn-action-secondary btn-action-sm w-100 justify-content-center">
                <i class="bi bi-arrow-clockwise"></i> Reset
            </a>
        </div>
    </form>
</div>
