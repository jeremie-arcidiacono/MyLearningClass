{{--
Author      : Jérémie Arcidiacono
Created     : May 2023
Description : The page to display the details of a course

Need to provide :
    - $course

--}}

@php
    /**
    * @var \App\Models\Course $course
    * @var \App\Models\Chapter $chapter The current chapter
    * @var \App\Models\Chapter $currentLoopChapter The current chapter in the loop of all chapters
    * @var \App\Auth $auth
    */
    use \App\Enums\ChapterProgressStatus;
    use App\Services\ChapterProgressService;

    $chapterProgressStatus = ChapterProgressService::Find($auth->getUser(), $chapter)->getStatus();
@endphp

@component('layouts.app', ['title' => 'Étudier un cours', 'stickyHeader' => false])
    <div class="rbt-lesson-area bg-color-white">
        <div class="rbt-lesson-content-wrapper">
            <div class="rbt-lesson-leftsidebar">
                <div class="rbt-course-feature-inner rbt-search-activation">
                    <div class="section-title">
                        <h4 class="rbt-title-style-3">Chapitres du cours</h4>
                    </div>


                    <div class="rbt-accordion-style rbt-accordion-02 for-right-content">


                        <div class="accordion" id="accordionExampleb2">

                            <div class="accordion-item card">
                                <div id="collapseTwo1" class="show">
                                    <div class="accordion-body card-body">
                                        <ul class="rbt-course-main-content liststyle">
                                            @foreach($course->getChapters() as $currentLoopChapter)
                                                <li>
                                                    <a href="{{ url('chapter.show',
                                                            ['courseId' => $course->getId(),
                                                            'chapter' => $currentLoopChapter->getPosition()])
                                                            }}"
                                                       @if($currentLoopChapter->getPosition() == $chapter->getPosition())
                                                           class="active"@endif
                                                    >
                                                        <div class="course-content-left">
                                                            @if($currentLoopChapter->getVideo() != null)
                                                                <i class="feather-play-circle"></i>
                                                            @endif
                                                            @if($currentLoopChapter->getRessource() != null)
                                                                <i class="feather-file-text"></i>
                                                            @endif

                                                            <span class="text">{{ $currentLoopChapter->getTitle() }}</span>
                                                        </div>

                                                        <div class="course-content-right">
                                                            @if($currentLoopChapter->getVideo() != null)
                                                                <span class="min-lable">
                                                                    {{ $currentLoopChapter->getVideo()->getDuration() }} s
                                                                </span>
                                                            @endif
                                                            @php
                                                                $currentLoopChapterProgress = \App\Services\ChapterProgressService::Find
                                                                ($auth->getUser(), $currentLoopChapter);

                                                            $currentLoopChapterProgressStatus = $currentLoopChapterProgress->getStatus();
                                                            @endphp
                                                            @if($currentLoopChapterProgressStatus == ChapterProgressStatus::Done)
                                                                <span class="rbt-check">
                                                                    <i class="feather-check bg-color-success"></i>
                                                                </span>
                                                            @elseif($currentLoopChapterProgressStatus == ChapterProgressStatus::InProgress)
                                                                <span class="rbt-check">
                                                                    <i class="bg-color-gray">...</i>
                                                                </span>
                                                            @else
                                                                <span class="rbt-check unread">
                                                                    <i class="feather-circle"></i>
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </a>
                                                </li>
                                            @endforeach
                                            <li>
                                        </ul>
                                    </div>
                                </div>
                            </div>


                        </div>


                    </div>
                </div>
            </div>

            <div class="rbt-lesson-rightsidebar overflow-hidden lesson-video">
                <div class="lesson-top-bar">
                    <div class="lesson-top-left">
                        <a href="{{ url('course.show', ['courseId' => $course->getId()]) }}">
                            <h5>{{ $course->getTitle() }}</h5>
                        </a>
                    </div>
                    <div class="lesson-top-right">
                    </div>
                </div>
                <div class="inner">
                    @if($chapter->getVideo() != null)
                        <div class="plyr__video-embed rbtplayer">
                            <video controls crossorigin playsinline>
                                <source src="{{ url('chapter.video',
                                                    ['courseId' => $course->getId(),
                                                    'chapterId' => $chapter->getId()]) }}"
                                        type="{{ $chapter->getVideo()->getMimeType() }}">
                            </video>
                        </div>
                    @endif
                    <div class="content">
                        <div class="section-title">
                            <h4>{{ $chapter->getTitle() }}</h4>
                            @if($chapter->getRessource() != null)
                                <p>Voici une ressource supplémentaire :</p>

                                <button id="downloadBtn" onclick="downloadRessource()">Télécharger le PDF</button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bg-color-extra2 ptb--15 overflow-hidden">
                    <div class="rbt-button-group">
                        @if($chapter->getPosition() > 1)
                            {{-- Go to previous --}}
                            <a class="rbt-btn icon-hover icon-hover-left btn-md bg-primary-opacity"
                               href="{{ url('chapter.show',
                                            ['courseId' => $course->getId(),
                                            'chapter' => $chapter->getPosition() - 1])
                                            }}">
                                <span class="btn-icon"><i class="feather-arrow-left"></i></span>
                                <span class="btn-text">Précédent</span>
                            </a>
                        @endif


                        @if($chapterProgressStatus == ChapterProgressStatus::ToDo)
                            <form action="{{ url('chapter.updateProgression',
                                            ['courseId' => $course->getId(),
                                            'chapterId' => $chapter->getId()])
                                            }}"
                                  method="POST">
                                @customCsrf
                                @method('PUT')
                                <input type="hidden" name="chapterProgressState"
                                       value="{{ ChapterProgressStatus::InProgress->value }}">
                                <button type="submit" class="rbt-btn btn-md bg-secondary">
                                    <span class="btn-text">Commencer</span>
                                </button>
                            </form>
                        @endif
                        @if($chapterProgressStatus == ChapterProgressStatus::InProgress)
                            <form action="{{ url('chapter.updateProgression',
                                            ['courseId' => $course->getId(),
                                            'chapterId' => $chapter->getId()])
                                            }}"
                                  method="POST">
                                @customCsrf
                                @method('PUT')
                                <input type="hidden" name="chapterProgressState"
                                       value="{{ ChapterProgressStatus::ToDo->value }}">
                                <button type="submit" class="rbt-btn btn-md bg-secondary">
                                    <span class="btn-text">Marquer comme à faire</span>
                                </button>
                            </form>
                            {{-- Mark as done --}}
                            <form action="{{ url('chapter.updateProgression',
                                            ['courseId' => $course->getId(),
                                            'chapterId' => $chapter->getId()])
                                            }}"
                                  method="POST">
                                @customCsrf
                                @method('PUT')
                                <input type="hidden" name="chapterProgressState"
                                       value="{{ ChapterProgressStatus::Done->value }}">
                                <button type="submit" class="rbt-btn btn-md bg-secondary">
                                    <span class="btn-text">Marquer comme terminé</span>
                                </button>
                            </form>
                        @endif
                        @if($chapterProgressStatus == ChapterProgressStatus::Done)
                            <form action="{{ url('chapter.updateProgression',
                                            ['courseId' => $course->getId(),
                                            'chapterId' => $chapter->getId()])
                                            }}"
                                  method="POST">
                                @customCsrf
                                @method('PUT')
                                <input type="hidden" name="chapterProgressState"
                                       value="{{ ChapterProgressStatus::InProgress->value }}">
                                <button type="submit" class="rbt-btn btn-md bg-secondary">
                                    <span class="btn-text">Marquer comme en cours</span>
                                </button>
                            </form>
                        @endif


                        {{-- Go to next --}}
                        @if($chapter->getPosition() < $course->getChapters()->count())
                            <a class="rbt-btn icon-hover btn-md"
                               href="{{ url('chapter.show',
                                            ['courseId' => $course->getId(),
                                            'chapter' => $chapter->getPosition() + 1])
                                            }}">
                                <span class="btn-text">Suivant</span>
                                <span class="btn-icon"><i class="feather-arrow-right"></i></span>
                            </a>
                        @endif

                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function downloadRessource() {
                const url = 'http://localhost:8888/cours/{{ $course->getId() }}/chapitres/{{ $chapter->getId() }}/ressource';

                // Create an invisible iframe element
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.src = url;

                // Append the iframe to the body, which will trigger the download
                document.body.appendChild(iframe);

                // Remove the iframe after the download
                setTimeout(() => {
                    document.body.removeChild(iframe);
                }, 5000);
            }</script>
    @endpush

@endcomponent
