@forelse($tests as $test)
    <tr>
        <td class="d-flex">
            {{ $loop->iteration }}
            <div class="checkbox text-end align-self-center ms-2">

                <div class="form-check custom-checkbox ">
                    <input type="checkbox" class="form-check-input checkItem" value="{{ $test->id }}" required="">
                    <label class="form-check-label" for="checkbox"></label>
                </div>
            </div>


        </td>
        <td>

            @if($test->icon)
                <img src="{{ asset('storage/' . $test->icon) }}" alt="{{ $test->title }}" width="50" class="rounded">
            @else
                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:50px;height:50px;">
                    <i class="fas fa-vial text-muted fs-4"></i>
                </div>
            @endif
        </td>
        <td class="fw-600">{{ $test->title }}</td>
        <td>
            <span class="badge light badge-{{ $test->status == 'active' ? 'success' : 'danger' }}">
                {{ ucfirst($test->status) }}
            </span>
        </td>
        <td>
            <small>{{ $test->created_at->format('d M, Y') }}</small>
        </td>
        <td class="text-center">
            {{-- <button class="btn btn-sm btn-info light" data-bs-toggle="modal"
                data-bs-target="#viewModal{{ $test->id }}">
                <i class="fas fa-eye"></i>
            </button> --}}
            <button class="btn btn-sm btn-warning light" data-bs-toggle="modal" data-bs-target="#editModal{{ $test->id }}">
                <i class="fas fa-edit"></i>
            </button>
            <form action="{{ route('admin.tests.destroy', $test) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger light delete-btn" title="Delete">
                    <i class="fas fa-trash"></i>
                </button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center text-danger">
          No result found
        </td>
    </tr>
@endforelse