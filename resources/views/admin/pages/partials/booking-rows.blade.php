@forelse($bookings as $booking)
    <tr>
        <td>
            <div class="checkbox text-end align-self-center">
                <div class="form-check custom-checkbox ">
                    <input type="checkbox" class="form-check-input checkItem" value="{{ $booking->id }}" required="">
                    <label class="form-check-label" for="checkbox"></label>
                </div>
            </div>
        </td>
        <td class="patient-info ps-0">

            <span class="text-nowrap ms-2">{{ $booking->name }}</span>
        </td>

        <td>{{ $booking->mobile }}</td>


        <td>


            <span>
                <a class="btn btn-sm btn-danger light deleteBook" data-id="{{ $booking->id }}">
                    <i class="fa fa-trash fs-18"></i>
                </a>

            </span>

        </td>
    </tr>
@empty
    <tr>
        <td colspan="4" class="text-center text-danger" >

            No results Found
        </td>
    </tr>
@endforelse