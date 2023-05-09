{{--
A course card component.

Need to provide :
    - $course : the course to display (App\Models\Course)
    - $badge : (optional) the text to display in the badge. Default is null (no badge).
    - $showDescription : (optional) whether to show the description or not. Default is true.
--}}

@php
    $showDescription = $showDescription ?? true;
@endphp

@php
    /** @var \App\Models\Course $course */

@endphp

<div class="rbt-card variation-01 rbt-hover">
    <div class="rbt-card-img">
        <a href="{{ url('course.show', ['courseId' => $course->getId()]) }}">
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

        <h4 class="rbt-card-title"><a
                    href="{{ url('course.show', ['courseId' => $course->getId()]) }}">{{ $course->getTitle() }}</a>
        </h4>
        <ul class="rbt-meta">
            <li><i class="feather-book"></i>{{ $course->getChapters()->count() }} Chapitres</li>
            <li><i class="feather-users"></i>{{ $course->getEnrollments()->count() }} Inscrits</li>
        </ul>
        @if($showDescription)
            <p class="rbt-card-text">{{ $course->getDescription() }}</p>
        @endif
        <div class="rbt-card-bottom">
            <div class="rbt-author-meta">
                <div class="rbt-author-info">
                    Par {{ $course->getOwner()->getFirstname() . ' ' . $course->getOwner()->getLastname() }}
                </div>
            </div>
            <a class="rbt-btn-link" href="{{ url('course.show', ['courseId' => $course->getId()]) }}">En savoir plus<i
                        class="feather-arrow-right"></i></a>
        </div>
    </div>
</div>
