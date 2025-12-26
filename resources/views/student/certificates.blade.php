@extends('layouts.app')

@section('title', 'My Certificates - ByteLearn')

@section('scripts')
<script>
    document.getElementById('app-data').textContent = JSON.stringify(@json($data));
</script>
@endsection
