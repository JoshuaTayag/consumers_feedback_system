@php $totalPrice = 0; @endphp
@foreach ($temp_items as $index => $temp_item)
<tr id="{{ $loop->iteration }}">
  <th>{{ $loop->iteration }}</th>
  <th>
    {{-- <input type="text" name="temp_nea_code|{{$temp_item->id}}" class="form-control" placeholder="{{$temp_item->item->ItemCode}}"> --}}
    <a href="" class="updateCode form-control" data-name="code" data-type="text" data-pk="{{ $temp_item->id }}" data-title="Enter code">{{ $temp_item->nea_code }}</a>
  </th>
  <td>
    <div contenteditable="false" class="border rounded p-2" aria-multiline="true">{{$temp_item->item->Description}}</div>
  </td>
  <th>
    <input type="text" class="form-control" value="{{$temp_item->item->unit_name}}" readonly>
  </th>
  <th>
    <input type="number" class="form-control" value="{{$temp_item->item->AveragePrice}}" readonly>
  </th>
  <th>
    <a href="" class="updateQuantity form-control" data-name="1000" data-type="number" data-pk="{{ $temp_item->id }}" data-title="Enter quantity">{{ $temp_item->quantity }}</a>
  </th>
  <th>
    {{-- <input type="number" name="temp_cost|{{$temp_item->id}}" class="form-control" value="{{$temp_item->unit_cost}}" required> --}}
    <a href="" class="updateCost form-control" data-name="cost" data-type="number" data-pk="{{ $temp_item->id }}" data-title="Enter unit cost">{{ $temp_item->unit_cost }}</a>
  </th>
  <th>
    <a href="#"  class="btn btn-danger" onclick="removeItem({{$temp_item->id}})"><i class="fa fa-times"></i></a>
  </th>
</tr>
@php {{ $totalPrice += ($temp_item->item->AveragePrice * $temp_item->quantity); }} @endphp
@endforeach
<!-- <tr>
  <td colspan="3"></td>
  <td>
    <p class="fw-bold">Total Cost:</p> 
  </td>
  <td class="fw-bold" >₱ {{ number_format($totalPrice, 2) }}</td>
  <td colspan="3"></td>
</tr> -->
