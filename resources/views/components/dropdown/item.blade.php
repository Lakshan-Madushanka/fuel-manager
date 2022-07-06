@props(['value', 'select'])

<option value="{{$value}}" @selected($select == $value) {{$attributes}}>{{$slot}}</option>
