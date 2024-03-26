@php $totalPrice = 0; @endphp
@foreach ($mrf->items as $index => $mrf_item)
<tr id="{{ $loop->iteration }}">
  <th>{{ $loop->iteration }}</th>
  <th>
    @if($mrf->status == 0)
    <a href="" class="updateCode form-control" data-name="code" data-type="text" data-pk="{{ $mrf_item->id }}" data-title="Enter code">{{ $mrf_item->nea_code }}</a>
    @else
      <input type="text" class="form-control" value="{{ $mrf_item->nea_code }}" readonly>
    @endif
  </th>
  <th>
    <!-- <input type="text" class="form-control" value="{{$mrf_item->item->Description}}" readonly> -->
    <div contenteditable="false" class="border rounded p-2" aria-multiline="true">{{$mrf_item->item->Description}}</div>
  </th>
  <th>
    <input type="text" class="form-control" value="{{$mrf_item->item->unit_name}}" readonly>
  </th>
  <th>
    <input type="number" class="form-control" value="{{$mrf_item->item->AveragePrice}}" readonly>
  </th>
  <th>
    @if($mrf->status == 0)
      <a href="" class="updateQuantity form-control" data-name="1000" data-type="number" data-pk="{{ $mrf_item->id }}" data-title="Enter quantity" @disabled($mrf->status != 0 || $mrf->requested_id != auth()->user()->id)>{{ $mrf_item->quantity }}</a>
    @elseif(isset($liquidation))
      <input type="hidden" class="form-control" name="item_ids[]" value="{{ $mrf_item->id }}">
      <input type="number" class="form-control" min="0" max="{{ $mrf_item->quantity }}" name="quantity[]" value="{{ $mrf_item->quantity }}">
    @else
      <input type="number" class="form-control" value="{{ $mrf_item->quantity }}" readonly>
    @endif
  </th>
  
  <!-- @if($mrf->status == 2)
    <th>
      <input type="number" class="form-control" value="{{ $mrf_item->liquidation_quantity }}" readonly>
    </th>
  @endif -->
  
  
    {{-- <input type="number" name="temp_cost|{{$mrf_item->id}}" class="form-control" value="{{$mrf_item->unit_cost}}" required> --}}
    @if($mrf->status == 0)
    <th>
      <a href="" class="updateCost form-control" data-name="cost" data-type="number" data-pk="{{ $mrf_item->id }}" data-title="Enter unit cost" disabled>{{ $mrf_item->unit_cost }}</a>
    </th>
    @else
      <th>
        <input type="number" class="form-control" value="{{ $mrf_item->unit_cost }}" readonly>
      </th>
    @endif
  
  
    @if($mrf->status == 0)
      <th>
        <a href="#"  class="btn btn-danger" onclick="removeItem({{$mrf_item->id}})"><i class="fa fa-times"></i></a>
      </th>
    @endif
  
</tr>
@php {{ $totalPrice += ($mrf_item->item->AveragePrice * $mrf_item->quantity); }} @endphp
@endforeach
<tr>
  <td colspan="3"></td>
  <td>
    <p class="fw-bold">Total Cost:</p> 
  </td>
  <td class="fw-bold" >₱ {{ number_format($totalPrice, 2) }}</td>
  <td colspan="3"></td>
</tr>