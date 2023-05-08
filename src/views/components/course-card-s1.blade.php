{{--
A course card component.

Need to provide :
    - $event : the event to display (App\Models\Event)
    - $badge : (optional) the text to display in the badge. Default is null (no badge).
--}}

@php
    /** @var \App\Models\Course $course */

@endphp

<div class="rbt-card variation-01 rbt-hover">
    <div class="rbt-card-img">
        <a href="course-details.html">
            <img src="{{ url('course.banner', ['courseId' => $course->getId()]) }}" alt="Card image">
            @if(isset($badge) && $badge !== '')
                <div class="rbt-badge-3 bg-white">
                    {{-- foreach word in $badge --}}
                    @foreach(explode(' ', $badge) as $word)
                        <span>{{ $word }}</span>
                    @endforeach
                </div>
            @endif
        </a>
    </div>
    <div class="rbt-card-body">

        <div class="rbt-card-top">
            <div class="rbt-category">
                <a href="#">{{ $course->getCategory()->getLabel() }}</a>
            </div>
            <div class="rbt-bookmark-btn">
                <a class="rbt-round-btn fs-2" title="Bookmark" href="#"><i class="feather-bookmark"></i></a>
            </div>
        </div>

        <h4 class="rbt-card-title"><a href="course-details.html">{{ $course->getTitle() }}</a>
        </h4>
        <ul class="rbt-meta">
            <li><i class="feather-book"></i>{{ $course->getChapters()->count() }} Chapitres</li>
            <li><i class="feather-users"></i>{{ $course->getEnrollments()->count() }} Inscrits</li>
        </ul>
        <p class="rbt-card-text">{{ $course->getDescription() }}</p>
        <div class="rbt-card-bottom">
            <div class="rbt-author-meta">
                <div class="rbt-author-info">
                    Par {{ $course->getOwner()->getFirstname() . ' ' . $course->getOwner()->getLastname() }}
                </div>
            </div>
            <a class="rbt-btn-link" href="course-details.html">En savoir plus<i class="feather-arrow-right"></i></a>
        </div>
    </div>
</div>
