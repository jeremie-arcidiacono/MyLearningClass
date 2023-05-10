{{--
A course card component, style 2.

Style 2 has :
    - No description
    - A progress bar
    - No bookmark button
    - No badge
    - No footer (author and 'learn more' link)

Style 2 is use when user is enrolled in a course.

Need to provide :
    - $course : the course to display (App\Models\Course)
    - $progressPercentage : (optional) the progress of the user in this course. Default is null (no progress bar displayed).
--}}

@php
    /**
    * @var \App\Models\Course $course
    * @var \App\Auth $auth
    * @var int $progressPercentage
    */

    $progressPercentage = $progressPercentage ?? null;
@endphp

<div class="rbt-card variation-01 rbt-hover">
    <div class="rbt-card-img">
        <a href="{{ url('chapter.show', ['courseId' => $course->getId()]) }}">
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
        </div>

        <h4 class="rbt-card-title"><a
                    href="{{ url('chapter.show', ['courseId' => $course->getId()]) }}">{{ $course->getTitle() }}</a>
        </h4>
        <ul class="rbt-meta">
            <li><i class="feather-book"></i>{{ $course->getChapters()->count() }} Chapitres</li>
            <li><i class="feather-users"></i>{{ $course->getEnrollments()->count() }} Inscrits</li>
        </ul>
        @if($showDescription)
            <p class="rbt-card-text">{{ $course->getDescription() }}</p>
        @endif

        @if($progressPercentage !== null)
            <div class="rbt-progress-style-2 mb--20 mt--10">
                <div class="single-progress">
                    <h6 class="rbt-title-style-2 mb--10">Complété :</h6>
                    <div class="progress">
                        <div class="progress-bar wow fadeInLeft bar-color-success" role="progressbar"
                             style="width: {{ $progressPercentage }}%; visibility: visible;
                             animation-name: fadeInLeft;"
                             aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100">
                        </div>
                        <span class="rbt-title-style-2 progress-number">{{ $progressPercentage }}%</span>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

