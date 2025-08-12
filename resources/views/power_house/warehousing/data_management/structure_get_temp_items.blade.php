@foreach ($temp_items as $index => $temp_item)
<tr id="{{ $loop->iteration }}">
  <th>
    {{-- <input type="text" name="temp_nea_code|{{$temp_item->id}}" class="form-control" placeholder="{{$temp_item->item->ItemCode}}"> --}}
    <input type="text" class="form-control" value="{{$temp_item->item->code}}" readonly>
  </th>
  <th>
    <input type="hidden" class="form-control" name="item_id[]" value="{{$temp_item->item_id}}" readonly>
    <input type="text" class="form-control" value="{{$temp_item->item->description}}" readonly>
  </th>
  <th>
    <input type="number" class="form-control" value="{{$temp_item->item->price}}" readonly>
  </th>
  <th>
    @if(isset($structure))
      <a href="" class="updateQuantity form-control" data-name="edit" data-type="number" data-pk="{{ $temp_item->id }}" data-title="Enter quantity">{{ $temp_item->quantity }}</a>
    @else
      <a href="" class="updateQuantity form-control" data-name="create" data-type="number" data-pk="{{ $temp_item->id }}" data-title="Enter quantity">{{ $temp_item->quantity }}</a>
    @endif
  </th>
  <th>
    @if(isset($structure))
      <a href="" class="updateCost form-control" data-name="edit" data-type="number" data-pk="{{ $temp_item->id }}" data-title="Enter unit cost">{{ $temp_item->unit_cost }}</a>
    @else
      <a href="" class="updateCost form-control" data-name="create" data-type="number" data-pk="{{ $temp_item->id }}" data-title="Enter unit cost">{{ $temp_item->unit_cost }}</a>
    @endif
  </th>
  <th>
    <a href="#"  class="btn btn-danger" onclick="removeItem({{$temp_item->id}})"><i class="fa fa-times"></i></a>
  </th>
</tr>
@endforeach