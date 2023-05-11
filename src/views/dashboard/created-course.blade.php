{{--
Author      : Jérémie Arcidiacono
Created     : May 2023
Description : The page to display the list of courses the user created.
--}}

@php
    /**
    * @var \App\Models\Course[] $createdCourses The courses the user created
    */
@endphp

@component('layouts.dashboard')
    <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
        <div class="content">
            <div class="section-title">
                <h4 class="rbt-title-style-3">Vos cours favoris</h4>
            </div>
            <div class="rbt-dashboard-table table-responsive mobile-table-750 mt--30">
                <table class="rbt-table table table-borderless">
                    <thead>
                    <tr>
                        <th>Cours</th>
                        <th>Catégorie</th>
                        <th>Chapitres</th>
                        <th>Inscrits</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($createdCourses as $course)
                        <tr>
                            <th>
                                <p class="b3 mb--5 fs-5">Modifié le {{ $course->getUpdatedAt()->format('d/m/Y') }}</p>
                                <span class="h6 mb--5">{{ $course->getTitle() }}</span>
                            </th>
                            <td>
                                <p class="b3">{{ $course->getCategory()->getLabel() }}</p>
                            </td>
                            <td>
                                <p class="b3">{{ $course->getChapters()->count() }}</p>
                            </td>
                            <td>
                                <p class="b3">{{ $course->getEnrollments()->count() }}</p>
                            </td>
                            <td>
                                @if($course->getVisibility() == \App\Enums\CourseVisibility::Draft)
                                    <span class="rbt-badge-5 bg-color-primary-opacity color-primary">Brouillon</span>
                                @elseif($course->getVisibility() == \App\Enums\CourseVisibility::Private)
                                    <span class="rbt-badge-5 bg-color-danger-opacity color-danger">Privé</span>
                                @elseif($course->getVisibility() == \App\Enums\CourseVisibility::Public)
                                    <span class="rbt-badge-5 bg-color-success-opacity color-success">Public</span>
                                @endif
                            </td>

                            <td>
                                <div class="rbt-button-group justify-content-end">
                                    <a class="rbt-btn btn-xs bg-primary-opacity radius-round" title="Voir plus"
                                       href="{{ url('course.edit', ['courseId' => $course->getId()]) }}">
                                        <i class="far fa-share-square pl--0"></i></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>

                @empty($createdCourses)
                    <h5 class="text-center">Vous n'avez pas encore créé de cours</h5>
                @endempty
            </div>
        </div>
    </div>
@endcomponent

