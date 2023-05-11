{{--
Author      : Jérémie Arcidiacono
Created     : May 2023
Description : The page is a form to create a new course (without chapters).
--}}

@php
    /**
     * @var \App\Models\CourseCategory[] $categories
     */
@endphp

@component('layouts.app', ['title' => 'Créer un cours', 'stickyHeader' => false])
    <div class="rbt-create-course-area bg-color-white rbt-section-gap">
        <div class="container">
            <div class="row g-5">

                <div class="col-lg-8">
                    <form action="{{ url('course.store') }}" method="post" enctype="multipart/form-data">
                        @customCsrf
                        <div class="card-body">
                            <!-- Start Course Field Wrapper  -->
                            <div class="rbt-course-field-wrapper rbt-default-form">
                                <div class="course-field mb--15">
                                    <label for="titre" class="fs-3">Titre du cours</label>
                                    <input id="titre" name="titre" type="text" placeholder="Nouveau cours"
                                           maxlength="150" minlength="5" required
                                           value="{{ $old['titre'] }}">
                                    <small class="d-block mt_dec--5"><i class="feather-info"></i> Le titre doit
                                        comporter
                                        150 caractères maximum.</small>
                                </div>

                                <div class="course-field mb--15">
                                    <label for="description" class="fs-3">Description</label>
                                    <textarea id="description" name="description" rows="4" maxlength="250" required>
                                        {{ $old['description'] }}</textarea>
                                    <small class="d-block mt_dec--5"><i class="feather-info"></i> La description doit
                                        comporter au maximum 250 caractères.</small>
                                </div>

                                <div class="course-field mb--20">
                                    <label for="categorie">Catégorie</label>
                                    <div class="rbt-modern-select bg-transparent height-45 mb--10">
                                        <select class="w-100" id="categorie" name="categorie">
                                            @foreach($categories as $category)
                                                <option
                                                        value="{{$category->getId()}}"
                                                        @if(isset($old['categorie']) && $old['categorie'] == $category->getId()) selected @endisset
                                                >{{$category->getLabel()}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="course-field mb--20">
                                    <h6>Bannière du cours</h6>
                                    <div class="rbt-create-course-thumbnail upload-area">
                                        <div class="upload-area">
                                            <div class="brows-file-wrapper" data-black-overlay="9">
                                                <!-- actual upload which is hidden -->
                                                <input name="createinputfile" id="createinputfile"
                                                       type="file" class="inputfile"
                                                       accept="@php
                                                           $output = '';
                                                           foreach ($config->get('models.course.bannerAllowedMimeTypes', []) as $mime) {
                                                                  $output .= $mime . ',';
                                                           }
                                                           echo rtrim($output, ',');
                                                       @endphp" required>
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

                                    <small><i class="feather-info"></i> <b>Taille:</b> 700x500 pixels, <b>Prise en
                                            charge
                                            des fichiers:</b> JPG, JPEG, PNG</small>
                                </div>


                            </div>
                            <!-- End Course Field Wrapper  -->
                        </div>

                        <div class="mt--10 row g-5 d-flex justify-content-center">
                            <div class="col-lg-8">
                                <button class="rbt-btn btn-gradient hover-icon-reverse w-100 text-center"
                                        type="submit">
                                    <span class="icon-reverse-wrapper">
                                        <span class="btn-text">Créer le cours</span>
                                    <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                    <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-lg-4">
                    <div class="rbt-create-course-sidebar course-sidebar sticky-top rbt-shadow-box rbt-gradient-border">
                        <div class="inner">
                            <div class="section-title mb--30">
                                <h4 class="title">Conseils de création de cours</h4>
                            </div>
                            <div class="rbt-course-upload-tips">
                                <ul class="rbt-list-style-1">
                                    <li><i class="feather-check"></i> La taille standard de la bannière du cours est de
                                        700x500.
                                    </li>
                                    <li><i class="feather-check"></i> Vous pourrez créer les chapitres du cours après
                                        avoir créé le cours.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endcomponent

