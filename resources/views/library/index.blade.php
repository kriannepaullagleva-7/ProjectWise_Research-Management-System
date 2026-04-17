@extends('layouts.app')

@section('title', 'Library')
@section('page-title', 'Saved Library')

@section('content')
<div class="card">
    <div class="card-body">
        <h2>Saved Items</h2>

        @if($saved->isEmpty())
            <p>No saved items yet.</p>
        @else
            <ul>
                @foreach($saved as $item)
                    <li>
                        <a href="{{ route('research.show', $item->researchProject) }}">
                            {{ $item->researchProject->title ?? 'Untitled' }}
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection