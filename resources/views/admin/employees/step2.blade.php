@extends('layouts.admin')
@section('page-title', 'Add Employee - Step 2')

@section('content')
<h4 class="mb-3" style="color:#f0c040">Educational Qualification</h4>

<div class="step-indicator mb-4">
    <div class="step done">1</div>
    <div class="step active">2</div>
    <div class="step">3</div>
    <div class="step">4</div>
    <div class="step">5</div>
</div>

<div class="card-dark">
    <form method="POST" action="{{ route('admin.employees.saveStep2', $employee->id) }}">
        @csrf

        <div id="qual-items">

            @if($employee->qualifications->count() > 0)
                @foreach($employee->qualifications as $index => $qual)
                <div class="qual-item border border-secondary rounded p-3 mb-3">
                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">Qualification Type</label>
                            <select name="qualifications[{{ $index }}][qualification_type]" class="form-select">
                                <option value="">Select</option>
                                @foreach(['10th','12th','Diploma',"Bachelor's","Master's",'PhD'] as $q)
                                    <option value="{{ $q }}" {{ $qual->qualification_type == $q ? 'selected' : '' }}>
                                        {{ $q }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Institution Name</label>
                            <input type="text" name="qualifications[{{ $index }}][institution_name]"
                                   class="form-control" value="{{ $qual->institution_name }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Field of Study</label>
                            <input type="text" name="qualifications[{{ $index }}][field_of_study]"
                                   class="form-control" value="{{ $qual->field_of_study }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="qualifications[{{ $index }}][start_date]"
                                   class="form-control" value="{{ $qual->start_date }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">End Date</label>
                            <input type="date" name="qualifications[{{ $index }}][end_date]"
                                   class="form-control" value="{{ $qual->end_date }}">
                        </div>

                        <!-- REMOVE BUTTON -->
                        <div class="col-md-12 text-end mt-2">
                            <button type="button" class="btn btn-danger btn-sm remove-qual">
                                Remove
                            </button>
                        </div>

                    </div>
                </div>
                @endforeach
            @else

            <!-- DEFAULT ONE BLOCK -->
            <div class="qual-item border border-secondary rounded p-3 mb-3">
                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label">Qualification Type</label>
                        <select name="qualifications[0][qualification_type]" class="form-select">
                            <option value="">Select</option>
                            <option>10th</option>
                            <option>12th</option>
                            <option>Diploma</option>
                            <option>Bachelor's</option>
                            <option>Master's</option>
                            <option>PhD</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Institution Name</label>
                        <input type="text" name="qualifications[0][institution_name]"
                               class="form-control" placeholder="Type here...">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Field of Study</label>
                        <input type="text" name="qualifications[0][field_of_study]"
                               class="form-control" placeholder="e.g. Computer Science">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="qualifications[0][start_date]" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">End Date</label>
                        <input type="date" name="qualifications[0][end_date]" class="form-control">
                    </div>

                    <!-- REMOVE BUTTON -->
                    <div class="col-md-12 text-end mt-2">
                        <button type="button" class="btn btn-danger btn-sm remove-qual">
                            Remove
                        </button>
                    </div>

                </div>
            </div>

            @endif

        </div>

        <!-- ADD MORE BUTTON -->
        <button type="button" id="add-qual"
                class="btn btn-outline-warning btn-sm mb-3">
            + Add More Item
        </button>

        <div class="d-flex justify-content-between mt-2">
            <a href="{{ route('admin.employees.create') }}" class="btn btn-secondary">Back</a>
            <button type="submit" class="btn btn-gold">Next →</button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
let qCount = {{ max($employee->qualifications->count(), 1) }};

/* ➕ ADD NEW ITEM */
document.getElementById('add-qual').addEventListener('click', function () {
    const container = document.getElementById('qual-items');

    const div = document.createElement('div');
    div.className = 'qual-item border border-secondary rounded p-3 mb-3';

    div.innerHTML = `
        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Qualification Type</label>
                <select name="qualifications[${qCount}][qualification_type]" class="form-select">
                    <option value="">Select</option>
                    <option>10th</option>
                    <option>12th</option>
                    <option>Diploma</option>
                    <option>Bachelor's</option>
                    <option>Master's</option>
                    <option>PhD</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Institution Name</label>
                <input type="text" name="qualifications[${qCount}][institution_name]" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Field of Study</label>
                <input type="text" name="qualifications[${qCount}][field_of_study]" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">Start Date</label>
                <input type="date" name="qualifications[${qCount}][start_date]" class="form-control">
            </div>

            <div class="col-md-4">
                <label class="form-label">End Date</label>
                <input type="date" name="qualifications[${qCount}][end_date]" class="form-control">
            </div>

            <div class="col-md-12 text-end mt-2">
                <button type="button" class="btn btn-danger btn-sm remove-qual">
                    Remove
                </button>
            </div>

        </div>
    `;

    container.appendChild(div);
    qCount++;
});

/* ❌ REMOVE ITEM */
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-qual')) {
        e.target.closest('.qual-item').remove();
    }
});
</script>
@endpush