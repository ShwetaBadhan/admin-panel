@forelse($application as $applications)
    <tr>
        <td>
            <div class="checkbox text-end align-self-center">
                <div class="form-check custom-checkbox ">
                    <input type="checkbox" class="form-check-input checkItem" value="{{ $applications->id }}" required="">
                    <label class="form-check-label" for="checkbox"></label>
                </div>
            </div>
        </td>
        <td class="patient-info ps-0">

            <span class="text-nowrap ms-2">{{ $applications->fullname }}</span>
        </td>
        <td class="text-primary">{{ $applications->job_title }}</td>
        <td>{{ $applications->phone }}</td>


        <td>
            <span class="me-3">
                <a href="javascript:void(0);" data-id="{{ $applications->id }}"
                    class="viewApp btn btn-sm btn-info light "><i class=" fa fa-eye fs-18"></i></a>
            </span>
            <span class="me-3">
                <a href="javascript:void(0);" data-id="{{ $applications->id }}"
                    class="editApp btn btn-sm btn-warning light"><i class="fa fa-pencil fs-18"></i></a>
            </span>

            <span>
                <a class="btn btn-sm btn-danger light deleteContact" data-id="{{ $applications->id }}">
                    <i class="fa fa-trash fs-18 "></i></a>
            </span>

        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center text-danger">
            No related search
        </td>
    </tr>

@endforelse