{{--
A little button to bookmark (or unbookmark) a course.
It only appears if the user is logged in.

Need to provide :
    - $course : the course to display (App\Models\Course)
--}}

@if($auth->check())
    <div class="rbt-bookmark-btn">
        @if($auth->getUser()->getBookmarkedCourses()->contains($course))
            <form action="{{ url('course.unbookmark', ['courseId' => $course->getId()]) }}" method="post">
                @method('DELETE')
                @customCsrf
                <input type="hidden" name="redirect">
                <a class="rbt-round-btn fs-2" title="Favoris"
                   href="javascript:void(0)" onclick="$(this).closest('form').submit()">
                    <i class="fas fa-bookmark"></i>
                </a>
            </form>
        @else
            <form action="{{ url('course.bookmark', ['courseId' => $course->getId()]) }}" method="post">
                @customCsrf
                <input type="hidden" name="redirect">
                <a class="rbt-round-btn fs-2" title="Favoris"
                   href="javascript:void(0)" onclick="$(this).closest('form').submit()">
                    <i class="far fa-bookmark"></i>
                </a>
            </form>
        @endif
    </div>
@endif


@push('scripts')
    <script>
        // Add the redirection to this page after bookmarking the course
        window.addEventListener('load', function () {
            document.getElementsByName('redirect').forEach(function (input) {
                input.value = window.location.href;
            });
        });
    </script>
@endpush
