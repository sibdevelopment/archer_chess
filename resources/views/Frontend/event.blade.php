@extends('layouts.frontend')
@section('title', 'Archerkids | Events')

@section('content')
    <style>
        .event-card {
            background: var(--acc-card);
            border: 1px solid var(--acc-border);
            border-radius: 14px;
            display: flex;
            flex-direction: column;
            height: 100%;
            /* 👈 ensures card fills available height */
            transition: transform .18s ease, box-shadow .18s ease;
        }

        .event-media {
            position: relative;
            aspect-ratio: 16/9;
            background: var(--acc-bg);
            flex-shrink: 0;
        }

        .event-body {
            flex: 1;
            /* 👈 takes up remaining space */
            padding: 16px;
            display: flex;
            flex-direction: column;
        }

        .event-title {
            font-size: 1.05rem;
            font-weight: 700;
            margin: 0 0 8px;
            line-height: 1.35;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
        }

        .event-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 14px;
            color: var(--acc-muted);
            font-size: .92rem;
            margin-bottom: 8px;
        }

        .event-desc {
            color: #374151;
            font-size: .95rem;
            flex-grow: 1;
            /* 👈 pushes actions to bottom */
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
            overflow: hidden;
        }

        .event-actions {
            padding: 0 16px 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            /* 👈 pushes this to bottom */
        }

        .events-grid .col {
            display: flex;
        }

        :root {
            --acc-primary: #4f46e5;
            /* indigo-600 */
            --acc-primary-600: #4338ca;
            --acc-muted: #6b7280;
            /* gray-500 */
            --acc-bg: #f8fafc;
            /* slate-50 */
            --acc-card: #ffffff;
            --acc-border: #e5e7eb;
        }

        .section-head {
            text-align: center;
            margin: 24px 0 16px;
        }

        .section-head .eyebrow {
            display: inline-block;
            font-size: .9rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--acc-muted);
        }

        .section-head h2 {
            font-weight: 800;
            margin: 8px 0 0;
        }

        .event-card {
            background: var(--acc-card);
            border: 1px solid var(--acc-border);
            border-radius: 14px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }

        .event-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, .07);
            border-color: #dbe3f0;
        }

        .event-media {
            position: relative;
            aspect-ratio: 16/9;
            /* keeps uniform height */
            background: var(--acc-bg);
        }

        .event-media img {
            width: 100%;
            height: 130%;
            object-fit: cover;
            display: block;
        }

        .event-badge {
            position: absolute;
            bottom: 12px;
            left: 12px;
            background: rgba(255, 255, 255, .92);
            backdrop-filter: saturate(180%) blur(6px);
            border: 1px solid var(--acc-border);
            color: #111827;
            font-weight: 600;
            font-size: .8rem;
            padding: .25rem .55rem;
            border-radius: 999px;
        }

        .event-body {
            padding: 16px 16px 12px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .event-title {
            font-size: 1.05rem;
            font-weight: 700;
            margin: 0;
            line-height: 1.35;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            overflow: hidden;
        }

        .event-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px 14px;
            color: var(--acc-muted);
            font-size: .92rem;
        }

        .event-meta .meta {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .event-desc {
            color: #374151;
            font-size: .95rem;
            margin: 2px 0 0;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 3;
            overflow: hidden;
        }

        .event-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 0 16px 16px;
        }

        .btn-pill {
            border-radius: 999px;
            font-weight: 700;
        }

        .btn-primary {
            background: var(--acc-primary);
            border-color: var(--acc-primary);
        }

        .btn-primary:hover {
            background: var(--acc-primary-600);
            border-color: var(--acc-primary-600);
        }

        .btn-outline {
            border: 1px solid var(--acc-border);
            background: #fff;
            color: #111827;
        }

        .btn-outline:hover {
            border-color: #cfd6e3;
            background: #f9fafb;
        }

        /* subtle grid spacing */
        .events-grid .col {
            margin-bottom: 22px;
        }

        /* Empty state */
        .empty-wrap {
            padding: 60px 0;
            text-align: center;
            color: var(--acc-muted);
        }

        .empty-card {
            border: 1px dashed var(--acc-border);
            border-radius: 14px;
            padding: 32px;
            background: #fff;
            display: inline-block;
        }

        /* Small tweaks for icons */
        .meta svg {
            width: 18px;
            height: 18px;
        }
    </style>

    <section class="py-4 py-md-5">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Every event is a new chance to grow</span>
                <h2>Events</h2>
            </div>

            @if ($events->isEmpty())
                <div class="empty-wrap">
                    <div class="empty-card">
                        <h5 class="mb-2">No events yet</h5>
                        <p class="mb-0">Please check back soon—new events will appear here.</p>
                    </div>
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 gy-3 gx-3 events-grid">
                    @foreach ($events as $event)
                        <div class="col">
                            <article class="event-card">
                                <div class="col d-flex">
                                    <article class=" w-100">
                                        <div class="event-media">
                                            <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}">
                                            <span class="event-badge">Mode: {{ $event->mode }}</span>
                                        </div>

                                        <div class="event-body">
                                            <h3 class="event-title">{{ $event->title }}</h3>
                                            <div class="event-meta">
                                                <span class="meta">📅 {{ toIndianDate($event->date) }}</span>
                                                <span class="meta">📍 {{ $event->location }}</span>
                                            </div>
                                            <p class="event-desc">{{ $event->short_description }}</p>
                                        </div>

                                        <div class="event-actions">
                                            @if ($event->brochure)
                                                <a href="{{ asset(Storage::url($event->brochure)) }}" target="_blank"
                                                    class="btn btn-outline btn-sm btn-pill">Brochure</a>
                                            @endif
                                            <a href="{{ $event->link }}" target="_blank"
                                                class="btn btn-primary btn-sm btn-pill">Register</a>
                                        </div>
                                    </article>
                                </div>

                            </article>
                        </div>
                    @endforeach
                </div>

                {{-- Optional: pagination slot (if $events is LengthAwarePaginator) --}}
                @if (method_exists($events, 'links'))
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $events->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            @endif
        </div>
    </section>
@endsection
