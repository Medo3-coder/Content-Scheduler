@extends('layouts.app')

@section('content')
<div class="container">
  <h2 class="mb-3">Dashboard</h2>

  @if(session('success'))
  <div class="alert alert-success">
      {{ session('success') }}
  </div>
@endif

  <form class="row g-3 mb-4" method="GET" action="{{ route('dashboard') }}">
    <div class="col-md-3">
      <label>Status</label>
      <select name="status" class="form-select">
        <option value="">All</option>
        <option value="draft" {{ $filters['status'] == 'draft' ? 'selected' : '' }}>Draft</option>
        <option value="scheduled" {{ $filters['status'] == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
        <option value="published" {{ $filters['status'] == 'published' ? 'selected' : '' }}>Published</option>
      </select>
    </div>
    <div class="col-md-3">
      <label>Date</label>
      <input type="date" name="date" class="form-control" value="{{ $filters['date'] ?? '' }}">
    </div>
    <div class="col-md-3 d-flex align-items-end">
      <button class="btn btn-outline-primary">Filter</button>
    </div>
  </form>

  @if (count($posts))
    <div class="list-group">
      @foreach ($posts as $post)
        <div class="list-group-item">
          <h5>{{ $post['title'] }}</h5>
          <p>{{ $post['content'] }}</p>
          <p>
            <strong>Status:</strong> {{ ucfirst($post['status']) }} |
            <strong>Scheduled:</strong> {{ \Carbon\Carbon::parse($post['scheduled_time'])->format('Y-m-d H:i') }}
          </p>
          <small>
            Platforms:
            @foreach ($post['platforms'] as $platform)
              <span class="badge bg-secondary">{{ $platform['name'] }}</span>
            @endforeach
          </small>
        </div>
      @endforeach
    </div>
  @else
    <div class="alert alert-info">No posts found for selected filters.</div>
  @endif
</div>
@endsection
