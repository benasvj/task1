@extends('layouts.app')

@section('content')
    <form action={{ route('device.store') }} method="POST" class="jumbotron">
        @csrf
        <div class="form-group">
            <label for="device_id">Device Id</label>
            <input type="text" class="form-control" id="device_id" name="device_id">
        </div>
        <div class="form-group">
            <label for="coordinates">GPS Coordinates</label>
            <input type="text" class="form-control" id="coordinates" name="coordinates">
        </div>
        <select class="form-control" id="place" name="place">
            <option disabled selected value> -- select place -- </option>
            <option value="home">Home</option>
            <option value="work">Work</option>
        </select>
        <button type="submit">Send</button> 
    </form>
@endsection


