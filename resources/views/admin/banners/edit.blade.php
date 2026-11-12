@extends('admin.layouts.app')

@section('header', 'Редактировать баннер')

@section('content')
<div class="card border-0 shadow-sm p-4" style="max-width: 700px;">
    @if ($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.banners.update', $banner) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Ady(zagolowka)<span class="text-danger">*</span></label>
            <input type="text" name="title" value="{{ old('title', $banner->title) }}" required class="form-control @error('title') is-invalid @enderror">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Href / ssylka</label>
            <input type="text" name="link" value="{{ old('link', $banner->link) }}" placeholder="mysal: /category/sport ýa-da https://saýt.com" class="form-control @error('link') is-invalid @enderror">
            <div class="form-text text-muted small">Islendik href salgysy: sahypa salgysy (mysal: <code>/category/sport</code>, <code>/article/habar-1</code>) ýa-da daşarky doly salgy (<code>https://...</code>).</div>
            @error('link')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Sortirowka poryadkasy</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" min="0" class="form-control @error('sort_order') is-invalid @enderror">
        </div>

        <div class="mb-3">
            <label class="form-label">Suratlar</label>
            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
            @if($banner->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/' . $banner->image) }}" alt="banner" class="img-thumbnail" style="max-height: 120px;">
                </div>
            @endif
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check mb-4">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Aktiwda</label>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Yza</a>
        </div>
    </form>
</div>
@endsection
