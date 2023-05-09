{{--
Author      : Jérémie Arcidiacono
Created     : May 2023
Description : The page to display a list of courses with a grid layout.
              User can filter the courses by category and search by title.
--}}

@php
    /**
    * @var \App\Models\Course[] $courses
    * @var \App\Models\Course $course
    * @var \App\Models\CourseCategory[] $categories
    */
@endphp

@component('layouts.app', ['title' => 'Accueil', 'stickyHeader' => true])
    <div class="rbt-page-banner-wrapper">
        <div class="rbt-banner-image"></div>
        <div class="rbt-banner-content">
            <!-- Start Banner Content Top  -->
            <div class="rbt-banner-content-top">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Start Breadcrumb Area  -->
                            <ul class="page-list">
                                <li class="rbt-breadcrumb-item"><a href="{{ url('home') }}">Home</a></li>
                                <li>
                                    <div class="icon-right"><i class="feather-chevron-right"></i></div>
                                </li>
                                <li class="rbt-breadcrumb-item active">Tous les cours</li>
                            </ul>
                            <!-- End Breadcrumb Area  -->

                            <div class=" title-wrapper">
                                <h1 class="title mb--0">Tous les cours</h1>
                                <a href="#" class="rbt-badge-2">
                                    <div class="image">🎉</div>{{ $nbCourseAvailable  }} Courses
                                </a>
                            </div>
                            <p class="description">Visualisez tous les cours disponibles sur la plateforme.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Banner Content Top  -->
            <div class="rbt-course-top-wrapper mt--40">
                <div class="container">
                    <div class="row g-5 align-items-center">
                        <div class="col-xl-5 col-lg-12 col-md-12">
                            <div class="rbt-sorting-list d-flex flex-wrap align-items-center">
                                <div class="rbt-short-item switch-layout-container">
                                    <ul class="course-switch-layout">
                                        <li class="course-switch-item">
                                            <button class="rbt-grid-view active" title="Grid Layout"><i
                                                        class="feather-grid"></i> <span class="text">Grid</span>
                                            </button>
                                        </li>
                                        <li class="course-switch-item">
                                            <button class="rbt-list-view" title="List Layout"><i
                                                        class="feather-list"></i> <span class="text">List</span>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                                <div class="rbt-short-item">
                                    <span class="course-index">Page {{$currentPage}} sur {{$totalPages}} </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-7 col-lg-12 col-md-12">
                            <form action="{{ url('course.index') }}" method="GET" id="filtersForm"
                                  class="rbt-search-style">
                                <input type="hidden" name="page" id="page" value="{{ $currentPage }}">
                                <div class="rbt-sorting-list d-flex flex-wrap align-items-end justify-content-start justify-content-xl-end">
                                    <div class="rbt-short-item">
                                        <input type="text" id="recherche" name="recherche"
                                               placeholder="Rechercher un cours"
                                               value="{{ $old['recherche'] ?? '' }}">
                                    </div>
                                    <div class="rbt-short-item">
                                        <div class="filter-select">
                                            <span class="select-label d-block">Categorie</span>
                                            <div class="filter-select rbt-modern-select search-by-category">
                                                <select id="categorie" name="categorie">
                                                    <option value="" @if(!isset($old['categorie'])) selected @endisset>
                                                        Tous
                                                    </option>
                                                    @foreach($categories as $category)
                                                        <option
                                                                value="{{$category->getId()}}"
                                                                @if(isset($old['categorie']) && $old['categorie'] == $category->getId()) selected @endisset
                                                        >{{$category->getLabel()}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rbt-short-item">
                                        <button class="rbt-btn btn-border radius-round" onclick="applyNewFilters()">
                                            Appliquer
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="rbt-section-overlayping-top rbt-section-gapBottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @if(!empty($courses))
                        <div class="rbt-course-grid-column">

                            @foreach($courses as $course)
                                <div class="course-grid-3">
                                    @component('components.course-card-s1', ['course' => $course])
                                    @endcomponent
                                </div>
                            @endforeach
                        </div>
                        <div class="row">
                            <div class="col-lg-12 mt--60">
                                @if($totalPages > 1)
                                    <nav>
                                        <ul class="rbt-pagination">
                                            @if($currentPage > 1)
                                                <li><a href="javascript:void(0);" aria-label="Précédent"
                                                       onclick="changePage({{ $currentPage - 1 }})">
                                                        <i class="feather-chevron-left"></i>
                                                    </a></li>
                                            @endif
                                            @for($i = 1; $i <= $totalPages; $i++)
                                                <li class="{{ $i == $currentPage ? 'active' : '' }}">
                                                    <a href="javascript:void(0);"
                                                       onclick="changePage({{ $i }})">{{ $i }}</a>
                                                </li>
                                            @endfor
                                            @if($currentPage < $totalPages)
                                                <li><a href="javascript:void(0);" aria-label="Suivant"
                                                       onclick="changePage({{ $currentPage + 1 }})">
                                                        <i class="feather-chevron-right"></i>
                                                    </a></li>
                                            @endif
                                        </ul>
                                    </nav>
                                @endif
                            </div>
                        </div>
                    @else
                        <h3 class="text-center">Aucun cours trouvé</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            // Method to change the page number in the filters form and submit it
            function changePage(page) {
                const form = document.getElementById('filtersForm');
                const pageInput = document.getElementById('page');
                pageInput.value = page;
                form.submit();
            }

            // Script to set the page to 1 when the user click on "submit filters"
            function applyNewFilters() {
                const form = document.getElementById('filtersForm');
                const pageInput = document.getElementById('page');
                pageInput.value = 1;
                form.submit();
            }
        </script>
    @endpush
@endcomponent

