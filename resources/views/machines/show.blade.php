@extends('layout.layout')
@php $title='Machine Details'; $subTitle='Machines'; @endphp

@section('content')
<div class="card h-100 p-0 radius-12">
  <div class="card-body p-24">
    <h5>{{ $machine->name }}</h5>
    <p>{{ $machine->description }}</p>
    @if($machine->image)
      <img src="{{ asset($machine->image) }}" width="200" class="rounded">
    @endif
    <div class="mt-3">
      <a href="{{ route('machines.index') }}" class="btn btn-secondary">Back</a>
      <a href="{{ route('machines.edit', $machine->uuid) }}" class="btn btn-primary">Edit</a>
    </div>
  </div>
</div>
@endsection
