{{--
Author      : Jérémie Arcidiacono
Created     : May 2023
Description : The page to display the list of users registered in the website.

Need to provide :
    $nbTotalUsers : The total number of users
    $users : The users to display (paginated and filtered)
    $currentPage
    $totalPages
--}}

@php
    /**
    * @var \App\Models\User[] $users The courses the user created
    * @var \App\Auth $auth
    */
@endphp

@component('layouts.dashboard')
    <div class="rbt-dashboard-content bg-color-white rbt-shadow-box">
        <div class="content">
            <div class="section-title">
                <h4 class="rbt-title-style-3">Utilisateurs : {{ $nbTotalUsers }}</h4>
            </div>

            <div class="rbt-dashboard-filter-wrapper">
                <div class="row g-5 justify-content-end">
                    <div class="col-lg-6">
                        <form action="{{ url('user.index') }}" class="rbt-search-style-1" id="searchForm">
                            <input type="hidden" name="page" id="page" value="{{ $currentPage }}">
                            <input type="text" placeholder="Rechercher un utilisateur" id="recherche" name="recherche"
                                   value="{{ $old['recherche'] }}">
                            <button class="search-btn" type="submit"><i class="feather-search"></i></button>
                        </form>
                    </div>
                </div>
            </div>

            <hr class="mt--30">

            <div class="rbt-dashboard-table table-responsive mobile-table-750 mt--30">
                <table class="rbt-table table table-borderless">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Inscrit depuis</th>
                        <th>Rôle</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <th>
                                <p class="b3">{{ $user->getId() }}</p>
                            </th>
                            <td>
                                <p class="b3">{{ $user->getLastname() }}</p>
                            </td>
                            <td>
                                <p class="b3">{{ $user->getFirstname() }}</p>
                            </td>
                            <td>
                                <p class="b3">{{ $user->getEmail() }}</p>
                            </td>
                            <td>
                                <p class="b3">{{ $user->getCreatedAt()->format('d/m/Y') }}</p>
                            </td>
                            <td>
                                @if($user->getRole()->getName() == 'user')
                                    <span class="rbt-badge-5 bg-color-primary-opacity color-primary">Étudiant</span>
                                @elseif($user->getRole()->getName() == 'teacher')
                                    <span class="rbt-badge-5 bg-color-success-opacity color-success">Professeur</span>
                                @elseif($user->getRole()->getName() == 'admin')
                                    <span class="rbt-badge-5 bg-color-danger-opacity color-danger">Admin</span>
                                @endif
                            </td>
                            <td>
                                @if($auth->can(\App\Enums\Action::Delete, new  App\Models\User()))
                                    @if(!\App\Services\UserService::UserHasPublicCourse($user) &&
                                        !\App\Services\UserService::UserHasCourseInProgressOrDone($user) &&
                                        !\App\Services\UserService::TeacherHasStudent($user))
                                        <form action="{{ url('user.destroy', ['userId' => $user->getId()]) }}"
                                              method="post">
                                            @customCsrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-icon btn-light"
                                                    data-bs-toggle="tooltip" title="Supprimer">
                                                <i class="feather-trash-2"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
                <small><i class="feather-info"></i>Vous ne pouvez supprimer un utilisateur que si celui-ci n'est pas
                    en train de suivre un cours (status de chapitre "en cours").</small>
                <small><i class="feather-info"></i>Vous ne pouvez pas supprimer un enseignant qui possède des cours
                    publics.</small>

                @empty($users)
                    <h5 class="text-center">Aucun utilisateur trouvé</h5>
                @endempty
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
        </div>
    </div>
    @push('scripts')
        <script>
            // Method to change the page number in the filters form and submit it
            function changePage(page) {
                const form = document.getElementById('searchForm');
                const pageInput = document.getElementById('page');
                pageInput.value = page;
                form.submit();
            }
        </script>
    @endpush

@endcomponent

