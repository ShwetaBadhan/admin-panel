{{-- resources/views/admin/pages/counters-section.blade.php --}}
@extends("admin.layout.admin-master")

@section("title", "Counters Section | Continuity Care")
@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Counters Section</li>
                </ol>
            </div>

            @if(session('success'))
                <script>Swal.fire('Success!', '{{ session('success') }}', 'success');</script>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-5">
                <div class="col-lg-12">
                    <div class="card border shadow-sm h-100">
                        <div class="card-header d-flex justify-content-between align-items-center bg-theme-light">
                            <h5 class="mb-0"><i class="fas fa-chart-line"></i> Counters/Statistics Section</h5>
                            <span class="badge bg-primary">Dynamic Counters</span>
                        </div>
                        <div class="card-body">

                            <form action="{{ route('counters-section.update', $section) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Background Settings -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Background Color</label>
                                        <div class="d-flex align-items-center">
                                            <input type="color" name="background_color" 
                                                   value="{{ old('background_color', $section->background_color ?? '#2d7a3e') }}" 
                                                   class="form-control form-control-color me-2" 
                                                   style="width: 60px; height: 40px;">
                                            <input type="text" name="background_color_text" 
                                                   value="{{ old('background_color', $section->background_color ?? '#2d7a3e') }}" 
                                                   class="form-control" 
                                                   id="colorPickerText">
                                        </div>
                                        <small class="text-muted">Choose background color for counters section</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Background Image (Optional)</label>
                                        @if($section->background_image && Storage::disk('public')->exists($section->background_image))
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $section->background_image) }}" 
                                                     class="img-fluid rounded" style="max-height: 100px;">
                                            </div>
                                        @endif
                                        <input type="file" name="background_image" 
                                               class="form-control @error('background_image') is-invalid @enderror" 
                                               accept="image/*">
                                        @error('background_image') 
                                            <div class="invalid-feedback">{{ $message }}</div> 
                                        @enderror
                                    </div>
                                </div>

                                <hr>

                                <!-- Dynamic Counters -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="mb-0"><i class="fas fa-calculator"></i> Counters</h6>
                                        <button type="button" class="btn btn-sm btn-success" id="addCounterBtn">
                                            <i class="fas fa-plus"></i> Add Counter
                                        </button>
                                    </div>
                                    
                                    <div id="countersContainer" class="row g-3">
                                        <!-- Counters will be added here dynamically -->
                                    </div>
                                </div>

                                <hr>
                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-success btn-lg px-5">
                                        <i class="fas fa-save me-2"></i>Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Counter Item Template -->
    <template id="counterTemplate">
        <div class="col-md-6 col-lg-3 counter-item">
            <div class="card border h-100">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <strong>Counter <span class="counter-number"></span></strong>
                    <button type="button" class="btn btn-sm btn-danger remove-counter">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small">Icon (Image URL or leave empty)</label>
                        <input type="text" name="counters[__index__][icon]" 
                               class="form-control form-control-sm" 
                               placeholder="Icon image path">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Number *</label>
                        <input type="text" name="counters[__index__][number]" 
                               class="form-control form-control-sm counter-number-input" 
                               placeholder="e.g. 3145" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Suffix (e.g. +, %, K)</label>
                        <input type="text" name="counters[__index__][suffix]" 
                               class="form-control form-control-sm" 
                               placeholder="e.g. +">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Label *</label>
                        <input type="text" name="counters[__index__][label]" 
                               class="form-control form-control-sm" 
                               placeholder="e.g. Organic Products" required>
                    </div>
                </div>
            </div>
        </div>
    </template>

@endsection

@push('scripts')
<script>
$(function () {
    const container = $('#countersContainer');
    const template = $('#counterTemplate').html();
    let index = 0;

    // Load existing counters
    const existingCounters = @json(old('counters', $section->counters ?? []));

    function renderCounter(icon = '', number = '', suffix = '', label = '') {
        let row = template.replace(/__index__/g, index);
        let $row = $(row);
        
        $row.find('.counter-number').text(index + 1);
        $row.find('input[name*="[icon]"]').val(icon);
        $row.find('input[name*="[number]"]').val(number);
        $row.find('input[name*="[suffix]"]').val(suffix);
        $row.find('input[name*="[label]"]').val(label);
        
        container.append($row);
        index++;
    }

    // Initial render
    if(existingCounters.length > 0) {
        existingCounters.forEach(counter => {
            renderCounter(
                counter.icon || '',
                counter.number || '',
                counter.suffix || '',
                counter.label || ''
            );
        });
    } else {
        // Default 4 counters
        const defaults = [
            { number: '3145', suffix: '+', label: 'Organic Products' },
            { number: '100', suffix: '%', label: 'Organic Guaranteed' },
            { number: '160', suffix: '+', label: 'Qualified Farmers' },
            { number: '310', suffix: '+', label: 'Agriculture Firm' },
        ];
        defaults.forEach(c => renderCounter('', c.number, c.suffix, c.label));
    }

    // Add new counter
    $('#addCounterBtn').click(function() {
        renderCounter();
    });

    // Remove counter
    $(document).on('click', '.remove-counter', function() {
        if($('.counter-item').length > 1) {
            $(this).closest('.counter-item').remove();
            // Re-number counters
            $('.counter-number').each(function(i) {
                $(this).text(i + 1);
            });
        } else {
            Swal.fire('Info', 'At least one counter is required', 'info');
        }
    });

    // Color picker sync
    $('input[name="background_color"]').on('input', function() {
        $('#colorPickerText').val($(this).val());
    });
    $('#colorPickerText').on('input', function() {
        $('input[name="background_color"]').val($(this).val());
    });
});
</script>
@endpush