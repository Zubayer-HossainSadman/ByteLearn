@extends('layouts.app')

@section('title', 'Browse Courses - ByteLearn')

@section('scripts')
<script>
    // Override initial data for courses page
    document.getElementById('app-data').textContent = JSON.stringify(@json($data));
</script>
@endsection
