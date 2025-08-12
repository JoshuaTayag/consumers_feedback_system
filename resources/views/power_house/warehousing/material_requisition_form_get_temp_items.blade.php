@php $totalPrice = 0; @endphp
@foreach ($temp_items as $index => $temp_item)
<tr id="{{ $loop->iteration }}">
  <th>{{ $loop->iteration }}</th>
  <th>
    {{-- <input type="text" name="temp_nea_code|{{$temp_item->id}}" class="form-control" placeholder="{{$temp_item->item->ItemCode}}"> --}}
    <div contenteditable="false" class="border rounded p-2" aria-multiline="true">{{$temp_item->item->code}}</div>
  </th>
  <td>
    <div contenteditable="false" class="border rounded p-2" aria-multiline="true">{{$temp_item->item->description}}</div>
  </td>
  <th>
    <input type="text" class="form-control" value="{{$temp_item->item->unit_name}}" readonly>
  </th>
  <th>
    <input type="number" class="form-control" value="{{$temp_item->item->price}}" readonly>
  </th>
  <th>
    <a href="" class="updateQuantity form-control" data-name="1000" data-type="number" data-pk="{{ $temp_item->id }}" data-title="Enter quantity">{{ $temp_item->quantity }}</a>
  </th>
  <th>
    <input type="number" name="temp_cost" class="form-control" value="{{$temp_item->item->price * $temp_item->quantity}}" required>
    {{-- <a href="" class="updateCost form-control" data-name="cost" data-type="number" data-pk="{{ $temp_item->id }}" data-title="Enter unit cost">{{ $temp_item->unit_cost }}</a> --}}
  </th>
  <th>
    <a href="#"  class="btn btn-danger" onclick="removeItem({{$temp_item->id}})"><i class="fa fa-times"></i></a>
  </th>
</tr>
@php $totalPrice += ($temp_item->item->AveragePrice * $temp_item->quantity); @endphp
@endforeach
<!-- <tr>
  <td colspan="3"></td>
  <td>
    <p class="fw-bold">Total Cost:</p> 
  </td>
  <td class="fw-bold" >₱ {{ number_format($totalPrice, 2) }}</td>
  <td colspan="3"></td>
</tr> -->
