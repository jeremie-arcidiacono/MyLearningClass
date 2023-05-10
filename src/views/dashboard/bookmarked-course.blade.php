{{--
Author      : Jérémie Arcidiacono
Created     : May 2023
Description : The page to display the list of courses the user added to his bookmarks.
--}}

@php
    /**
    * @var \App\Models\Course[] $bookmarkedCourses The courses the user bookmarked
    */
@endphp

@component('layouts.dashboard')
    <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
        <div class="content">
            <div class="section-title">
                <h4 class="rbt-title-style-3">Vos cours favoris</h4>
            </div>
            <div class="row g-5">
                @foreach($bookmarkedCourses as $course)
                    <div class="col-lg-4 col-md-6 col-12">
                        @component('components.course-card-s1', ['course' => $course, 'showDescription' =>false])

                        @endcomponent
                    </div>
                @endforeach

                @empty($bookmarkedCourses)
                    <h5 class="text-center">Vous n'avez pas encore de cours favoris.</h5>
                @endempty
            </div>
        </div>
    </div>
@endcomponent

