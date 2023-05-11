{{--
Author      : Jérémie Arcidiacono
Created     : May 2023
Description : The page is a detailed view of a course that the teacher has created.
              There are servals tabs to manage the course like edit the main info,
              add/remove chapters, etc.
--}}

@php
    /**
    * @var \App\Models\Course $course
    * @var \App\Models\CourseCategory[] $categories
    * @var \App\Models\CourseEnrollment[] $enrollments
    * @var \App\Models\Chapter $chapter
    */
@endphp

@component('layouts.app', ['title' => 'Votre cours', 'stickyHeader' => false])
    <div class="rbt-create-course-area bg-color-white rbt-section-gap">
        <div class="container">
            <div class="row g-5">

                <div class="col-lg-8">
                    <div class="border-danger border border-1 mb--20 p-2 bg-white p-5">
                        <div class="course-field mb--20">
                            <form action="{{ url('course.update', ['courseId' => $course->getId()]) }}" method="post">
                                @method('PUT')
                                @customCsrf
                                <p class="fs-3 mb--0">
                                    <i class="feather-alert-triangle color-danger"></i>
                                    Visibilité
                                </p>
                                @if($course->getVisibility() == \App\Enums\CourseVisibility::Draft)
                                    <p class="fs-3 mb--0">
                                        Votre cours est en mode brouillon. Il n'est pas visible par les
                                        étudiants.
                                    </p>
                                    <button class="rbt-btn btn-sm rbt-switch-btn rbt-switch-y mb--10
                                        @if(count($course->getChapters()) < 1) disabled @endif"
                                            type="submit"
                                            name="visibilite"
                                            value="{{ \App\Enums\CourseVisibility::Public->value }}">
                                        <span data-text="Publier">Publier</span>
                                    </button>
                                @elseif($course->getVisibility() == \App\Enums\CourseVisibility::Public)
                                    <p class="fs-3 mb--0">
                                        Votre cours est en mode public. Il est visible par les étudiants.
                                    </p>
                                    <button class="rbt-btn btn-sm rbt-switch-btn rbt-switch-x mb--10"
                                            type="submit"
                                            name="visibilite"
                                            value="{{ \App\Enums\CourseVisibility::Private->value }}">
                                        <span data-text="Rendre privé">Rendre privé</span>
                                    </button>
                                @else
                                    <p class="fs-3 mb--0">
                                        Votre cours est en mode privé. Il n'est visible que par les étudiants
                                        déjà inscrits.
                                    </p>
                                    <button class="rbt-btn btn-sm rbt-switch-btn rbt-switch-y mb--10"
                                            type="submit"
                                            name="visibilite"
                                            value="{{ \App\Enums\CourseVisibility::Public->value }}">
                                        <span data-text="Rendre public">Rendre public</span>
                                    </button>
                                @endif
                                <small class="d-block mt_dec--5"><i class="feather-info"></i>
                                    Pour publier un cours, il doit avoir au moins un chapitre.
                                </small>
                                <small class="d-block mt_dec--5"><i class="feather-info"></i>
                                    Un cours privé n'est visible que par les étudiants déjà inscrits.
                                </small>
                            </form>
                        </div>

                        <div class="course-field">
                            <form action="{{ url('course.destroy', ['courseId' => $course->getId()]) }}"
                                  method="post">
                                @method('DELETE')
                                @customCsrf
                                <p class="fs-3 mb--0">
                                    <i class="feather-alert-triangle color-danger"></i>
                                    Supprimer le cours
                                </p>
                                <p class="fs-3 mb--0">
                                    Vous pouvez supprimer votre cours si aucun étudiant n'est inscrit.
                                    Attention, cette action est irréversible.
                                </p>
                                <button class="rbt-btn btn-sm rbt-switch-btn rbt-switch-x mb--10
                                        @if(count($course->getEnrollments()) > 0) disabled @endif"
                                        type="submit">
                                    <span data-text="Supprimer">Supprimer</span>
                                </button>
                            </form>
                        </div>
                    </div>


                    <div class="rbt-accordion-style rbt-accordion-01 rbt-accordion-06 accordion">
                        <div class="accordion" id="accordionConfigCourse">
                            <div class="accordion-item card">
                                <h2 class="accordion-header card-header" id="accOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#accCollapseOne" aria-expanded="true"
                                            aria-controls="accCollapseOne">Informations sur le cours
                                    </button>
                                </h2>
                                <div id="accCollapseOne" class="accordion-collapse collapse show"
                                     aria-labelledby="accOne" data-bs-parent="#accordionConfigCourse">
                                    <form action="{{ url('course.update', ['courseId' => $course->getId()]) }}"
                                          method="post"
                                          enctype="multipart/form-data">
                                        @method('PUT')
                                        @customCsrf
                                        <div class="card-body">
                                            <!-- Start Course Field Wrapper  -->
                                            <div class="rbt-course-field-wrapper rbt-default-form">
                                                <div class="course-field mb--15">
                                                    <label for="titre" class="fs-3">Titre du cours</label>
                                                    <input id="titre" name="titre" type="text"
                                                           placeholder="Nouveau cours"
                                                           maxlength="150"
                                                           value="{{ $old['titre'] ?? $course->getTitle() }}">
                                                    <small class="d-block mt_dec--5"><i class="feather-info"></i> Le
                                                        titre doit comporter 150 caractères maximum.</small>
                                                </div>

                                                <div class="course-field mb--15">
                                                    <label for="titre" class="fs-3">Titre du cours</label>
                                                    <input id="titre" name="titre" type="text"
                                                           placeholder="Nouveau cours"
                                                           maxlength="150"
                                                           value="{{ $old['titre'] ?? $course->getTitle() }}">
                                                    <small class="d-block mt_dec--5"><i class="feather-info"></i> Le
                                                        titre doit comporter 150 caractères maximum.</small>
                                                </div>

                                                <div class="course-field mb--15">
                                                    <label for="description" class="fs-3">Description</label>
                                                    <textarea id="description" name="description" rows="4"
                                                              maxlength="250">{{
                                $old['description'] ?? $course->getDescription() }}</textarea>
                                                    <small class="d-block mt_dec--5"><i class="feather-info"></i> La
                                                        description doit
                                                        comporter au maximum 250 caractères.</small>
                                                </div>

                                                <div class="course-field mb--20">
                                                    <label for="categorie">Catégorie</label>
                                                    <div class="rbt-modern-select bg-transparent height-45 mb--10">
                                                        <select class="w-100" id="categorie" name="categorie">
                                                            @php
                                                                $selectedCategoryId = $old['categorie'] ?? $course->getCategory()->getId();
                                                            @endphp
                                                            @foreach($categories as $category)
                                                                <option
                                                                        value="{{$category->getId()}}"
                                                                        @if(($selectedCategoryId == $category->getId()))
                                                                            selected @endisset
                                                                >{{$category->getLabel()}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="course-field mb--20">
                                                    <h6>Changer la bannière du cours</h6>
                                                    <div class="rbt-create-course-thumbnail upload-area">
                                                        <div class="upload-area">
                                                            <div class="brows-file-wrapper" data-black-overlay="9">
                                                                <!-- actual upload which is hidden -->
                                                                <input name="createinputfile" id="createinputfile"
                                                                       type="file" class="inputfile"
                                                                       accept="image/jpeg,image/png">
                                                                <img id="createfileImage"
                                                                     src="/assets/images/others/thumbnail-placeholder.svg"
                                                                     alt="file image">
                                                                <!-- our custom upload button -->
                                                                <label class="d-flex" for="createinputfile"
                                                                       title="Aucun fichier choisi">
                                                                    <i class="feather-upload"></i>
                                                                    <span class="text-center">Choisissez un fichier</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <small><i class="feather-info"></i> <b>Taille:</b> 700x500
                                                        pixels,
                                                        <b>Prise en
                                                            charge
                                                            des fichiers:</b> JPG, JPEG, PNG</small>
                                                </div>


                                            </div>
                                            <!-- End Course Field Wrapper  -->
                                        </div>

                                        <div class="mb--20 row g-5 d-flex justify-content-center">
                                            <div class="col-lg-8">
                                                <button class="rbt-btn btn-gradient hover-icon-reverse w-100 text-center"
                                                        href="javascript:void(0)"
                                                        onclick="this.closest('form').submit()">
                                                    <span class="icon-reverse-wrapper">
                                                        <span class="btn-text">Enregistrer les modifications</span>
                                                        <span class="btn-icon">
                                                            <i class="feather-arrow-right"></i>
                                                        </span>
                                                        <span class="btn-icon">
                                                            <i class="feather-arrow-right"></i>
                                                        </span>
                                                    </span>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="accordion-item card">
                                <h2 class="accordion-header card-header" id="accTwo">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#accCollapseTwo" aria-expanded="false"
                                            aria-controls="accCollapseTwo">
                                        Course Builder
                                    </button>
                                </h2>
                                <div id="accCollapseTwo" class="accordion-collapse collapse"
                                     aria-labelledby="accTwo"
                                     data-bs-parent="#accordionConfigCourse">
                                    <div class="accordion-body card-body">

                                        <div class="rbt-dashboard-table table-responsive mobile-table-750">
                                            <table class="rbt-table table table-borderless">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Titre</th>
                                                    <th>Video</th>
                                                    <th>Ressource</th>
                                                    <th>Actions</th>
                                                </tr>
                                                </thead>

                                                <tbody>
                                                @foreach($course->getChapters() as $chapter)
                                                    <tr>
                                                        <td>{{ $chapter->getPosition() }}</td>
                                                        <td>{{ $chapter->getTitle() }}</td>
                                                        <td>
                                                            @if($chapter->getVideo() != null)
                                                                <a href="{{ url('chapter.video',
                                                                                ['courseId' => $course->getId(),
                                                                                'chapterId' => $chapter->getId()]) }}"
                                                                   target="_blank">
                                                                    {{ $chapter->getVideo()->getName()}}
                                                                </a>
                                                            @else
                                                                <i class="feather-x-circle"></i>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($chapter->getRessource() != null)
                                                                <a href="{{ url('chapter.ressource',
                                                                                ['courseId' => $course->getId(),
                                                                                'chapterId' => $chapter->getId()]) }}"
                                                                   target="_blank">
                                                                    {{ $chapter->getRessource()->getName()}}
                                                                </a>
                                                            @else
                                                                <i class="feather-x-circle"></i>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="d-flex justify-content-center">
                                                                <a href="{{ url('course.chapter.edit', ['id' => $chapter->getId()]) }}"
                                                                   class="btn btn-sm btn-icon btn-light"
                                                                   data-bs-toggle="tooltip" title="Modifier">
                                                                    <i class="feather-edit"></i>
                                                                </a>
                                                                <a href="{{ url('course.chapter.delete', ['id' => $chapter->getId()]) }}"
                                                                   class="btn btn-sm btn-icon btn-light"
                                                                   data-bs-toggle="tooltip" title="Supprimer">
                                                                    <i class="feather-trash-2"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <button class="rbt-btn btn-md btn-gradient hover-icon-reverse" type="button"
                                                data-bs-toggle="modal" data-bs-target="#modalAddChapter">
                                                <span class="icon-reverse-wrapper">
                                                    <span class="btn-text">Add New Topic</span>
                                                <span class="btn-icon"><i class="feather-plus-circle"></i></span>
                                                <span class="btn-icon"><i class="feather-plus-circle"></i></span>
                                                </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-4">
                    <div class="mb--15">
                        <div class="mb--10">Actuellement, votre cours s'affiche comme ceci :</div>
                        @component('components.course-card-s1', ['course' => $course])@endcomponent
                    </div>
                    <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
                        <div class="content">
                            <div class="section-title">
                                <h4 class="rbt-title-style-3">Étudiants inscrits</h4>
                            </div>
                            @if($enrollments->isEmpty())
                                <i class="feather-info"></i>  Aucun étudiant inscrit
                            @else
                                <div class="rbt-dashboard-table table-responsive mobile-table-750">
                                    <table class="rbt-table table table-borderless">
                                        <thead>
                                        <tr>
                                            <th>Nom d'étudiant</th>
                                            <th>Date de l'inscription</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($enrollments as $enrollment)
                                            <tr>
                                                <td>{{ $enrollment->getStudent()->getFirstname() . ' ' . $enrollment->getStudent()->getLastname()}}</td>
                                                <td>{{ $enrollment->getCreatedAt()->format('d.m.y') }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal for chapter creation --}}
    <div class="rbt-default-modal modal fade" id="modalAddChapter" tabindex="-1"
         aria-labelledby="modalAddChapterLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="rbt-round-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="feather-x"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="inner rbt-default-form">
                        <div class="row">
                            <div class="col-lg-12">
                                <h5 class="modal-title mb--20" id="modalAddChapterLabel">Add Topic</h5>
                                <div class="course-field mb--20">
                                    <label for="modal-field-1">Topic Name</label>
                                    <input id="modal-field-1" type="text">
                                    <small><i class="feather-info"></i> Topic titles are displayed publicly wherever
                                        required. Each topic may contain one or more lessons, quiz and
                                        assignments.</small>
                                </div>
                                <div class="course-field mb--20">
                                    <label for="modal-field-2">Topic Summary</label>
                                    <textarea id="modal-field-2"></textarea>
                                    <small><i class="feather-info"></i> Add a summary of short text to prepare
                                        students
                                        for the activities for the topic. The text is shown on the course page
                                        beside
                                        the tooltip beside the topic name.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="top-circle-shape"></div>
                <div class="modal-footer pt--30">
                    <button type="button" class="rbt-btn btn-border btn-md radius-round-10" data-bs-dismiss="modal">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
@endcomponent

